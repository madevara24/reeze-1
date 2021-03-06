<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProjectMember extends Model
{
    protected $primaryKey = null;
    public $incrementing = false;
    protected $table = 'project_members';
    protected $fillable = ['user_id', 'project_id'];

    public function user()
    {
        return $this->belongsTo(\App\User::class, 'user_id', 'id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }
}
