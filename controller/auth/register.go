package auth

import (
	"encoding/json"
	"net/http"

	"github.com/gin-gonic/gin"
	"github.com/reeze-project/reeze/model"
	"golang.org/x/crypto/bcrypt"
)

type RegisteredUser struct {
	Username string `json:"username"`
	Email    string `json:"email"`
	Password string `json:"password"`
	Fullname string `json:"fullname"`
}

func RegisterUser(c *gin.Context) {
	res, err := c.GetRawData()
	if err != nil {
		log.LogError(err)
	}
	register := &RegisteredUser{}
	err = json.Unmarshal(res, &register)
	if err != nil {
		log.LogError(err)
		c.JSON(http.StatusInternalServerError, gin.H{
			"error": err.Error(),
		})
	}

	hashedPassword, err := bcrypt.GenerateFromPassword([]byte(register.Password), 10)
	if err != nil {
		log.LogError(err)
		c.JSON(http.StatusInternalServerError, gin.H{
			"error": err.Error(),
		})
	}

	user := &model.User{Username: register.Username, Email: register.Email, Password: string(hashedPassword), Fullname: register.Fullname}

	err = user.CreateUser()
	if err != nil {
		log.LogError(err)
		c.JSON(http.StatusInternalServerError, gin.H{
			"error": err.Error(),
		})
	}

	c.JSON(http.StatusOK, user)
}
