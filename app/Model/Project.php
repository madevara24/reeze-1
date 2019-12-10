<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'projects';

    public function card()
    {
        return $this->belongsTo(\App\User::class, 'pic_id', 'id');
    }
}
