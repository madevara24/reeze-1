package helper

import (
	"context"
	"encoding/json"
	"fmt"
	"log"
	"net/http"

	"github.com/gin-gonic/gin"
	"github.com/google/go-github/github"
	"github.com/reeze-project/reeze/config"
	"golang.org/x/oauth2"
)

func VerifyUser(c *gin.Context) (*github.User, *github.Client, error) {
	ctx := context.Background()
	jsonToken := c.Request.Header.Get("token")
	token, err := TokenFromJSON(jsonToken)
	if err != nil {
		log.Fatalf("error %s", err)
	}
	oauthClient := config.OauthConf.Client(oauth2.NoContext, token)
	client := github.NewClient(oauthClient)
	user, _, err := client.Users.Get(ctx, "")
	if err != nil {
		fmt.Printf("client.Users.Get() faled with '%s'\n", err)
		c.Redirect(http.StatusTemporaryRedirect, "/")
		return nil, nil, err
	}
	return user, client, nil
}

func TokenToJSON(token *oauth2.Token) (string, error) {
	if tkn, err := json.Marshal(token); err != nil {
		return "", err
	} else {
		return string(tkn), nil
	}
}

func TokenFromJSON(jsonToken string) (*oauth2.Token, error) {
	var token oauth2.Token
	if err := json.Unmarshal([]byte(jsonToken), &token); err != nil {
		return nil, err
	}
	return &token, nil
}
