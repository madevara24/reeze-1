package main

import (
	"fmt"

	"github.com/reeze-project/reeze/config"
	"github.com/reeze-project/reeze/controller"
	"github.com/reeze-project/reeze/model"

	_ "github.com/jinzhu/gorm/dialects/mysql"
)

func main() {
	config.SetupConfig()
	config.SetupDatabase()
	model.SchemaAutoMigrate()
	db, err := model.InitDatabase()
	defer db.Close()
	if err != nil {
		fmt.Println(err)
	}

	r := controller.SetupRouter()

	r.Run(":8000")
}
