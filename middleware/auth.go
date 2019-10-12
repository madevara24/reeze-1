package middleware

import (
	"net/http"

	"golang.org/x/oauth2"

	"github.com/gin-gonic/gin"
	"github.com/reeze-project/reeze/helpers"
)

func AuthMiddleware(c *gin.Context) {
	var token *oauth2.Token
	var err error
	headerToken := c.Request.Header.Get("token")
	if headerToken == "" {
		token, err = helpers.GetToken(c)
		if err != nil {
			c.AbortWithStatusJSON(http.StatusUnauthorized, "Invalid Token")
		}
	} else {
		token, _ = helpers.TokenFromJSON(headerToken)
	}

	if token.Valid() {
		c.Next()
	} else {
		c.AbortWithStatusJSON(http.StatusUnauthorized, "Invalid token")
	}
}
