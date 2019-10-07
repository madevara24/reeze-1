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

type Result struct {
	Requester *User
	Owner     *User
	Projects  *Project
	Cards     *Card
}

func (c *Card) GetCardsByProject(pid uint64) ([]*Result, error) {
	rows, err := db.Query(`SELECT cards.id,
                    projects.name,
                    req.username as 'req',
                    own.username as 'own',
                    github_branch_name,
                    cards.description,
                    points,
                    iteration,
                    type
                    FROM cards
                    JOIN users req on cards.requester = req.id
                    JOIN users own ON cards.owner = own.id
                    JOIN projects on cards.project_id = projects.id
                    WHERE cards.project_id = ?
                    ORDER BY cards.id ASC`, pid)

	if err != nil {
		log.LogError(err)
		return nil, err
	}

	defer rows.Close()
	var result []*Result

	for rows.Next() {
		var eachCard = &Card{}
		var eachRequester = &User{}
		var eachOwner = &User{}
		var eachProject = &Project{}

		var err = rows.Scan(&eachCard.ID, &eachProject.Name, &eachRequester.Username, &eachOwner.Username, &eachCard.GithubBranchName,
			&eachCard.Description, &eachCard.Points, &eachCard.Iteration, &eachCard.Type)
		if err != nil {
			log.LogError(err)
			return nil, err
		}
		result = append(result, &Result{Requester: eachRequester, Owner: eachOwner, Projects: eachProject, Cards: eachCard})
	}

	return result, nil
}

func (c *Card) GetCardById(cid uint64) string {
	return "card"
}

func (c *Card) CreateCard() error {
	_, err := db.Exec(`INSERT INTO cards (project_id,
                     owner,
                     requester,
                     github_branch_name,
                     description,
                     points,
                     iteration,
                     type)
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
