package model

import (
	"fmt"

	"github.com/jinzhu/gorm"
	"github.com/reeze-project/reeze/config"
)

func Database() (*gorm.DB, error) {
	var dbName string = config.GetDatabaseName()
	var dbUsername string = config.GetDatabaseUsername()
	var dbPassword string = config.GetDatabasePassword()
	db, err := gorm.Open("mysql", dbUsername+":"+dbPassword+"@/"+dbName+"?charset=utf8&parseTime=True&loc=Local")
	if err != nil {
		fmt.Println(err)
		return nil, err
	}
	defer db.Close()
	return db, nil
}

func InitDatabase() {
	db, err := Database()
	if err != nil {
		fmt.Println(err)
	}
	db.AutoMigrate(&User{})
}
