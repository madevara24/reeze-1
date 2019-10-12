package model

import "time"

type ProjectMember struct {
	User      *User      `json:"user"`
	UserID    uint64     `json:"-"`
	Project   *Project   `json:"project"`
	ProjectID uint64     `json:"-"`
	CreatedAt *time.Time `json:"created_at"`
	UpdatedAt *time.Time `json:"updated_at"`
}

func (pm *ProjectMember) GetProjectMember(pid uint64) ([]*ProjectMember, error) {
	rows, err := db.Query(`SELECT users.id,
                            users.username,
                            users.created_at,
                            users.updated_at,
                            project_members.user_id,
                            project_members.project_id,
                            project_members.created_at,
                            project_members.updated_at,
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
                            WHERE project_members.project_id = ?`, pid)

	if err != nil {
		log.LogError(err)
		return nil, err
	}
	defer rows.Close()
	var result []*ProjectMember

	for rows.Next() {
		var eachUser = &User{}
		var eachProject = &Project{}
		var eachProjectMember = &ProjectMember{}
		var err = rows.Scan(&eachUser.ID,
			&eachUser.Username,
			&eachUser.CreatedAt,
			&eachUser.UpdatedAt,
			&eachProjectMember.UserID,
			&eachProjectMember.ProjectID,
			&eachProjectMember.CreatedAt,
			&eachProjectMember.UpdatedAt,
			&eachProject.ID,
			&eachProject.Name,
			&eachProject.Repository,
			&eachProject.Description,
			&eachProject.SprintDuration,
			&eachProject.SprintStartDay,
			&eachProject.CreatedAt,
			&eachProject.UpdatedAt,
		)
		if err != nil {
			log.LogError(err)
			return nil, err
		}
		result = append(result, &ProjectMember{User: eachUser, UserID: eachProjectMember.UserID, Project: eachProject, ProjectID: eachProjectMember.ProjectID})
	}

	return result, nil
}
