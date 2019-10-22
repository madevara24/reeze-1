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
		card := r.Group("/card")
		{
			card.GET("/:project_id", projectCards)
			card.POST("/create", createProjectCard)
			card.PUT("/:card_id", updateProjectCard)
			card.DELETE("/:card_id", deleteProjectCard)
		}

		//Projects
		project := r.Group("/project")
		{
			project.GET("/", userProjects)
			project.GET("/project_member/:project_id", projectMember)
			project.POST("/create", createProject)
			project.POST("/:project_id/:card_id/create_branch", createBranch)
			project.POST("/:project_id/create_pr", createPullRequest)
			project.PUT("/:project_id", updateProject)
			project.DELETE("/:project_id", deleteProject)
		}

		api.GET("/list-repo", getListRepositories)
	}

	return r
}
