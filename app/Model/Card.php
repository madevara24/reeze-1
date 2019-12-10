<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $table = 'cards';

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function owner()
    {
        return $this->belongsTo(\App\User::class, 'owner', 'id');
    }

    public function requester()
    {
        return $this->belongsTo(\App\User::class, 'requester', 'id');
    }
}
