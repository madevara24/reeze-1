package model

import (
	"time"
)

type User struct {
	ID        uint64     `gorm:"primary_key;auto_increment" json:"id"`
	Username  string     `gorm:"type:varchar(100);not null" json:"username"`
	Email     string     `gorm:"type:varchar(100);unique;not null" json:"email"`
	Password  string     `gorm:"not null" json:"-"`
	Fullname  string     `gorm:"type:varchar(100)" json:"fullname"`
	GithubID  int64      `gorm:"type:integer(10)" json:"github_id"`
	CreatedAt *time.Time `gorm:"default:CURRENT_TIMESTAMP" json:"created_at"`
	UpdatedAt *time.Time `gorm:"default:CURRENT_TIMESTAMP" json:"updated_at"`
}

func (u *User) GetUserById(uid uint64) (*User, error) {
	stmt, err := db.Prepare("SELECT * FROM users WHERE id = ?")
	user := u
	if err != nil {
		log.LogError(err)
		return nil, err
	}

	err = stmt.QueryRow(uid).Scan(&user.ID, &user.Username, &user.Email, &user.Password, &user.Fullname, &user.GithubID, &user.CreatedAt, &user.UpdatedAt)

	if err != nil {
		log.LogError(err)
		return nil, err
	}

	return user, nil
}

func (u *User) CreateUser() error {
	_, err := db.Exec("INSERT INTO users (username, email, password, fullname, github_id) VALUES(?, ?, ?, ?, ?) ", u.Username, u.Email, u.Password, u.Fullname, u.GithubID)
	if err != nil {
		log.LogError(err)
		return err
	}
	return nil
}
