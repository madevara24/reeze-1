package model

import (
	"errors"
	"time"
)

//User is Struct of User
type User struct {
	ID        uint64     `json:"id"`
	Username  string     `json:"username"`
	CreatedAt *time.Time `json:"created_at"`
	UpdatedAt *time.Time `json:"updated_at"`
}

func (u *User) GetUserById(uid uint64) (*User, error) {
	stmt, err := db.Prepare("SELECT * FROM users WHERE id = ?")
	if err != nil {
		log.LogError(err)
		return &User{}, err
	}

	err = stmt.QueryRow(uid).Scan(&u.ID, &u.Username, &u.CreatedAt, &u.UpdatedAt)

	if err != nil {
		log.LogError(err)
		return nil, err
	}

	return u, nil
}

func (u *User) GetUserByUsername(username string) (*User, error) {
	stmt, err := db.Prepare("SELECT * FROM users WHERE username = ?")
	if err != nil {
		log.LogError(err)
		return nil, err
	}

	err = stmt.QueryRow(username).Scan(&u.ID, &u.Username, &u.CreatedAt, &u.UpdatedAt)

	if err != nil {
		log.LogError(err)
		return nil, err
	}

	return u, nil
}

func (u *User) CreateUser() (uint64, error) {
	var lastInsertID uint64
	err := db.QueryRow("INSERT INTO users (username) VALUES(?)", u.Username).Scan(&lastInsertID)
	if err != nil {
		log.LogError(err)
		return 0, err
	}
	return lastInsertID, nil
}

func (u *User) UpdateUser() error {
	return errors.New("Function is not implemented yet")
}
