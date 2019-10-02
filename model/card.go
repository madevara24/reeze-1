package model

import "time"

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

func (c *Card) GetCardByProject(pid uint64) (*[]Card, error) {
	return &[]Card{}, nil
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
