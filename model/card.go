package model

import (
	"time"
)

type Card struct {
	ID               uint64     `json:"id"`
	ProjectID        uint64     `json:"project_id"`
	OwnerID          uint64     `json:"owner_id"`
	RequesterID      uint64     `json:"requester_id"`
	GithubBranchName string     `json:"github_branch_name"`
	Description      string     `json:"description"`
	Points           uint8      `json:"points"`
	Iteration        uint8      `json:"iteration"`
	Type             string     `json:"type"`
	CreatedAt        *time.Time `json:"created_at"`
	UpdatedAt        *time.Time `json:"updated_at"`
}

func (c *Card) GetCardByProject(pid uint64) ([]*Card, error) {
	rows, err := db.Query("SELECT * FROM cards WHERE project_id = ?", pid)

	if err != nil {
		log.LogError(err)
		return []*Card{}, err
	}

	defer rows.Close()

	var result []*Card

	for rows.Next() {
		var each = &Card{}
		var err = rows.Scan(&each.ID, &each.ProjectID, &each.OwnerID, &each.RequesterID,
			&each.GithubBranchName, &each.Description, &each.Points, &each.Iteration, &each.Type,
			&each.CreatedAt, &each.UpdatedAt)
		if err != nil {
			log.LogError(err)
			return []*Card{}, err
		}

		result = append(result, each)
	}

	return result, nil
}

func (c *Card) GetCardById(cid uint64) string {
	return "card"
}

func (c *Card) CreateCard() error {
	_, err := db.Exec(`INSERT INTO cards (project_id, owner, requester, github_branch_name, description, points, iteration, type)
					 VALUES(?, ?, ?, ?, ?, ?, ?, ?) `,
		c.ProjectID, c.OwnerID, c.RequesterID, c.GithubBranchName, c.Description, c.Points, c.Iteration, c.Type)
	if err != nil {
		log.LogError(err)
		return err
	}
	return nil
}

func (c *Card) UpdateCard() string {
	return "updated"
}

func (c *Card) DeleteCard() string {
	return "deleted"
}
