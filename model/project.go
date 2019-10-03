package model

import (
	"time"
)

//Project is Struct of Project.
type Project struct {
	ID             uint64     `json:"id"`
	Name           string     `json:"name"`
	Repository     string     `json:"repository"`
	Description    string     `json:"description"`
	SprintDuration uint8      `json:"sprint_duration"`
	SprintStartDay uint8      `json:"sprint_start_day"`
	CreatedAt      *time.Time `json:"created_at"`
	UpdatedAt      *time.Time `json:"updated_at"`
}

func (p *Project) GetAllProject() ([]*Project, error) {
	rows, err := db.Query("SELECT * FROM projects")
	if err != nil {
		log.LogError(err)
		return []*Project{}, err
	}

	defer rows.Close()

	var result []*Project

	for rows.Next() {
		var each = &Project{}
		var err = rows.Scan(&each.ID, &each.Name, &each.Repository, &each.Description, &each.SprintDuration, &each.SprintStartDay, &each.CreatedAt, &each.UpdatedAt)
		if err != nil {
			log.LogError(err)
			return []*Project{}, err
		}

		result = append(result, each)
	}

	return result, nil
}

func (p *Project) GetProjectById(pid uint64) (*Project, error) {
	return &Project{}, nil
}

func (p *Project) CreateProject() string {
	return "created"
}

func (p *Project) UpdateProject(pid uint64, update Project) string {
	return "updated"
}

func (p *Project) DeleteProject(pid uint64) string {
	return "deleted"
}
