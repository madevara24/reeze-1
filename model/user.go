package model

import (
	"strconv"
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

func (u *User) GetUserById(uid uint64) (*User, error) {
	stmt, err := db.Prepare("SELECT * FROM users WHERE id = ?")
	if err != nil {
		log.LogError(err)
		return nil, err
	}

	var user = u
	err = stmt.QueryRow(uid).Scan(&user.ID, &user.Username, &user.Fullname, &user.GithubID, &user.CreatedAt, &user.UpdatedAt)
	if err != nil {
		log.LogError(err)
		return nil, err
	}
	log.LogInfo("User with id " + strconv.FormatUint(uid, 10) + " is success")
	return user, nil
}

func (u *User) CreateUser() error {
	_, err := db.Exec("INSERT INTO users (username, fullname, github_id) VALUES(?, ?, ?)", u.Username, u.Fullname, u.GithubID)
	if err != nil {
		log.LogError(err)
		return err
	}
	return nil
}
