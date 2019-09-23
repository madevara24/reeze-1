package controller

import (
	b64 "encoding/base64"
	"fmt"
	"net/http"
	"time"

	"github.com/gin-gonic/gin"
	"github.com/reeze-project/reeze/config"
	"github.com/reeze-project/reeze/helpers"
	"golang.org/x/oauth2"
)

var token *oauth2.Token

const htmlIndex = `<html><body>
Logged in with <a href="/login-github">GitHub</a>
</body></html>
`

const loggedIn = `<html><body>
Hello user! 
<form action="/logout" method="POST">
	<button type="submit">LogOut</button>
</form>
</body></html>`

const logOut = `<html><body>
Berhasil Log out, <a href="/login-github">Login Kembali</a>
</body></html>`

func homeIndex(c *gin.Context) {
	if token.Valid() {
		c.Writer.WriteHeader(http.StatusOK)
		c.Writer.Write([]byte(loggedIn))
	} else {
		c.Writer.WriteHeader(http.StatusOK)
		c.Writer.Write([]byte(htmlIndex))
	}
	// oauthState, _ := c.Request.Cookie("login")
	// if oauthState == nil {
	// 	c.Writer.WriteHeader(http.StatusOK)
	// 	c.Writer.Write([]byte(htmlIndex))
	// } else {
	// 	c.Writer.WriteHeader(http.StatusOK)
	// 	c.Writer.Write([]byte(loggedIn))
	// }
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
	token.Expiry = time.Now().Add(time.Second * 1)
	var client = &http.Client{}
	apiURL := "https://api.github.com/applications/"
	url := apiURL + config.OauthConf.ClientID + "/grants/" + token.AccessToken

	request, err := http.NewRequest("DELETE", url, nil)
	headerTokenRaw := config.OauthConf.ClientID + ":" + config.OauthConf.ClientSecret
	basic := b64.StdEncoding.EncodeToString([]byte(headerTokenRaw))

	request.Header.Set("Authorization", "Basic "+basic)
	if err != nil {
		log.LogError(err)
	}
	_, err = client.Do(request)
	if err != nil {
		log.LogError(err)
	}

	c.Writer.WriteHeader(http.StatusOK)
	c.Writer.Write([]byte(logOut))
}

func loginGithub(c *gin.Context) {
	state := helpers.GenerateStateOauthCookie(c)
	url := config.OauthConf.AuthCodeURL(state, oauth2.AccessTypeOnline)

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

	code := c.Request.FormValue("code")
	token, err = config.OauthConf.Exchange(oauth2.NoContext, code)
	token.Expiry = time.Now().Add(time.Hour * 24)
	if err != nil {
		log.LogError(err)
		c.Redirect(http.StatusTemporaryRedirect, "/")
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
