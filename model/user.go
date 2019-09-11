package model

import (
	"github.com/jinzhu/gorm"
)

type User struct {
	gorm.Model
	Username string `gorm:"type:varchar(100)"`
	Email    string `gorm:"type:varchar(100);unique_index"`
	GithubID uint
}

func GetUser() {

}
