package model

import "time"

type CardLog struct {
	ID        uint64     `json:"id"`
	CardID    uint64     `json:"card_id"`
	State     string     `json:"state"`
	CreatedAt *time.Time `json:"created_at"`
	UpdatedAt *time.Time `json:"updated_at"`
}
