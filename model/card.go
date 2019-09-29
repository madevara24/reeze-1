package model

import "time"

type Card struct {
	ID               uint64     `json:"id"`
	ProjectID        uint64     `json:"project_id"`
	Project          Project    `json:"project"`
	OwnerID          uint64     `json:"owner_id"`
	Owner            User       `json:"owner"`
	RequesterID      uint64     `json:"requester_id"`
	Requester        User       `json:"requester"`
	GithubBranchName string     `json:"github_branch_name"`
	Description      string     `json:"description"`
	Points           uint8      `json:"points"`
	Iteration        uint8      `json:"iteration"`
	Type             string     `json:"type"`
	CreatedAt        *time.Time `json:"created_at"`
	UpdatedAt        *time.Time `json:"updated_at"`
}
