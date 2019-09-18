package model

import (
	"time"
)

type Card struct {
	ID             uint64     `gorm:"primary_key;auto_increment" json:"id"`
	Title          string     `gorm:"type:varchar(20)" json:"title"`
	Description    string     `gorm:"type:varchar(255)" json:"description"`
	State          string     `sql:"enum('started', 'finished')" json:"state"`
	MovedToBacklog *time.Time `gorm:"default:CURRENT_TIMESTAMP" json:"moved_to_backlog_at"`
	Started        *time.Time `gorm:"default:CURRENT_TIMESTAMP" json:"started_at"`
	Finished       *time.Time `gorm:"default:CURRENT_TIMESTAMP" json:"finished_at"`
	Released       *time.Time `gorm:"default:CURRENT_TIMESTAMP" json:"released_at"`
	Requestor      User       `json:"requestor"`
	Point          uint       `json:"point"`
	Owner          User       `json:"owner"`
	Type           string     `sql:"enum('feature', 'bug', 'chore')" json:"type"`
	BranchName     string     `gorm:"type:varchar(100);default:null" json:"branch_name"`
	CreatedAt      time.Time  `gorm:"default:CURRENT_TIMESTAMP" json:"created_at"`
	UpdatedAt      time.Time  `gorm:"default:CURRENT_TIMESTAMP" json:"updated_at"`
}
