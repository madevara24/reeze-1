package controller

import (
	"encoding/json"
	"fmt"
	"net/http"

	"github.com/gin-gonic/gin"
	"github.com/zainokta/reeze/helpers"
)

func createBranch(c *gin.Context) {
	res, err := c.GetRawData()
	if err != nil {
		log.LogError(err)
	}

	data := new(struct {
		BranchName string `json:"branch_name"`
	})

	err = json.Unmarshal(res, &data)
	repositoryName := "rental-girlfriend-laravel"
	ref, _, err := helpers.CreateBranch(c, repositoryName, data.BranchName)
	if err != nil {
		log.LogError(err)
		c.JSON(http.StatusBadRequest, err)
	} else {
		info := fmt.Sprintf("References created: %s\n", ref.GetRef())
		log.LogInfo(info)
		c.JSON(http.StatusOK, "Branch "+data.BranchName+" is successfully created")
	}
}

func createPullRequest(c *gin.Context) {
	repositoryName := "rental-girlfriend-laravel"
	pr, res, err := helpers.CreatePullRequest(c, repositoryName)
	if err != nil {
		log.LogError(err)
		c.JSON(res.StatusCode, err)
	} else {
		info := fmt.Sprintf("Pull request created %s", pr.GetURL())
		log.LogInfo(info)
	}

	prList, _, err := helpers.GetPullRequestsList(c, repositoryName)

	for _, list := range prList {
		merge, res, err := helpers.MergePullRequest(c, list, repositoryName)
		if err != nil {
			log.LogError(err)
			c.JSON(res.StatusCode, err)
		} else {
			info := fmt.Sprintf("Branch merged : %s\n", merge.GetMessage())
			log.LogInfo(info)
		}
	}
}

func getListRepositories(c *gin.Context) {
	repo, _ := helpers.ListAllRepos(c)
	c.JSON(http.StatusOK, gin.H{"data": repo})
}
