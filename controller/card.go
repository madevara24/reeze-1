package controller

import (
	"encoding/json"
	"net/http"

	"github.com/gin-gonic/gin"
	"github.com/zainokta/reeze/helpers"
	"github.com/zainokta/reeze/model"
)

func createProjectCard(c *gin.Context) {
	card := &model.Card{}

	res, err := c.GetRawData()
	if err != nil {
		log.LogError(err)
	}
	err = json.Unmarshal(res, card)

	id, err := helpers.GetLoginUserID(c)
	if err != nil {
		log.LogError(err)
		c.JSON(http.StatusUnauthorized, gin.H{"error": "Please Login first"})
	}

	err = card.CreateCard(id)
	if err != nil {
		log.LogError(err)
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Error while creating new card"})
	} else {
		c.JSON(http.StatusOK, gin.H{"success": "Card was successfully created"})
	}
}
