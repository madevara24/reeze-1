package model

import (
	"database/sql"

	"github.com/reeze-project/reeze/config"
)

var (
	db         *sql.DB
	log        *config.Logger
	dbName     string
	dbUsername string
	dbPassword string
	dbURL      string
)

func InitDatabase(confLogger *config.Logger) *sql.DB {
	log = confLogger

	db, _ = sql.Open("mysql", dbURL)

	return db
}
