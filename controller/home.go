package controller

import (
	"fmt"
	"net/http"

	"github.com/gin-gonic/gin"
	"github.com/reeze-project/reeze/config"
	"github.com/reeze-project/reeze/helpers"
	"github.com/reeze-project/reeze/model"
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
		err := fmt.Errorf("invalid oauth state, expected '%s', got '%s'", config.OauthStateString, state)
		log.LogError(err)
		c.Redirect(http.StatusTemporaryRedirect, "/")
		return
	}

	code := c.Request.FormValue("code")
	token, err := config.OauthConf.Exchange(oauth2.NoContext, code)
	if err != nil {
		err = fmt.Errorf("oauthConf.Exchange() failed with '%s'", err)
		log.LogError(err)
		c.Redirect(http.StatusTemporaryRedirect, "/")
		return
	}
	client := helpers.CreateClient(token)
	user, _, err := helpers.GetUser(client)

	if err != nil {
		err = fmt.Errorf("failed to get user with error : %s", err)
		log.LogError(err)
		c.JSON(http.StatusUnauthorized, gin.H{"error": err.Error()})
	}
	newUser := &model.User{Username: *user.Login, GithubID: *user.ID}
	err = newUser.CreateUser()
	if err != nil {
		log.LogError(err)
		c.BindJSON(err)
		c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
	} else {
		c.JSON(http.StatusOK, token)
	}

}
