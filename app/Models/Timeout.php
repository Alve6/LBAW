<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timeout extends Model
{
    protected $table = 'timeouts';
    
    protected $primaryKey = 'moderator_action_id';
    
    public $timestamps = false;
    
    protected $fillable = [
        'moderator_action_id',
        'user_id',
        'start_time',
        'end_time',
        'reason'
    ];

    // Relationships
    public function moderatorAction()
    {
        return $this->belongsTo(ModeratorAction::class, 'moderator_action_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
