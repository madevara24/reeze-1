package model

import "time"

type ProjectMember struct {
	UserID    uint64     `json:"user_id"`
	ProjectID uint64     `json:"project_id"`
	CreatedAt *time.Time `json:"created_at"`
	UpdatedAt *time.Time `json:"updated_at"`
}
