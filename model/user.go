package model

import (
	"fmt"

	"github.com/jinzhu/gorm"
)

type User struct {
	gorm.Model
	Username string `gorm:"type:varchar(100)"`
	GithubID int64  `gorm:"type:integer(10);unique_index"`
}

func GetUser() {

}

func (u User) CreateUser() {
	db, err := Database()
	if err != nil {
		fmt.Println(err)
	}
	db.NewRecord(u)
	db.Create(&u)
}
