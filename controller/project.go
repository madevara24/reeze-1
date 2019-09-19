package controller

import (
	"fmt"
	"net/http"

	"github.com/gin-gonic/gin"
	"github.com/reeze-project/reeze/helpers"
)

func createBranch(c *gin.Context) {
	branchName := c.Request.FormValue("branch_name")
	ref, _, err := helpers.CreateBranch(c, branchName)
	if err != nil {
		log.LogError(err)
		c.JSON(http.StatusBadRequest, err)
	} else {
		info := fmt.Sprintf("References created: %s\n", ref.GetRef())
		log.LogInfo(info)
		c.JSON(http.StatusOK, "Branch "+branchName+" is successfully created")
	}
}

func createPullRequest(c *gin.Context) {
	pr, res, err := helpers.CreatePullRequest(c)
	if err != nil {
		log.LogError(err)
		c.JSON(res.StatusCode, err)
	} else {
		info := fmt.Sprintf("Pull request created %s", pr.GetURL())
		log.LogInfo(info)
	}

	prList, _, err := helpers.GetPullRequestsList(c)

	for _, list := range prList {
		merge, res, err := helpers.MergePullRequest(c, list)
		if err != nil {
			log.LogError(err)
			c.JSON(res.StatusCode, err)
		} else {
			info := fmt.Sprintf("Branch merged : %s\n", merge.GetMessage())
			log.LogInfo(info)
		}
	}
}
