<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Admin extends User
{
    // Explicit definition of the 'admins' table
    protected $table = 'admins';

    //PK is user_id from users table and 
    // this is not an auto-incrementing integer for this table
    protected $primaryKey = 'user_id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'user_id'
    ];

    // Define relationships, allows access to shared data
    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    //public static function isAdmin(int $name)
    //{
    //    return self::where("user_id", $name)->exists();
    //}

    //get all generic administrative actions
    public function actions(): HasMany {
        return $this->hasMany(AdminAction::class, 'admin_id', 'user_id');
    }

    //Get specific news moderation actions
    public function newsActions(): HasManyThrough{
        return $this->hasManyThrough(
            AdminNewsAction::class,
            AdminAction::class,
            'admin_id', // Foreign key on AdminAction table
            'admin_action_id', // Foreign key on AdminNewsAction table
            'user_id', // Local key on Admin table
            'id' // Local key on AdminAction table
        );
    }

    //Get specific user management actions
    public function userActions(): HasManyThrough{
        return $this->hasManyThrough(
            AdminUserAction::class,
            AdminAction::class,
            'admin_id',
            'admin_action_id',
            'user_id', 
            'id' 
        );
    }

    //Get promotions performed by an admin
    public function promotions(): HasManyThrough{
        return $this->hasManyThrough(
            Promotion::class,
            AdminAction::class,
            'admin_id', 
            'admin_action_id', 
            'user_id',
            'id' 
        );
    }

    //Get reports acknowledged by an admin
    public function acknowledgedReports() {
        return $this->belongsToMany(
            Report::class,
            'acknowledged_reports',
            'report_id',
            'admin_id'
        );
    }

    //Get report notifications assigned to an admin
    public function reportNotifications(): BelongsToMany {
        return $this->belongsToMany(
            ReportNotification::class,
            'report_notifications_admins',
            'admin_id',
            'report_notification_id'
        );
    }
}
