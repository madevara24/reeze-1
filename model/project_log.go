package model

import "time"

type ProjectLog struct {
	ID        uint64     `json:"id"`
	UserID    uint64     `json:"user_id"`
	User      *User      `json:"user"`
	ProjectID uint64     `json:"project_id"`
	Project   *Project   `json:"project"`
	Log       string     `json:"log"`
	CreatedAt *time.Time `json:"created_at"`
	UpdatedAt *time.Time `json:"updated_at"`
}

func (pl *ProjectLog) InsertProjectLog(uid uint64, pid uint64, logText string) error {
	_, err := db.Exec(`INSERT INTO project_logs (user_id,
        project_id) VALUES(?, ?, ?) `, uid, pid, logText)
	if err != nil {
		log.LogError(err)
		return err
	}
	return nil
}

func (pl *ProjectLog) GetProjectLogs() ([]*ProjectLog, error) {
	rows, err := db.Query(`SELECT users.*,
    project_logs.*,
    projects.*
    FROM project_logs
    JOIN users ON project_logs.user_id = users.id
    JOIN projects ON project_logs.project_id = projects.id`)

	if err != nil {
		log.LogError(err)
		return nil, err
	}
	defer rows.Close()
	var result []*ProjectLog

	for rows.Next() {
		var eachUser = &User{}
		var eachProject = &Project{}
		var eachProjectLog = &ProjectLog{}
		var err = rows.Scan(&eachUser.ID,
			&eachUser.Username,
			&eachUser.CreatedAt,
			&eachUser.UpdatedAt,
			&eachProjectLog.UserID,
			&eachProjectLog.ProjectID,
			&eachProjectLog.CreatedAt,
			&eachProjectLog.UpdatedAt,
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
		result = append(result, &ProjectLog{User: eachUser, UserID: eachProjectLog.UserID, Project: eachProject, ProjectID: eachProjectLog.ProjectID})
	}

	return result, nil
}
