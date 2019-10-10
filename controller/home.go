package controller

import (
	"fmt"
	"net/http"

	"github.com/gin-gonic/gin"
	"github.com/reeze-project/reeze/helpers"
	"github.com/reeze-project/reeze/model"
)

const htmlIndex = `<html><body>
Logged in with <a href="/login-github">GitHub</a>
</body></html>`

const loggedIn = `<html><body>
Hello user!
<form action="/logout" method="POST">
	<button type="submit">LogOut</button>
</form>
</body></html>`

func testAPI(c *gin.Context) {
	user := &model.Card{}
	users, err := user.GetCardsByProject(1)
	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
	} else {
		c.JSON(http.StatusOK, gin.H{"data": users})
	}
}

func homeIndex(c *gin.Context) {
	_, err := c.Request.Cookie("user")
	if err != nil {
		c.Writer.WriteHeader(http.StatusOK)
		c.Writer.Write([]byte(htmlIndex))
	} else {
		c.Writer.WriteHeader(http.StatusOK)
		c.Writer.Write([]byte(loggedIn))
	}
}

func logoutUser(c *gin.Context) {
	token := helpers.GetToken(c)
	err := helpers.LogoutGithub(token)
	if err != nil {
		log.LogError(err)
	}
	helpers.RemoveCookie(c, "login")
	helpers.RemoveCookie(c, "user")

	c.Redirect(http.StatusPermanentRedirect, "/")
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

	token, err := helpers.ExchangeToken(c)
	if err != nil {
		log.LogError(err)
	}

	client := helpers.CreateClient(token)
	user, _, err := helpers.GetUser(client)

	if err != nil {
		err = fmt.Errorf("Failed to get user with error : %s", err)
		log.LogError(err)
		c.JSON(http.StatusUnauthorized, gin.H{"error": err.Error()})
	}

	checkUser := &model.User{}
	check, err := checkUser.GetUserByUsername(*user.Login)
	if err != nil {
		log.LogError(err)
	}

	if check == nil {
		newUser := &model.User{Username: *user.Login}
		id, err := newUser.CreateUser()
		helpers.SetUserCookie(c, id, token)

		if err != nil {
			err = fmt.Errorf("Cannot create user")
			log.LogError(err)
			c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
		} else {
			// c.Redirect(http.StatusPermanentRedirect, "/")

			c.JSON(http.StatusOK, token)
		}
	} else {
		helpers.SetUserCookie(c, check.ID, token)
		fmt.Println("User logged in")

		// c.Redirect(http.StatusPermanentRedirect, "/")
		c.JSON(http.StatusOK, token)
	}

}
