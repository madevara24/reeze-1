package controller

import (
	"fmt"
	"net/http"

	"github.com/gin-gonic/gin"
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
