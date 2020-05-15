<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $table = 'cards';
    public $incrementing = false;
    protected $fillable = [
        'id',
        'title',
        'project_id',
        'github_branch_name',
        'description',
        'owner',
        'requester',
        'points',
        'type'
    ];


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

    public function cardLog()
    {
        return $this->hasMany(CardLog::class, 'card_id', 'id');
    }
}
