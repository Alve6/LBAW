<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Promotion extends Model {
    //Disable default timestamps
    public $timestamps = false;

    //Explicitly define table name
    protected $table = 'promotions';

    //Explicitly define primary key, it is not auto-incrementing
    protected $primaryKey = 'admin_action_id';
    public $incrementing = false;

    // Explicitly define fillable fields
    protected $fillable = [
        'admin_action_id',
        'type',
        'user_id',
    ];

    // Get the admin action associated with this promotion
    public function action(): BelongsTo {
        return $this->belongsTo(AdminAction::class, 'admin_action_id', 'id');
    }

    // Get the user associated with this promotion
    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}