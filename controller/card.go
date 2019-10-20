package controller

import (
	"encoding/json"
	"net/http"
	"strconv"

	"github.com/gin-gonic/gin"
	"github.com/zainokta/reeze/helpers"
	"github.com/zainokta/reeze/model"
)

func createProjectCard(c *gin.Context) {
	projectID := c.Param("project_id")
	pid, _ := strconv.ParseUint(projectID, 10, 64)

	card := &model.Card{}

	res, err := c.GetRawData()
	if err != nil {
		log.LogError(err)
	}
	err = json.Unmarshal(res, card)

	uid, err := helpers.GetLoginUserID(c)
	if err != nil {
		log.LogError(err)
		c.JSON(http.StatusUnauthorized, gin.H{"error": "Please Login first."})
		return
	}

	err = card.CreateCard(pid, uid)
	if err != nil {
		log.LogError(err)
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Error while creating new card."})
		return
	}
	c.JSON(http.StatusOK, gin.H{"success": "Card was successfully created."})
}

func updateProjectCard(c *gin.Context) {
	cardID := c.Param("card_id")
	cid, _ := strconv.ParseUint(cardID, 10, 64)

	card := &model.Card{}
	res, err := c.GetRawData()
	if err != nil {
		log.LogError(err)
	}
	err = json.Unmarshal(res, card)

	// uid, err := helpers.GetLoginUserID(c)
	// if err != nil {
	// 	log.LogError(err)
	// 	c.JSON(http.StatusUnauthorized, gin.H{"error": "Please Login first"})
	// 	return
	// }

	err = card.UpdateCard(cid)
	if err != nil {
		log.LogError(err)
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Error while creating new card"})
		return
	}
	c.JSON(http.StatusOK, gin.H{"success": "Card was successfully created."})
}

func projectCards(c *gin.Context) {
	projectID := c.Param("project_id")
	id, _ := strconv.ParseUint(projectID, 10, 64)

	card := &model.Card{}
	cards, err := card.GetCardsByProject(id)
	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Error while fetching data from database."})
		return
	}
	c.JSON(http.StatusOK, gin.H{"data": cards})
}

func deleteProjectCard(c *gin.Context) {
	cardID := c.Param("card_id")
	cid, _ := strconv.ParseUint(cardID, 10, 64)

	card := &model.Card{}
	err := card.DeleteCard(cid)
	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Error while deleting card from database."})
		return
	}
	c.JSON(http.StatusOK, gin.H{"success": "Card was successfully deleted."})
}
