package controller

import (
	"context"
	"fmt"
	"net/http"
	"strconv"

	"github.com/gin-gonic/gin"
	"github.com/google/go-github/github"
	"github.com/reeze-project/reeze/config"
	"golang.org/x/oauth2"
)

const htmlIndex = `<html><body>
Logged in with <a href="/login">GitHub</a>
</body></html>
`

func homeIndex(c *gin.Context) {
	c.Writer.WriteHeader(http.StatusOK)
	c.Writer.Write([]byte(htmlIndex))
}

func loginGithub(c *gin.Context) {
	url := config.OauthConf.AuthCodeURL(config.OauthStateString, oauth2.AccessTypeOnline)
	c.Redirect(http.StatusTemporaryRedirect, url)
}

func githubCallback(c *gin.Context) {
	ctx := context.Background()
	state := c.Request.FormValue("state")
	if state != config.OauthStateString {
		fmt.Printf("invalid oauth state, expected '%s', got '%s'\n", config.OauthStateString, state)
		c.Redirect(http.StatusTemporaryRedirect, "/")
		return
	}

	code := c.Request.FormValue("code")
	token, err := config.OauthConf.Exchange(oauth2.NoContext, code)

	if err != nil {
		fmt.Printf("oauthConf.Exchange() failed with '%s'\n", err)
		c.Redirect(http.StatusTemporaryRedirect, "/")
		return
	}

	//return json tokennya, buat di save di localstorage

	oauthClient := config.OauthConf.Client(oauth2.NoContext, token)
	client := github.NewClient(oauthClient)
	user, _, err := client.Users.Get(ctx, "")
	if err != nil {
		fmt.Printf("client.Users.Get() faled with '%s'\n", err)
		c.Redirect(http.StatusTemporaryRedirect, "/")
		return
	}
	c.String(http.StatusOK, strconv.FormatInt(*user.ID, 10))

	//Create New Branch
	// master, _, err := client.Git.GetRef(ctx, *user.Login, "rental-girlfriend-laravel", "refs/heads/master")
	// s := "refs/heads/test-pr-lagi"

	// ref := &github.Reference{
	// 	Ref:    github.String(s),
	// 	Object: master.GetObject(),
	// }

	// serv, _, err := client.Git.CreateRef(ctx, *user.Login, "rental-girlfriend-laravel", ref)
	// fmt.Printf("References created: %s\n", serv.GetURL())

	//Create Pull Request
	// newPR := &github.NewPullRequest{
	// 	Title:               github.String("My awesome pull request"),
	// 	Head:                github.String("test-pr"),
	// 	Base:                github.String("master"),
	// 	Body:                github.String("This is the description of the PR created with the package `github.com/google/go-github/github`"),
	// 	MaintainerCanModify: github.Bool(true),
	// }

	// pr, _, err := client.PullRequests.Create(ctx, *user.Login, "rental-girlfriend-laravel", newPR)
	// fmt.Printf("PR created: %s\n", pr.GetHTMLURL())
	//c.Redirect(http.StatusTemporaryRedirect, "/")
}
