package controller

import (
	"encoding/json"
	"fmt"
	"net/http"

	"github.com/gin-gonic/gin"
	"github.com/zainokta/reeze/helpers"
	"github.com/zainokta/reeze/model"
)

func userProjects(c *gin.Context) {
	id, _ := helpers.GetLoginUserID(c)
	project := &model.Project{}
	projects, err := project.GetUserProjects(id)
	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
		return
	}
	c.JSON(http.StatusOK, gin.H{"data": projects})
}
func projectMember(c *gin.Context) {
	id := helpers.GetParamID(c, "project_id")

	pm := &model.ProjectMember{}
	pms, err := pm.GetProjectMember(id)
	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
		return
	}
	c.JSON(http.StatusOK, gin.H{"data": pms})
}

func createProject(c *gin.Context) {

	res, err := c.GetRawData()
	if err != nil {
		log.LogError(err)
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Internal Server Error"})
		return
	}

	data := &model.Project{}
	err = json.Unmarshal(res, data)
	if err != nil {
		log.LogError(err)
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Internal Server Error"})
		return
	}

	uid, err := helpers.GetLoginUserID(c)
	if err != nil {
		log.LogError(err)
		c.JSON(http.StatusUnauthorized, gin.H{"error": "Please Login first"})
		return
	}
	err = data.CreateProject(uid)
	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Error while creating new project."})
		return
	}

	c.JSON(http.StatusOK, gin.H{"success": "Project has successfully created."})
}

func updateProject(c *gin.Context) {
	pid := helpers.GetParamID(c, "project_id")

	project := &model.Project{}
	res, err := c.GetRawData()
	if err != nil {
		log.LogError(err)
	}
	err = json.Unmarshal(res, project)

	err = project.UpdateProject(pid)
	if err != nil {
		log.LogError(err)
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Error while updating project"})
		return
	}
	c.JSON(http.StatusOK, gin.H{"success": "Project was successfully updated."})
}

type DataBranch struct {
	BranchName string `json:"branch_name"`
}

func createBranch(c *gin.Context) {
	cid := helpers.GetParamID(c, "card_id")
	pid := helpers.GetParamID(c, "project_id")

	res, err := c.GetRawData()
	if err != nil {
		log.LogError(err)
	}

	data := &DataBranch{}

	err = json.Unmarshal(res, data)

	repositoryName := helpers.GetProjectRepositoryName(pid)
	if repositoryName == nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Repository name cannot be null."})
		return
	}

	ref, _, err := helpers.CreateBranch(c, *repositoryName, data.BranchName)
	if err != nil {
		log.LogError(err)
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Error while creating new branch."})
		return
	}

	cardModel := &model.Card{}
	card, err := cardModel.GetCardByID(cid)
	card.GithubBranchName = &data.BranchName
	card.UpdateCard(cid)

	if err != nil {
		log.LogError(err)
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Error while updating card."})
		return
	}
	info := fmt.Sprintf("References created: %s\n", ref.GetRef())
	log.LogInfo(info)
	c.JSON(http.StatusOK, "Branch "+data.BranchName+" is successfully created")
}

func createPullRequest(c *gin.Context) {
	pid := helpers.GetParamID(c, "project_id")
	repositoryName := helpers.GetProjectRepositoryName(pid)
	if repositoryName == nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Repository name cannot be null."})
		return
	}

	//TODO ADD BRANCH TO MERGE
	pr, res, err := helpers.CreatePullRequest(c, *repositoryName)
	if err != nil {
		log.LogError(err)
		c.JSON(res.StatusCode, err)
		return
	}

	info := fmt.Sprintf("Pull request created %s", pr.GetURL())
	log.LogInfo(info)

	prList, _, err := helpers.GetPullRequestsList(c, *repositoryName)

	for _, list := range prList {
		merge, res, err := helpers.MergePullRequest(c, list, *repositoryName)
		if err != nil {
			log.LogError(err)
			c.JSON(res.StatusCode, err)
			return
		}
		info := fmt.Sprintf("Branch merged : %s\n", merge.GetMessage())
		log.LogInfo(info)
	}
}

func deleteProject(c *gin.Context) {
	pid := helpers.GetParamID(c, "project_id")

	project := &model.Project{}
	err := project.DeleteProject(pid)
	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Error while deleting project from database."})
		return
	}
	c.JSON(http.StatusOK, gin.H{"success": "Project was successfully deleted."})
}

func getListRepositories(c *gin.Context) {
	repo, err := helpers.ListAllRepos(c)
	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Error while getting list of repositories."})
		return
	}
	c.JSON(http.StatusOK, gin.H{"data": repo})
}
