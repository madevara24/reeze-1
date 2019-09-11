package controller

import (
	"fmt"
	"net/http"

	"github.com/gin-gonic/gin"
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
	branchName := c.Request.FormValue("branch_name")
	ref, _, err := helper.CreateBranch(c, branchName)
	if err != nil {
		c.JSON(http.StatusBadRequest, err)
	} else {
		fmt.Printf("References created: %s\n", ref.GetRef())
		c.JSON(http.StatusOK, "Branch "+branchName+" is successfully created")
	}
}

func createPullRequest(c *gin.Context) {
	pr, _, err := helper.CreatePullRequest(c)
	if err != nil {

	} else {
		fmt.Printf("Pull request created %s", pr.GetURL())
	}

	prList, _, err := helper.GetPullRequestsList(c)

	for _, list := range prList {
		merge, _, err := helper.MergePullRequest(c, list)
		if err != nil {
			c.JSON(http.StatusBadRequest, err)
		} else {
			//fmt.Printf("PR created: %s\n", pr.GetHTMLURL())
			fmt.Printf("Branch merged : %s\n", merge.GetMessage())
		}
	}
}
