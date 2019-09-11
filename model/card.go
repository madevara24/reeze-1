package model

import (
	"time"

	"github.com/jinzhu/gorm"
)

type Card struct {
	gorm.Model
	Title          string `gorm:"type:varchar(20)"`
	Description    string `gorm:"type:varchar(255)"`
	State          string `sql:"enum('started', 'finished')"`
	MovedToBacklog *time.Time
	Started        *time.Time
	Finished       *time.Time
	Released       *time.Time
	Requestor      User
	Point          uint
	Owner          User
	Type           string `sql:"enum('feature', 'bug', 'chore')"`
	BranchName     string `gorm:"type:varchar(100);default:null"`
}
