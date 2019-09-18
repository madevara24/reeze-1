package model

import (
	"database/sql"
	"fmt"

	"github.com/jinzhu/gorm"
	"github.com/reeze-project/reeze/config"
)

var (
	migration  *gorm.DB
	db         *sql.DB
	dbName     string
	dbUsername string
	dbPassword string
	dbURL      string
)

func SchemaAutoMigrate() {
	dbName = config.GetDatabaseName()
	dbUsername = config.GetDatabaseUsername()
	dbPassword = config.GetDatabasePassword()
	dbURL = fmt.Sprintf("%s:%s@/%s?charset=utf8&parseTime=True&loc=Local", dbUsername, dbPassword, dbName)

	var err error
	migration, err = gorm.Open("mysql", dbURL)

	if err != nil {
		fmt.Println(err)
	}
	defer migration.Close()
	migration.AutoMigrate(&User{}, &Card{})
}

func InitDatabase() (*sql.DB, error) {
	var err error
	db, err = sql.Open("mysql", dbURL)
	if err != nil {
		return nil, err
	}
	return db, nil
}
