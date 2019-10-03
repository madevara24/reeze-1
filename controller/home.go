package controller

import (
	"fmt"
	"net/http"

	"github.com/gin-gonic/gin"
	"github.com/reeze-project/reeze/helpers"
	"github.com/reeze-project/reeze/model"
	"golang.org/x/oauth2"
)

var token *oauth2.Token

const htmlIndex = `<html><body>
Logged in with <a href="/login-github">GitHub</a>
</body></html>`

const loggedIn = `<html><body>
Hello user!
<form action="/logout" method="POST">
	<button type="submit">LogOut</button>
</form>
</body></html>`

const logOut = `<html><body>
Berhasil Log out, <a href="/login-github">Login Kembali</a>
</body></html>`

func testAPI(c *gin.Context) {
	user := &model.Card{}
	users, err := user.GetCardByProject(1)
	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
	} else {
		c.JSON(http.StatusOK, users)
	}
}

func homeIndex(c *gin.Context) {
	if token.Valid() {
		c.Writer.WriteHeader(http.StatusOK)
		c.Writer.Write([]byte(loggedIn))
	} else {
		c.Writer.WriteHeader(http.StatusOK)
		c.Writer.Write([]byte(htmlIndex))
	}
}

func logoutUser(c *gin.Context) {
	c.SetCookie(
		"login",
		"",
		-1,
		"/",
		"127.0.0.1",
		false,
		true,
	)
	err := helpers.LogoutGithub(token)
	if err != nil {
		log.LogError(err)
	}

	c.Writer.WriteHeader(http.StatusOK)
	c.Writer.Write([]byte(logOut))
}

func loginGithub(c *gin.Context) {
	url := helpers.GenerateOauthURL(c)
	c.Redirect(http.StatusTemporaryRedirect, url)
}

func githubCallback(c *gin.Context) {
	var err error

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

	token, err = helpers.ExchangeToken(c)
	if err != nil {
		log.LogError(err)
	}

	client := helpers.CreateClient(token)
	_, _, err = helpers.GetUser(client)

	if err != nil {
		err = fmt.Errorf("failed to get user with error : %s", err)
		log.LogError(err)
		c.JSON(http.StatusUnauthorized, gin.H{"error": err.Error()})
	}
	c.JSON(200, token)

	// newUser := &model.User{GithubID: sql.NullInt64{Int64: *user.ID, Valid: true}}
	// //err = newUser.UpdateUser()
	// if err != nil {
	// 	log.LogError(err)
	// 	c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
	// } else {
	// 	c.JSON(http.StatusOK, token)
	// }
}
