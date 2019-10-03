package model

import "time"

type CardLog struct {
	ID         uint64     `json:"id"`
	CardID     uint64     `json:"card_id"`
	StartedAt  *time.Time `json:"started_at"`
	FinishedAt *time.Time `json:"finished_at"`
	ReleasedAt *time.Time `json:"released_at"`
	CreatedAt  *time.Time `json:"created_at"`
	UpdatedAt  *time.Time `json:"updated_at"`
}
