package main

import (
	_ "database/sql"

	"github.com/reeze-project/reeze/config"
	"github.com/reeze-project/reeze/controller"
	"github.com/reeze-project/reeze/model"
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
