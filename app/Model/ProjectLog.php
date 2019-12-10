<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProjectLog extends Model
{
    protected $table = 'project_logs';

    public function user()
    {
        return $this->belongsTo(\App\User::class, 'user_id', 'id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }
}
