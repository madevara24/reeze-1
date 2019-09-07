package model

import (
	"log"

	"github.com/jinzhu/gorm"
	_ "github.com/jinzhu/gorm/dialects/mysql"
)

type User struct {
	gorm.Model
	ID uint
}

func GetUser() {

}

func errCheck(err error) {
	if err != nil {
		log.Fatalf("Error %v", err)
	}
}

func main() {
	db, err := gorm.Open("mysql", "user:password@/dbname?charset=utf8&parseTime=True&loc=Local")
	errCheck(err)
	defer db.Close()
}
