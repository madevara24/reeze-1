package helpers

import (
	"crypto/rand"
	"encoding/base64"
	"encoding/json"
	"net/http"
	"os"
	"time"

	"github.com/gin-gonic/gin"
	"golang.org/x/oauth2"
)

func GenerateStateOauthCookie(c *gin.Context) string {
	var expiration = time.Now().Add(30 * 24 * time.Hour)

	b := make([]byte, 16)
	rand.Read(b)
	state := base64.URLEncoding.EncodeToString(b)
	cookie := http.Cookie{Name: "login", Value: state, Expires: expiration, HttpOnly: true, MaxAge: 3600 * 24 * 30}
	http.SetCookie(c.Writer, &cookie)

	return state
}

func SetUserCookie(c *gin.Context, id uint64, token *oauth2.Token) {
	value := new(struct {
		UserID    uint64        `json:"user_id"`
		UserToken *oauth2.Token `json:"user_token"`
	})
	value.UserID = id
	value.UserToken = token
	jsonByte, _ := json.Marshal(value)
	encodedValue := base64.URLEncoding.EncodeToString(jsonByte)

	var expiration = time.Now().Add(30 * 24 * time.Hour)
	cookie := http.Cookie{Name: "user", Value: encodedValue, Expires: expiration, HttpOnly: true, MaxAge: 3600 * 24 * 30}
	http.SetCookie(c.Writer, &cookie)
}

func RemoveCookie(c *gin.Context, cookieName string) {
	c.SetCookie(
		cookieName,
		"",
		-1,
		"/",
		os.Getenv("APP_URL"),
		false,
		true,
	)
}
