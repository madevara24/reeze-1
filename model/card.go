package model

import (
	"time"
)

type Card struct {
	ID uint64 `json:"id"`
	// ProjectID      uint64     `json:"project_id"`
	// Project        Project    `gorm:"foreignkey:ProjectID" sql:"-" json:"-"`
	Title          string     `json:"title"`
	Description    string     `json:"description"`
	State          string     `json:"state"`
	MovedToBacklog *time.Time `json:"moved_to_backlog_at"`
	Started        *time.Time `json:"started_at"`
	Finished       *time.Time `json:"finished_at"`
	Released       *time.Time `json:"released_at"`
	Requestor      User       `json:"requestor"`
	Point          uint       `json:"point"`
	Owner          User       `json:"owner"`
	Type           string     `json:"type"`
	BranchName     string     `json:"branch_name"`
	CreatedAt      *time.Time `json:"created_at"`
	UpdatedAt      *time.Time `json:"updated_at"`
}
