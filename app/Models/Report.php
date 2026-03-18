<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model {
    public $timestamps = false;

    protected $fillable = [
        'content',
        'user_id',
        'target_url',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function acknowledgedBy() {
        return $this->belongsToMany(
            Admin::class,
            'acknowledged_reports',
            'report_id',
            'admin_id',
        );
    }

    public function notificationLink() {
        return $this->hasOne(ReportNotification::class);
    }
}