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
	log        *config.Logger
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

func InitDatabase(confLogger *config.Logger) *sql.DB {
	log = confLogger

	db, _ = sql.Open("mysql", dbURL)

	return db
}
