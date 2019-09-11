package model

import (
	"database/sql"

	"github.com/jinzhu/gorm"
)

type Project struct {
	gorm.Model
	Name       string `gorm:"type:varchar(20);not null"`
	Repository sql.NullString
}
