package helpers

import (
	"encoding/base64"
	"encoding/json"

	"github.com/gin-gonic/gin"
	"golang.org/x/oauth2"
)

func GetLoginUserID(c *gin.Context) uint64 {
	cookie, _ := c.Request.Cookie("user")
	creds := new(struct {
		UserID    uint64        `json:"user_id"`
		UserToken *oauth2.Token `json:"user_token"`
	})

	decodedValue, _ := base64.URLEncoding.DecodeString(cookie.Value)
	_ = json.Unmarshal(decodedValue, &creds)

	return creds.UserID
}
