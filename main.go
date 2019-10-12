package main

import (
	_ "github.com/go-sql-driver/mysql"
	"github.com/zainokta/reeze/config"
	"github.com/zainokta/reeze/controller"
	"github.com/zainokta/reeze/model"
)

func main() {
	log := &config.Logger{}
	log.InitLogger()

	config.SetupDatabase()
	db := model.InitDatabase(log)
	defer db.Close()
	r := controller.SetupRouter(log)

	r.Run(":8000")
}
