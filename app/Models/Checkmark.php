<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Checkmark extends Model
{
    protected $table = 'checkmarks';
    
    protected $primaryKey = 'moderator_action_id';
    
    public $timestamps = false;
    
    protected $fillable = [
        'moderator_action_id',
        'news_id'
    ];

    // Relationships
    public function moderatorAction()
    {
        return $this->belongsTo(ModeratorAction::class, 'moderator_action_id');
    }

    public function news()
    {
        return $this->belongsTo(News::class, 'news_id');
    }
}
