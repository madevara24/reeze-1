package model

import (
	"database/sql"

	"github.com/reeze-project/reeze/config"
)

var (
	db    *sql.DB
	log   *config.Logger
	dbURL string
)

func InitDatabase(confLogger *config.Logger) *sql.DB {
	log = confLogger
	dbURL = config.GetDatabaseUsername() + ":@tcp(" + config.GetDatabaseHost() + ":3306)/" + config.GetDatabaseName()

	db, _ = sql.Open("mysql", dbURL+"?parseTime=true")

	return db
}
