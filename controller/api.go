package controller

import (
	"github.com/gin-contrib/sessions"
	"github.com/gin-contrib/sessions/cookie"
	"github.com/gin-gonic/gin"
	"github.com/reeze-project/reeze/config"
	"github.com/reeze-project/reeze/controller/auth"
)

var log *config.Logger

func SetupRouter(confLogger *config.Logger) *gin.Engine {
	log = confLogger

	r := gin.Default()

	store := cookie.NewStore([]byte("reeze_project"))

	r.Use(sessions.Sessions("user_session", store))

	// r.Use(csrf.Middleware(csrf.Options{
	// 	Secret: "reeze_project_csrf",
	// 	ErrorFunc: func(c *gin.Context) {
	// 		c.JSON(400, "CSRF Token Mismatch")
	// 		c.Abort()
	// 	},
	// }))

	r.GET("/", homeIndex)
	r.POST("/login", auth.Login)
	r.POST("/register", auth.RegisterUser)
	r.GET("/login-github", loginGithub)
	r.GET("/logout", logout)
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
