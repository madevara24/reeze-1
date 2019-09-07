package main

import (
	"github.com/reeze-project/reeze/config"
	"github.com/reeze-project/reeze/controller"
)

func main() {
	config.SetupConfig()
	r := controller.SetupRouter()

	r.Run(":8000")
}
