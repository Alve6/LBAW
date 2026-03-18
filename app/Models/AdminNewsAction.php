<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminNewsAction extends Model {
    // Disable default timestamps
    public $timestamps = false;

    // Explicitly define table name
    protected $table = 'admin_news_actions';

    // Explicitly define primary key, it is not auto-incrementing
    protected $primaryKey = 'admin_action_id';  
    public $incrementing = false;

    // Explicitly define fillable fields
    protected $fillable = [
        'admin_action_id',
        'news_id',
        'reason',
    ];

    // Get the parent admin action record
    public function action(): BelongsTo {
        return $this->belongsTo(AdminAction::class, 'admin_action_id', 'id');
    }

    // Get the news item associated with this action
    public function news(): BelongsTo {
        return $this->belongsTo(News::class, 'news_id', 'id');
    }
}