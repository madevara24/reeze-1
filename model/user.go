package model

import (
	"fmt"
	"time"
)

type User struct {
	ID        uint64    `gorm:"primary_key;auto_increment" json:"id"`
	Username  string    `gorm:"type:varchar(100)" json:"username"`
	Fullname  string    `gorm:"type:varchar(100)" json:"fullname"`
	GithubID  int64     `gorm:"type:integer(10);unique_index" json:"github_id"`
	CreatedAt time.Time `gorm:"default:CURRENT_TIMESTAMP" json:"created_at"`
	UpdatedAt time.Time `gorm:"default:CURRENT_TIMESTAMP" json:"updated_at"`
}

func (u User) CreateUser() {
	_, err := db.Exec("INSERT INTO users (username, fullname, github_id) VALUES(?, ?, ?)", u.Username, u.Fullname, u.GithubID)
	if err != nil {
		fmt.Println(err)
	}
}
