package model

import (
	"fmt"

	"github.com/jinzhu/gorm"
	_ "github.com/jinzhu/gorm/dialects/mysql"
)

func SetupDatabase() {
	db, err := gorm.Open("mysql", "root:@/dbname?charset=utf8&parseTime=True&loc=Local")
	if err != nil {
		fmt.Println(err)
	}
	defer db.Close()
}
