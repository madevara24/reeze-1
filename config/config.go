package config

import (
	"log"
	"os"

	"github.com/joho/godotenv"
	"golang.org/x/oauth2"
	githuboauth "golang.org/x/oauth2/github"
)

var (
	OauthConf = SetupConfig()

	OauthStateString = "TA Zain Devara"
)

func SetupConfig() *oauth2.Config {
	err := godotenv.Load()
	if err != nil {
		log.Fatal("Error loading .env file!")
	}

	return &oauth2.Config{
		ClientID:     os.Getenv("ClientID"),
		ClientSecret: os.Getenv("ClientSecret"),
		Scopes:       []string{"user", "repo"},
		Endpoint:     githuboauth.Endpoint,
	}
}
