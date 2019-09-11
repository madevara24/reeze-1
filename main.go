package main

import (
	"github.com/reeze-project/reeze/config"
	"github.com/reeze-project/reeze/controller"
	"github.com/reeze-project/reeze/model"

	_ "github.com/jinzhu/gorm/dialects/mysql"
)

func main() {
	config.SetupConfig()
	config.SetupDatabase()
	model.InitDatabase()
	r := controller.SetupRouter()

	r.Run(":8000")
}
