package controller

import (
	"github.com/gin-contrib/sessions"
	"github.com/gin-contrib/sessions/cookie"
	"github.com/gin-gonic/gin"
	cors "github.com/rs/cors/wrapper/gin"
	"github.com/zainokta/reeze/config"
	"github.com/zainokta/reeze/middleware"
)

var log *config.Logger

func SetupRouter(confLogger *config.Logger) *gin.Engine {
	log = confLogger

	r := gin.Default()
	store := cookie.NewStore([]byte("reeze_project"))

	r.Use(sessions.Sessions("user_session", store))
	r.Use(cors.Default())

	r.GET("/", homeIndex)
	r.POST("/logout", logoutUser)

	r.GET("/login-github", loginGithub)
	r.GET("/github-callback", githubCallback)

	api := r.Group("/api/v1").Use(middleware.AuthMiddleware)
	{

		//Cards
		api.GET("/card/:project_id", projectCards)
		api.POST("/card/:project_id/create", createProjectCard)
		api.PUT("/card/:card_id/edit", updateProjectCard)
		api.DELETE("/card/:card_id/delete", deleteProjectCard)

		//Projects
		api.GET("/project/", userProjects)
		api.GET("/project/:project_id/project_member", projectMember)
		api.POST("/project/create", createProject)
		api.POST("/project/merge_release/:project_id", mergeRelease)
		api.POST("/project/create_branch/:project_id/:card_id", createBranch)
		api.PUT("/project/:project_id/edit", updateProject)
		api.DELETE("/project/:project_id/delete", deleteProject)

		api.GET("/list-repo", getListRepositories)
	}

	return r
}
