<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'projects';

    protected $fillable = ['name', 'repository', 'pic_id', 'description', 'sprint_duration', 'sprint_start_day'];

    public function pic()
    {
        return $this->belongsTo(\App\User::class, 'pic_id', 'id');
    }

    public function project_member()
    {
        return $this->hasMany(ProjectMember::class, 'project_id', 'id');
    }
}
