package controller

import (
	"fmt"
	"net/http"

	"github.com/gin-gonic/gin"
	"github.com/reeze-project/reeze/helper"
)

func createBranch(c *gin.Context) {
	branchName := c.Request.FormValue("branch_name")
	ref, _, err := helper.CreateBranch(c, branchName)
	if err != nil {
		c.JSON(http.StatusBadRequest, err)
	} else {
		fmt.Printf("References created: %s\n", ref.GetRef())
		c.JSON(http.StatusOK, "Branch "+branchName+" is successfully created")
	}
}

func createPullRequest(c *gin.Context) {
	pr, res, err := helper.CreatePullRequest(c)
	if err != nil {
		c.JSON(res.StatusCode, err)
	} else {
		fmt.Printf("Pull request created %s", pr.GetURL())
	}

	prList, _, err := helper.GetPullRequestsList(c)

	for _, list := range prList {
		merge, res, err := helper.MergePullRequest(c, list)
		if err != nil {
			c.JSON(res.StatusCode, err)
		} else {
			//fmt.Printf("PR created: %s\n", pr.GetHTMLURL())
			fmt.Printf("Branch merged : %s\n", merge.GetMessage())
		}
	}
}
