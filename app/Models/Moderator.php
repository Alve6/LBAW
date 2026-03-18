<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Moderator extends User
{
    //
    public $timestamps = false;
    protected $table = 'moderators';
    protected $primaryKey = "user_id";

    protected $fillable = [
        'user_id'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
