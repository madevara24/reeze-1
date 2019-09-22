package auth

import (
	"encoding/json"
	"fmt"
	"net/http"

	"github.com/gin-gonic/gin"
	"github.com/reeze-project/reeze/model"
	"golang.org/x/crypto/bcrypt"
)

type Credentials struct {
	Email    string `json:"email"`
	Password string `json:"password"`
}

func Login(c *gin.Context) {
	res, err := c.GetRawData()

	if err != nil {
		log.LogError(err)
	}
	creds := &Credentials{}
	err = json.Unmarshal(res, &creds)
	if err != nil {
		log.LogError(err)
		c.JSON(http.StatusInternalServerError, gin.H{
			"error": err,
		})
	}
	input := &model.User{Email: creds.Email}

	result := &model.User{}
	result, err = input.GetPasswordByEmail(input.Email)
	if err != nil {
		log.LogError(err)
		c.JSON(http.StatusInternalServerError, gin.H{
			"error": err,
		})
	}

	inputPassword := []byte(creds.Password)
	comparePassword := []byte(result.Password)
	fmt.Println(creds.Password)
	err = bcrypt.CompareHashAndPassword(comparePassword, inputPassword)
	if err != nil {
		log.LogError(err)
		c.JSON(http.StatusUnauthorized, gin.H{
			"error": err,
		})
	}
	c.JSON(200, gin.H{
		"success": "Logged in",
	})
}
