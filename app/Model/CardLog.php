<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CardLog extends Model
{
    protected $table = 'card_logs';
    protected $fillable = [
        'card_id', 'state'
    ];

    public function card()
    {
        return $this->belongsTo(Card::class, 'card_id', 'id');
    }
}
