package model

import (
	"time"
)

type Project struct {
	ID             uint64     `json:"id"`
	Name           string     `json:"name"`
	Repository     string     `json:"repository"`
	Description    string     `json:"description"`
	SprintDuration uint8      `json:"sprint_duration"`
	SprintStartDay uint8      `json:"sprint_start_day"`
	CreatedAt      *time.Time `json:"created_at"`
	UpdatedAt      *time.Time `json:"updated_at"`
}
