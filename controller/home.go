package controller

import (
	"context"
	"fmt"
	"log"
	"net/http"

	"github.com/gin-gonic/gin"
	"github.com/google/go-github/github"
	"github.com/reeze-project/reeze/config"
	"github.com/reeze-project/reeze/helper"
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

	c.JSON(http.StatusOK, token)
}

func createBranch(c *gin.Context) {
	ctx := context.Background()
	user, client, err := helper.VerifyUser(c)
	if err != nil {
		log.Fatalf("error %s", err)
	}
	master, _, err := client.Git.GetRef(ctx, *user.Login, "rental-girlfriend-laravel", "refs/heads/master")
	branchName := c.Request.FormValue("branch_name")
	s := "refs/heads/" + branchName

	ref := &github.Reference{
		Ref:    github.String(s),
		Object: master.GetObject(),
	}

	serv, _, err := client.Git.CreateRef(ctx, *user.Login, "rental-girlfriend-laravel", ref)
	fmt.Printf("References created: %s\n", serv.GetURL())
	if err != nil {
		log.Fatalf("error %s", err)
	} else {
		c.JSON(http.StatusOK, "Branch "+branchName+" is successfully created")
	}
}

func createPullRequest(c *gin.Context) {
	ctx := context.Background()
	user, client, err := helper.VerifyUser(c)
	if err != nil {
		log.Fatalf("error %s", err)
	}
	newPR := &github.NewPullRequest{
		Title:               github.String("My awesome pull request"),
		Head:                github.String("test-pr"),
		Base:                github.String("master"),
		Body:                github.String("This is the description of the PR created with the package `github.com/google/go-github/github`"),
		MaintainerCanModify: github.Bool(true),
	}

	pr, _, err := client.PullRequests.Create(ctx, *user.Login, "rental-girlfriend-laravel", newPR)
	fmt.Printf("PR created: %s\n", pr.GetHTMLURL())
}
