package auth

import (
	"encoding/json"
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
			"error": err.Error(),
		})
	}
	input := &model.User{Email: creds.Email}

	result := &model.User{}
	result, err = input.GetPasswordByEmail(input.Email)
	if err != nil {
		log.LogError(err)
		c.JSON(http.StatusUnauthorized, gin.H{
			"error": "Invalid Email and/or Password.",
		})
	}

	if result != nil {
		inputPassword := []byte(creds.Password)
		comparePassword := []byte(result.Password)

		err = bcrypt.CompareHashAndPassword(comparePassword, inputPassword)
		if err != nil {
			log.LogError(err)
			c.JSON(http.StatusUnauthorized, gin.H{
				"error": "Invalid Email and/or Password.",
			})
		} else {
			c.JSON(http.StatusOK, gin.H{
				"success": "Logged in",
			})
		}
	}
}
