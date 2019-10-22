package helpers

import (
	"strconv"

	"github.com/gin-gonic/gin"
	"github.com/zainokta/reeze/model"
)

func GetParamID(c *gin.Context, param string) uint64 {
	paramID := c.Param(param)
	id, _ := strconv.ParseUint(paramID, 10, 64)
	return id
}

func GetProjectRepositoryName(pid uint64) *string {
	projectModel := model.Project{}
	project, err := projectModel.GetProjectByID(pid)
	if err != nil {
		return nil
	}
	return project.Repository
}
