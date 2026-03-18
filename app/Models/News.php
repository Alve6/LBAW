<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Http\Controllers\FileController;

class News extends Model
{
    //
    public $timestamps = false;
    protected $fillable = ['title', 'content', 'user_id', 'image'];


    public function categories() {
        return $this->belongsToMany(Category::class, 'news_category', 'news_id', 'category_id');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function newsVotes() {
        return $this->hasMany(NewsVote::class);
    }

    public function checkmark() {
        return $this->hasOne(Checkmark::class, 'news_id');
    }

    public function hasCheckmark() {
        return $this->checkmark()->exists();
    }

    public function getImage() {
        return FileController::get('news', $this->id);
    }
}
