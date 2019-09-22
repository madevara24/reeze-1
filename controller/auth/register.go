package auth

import (
	"encoding/json"

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
	}

	hashedPassword, err := bcrypt.GenerateFromPassword([]byte(register.Password), 10)
	user := &model.User{Username: register.Username, Email: register.Email, Password: string(hashedPassword), Fullname: register.Fullname}

	err = user.CreateUser()
	if err != nil {
		log.LogError(err)
	}

	c.JSON(200, user)
}
