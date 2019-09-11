package model

import (
	"github.com/jinzhu/gorm"
)

type Project struct {
	gorm.Model
	Name       string `gorm:"type:varchar(20);not null"`
	Repository string `gorm:"type:varchar(100);default:null"`
}
