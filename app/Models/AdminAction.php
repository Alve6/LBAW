<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AdminAction extends Model {
    //Disable default timestamps
    public $timestamps = false;

    // Explicitly define table name
    protected $table = 'admin_actions';

    // Explicitly define fillable fields
    protected $fillable = [
        'admin_id',
        'date',
    ];

    // Cast the date column to a Carbon instance for easy date manipulation
    protected $casts = [
        'date' => 'datetime',
    ];

    // Get the admin who performed this action
    public function admin(): BelongsTo {
        return $this->belongsTo(Admin::class, 'admin_id', 'user_id');
    }

    // Get the news action associated with this admin action
    public function newsAction(): HasOne {
        return $this->hasOne(AdminNewsAction::class, 'admin_action_id');
    }

    // Get the user action associated with this admin action
    public function userAction(): HasOne {
        return $this->hasOne(AdminUserAction::class, 'admin_action_id');
    }

    // Get the promotion associated with this admin action
    public function promotion(): HasOne {
        return $this->hasOne(Promotion::class, 'admin_action_id');
    }
}