<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Http\Controllers\FileController;

use App\Models\Category;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    // Disable default created_at and updated_at timestamps for this model.
    public $timestamps  = false;

    private $recovery_code;

    /**
     * The attributes that are mass assignable.
     *
     * Only these fields may be filled using methods like create() or update().
     * This protects against mass-assignment vulnerabilities.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'description',
        'profile_image'
    ];

    /**
     * The attributes that should be hidden when serializing the model
     * (e.g., to arrays or JSON).
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast to a specific type.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            // Ensures password is always hashed automatically when set.
            'password' => 'hashed',
        ];
    }

    public function news() {
        return $this->hasMany(News::class);
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }
 
    public function votes() {
        return $this->hasMany(Vote::class);
    }

    public function comment_votes() {
        return $this->hasMany(CommentVote::class);
    }

    public function news_votes() {
        return $this->hasMany(NewsVote::class);
    }

    public function news_reputation() {
        return $this->throughNews()->hasNewsVotes()->count();
    }

    public function comments_reputation() {
        return $this->throughComments()->hasCommentVotes()->count();
    }

    public function reputation() {
        return $this->news_reputation() + $this->comments_reputation();
    }

    public function getProfileImage() {
        return FileController::get('users', $this->id);
    }

    public function timeouts(){
        return $this->hasMany(Timeout::class, 'user_id');
    }

    public function isTimedOut(){
        return $this->timeouts()->where('start_time', '<=', now())
                                ->where('end_time', '>', now())->exists();
    }
                                
    // Users that follow THIS user
    public function followers()
    {
        return $this->belongsToMany(
            User::class,
            'follows',      // pivot table
            'followed_id',  // column that refers to THIS user
            'follower_id'   // column that refers to the follower
        );
    }

    // Users that THIS user is following
    public function following()
    {
        return $this->belongsToMany(
            User::class,
            'follows',
            'follower_id',  // this user
            'followed_id'   // the user being followed
        );
    }

    public function isFollowing(User $user): bool
    {
        return $this->following()
                    ->where('followed_id', $user->id)
                    ->exists();
    }
    
    public function isAdmin() {
        return Admin::where('user_id', $this->id)->exists();
    }

    public function isModerator() {
        return Moderator::where('user_id', $this->id)->exists();
    }

    public function reports() {
        return $this->hasMany(Report::class);
    }

    // Categories que ESTE user segue
    public function followedCategories()
    {
        return $this->belongsToMany(
            Category::class,
            'category_follows',
            'user_id',
            'category_name',     // foreign key no pivot que aponta para Category
            'id',
            'name'               // primary key da Category é 'name'
        );
    }

    public function isFollowingCategory(Category $category): bool
    {
        return $this->followedCategories()
                    ->where('categories.name', $category->name)
                    ->exists();
    }

}

