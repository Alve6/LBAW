<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminUserAction extends Model {
    //Disable timestamps Because this table only has specific columns
    public $timestamps = false;

    //Explicitly define table name
    protected $table = 'admin_user_actions';

    //Explicitly define primary key, it is not auto-incrementing
    protected $primaryKey = 'admin_action_id';
    public $incrementing = false;

    // Explicitly define fillable fields
    protected $fillable = [
        'admin_action_id',
        'user_id',
        'reason',
    ];

    // Get the parent admin action record
    public function action(): BelongsTo {
        return $this->belongsTo(AdminAction::class, 'admin_action_id', 'id');
    }

    // Get the user associated with this action
    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}