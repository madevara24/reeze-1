package controller

import (
	"fmt"
	"net/http"

	"github.com/gin-contrib/sessions"
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

const loggedIn = `<html><body>
Hello user! <a href="/logout">Log out</a>
</body></html>`

const logOut = `<html><body>
Berhasil Log out, <a href="/login">Login Kembali</a>
</body></html>`

func homeIndex(c *gin.Context) {
	session := sessions.Default(c)

	if session.Get("token") == nil {
		c.Writer.WriteHeader(http.StatusOK)
		c.Writer.Write([]byte(htmlIndex))
	} else {
		c.Writer.WriteHeader(http.StatusOK)
		c.Writer.Write([]byte(loggedIn))
	}

}

func logout(c *gin.Context) {
	session := sessions.Default(c)
	session.Clear()
	fmt.Println("berhasil menghapus session")
	c.Writer.WriteHeader(http.StatusOK)
	c.Writer.Write([]byte(logOut))
}

func loginGithub(c *gin.Context) {
	state := helpers.GenerateStateOauthCookie(c)
	url := config.OauthConf.AuthCodeURL(state, oauth2.AccessTypeOnline)

	c.Redirect(http.StatusTemporaryRedirect, url)
}

func githubCallback(c *gin.Context) {
	session := sessions.Default(c)
	oauthState, err := c.Request.Cookie("login")
	if err != nil {
		log.LogError(err)
	}

	state := c.Request.FormValue("state")
	if state != oauthState.Value {
		log.LogError(err)
		c.Redirect(http.StatusTemporaryRedirect, "/")
		return
	}

	code := c.Request.FormValue("code")
	token, err := config.OauthConf.Exchange(oauth2.NoContext, code)
	if err != nil {
		log.LogError(err)
		c.Redirect(http.StatusTemporaryRedirect, "/")
	}

	tokenJSON, err := helpers.TokenToJSON(token)
	if err != nil {
		log.LogError(err)
	}

	session.Set("oauth_token", tokenJSON)
	err = session.Save()
	if err != nil {
		log.LogError(err)
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
		c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
	} else {
		c.JSON(http.StatusOK, token)
	}

}
