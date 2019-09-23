package main

import (
	"github.com/reeze-project/reeze/config"
	"github.com/reeze-project/reeze/controller"
	"github.com/reeze-project/reeze/model"

	_ "github.com/jinzhu/gorm/dialects/mysql"
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
