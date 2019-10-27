package model

import (
	"time"
)

type ProjectMember struct {
	User      *User      `json:"user"`
	UserID    uint64     `json:"-"`
	Project   *Project   `json:"project"`
	ProjectID uint64     `json:"-"`
	CreatedAt *time.Time `json:"created_at"`
	UpdatedAt *time.Time `json:"updated_at"`
}

func (pm *ProjectMember) GetProjectMember(pid uint64) ([]*ProjectMember, error) {
	rows, err := db.Query(`SELECT users.*,
                            project_members.*,
                            projects.*
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
			&eachProject.PicID,
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

func (pm *ProjectMember) InsertProjectMember(uid uint64, pid uint64) error {
	_, err := db.Exec(`INSERT INTO project_members (user_id,
        project_id) VALUES(?, ?) `, uid, pid)
	if err != nil {
		log.LogError(err)
		return err
	}
	return nil
}

func (pm *ProjectMember) DeleteMemberFromProject(uid uint64, pid uint64) error {
	_, err := db.Exec(`DELETE FROM project_members WHERE project_id = ? AND user_id = ?`, pid, uid)
	if err != nil {
		log.LogError(err)
		return err
	}
	return nil
}

func (pm *ProjectMember) DeleteAllProjectFromUser(uid uint64) error {
	_, err := db.Exec(`DELETE FROM project_members WHERE user_id = ?`, uid)
	if err != nil {
		log.LogError(err)
		return err
	}
	return nil
}

func (pm *ProjectMember) DeleteAllUserFromProject(pid uint64) error {
	_, err := db.Exec(`DELETE FROM project_members WHERE project_id = ?`, pid)
	if err != nil {
		log.LogError(err)
		return err
	}
	return nil
}
