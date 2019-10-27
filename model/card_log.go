package model

import "time"

type CardLog struct {
	ID        uint64     `json:"id"`
	CardID    uint64     `json:"card_id"`
	State     string     `json:"state"`
	CreatedAt *time.Time `json:"created_at"`
	UpdatedAt *time.Time `json:"updated_at"`
}

func (cl *CardLog) InsertCardLog(cid uint64, state string) error {
	_, err := db.Exec(`INSERT INTO card_logs (card_id, state) VALUES (?, ?)`, cid, state)
	if err != nil {
		log.LogError(err)
		return err
	}
	return nil
}
