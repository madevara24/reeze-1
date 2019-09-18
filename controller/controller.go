package controller

import (
	"github.com/gin-gonic/gin"
)

func SetupRouter() *gin.Engine {
	r := gin.Default()

	r.GET("/", homeIndex)
	r.GET("/login", loginGithub)
	r.GET("/github-callback", githubCallback)

	api := r.Group("/api")
	{
		v1 := api.Group("/v1")
		{
			v1.POST("/create-branch", createBranch)
			v1.POST("/create-pr", createPullRequest)
		}

	}

	return r
}
