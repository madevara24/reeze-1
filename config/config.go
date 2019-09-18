package config

import (
	"log"
	"math/rand"
	"os"
	"time"

	"github.com/joho/godotenv"
	"golang.org/x/oauth2"
	githuboauth "golang.org/x/oauth2/github"
)

var (
	//OauthConf is a variable to store oauth config
	OauthConf = SetupConfig()

	//OauthStateString is a variable to store oauth state
	OauthStateString = randStringRunes(30)
	letterRunes      = []rune("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890")
	databaseName     string
	databaseUsername string
	databaseHost     string
	databasePassword string
)

func init() {
	rand.Seed(time.Now().UnixNano())
}

func randStringRunes(n int) string {
	b := make([]rune, n)
	for i := range b {
		b[i] = letterRunes[rand.Intn(len(letterRunes))]
	}
	return string(b)
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
	databaseName = os.Getenv("DB_NAME")
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
