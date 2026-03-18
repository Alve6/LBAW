<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $primaryKey = 'name';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['name'];

    public function news() {
        return $this->belongsToMany(News::class, 'news_category', 'category_id', 'news_id');
    }

    // Users que seguem ESTA category
    public function followers()
    {
        return $this->belongsToMany(
            User::class,
            'category_follows',
            'category_name', // pivot -> category
            'user_id',       // pivot -> user
            'name',          // local key em categories
            'id'             // local key em users
        );
    }
}
