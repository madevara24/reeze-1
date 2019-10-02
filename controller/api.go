package controller

import (
	"github.com/gin-contrib/sessions"
	"github.com/gin-contrib/sessions/cookie"
	"github.com/gin-gonic/gin"
	"github.com/reeze-project/reeze/config"
)

var log *config.Logger

func SetupRouter(confLogger *config.Logger) *gin.Engine {
	log = confLogger

	r := gin.Default()

	store := cookie.NewStore([]byte("reeze_project"))

	r.Use(sessions.Sessions("user_session", store))

	r.GET("/", homeIndex)
	r.POST("/logout", logoutUser)

	r.GET("/login-github", loginGithub)
	r.GET("/github-callback", githubCallback)

	api := r.Group("/api/v1")
	{
		api.GET("/test", testAPI)
		api.POST("/create-branch", createBranch)
		api.POST("/create-pr", createPullRequest)
	}

	return r
}
