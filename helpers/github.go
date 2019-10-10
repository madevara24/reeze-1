package helpers

import (
	"context"
	"encoding/base64"
	"encoding/json"
	"net/http"
	"time"

	"github.com/gin-gonic/gin"
	"github.com/google/go-github/github"
	"github.com/reeze-project/reeze/config"
	"golang.org/x/oauth2"
)

func ListAllRepos(c *gin.Context) ([]string, error) {
	user, client, err := VerifyUser(c)
	if err != nil {
		c.Redirect(http.StatusUnauthorized, "/")
	}
	repos, _, err := client.Repositories.List(c, *user.Login, nil)
	if err != nil {
		return nil, err
	}
	var result []string

	for _, repo := range repos {
		result = append(result, *repo.Name)
	}
	return result, nil
}

func CreateBranch(c *gin.Context, repositoryName string, branchName string) (*github.Reference, *github.Response, error) {
	user, client, err := VerifyUser(c)
	master, _, err := client.Git.GetRef(c, *user.Login, repositoryName, "refs/heads/master")

	s := "refs/heads/" + branchName

	ref := &github.Reference{
		Ref:    github.String(s),
		Object: master.GetObject(),
	}

	if err != nil {
		c.Redirect(http.StatusUnauthorized, "/")
	}
	return client.Git.CreateRef(c, *user.Login, repositoryName, ref)
}

func CreatePullRequest(c *gin.Context, repositoryName string) (*github.PullRequest, *github.Response, error) {
	user, client, err := VerifyUser(c)
	if err != nil {
		c.Redirect(http.StatusUnauthorized, "/")
	}

	newPR := &github.NewPullRequest{
		Title: github.String("Test PR"),
		Head:  github.String("refs/heads/test-branch"),
		Base:  github.String("refs/heads/feature/make_role_permission_system"),
	}
	if err != nil {
		c.Redirect(http.StatusUnauthorized, "/")
	}
	return client.PullRequests.Create(c, *user.Login, repositoryName, newPR)
}

func GetPullRequestsList(c *gin.Context, repositoryName string) ([]*github.PullRequest, *github.Response, error) {
	user, client, err := VerifyUser(c)
	if err != nil {
		c.Redirect(http.StatusUnauthorized, "/")
	}

	return client.PullRequests.List(c, *user.Login, repositoryName, nil)
}

func MergePullRequest(c *gin.Context, list *github.PullRequest, repositoryName string) (*github.PullRequestMergeResult, *github.Response, error) {
	user, client, err := VerifyUser(c)
	if err != nil {
		return nil, nil, err
	}

	return client.PullRequests.Merge(c, *user.Login, repositoryName, list.GetNumber(), "Merge", nil)
}

func CreateClient(token *oauth2.Token) *github.Client {
	oauthClient := config.OauthConf.Client(oauth2.NoContext, token)
	client := github.NewClient(oauthClient)
	return client
}

func GetUser(client *github.Client) (*github.User, *github.Response, error) {
	ctx := context.Background()
	user, res, err := client.Users.Get(ctx, "")
	if err != nil {
		return nil, nil, err
	}
	return user, res, nil
}

func VerifyUser(c *gin.Context) (*github.User, *github.Client, error) {
	var token *oauth2.Token
	headerToken := c.Request.Header.Get("token")
	if headerToken == "" {
		token = GetToken(c)
	} else {
		token, _ = TokenFromJSON(headerToken)
	}

	client := CreateClient(token)
	user, _, err := GetUser(client)

	if err != nil {
		return nil, nil, err
	}
	return user, client, nil
}

func ExchangeToken(c *gin.Context) (*oauth2.Token, error) {
	code := c.Request.FormValue("code")
	token, err := config.OauthConf.Exchange(oauth2.NoContext, code)
	token.Expiry = time.Now().Add(time.Hour * 24)
	if err != nil {
		return nil, err
	}
	return token, nil
}

func TokenFromJSON(jsonToken string) (*oauth2.Token, error) {
	var token oauth2.Token
	if err := json.Unmarshal([]byte(jsonToken), &token); err != nil {
		return nil, err
	}
	return &token, nil
}

func TokenToJSON(token *oauth2.Token) (string, error) {
	if d, err := json.Marshal(token); err != nil {
		return "", err
	} else {
		return string(d), nil
	}
}

func GetToken(c *gin.Context) *oauth2.Token {
	cookie, _ := c.Request.Cookie("user")
	creds := new(struct {
		UserID    uint64        `json:"user_id"`
		UserToken *oauth2.Token `json:"user_token"`
	})

	decodedValue, _ := base64.URLEncoding.DecodeString(cookie.Value)
	_ = json.Unmarshal(decodedValue, &creds)

	return creds.UserToken
}

func GenerateOauthURL(c *gin.Context) string {
	state := GenerateStateOauthCookie(c)
	url := config.OauthConf.AuthCodeURL(state, oauth2.AccessTypeOnline)
	return url
}

func LogoutGithub(token *oauth2.Token) error {
	token.Expiry = time.Now().Add(time.Second * 1)
	var client = &http.Client{}
	apiURL := "https://api.github.com/applications/"
	url := apiURL + config.OauthConf.ClientID + "/grants/" + token.AccessToken

	request, err := http.NewRequest("DELETE", url, nil)
	headerTokenRaw := config.OauthConf.ClientID + ":" + config.OauthConf.ClientSecret
	basic := base64.StdEncoding.EncodeToString([]byte(headerTokenRaw))

	request.Header.Set("Authorization", "Basic "+basic)
	if err != nil {
		return err
	}
	_, err = client.Do(request)
	if err != nil {
		return err
	}
	return nil
}
