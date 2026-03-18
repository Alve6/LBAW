<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ReportNotification extends Model {
    //Disable default timestamps
    public $timestamps = false;

    //Explicitly define table name
    protected $table = 'report_notifications';

    //Explicitly define primary key, it is not auto-incrementing
    protected $primaryKey = 'notification_id';
    public $incrementing = false;

    // Explicitly define fillable fields
    protected $fillable = [
        'notification_id',
        'report_id',
    ];

    // Get the parent notification record
    public function notification(): BelongsTo {
        return $this->belongsTo(Notification::class, 'notification_id', 'id');
    }

    // Get the report associated to this notification
    public function report(): BelongsTo {
        return $this->belongsTo(Report::class, 'report_id', 'id');
    }

    // Get the admins associated with this report notification
    public function admins(): BelongsToMany {
        return $this->belongsToMany(
            Admin::class,
            'report_notifications_admins',
            'report_notification_id',
            'admin_id',
        );
    }
}