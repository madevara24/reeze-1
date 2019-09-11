package main

import (
	"github.com/reeze-project/reeze/config"
	"github.com/reeze-project/reeze/controller"
	//"github.com/reeze-project/reeze/database"
)

func main() {
	config.SetupConfig()
	//database.SetupDatabase()
	r := controller.SetupRouter()

	r.Run(":8000")
}
