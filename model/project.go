package model

import (
	"time"
)

//Project is Struct of Project.
type Project struct {
	ID             uint64     `json:"id"`
	Name           string     `json:"name"`
	Repository     *string    `json:"repository"`
	PicID          uint64     `json:"pic_id"`
	Description    *string    `json:"description"`
	SprintDuration *uint8     `json:"sprint_duration"`
	SprintStartDay *uint8     `json:"sprint_start_day"`
	CreatedAt      *time.Time `json:"created_at"`
	UpdatedAt      *time.Time `json:"updated_at"`
}

type ResultProject struct {
	Project *Project `json:"project"`
	PicName *string  `json:"pic_name"`
}

func (p *Project) GetAllProject() ([]*ResultProject, error) {
	rows, err := db.Query("SELECT projects.*, users.username FROM projects JOIN users ON users.id = pic_id")
	if err != nil {
		log.LogError(err)
		return nil, err
	}

	defer rows.Close()

	var result []*ResultProject

	for rows.Next() {
		var each = &Project{}
		var user = &User{}
		var err = rows.Scan(&each.ID, &each.Name, &each.Repository, &each.PicID, &each.Description, &each.SprintDuration, &each.SprintStartDay, &each.CreatedAt, &each.UpdatedAt, &user.Username)
		if err != nil {
			log.LogError(err)
			return nil, err
		}

		result = append(result, &ResultProject{each, &user.Username})
	}

	return result, nil
}

type ResultUserProjects struct {
	Project     *Project `json:"project"`
	PicUsername *string  `json:"pic_username"`
}

func (p *Project) GetUserProjects(uid uint64) ([]*ResultUserProjects, error) {
	rows, err := db.Query(`SELECT pic.username,
                        projects.*
                        FROM project_members
                        JOIN projects ON project_members.project_id = projects.id
                        JOIN users as pic ON projects.pic_id = pic.id
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
		var err = rows.Scan(&eachUser.Username,
			&eachProject.ID,
			&eachProject.Name,
			&eachProject.Repository,
			&eachProject.PicID,
			&eachProject.Description,
			&eachProject.SprintDuration,
			&eachProject.SprintStartDay,
			&eachProject.CreatedAt,
			&eachProject.UpdatedAt)
		if err != nil {
			log.LogError(err)
			return nil, err
		}

		result = append(result, &ResultUserProjects{Project: eachProject, PicUsername: &eachUser.Username})
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
		project.PicID,
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
        pic_id,
        description,
        sprint_duration,
        sprint_start_day)
        VALUES(?, ?, ?, ?, ?, ?)`, p.Name, p.Repository, uid, p.Description, p.SprintDuration, p.SprintStartDay)
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
            pic_id = ?,
            description = ?,
            sprint_duration = ?,
            sprint_start_day = ?,
            created_at = ?,
            updated_at = ?
            WHERE id = ?`, p.Name, p.Repository, p.PicID, p.Description, p.SprintDuration, p.SprintStartDay, p.CreatedAt, time.Now(), pid)

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
