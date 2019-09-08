package controller

import (
	"github.com/gin-gonic/gin"
)

func SetupRouter() *gin.Engine {
	r := gin.Default()

	r.GET("/", homeIndex)
	r.GET("/login", loginGithub)
	r.GET("/github-callback", githubCallback)
	r.POST("/create-branch", createBranch)
	return r
}
