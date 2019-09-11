package helper

import (
	"context"
	"encoding/json"
	"fmt"
	"log"
	"net/http"

	"github.com/gin-gonic/gin"
	"github.com/google/go-github/github"
	"github.com/reeze-project/reeze/config"
	"golang.org/x/oauth2"
)

func CreateBranch(c *gin.Context, branchName string) (*github.Reference, *github.Response, error) {
	ctx := context.Background()
	user, client, err := VerifyUser(c)
	master, _, err := client.Git.GetRef(ctx, *user.Login, "rental-girlfriend-laravel", "refs/heads/master")

	s := "refs/heads/" + branchName

	ref := &github.Reference{
		Ref:    github.String(s),
		Object: master.GetObject(),
	}

	if err != nil {
		c.Redirect(http.StatusUnauthorized, "/")
	}
	return client.Git.CreateRef(ctx, *user.Login, "rental-girlfriend-laravel", ref)
}

func CreatePullRequest(c *gin.Context) (*github.PullRequest, *github.Response, error) {
	ctx := context.Background()
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
	return client.PullRequests.Create(ctx, *user.Login, "rental-girlfriend-laravel", newPR)
}

func GetPullRequestsList(c *gin.Context) ([]*github.PullRequest, *github.Response, error) {
	ctx := context.Background()
	user, client, err := VerifyUser(c)
	if err != nil {
		c.Redirect(http.StatusUnauthorized, "/")
	}

	return client.PullRequests.List(ctx, *user.Login, "rental-girlfriend-laravel", nil)
}

func MergePullRequest(c *gin.Context, list *github.PullRequest) (*github.PullRequestMergeResult, *github.Response, error) {
	ctx := context.Background()
	user, client, err := VerifyUser(c)
	if err != nil {
		c.Redirect(http.StatusUnauthorized, "/")
	}

	return client.PullRequests.Merge(ctx, *user.Login, "rental-girlfriend-laravel", list.GetNumber(), "Merge", nil)
}

func VerifyUser(c *gin.Context) (*github.User, *github.Client, error) {
	ctx := context.Background()
	jsonToken := c.Request.Header.Get("token")
	token, err := TokenFromJSON(jsonToken)
	if err != nil {
		log.Fatalf("error %s", err)
	}
	oauthClient := config.OauthConf.Client(oauth2.NoContext, token)
	client := github.NewClient(oauthClient)
	user, _, err := client.Users.Get(ctx, "")
	if err != nil {
		fmt.Printf("client.Users.Get() faled with '%s'\n", err)
		c.Redirect(http.StatusTemporaryRedirect, "/")
		return nil, nil, err
	}
	return user, client, nil
}

func TokenFromJSON(jsonToken string) (*oauth2.Token, error) {
	var token oauth2.Token
	if err := json.Unmarshal([]byte(jsonToken), &token); err != nil {
		return nil, err
	}
	return &token, nil
}
