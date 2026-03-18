<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModeratorAction extends Model
{
    protected $table = 'moderator_actions';
    
    public $timestamps = false;
    
    protected $fillable = [
        'moderator_id',
        'date'
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    // Relationships
    public function moderator()
    {
        return $this->belongsTo(Moderator::class, 'moderator_id', 'user_id');
    }

    public function checkmark()
    {
        return $this->hasOne(Checkmark::class, 'moderator_action_id');
    }

    public function timeout()
    {
        return $this->hasOne(Timeout::class, 'moderator_action_id');
    }
}
