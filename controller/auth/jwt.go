package auth

import (
	"encoding/json"
	"time"

	"github.com/gin-contrib/sessions"
	"github.com/gin-gonic/gin"

	jwt "github.com/dgrijalva/jwt-go"
	"github.com/reeze-project/reeze/config"
)

var log *config.Logger

type M map[string]interface{}

type JWTClaims struct {
	jwt.StandardClaims
	Username string `json:"username"`
	Fullname string `json:"fullname"`
	Email    string `json:"email"`
}

func ClaimToken(c *gin.Context) {
	session := sessions.Default(c)
	claims := JWTClaims{
		StandardClaims: jwt.StandardClaims{
			Issuer:    config.ApplicationName,
			ExpiresAt: time.Now().Add(config.LoginExpirationDuration).Unix(),
		},
		// Username: user["username"].(string),
		// Fullname: user["fullname"].(string),
		// Email:    user["email"].(string),
	}
	token := jwt.NewWithClaims(config.JWTSigningMethod, claims)
	signedToken, err := token.SignedString(config.JWTSignatureKey)
	if err != nil {
		log.LogError(err)
	}

	tokenString, _ := json.MarshalIndent(M{"token": signedToken}, "", " ")
	session.Set("jwt_token", tokenString)
	err = session.Save()
	if err != nil {
		log.LogError(err)
	}
}
