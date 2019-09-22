package model

import (
	"time"
)

type Project struct {
	ID          uint64     `gorm:"primary_key;auto_increment" json:"id"`
	Name        string     `gorm:"type:varchar(20);not null" json:"name"`
	Repository  string     `gorm:"type:varchar(100);default:null" json:"repository"`
	Description string     `sql:"type:text;" json:"description"`
	CreatedAt   *time.Time `gorm:"default:CURRENT_TIMESTAMP" json:"created_at"`
	UpdatedAt   *time.Time `gorm:"default:CURRENT_TIMESTAMP" json:"updated_at"`
}
