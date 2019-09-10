package controller

import (
	"context"
	"fmt"
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
	master, _, err := client.Git.GetRef(ctx, *user.Login, "rental-girlfriend-laravel", "refs/heads/master")
	branchName := c.Request.FormValue("branch_name")
	s := "refs/heads/" + branchName

	ref := &github.Reference{
		Ref:    github.String(s),
		Object: master.GetObject(),
	}

	serv, _, err := client.Git.CreateRef(ctx, *user.Login, "rental-girlfriend-laravel", ref)
	if err != nil {
		c.JSON(http.StatusBadRequest, err)
	} else {
		fmt.Printf("References created: %s\n", serv.GetRef())
		c.JSON(http.StatusOK, "Branch "+branchName+" is successfully created")
	}
}

func createPullRequest(c *gin.Context) {
	ctx := context.Background()
	user, client, err := helper.VerifyUser(c)
	if err != nil {
		c.Redirect(http.StatusUnauthorized, "/")
	}
	//create new pull request
	newPR := &github.NewPullRequest{
		Title: github.String("Test PR"),
		Head:  github.String("refs/heads/test-branch"),
		Base:  github.String("refs/heads/feature/make_role_permission_system"),
		Body:  github.String("This is the description of the PR created with the package `github.com/google/go-github/github`"),
	}

	pr, _, err := client.PullRequests.Create(ctx, *user.Login, "rental-girlfriend-laravel", newPR)
	if err != nil {
		c.JSON(http.StatusBadRequest, err)
	} else {
		fmt.Printf("PR created: %s\n", pr.GetHTMLURL())
	}

	//get list pull request
	prList, _, err := client.PullRequests.List(ctx, *user.Login, "rental-girlfriend-laravel", nil)
	for _, list := range prList {
		//merge pull request
		merge, _, err := client.PullRequests.Merge(ctx, *user.Login, "rental-girlfriend-laravel", list.GetNumber(), "Merge", nil)
		if err != nil {
			c.JSON(http.StatusBadRequest, err)
		} else {
			//fmt.Printf("PR created: %s\n", pr.GetHTMLURL())
			fmt.Printf("Branch merged : %s\n", merge.GetMessage())
		}
	}
}
