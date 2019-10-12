package config

import (
	"log"
	"os"

	"github.com/joho/godotenv"
	"golang.org/x/oauth2"
	githuboauth "golang.org/x/oauth2/github"
)

var (
	//OauthConf is a variable to store oauth config
	OauthConf        = SetupConfig()
	databaseName     string
	databaseUsername string
	databaseHost     string
	databasePassword string
)

func init() {
	SetupDatabase()
}

//SetupConfig is a function to setup an oauth config
func SetupConfig() *oauth2.Config {
	err := godotenv.Load()
	if err != nil {
		log.Print("Error loading .env file!")
	}

	return &oauth2.Config{
		ClientID:     os.Getenv("CLIENT_ID"),
		ClientSecret: os.Getenv("CLIENT_SECRET"),
		Scopes:       []string{"read:user", "repo", "admin:org_hook"},
		Endpoint:     githuboauth.Endpoint,
	}
}

func SetupDatabase() {
	databaseName = os.Getenv("DB_DATABASE")
	databaseUsername = os.Getenv("DB_USERNAME")
	databaseHost = os.Getenv("DB_HOST")
	databasePassword = os.Getenv("DB_PASSWORD")
}

func GetDatabaseName() string {
	return databaseName
}

func GetDatabaseUsername() string {
	return databaseUsername
}

func GetDatabaseHost() string {
	return databaseHost
}

func GetDatabasePassword() string {
	return databasePassword
}
