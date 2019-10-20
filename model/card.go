package model

import (
	"time"
)

type Card struct {
	ID               uint64     `json:"id"`
	Title            string     `json:"title"`
	ProjectID        uint64     `json:"-"`
	OwnerID          *uint64    `json:"-"`
	RequesterID      uint64     `json:"-"`
	GithubBranchName *string    `json:"github_branch_name"`
	Description      *string    `json:"description"`
	Points           *uint8     `json:"points"`
	Iteration        *uint8     `json:"iteration"`
	Type             string     `json:"type"`
	CreatedAt        *time.Time `json:"created_at"`
	UpdatedAt        *time.Time `json:"updated_at"`
}

type ResultProjectCards struct {
	Requester *User    `json:"requester"`
	Owner     *User    `json:"owner"`
	Projects  *Project `json:"project"`
	Cards     *Card    `json:"cards"`
}

func (c *Card) GetCardsByProject(pid uint64) ([]*ResultProjectCards, error) {

	rows, err := db.Query(`SELECT
                    cards.id,
                    cards.title,
                    cards.project_id,
                    cards.owner,
                    cards.requester,
                    cards.github_branch_name,
                    cards.description,
                    cards.points,
                    cards.iteration,
                    cards.type,
                    cards.created_at,
                    cards.updated_at,
                    projects.id,
                    projects.name,
                    projects.repository,
                    projects.description,
                    projects.sprint_duration,
                    projects.sprint_start_day,
                    projects.created_at,
                    projects.updated_at,
                    req.id,
                    req.username,
                    req.created_at,
                    req.updated_at,
                    own.id,
                    own.username,
                    own.created_at,
                    own.updated_at
                    FROM cards JOIN users req on cards.requester = req.id
                    JOIN users own on cards.owner = own.id
                    JOIN projects on cards.project_id = projects.id
                    WHERE cards.project_id = ? ORDER BY cards.iteration, cards.points ASC`, pid)

	if err != nil {
		log.LogError(err)
		return nil, err
	}

	defer rows.Close()
	var result []*ResultProjectCards

	for rows.Next() {
		var eachCard = &Card{}
		var eachRequester = &User{}
		var eachOwner = &User{}
		var eachProject = &Project{}

		var err = rows.Scan(&eachCard.ID,
			&eachCard.Title,
			&eachCard.ProjectID,
			&eachCard.OwnerID,
			&eachCard.RequesterID,
			&eachCard.GithubBranchName,
			&eachCard.Description,
			&eachCard.Points,
			&eachCard.Iteration,
			&eachCard.Type,
			&eachCard.CreatedAt,
			&eachCard.UpdatedAt,
			&eachProject.ID,
			&eachProject.Name,
			&eachProject.Repository,
			&eachProject.Description,
			&eachProject.SprintDuration,
			&eachProject.SprintStartDay,
			&eachProject.CreatedAt,
			&eachProject.UpdatedAt,
			&eachRequester.ID,
			&eachRequester.Username,
			&eachRequester.CreatedAt,
			&eachRequester.UpdatedAt,
			&eachOwner.ID,
			&eachOwner.Username,
			&eachOwner.CreatedAt,
			&eachOwner.UpdatedAt,
		)
		if err != nil {
			log.LogError(err)
			return nil, err
		}
		result = append(result, &ResultProjectCards{Requester: eachRequester, Owner: eachOwner, Projects: eachProject, Cards: eachCard})
	}

	return result, nil
}

func (c *Card) CreateCard(pid uint64, uid uint64) error {
	_, err := db.Exec(`INSERT INTO cards (project_id,
                     owner,
                     requester,
                     github_branch_name,
                     description,
                     points,
                     iteration,
                     type)
					 VALUES(?, ?, ?, ?, ?, ?, ?, ?) `,
		pid, c.OwnerID, uid, c.GithubBranchName, c.Description, c.Points, c.Iteration, c.Type)
	if err != nil {
		log.LogError(err)
		return err
	}
	return nil
}

func (c *Card) UpdateCard(cid uint64) error {
	_, err := db.Exec(`UPDATE cards SET
                title = ?,
                project_id = ?,
                owner = ?,
                requester = ?,
                github_branch_name = ?,
                description = ?,
                points = ?,
                iteration = ?,
                type = ?,
                updated_at = ?
                WHERE id = ?`, c.Title, c.ProjectID, c.OwnerID, c.RequesterID, c.GithubBranchName, c.Description, c.Points, c.Iteration, c.Type, time.Now(), cid)
	if err != nil {
		log.LogError(err)
		return err
	}
	return nil
}

func (c *Card) DeleteCard(cid uint64) error {
	_, err := db.Exec("DELETE FROM cards WHERE id = ?", cid)
	if err != nil {
		log.LogError(err)
		return err
	}
	return nil
}
