package model

import (
	"time"
)

//Project is Struct of Project.
type Project struct {
	ID             uint64     `json:"id"`
	Name           string     `json:"name"`
	Repository     *string    `json:"repository"`
	Description    *string    `json:"description"`
	SprintDuration *uint8     `json:"sprint_duration"`
	SprintStartDay *uint8     `json:"sprint_start_day"`
	CreatedAt      *time.Time `json:"created_at"`
	UpdatedAt      *time.Time `json:"updated_at"`
}

func (p *Project) GetAllProject() ([]*Project, error) {
	rows, err := db.Query("SELECT * FROM projects")
	if err != nil {
		log.LogError(err)
		return nil, err
	}

	defer rows.Close()

	var result []*Project

	for rows.Next() {
		var each = &Project{}
		var err = rows.Scan(&each.ID, &each.Name, &each.Repository, &each.Description, &each.SprintDuration, &each.SprintStartDay, &each.CreatedAt, &each.UpdatedAt)
		if err != nil {
			log.LogError(err)
			return nil, err
		}

		result = append(result, each)
	}

	return result, nil
}

type ResultUserProjects struct {
	User    *User    `json:"user"`
	Project *Project `json:"projects"`
}

func (p *Project) GetUserProjects(pid uint64) ([]*ResultUserProjects, error) {

	rows, err := db.Query(`SELECT user.id,
                        user.username,
                        user.created_at,
                        user.updated_at,
                        projects.id,
                        projects.name,
                        projects.repository,
                        projects.description,
                        projects.sprint_duration,
                        projects.sprint_start_day,
                        projects.created_at,
                        projects.updated_at
                        FROM project_members
                        JOIN users ON project_members.user_id = users.id
                        JOIN projects ON project_members.project_id = projects.id
                        WHERE project_members.user_id = ?`, pid)
	if err != nil {
		log.LogError(err)
		return nil, err
	}

	defer rows.Close()

	var result []*ResultUserProjects

	for rows.Next() {
		var eachUser = &User{}
		var eachProject = &Project{}
		var err = rows.Scan(eachUser.ID,
			eachUser.Username,
			eachUser.CreatedAt,
			eachUser.UpdatedAt,
			eachProject.ID,
			eachProject.Name,
			eachProject.Repository,
			eachProject.Description,
			eachProject.SprintDuration,
			eachProject.SprintStartDay,
			eachProject.CreatedAt,
			eachProject.UpdatedAt)
		if err != nil {
			log.LogError(err)
			return nil, err
		}

		result = append(result, &ResultUserProjects{User: eachUser, Project: eachProject})
	}

	return result, nil
}

func (p *Project) GetProjectById(pid uint64) (*Project, error) {
	return nil, nil
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
