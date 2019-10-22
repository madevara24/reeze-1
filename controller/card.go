package controller

import (
	"encoding/json"
	"net/http"

	"github.com/gin-gonic/gin"
	"github.com/zainokta/reeze/helpers"
	"github.com/zainokta/reeze/model"
)

func projectCards(c *gin.Context) {
	pid := helpers.GetParamID(c, "project_id")

	card := &model.Card{}
	cards, err := card.GetCardsByProject(pid)
	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Error while fetching data from database."})
		return
	}
	c.JSON(http.StatusOK, gin.H{"data": cards})
}

func createProjectCard(c *gin.Context) {
	pid := helpers.GetParamID(c, "project_id")

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
	cid := helpers.GetParamID(c, "card_id")

	cardModel := &model.Card{}
	res, err := c.GetRawData()
	if err != nil {
		log.LogError(err)
	}
	err = json.Unmarshal(res, cardModel)

	card, err := cardModel.GetCardByID(cid)
	if err != nil {
		log.LogError(err)
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Error while fetching card from database."})
		return
	}

	//TODO CHECK CARD STATE
	cardModel.OwnerID = card.OwnerID
	cardModel.ProjectID = card.ProjectID
	cardModel.RequesterID = card.RequesterID

	err = cardModel.UpdateCard(cid)
	if err != nil {
		log.LogError(err)
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Error while updating card"})
		return
	}
	c.JSON(http.StatusOK, gin.H{"success": "Card was successfully updated."})
}

func deleteProjectCard(c *gin.Context) {
	cid := helpers.GetParamID(c, "card_id")

	card := &model.Card{}
	err := card.DeleteCard(cid)
	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Error while deleting card from database."})
		return
	}
	c.JSON(http.StatusOK, gin.H{"success": "Card was successfully deleted."})
}
