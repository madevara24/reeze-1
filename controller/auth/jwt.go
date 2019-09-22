package auth

import (
	"encoding/json"
	"time"

	jwt "github.com/dgrijalva/jwt-go"
	"github.com/reeze-project/reeze/config"
)

var log *config.Logger

func init() {
	log = &config.Logger{}
	log.InitLogger()
}

type M map[string]interface{}

type JWTClaims struct {
	jwt.StandardClaims
	Username string `json:"username"`
	Fullname string `json:"fullname"`
	Email    string `json:"email"`
}

func ClaimToken() (string, error) {
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

	tokenString, err := json.MarshalIndent(M{"token": signedToken}, "", " ")
	if err != nil {
		log.LogError(err)
		return "", err
	}
	return string(tokenString), nil
}
