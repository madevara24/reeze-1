package middleware

import (
	"net/http"

	"github.com/gin-gonic/gin"
	"github.com/reeze-project/reeze/helpers"
)

func AuthMiddleware(c *gin.Context) {
	jsonToken := c.Request.Header.Get("token")
	token, err := helpers.TokenFromJSON(jsonToken)

	client := helpers.CreateClient(token)
	_, _, err = helpers.GetUser(client)

	if err != nil {
		c.AbortWithStatusJSON(http.StatusUnauthorized, "Token not found")
	}
	c.Next()
}
