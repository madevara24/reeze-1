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
	User    *User    `json:"-"`
	Project *Project `json:"project"`
}

func (p *Project) GetUserProjects(uid uint64) ([]*ResultUserProjects, error) {
	rows, err := db.Query(`SELECT users.id,
                        users.username,
                        users.created_at,
                        users.updated_at,
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
                        WHERE project_members.user_id = ?`, uid)
	if err != nil {
		log.LogError(err)
		return nil, err
	}

	defer rows.Close()

	var result []*ResultUserProjects

	for rows.Next() {
		var eachUser = &User{}
		var eachProject = &Project{}
		var err = rows.Scan(&eachUser.ID,
			&eachUser.Username,
			&eachUser.CreatedAt,
			&eachUser.UpdatedAt,
			&eachProject.ID,
			&eachProject.Name,
			&eachProject.Repository,
			&eachProject.Description,
			&eachProject.SprintDuration,
			&eachProject.SprintStartDay,
			&eachProject.CreatedAt,
			&eachProject.UpdatedAt)
		if err != nil {
			log.LogError(err)
			return nil, err
		}

		result = append(result, &ResultUserProjects{User: eachUser, Project: eachProject})
	}

	return result, nil
}

func (p *Project) GetProjectByID(pid uint64) (*Project, error) {
	row := db.QueryRow(`SELECT * FROM projects
                    WHERE id = ?`, pid)

	var project *Project
	err := row.Scan(project.ID,
		project.Name,
		project.Repository,
		project.Description,
		project.SprintDuration,
		project.SprintStartDay,
		project.CreatedAt, project.UpdatedAt)

	if err != nil {
		return nil, err
	}

	return project, nil
}

func (p *Project) CreateProject(uid uint64) error {
	res, err := db.Exec(`INSERT INTO projects (name,
        repository,
        description,
        sprint_duration,
        sprint_start_day)
        VALUES(?, ?, ?, ?, ?)`, p.Name, p.Repository, p.Description, p.SprintDuration, p.SprintStartDay)
	if err != nil {
		log.LogError(err)
		return err
	}
	lastInsertID, _ := res.LastInsertId()

	pm := &ProjectMember{}
	pm.InsertProjectMember(uid, uint64(lastInsertID))
	return nil
}

func (p *Project) UpdateProject(pid uint64) error {
	_, err := db.Exec(`UPDATE projects SET
            name = ?,
            repository = ?,
            description = ?,
            sprint_duration = ?,
            sprint_start_day = ?,
            created_at = ?,
            updated_at = ?
            WHERE id = ?`, p.Name, p.Repository, p.Description, p.SprintDuration, p.SprintStartDay, p.CreatedAt, time.Now(), pid)

	if err != nil {
		log.LogError(err)
		return err
	}

	return nil
}

func (p *Project) DeleteProject(pid uint64) error {
	_, err := db.Exec(`DELETE FROM projects WHERE id = ?`, pid)
	if err != nil {
		log.LogError(err)
		return err
	}

	return nil
}
