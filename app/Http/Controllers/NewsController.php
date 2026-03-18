<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use App\Models\Comment;
use App\Models\Vote;
use App\Models\NewsVote;
use App\Models\CommentVote;
use App\Models\ModeratorAction;
use App\Models\Checkmark;
use App\Models\Moderator;
use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use App\Models\Category;

class NewsController extends Controller
{

    public function create()
    {
        Gate::authorize('create', News::class);
        return view('pages.news.createnews');
    }

    public function store(Request $request)
    {
        Gate::authorize('create', News::class);
        $request->validate([
            'title' => 'required|string|unique:news|max:250',
            'newscontent' => 'required|string',
            'file' => 'nullable|image|mimes:jpeg,jpg,png,gif',
            'categories' => 'required_without:suggested_tag',
            'suggested_tag' => 'nullable|string|max:50'
        ]);

        $user_id = auth()->id();

        $news = News::create([
            'title'=> $request->title,
            'content'=> $request->newscontent,
            'user_id' => $user_id,
        ]);

        if($request->has('categories')) {
            $categories = is_array($request->categories) ? $request->categories : [$request->categories];
            $news->categories()->attach($categories);
        }

        if ($request->filled('suggested_tag')) {
            $rawName = trim($request->suggested_tag);
            
            $existingOfficial = Category::where('name', 'ILIKE', $rawName)
                                        ->where('name', 'NOT LIKE', '~%')
                                        ->first();

            if ($existingOfficial) {
                $news->categories()->attach($existingOfficial->name);
            } else {
                $pendingName = '~' . $rawName;
                $existingSuggestion = Category::where('name', 'ILIKE', $pendingName)->first();

                if ($existingSuggestion) {
                    $news->categories()->attach($existingSuggestion->name);
                } else {
                    $newTag = new Category();
                    $newTag->name = $pendingName;
                    $newTag->timestamps = false;
                    $newTag->save();
                    
                    $news->categories()->attach($newTag->name);
                }
            }
        }

        if($request->hasFile('file')) {
            $file = FileController::upload($request, $news->id);
            $news->image = $file;
        }

        $news->save();

        return redirect()->route('news.show', ['news' => $news])
            ->withSuccess('News successfully created!');
    }

    public function show(News $news)
    {
        $visibleCategories = $news->categories->filter(function ($category) {
            return !str_starts_with($category->name, '~');
        });

        return view('pages.news.news', ['news' => $news, 'categories'=> $visibleCategories]);
    }

    public function edit(News $news)
    {
        Gate::authorize('update', $news);
        return view('pages.news.editnews', ['news' => $news]);
    }

    public function update(Request $request, News $news)
    {
        Gate::authorize('update', $news);
        $request->validate([
            'title' => 'nullable|string|unique:news|max:250',
            'newscontent' => 'nullable|string',
            'file' => 'nullable|image|mimes:jpeg,jpg,png,gif',
            'categories' => 'required'
        ]);

        $news->update([
            'title'=> $request->title ?? $news->title,
            'content'=> $request->newscontent ?? $news->content,
        ]);

        if($request->has('categories')) {
            $categories = is_array($request->categories) ? $request->categories : [$request->categories];
            $news->categories()->sync($categories);
        }

        if($request->hasFile('file')) {
            $file = FileController::upload($request, $news->id);
            $news->image = $file;
        }

        $news->save();

        return redirect()->route('news.show', ['news' => $news])
            ->withSuccess('News successfully edited!');
    }

    public function delete(News $news)
    {
        Gate::authorize('delete', $news);
        return view('pages.news.deletenews', ['news' => $news]);
    }

    public function destroy(News $news)
    {
        Gate::authorize('delete', $news);
        if($news->newsVotes()->count() == 0 && $news->comments()->count() == 0) {
            $news->delete();
            return redirect()->route('allNews')
                ->withSuccess('News deleted successfully!');
        }
        else {
            return back()->withErrors([
                'news' => 'This news item has votes or comments.'
            ]);
        }
    }

    public function showAllNews(Request $request)
    {
        $search = trim($request->input('search'));
        $author = trim($request->input('author'));
        $category = trim($request->input('category'));
        $verified = $request->boolean('verified');
        $sort = $request->input('sort');

        $query = News::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereRaw("tsvectors @@ plainto_tsquery('english', ?)", [$search])
                    ->orWhereHas('user', function($u) use ($search) {
                        $u->where('username', 'ILIKE', '%' . $search . '%')
                        ->orWhere('name', 'ILIKE', '%' . $search . '%');
                    })

                    ->orWhereHas('categories', function($c) use ($search) {
                        $c->where('name', 'ILIKE', '%' . $search . '%');
                });

            });
        } 

        if($author) {
            $query->whereHas('user', function($q) use ($author) {
                $q->where('username', 'ILIKE', '%' . $author . '%')
                  ->orWhere('name', 'ILIKE', '%' . $author . '%');
            });
        }
        
        if($category) {
            $query->whereHas('categories', function($q) use ($category) {
                $q->where('name', 'ILIKE', '%' . $category . '%');
            });
        }
        
        if($verified) {
            $query->whereHas('checkmark');
        }
        
        if($sort === 'upvotes') {
            $query->withCount(['newsVotes as upvotes_count' => function ($q) {
                $q->join('votes', 'news_votes.vote_id', '=', 'votes.id')
                  ->where('votes.value', 1);
            }])
            ->orderBy('upvotes_count', 'desc');
        } elseif ($search && $sort !== 'date') {
            $query->orderByRaw("ts_rank(tsvectors, plainto_tsquery('english', ?)) DESC", [$search]);
        } else {
            $query->orderBy('date', 'desc');
        }

        $allNews = $query->get();

        $news_categories = [];
        foreach ($allNews as $news) {
            $cats = $news->categories->filter(fn($c) => !str_starts_with($c->name, '~'));
            $news_categories[] = ['news' => $news, 'categories' => $cats];
        }
    
        return view('pages.allnews', [
            'news_categories' => $news_categories,
        ]);
    }

    public function homepage()
    {
        $search = trim(request('search'));
        $user = auth()->user();
        $newsQuery = News::query();

        if ($user) {
            $followingIds = $user->following()->pluck('users.id');
            $followedCategoryNames = $user->followedCategories()->pluck('categories.name');

            if ($followingIds->count() > 0 || $followedCategoryNames->count() > 0) {
                $newsQuery->where(function($q) use ($followingIds, $followedCategoryNames) {
                    if ($followingIds->count() > 0) {
                        $q->orWhereIn('user_id', $followingIds);
                    }
                    if ($followedCategoryNames->count() > 0) {
                        $q->orWhereHas('categories', function($catQ) use ($followedCategoryNames) {
                            $catQ->whereIn('name', $followedCategoryNames);
                        });
                    }
                });
                $newsQuery->orderBy('date', 'desc')->take(10);
            } else {
                $newsQuery->orderBy('date', 'desc')->take(3);
            }
        } else {
            $newsQuery->orderBy('date', 'desc')->take(3);
        }

        if ($search) {
            $newsQuery->where(function($q) use ($search) {
                $q->whereRaw("tsvectors @@ plainto_tsquery('english', ?)", [$search])
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('username', 'ILIKE', '%' . $search . '%')
                        ->orWhere('name', 'ILIKE', '%' . $search . '%');
                  })
                  ->orWhereHas('categories', function($c) use ($search) {
                      $c->where('name', 'ILIKE', '%' . $search . '%');
                  });
            });

            $newsQuery->orderByRaw("ts_rank(tsvectors, plainto_tsquery('english', ?)) DESC", [$search]);
        } else {
            $newsQuery->orderBy('date', 'desc');
        }

        $allNews = $newsQuery->get();

        $news_categories = [];
        foreach($allNews as $news) {
            $cats = $news->categories->filter(fn($c) => !str_starts_with($c->name, '~'));
            $news_categories[] = ['news' => $news, 'categories' => $cats];
        }
        return view('pages.homepage', ['news_categories' => $news_categories]);
    }

    public function storeComment(Request $request, News $news)
    {
        $content = $request->input('content');
        
        if (!$content || strlen(trim($content)) === 0) {
            return response()->json(['success' => false, 'error' => 'Content required'], 400);
        }

        try {
            $comment = Comment::create([
                'content' => trim($content),
                'user_id' => auth()->id(),
                'news_id' => $news->id
            ]);

            (new CommentNotificationController())->store($comment);

            return response()->json(['success' => true, 'message' => 'Comment posted!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function editComment(Request $request, Comment $comment)
    {
        Gate::authorize('update', $comment);

        $content = $request->input('content');
        
        if (!$content || strlen(trim($content)) === 0) {
            return response()->json(['success' => false, 'error' => 'Content required'], 400);
        }

        try {
            $comment->content = trim($content);
            $comment->save();

            return response()->json(['success' => true, 'message' => 'Comment edited!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function deleteComment(Comment $comment)
    {
        Gate::authorize('delete', $comment);

        try {
            $comment->delete();
            return response()->json(['success' => true, 'message' => 'Comment deleted!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function toggleVote(Request $request, News $news)
    {
        try {
            $userId = auth()->id();
            $voteValue = $request->input('value'); 
            
            if (!in_array($voteValue, [1, -1])) {
                return response()->json(['success' => false, 'error' => 'Invalid vote value'], 400);
            }
            
            $existingVote = NewsVote::where('news_id', $news->id)
                ->where('user_id', $userId)
                ->first();
            
            if ($existingVote) {
                $vote = Vote::find($existingVote->vote_id);
                
                if ($vote->value == $voteValue) {
                    $existingVote->delete();
                    $vote->delete();
                    $userVote = 0;
                } else {
                    $vote->value = $voteValue;
                    $vote->save();
                    $userVote = $voteValue;
                }
            } else {
                $vote = Vote::create([
                    'user_id' => $userId,
                    'value' => $voteValue
                ]);
                
                NewsVote::create([
                    'vote_id' => $vote->id,
                    'news_id' => $news->id,
                    'user_id' => $userId
                ]);
                
                (new VoteNotificationController())->store($vote);
                $userVote = $voteValue;
            }
            
            $upvotes = NewsVote::join('votes', 'news_votes.vote_id', '=', 'votes.id')
                ->where('news_votes.news_id', $news->id)
                ->where('votes.value', 1)
                ->count();
                
            $downvotes = NewsVote::join('votes', 'news_votes.vote_id', '=', 'votes.id')
                ->where('news_votes.news_id', $news->id)
                ->where('votes.value', -1)
                ->count();
            
            return response()->json([
                'success' => true,
                'userVote' => $userVote,
                'upvotes' => $upvotes,
                'downvotes' => $downvotes
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function toggleCommentVote(Request $request, Comment $comment)
    {
        try {
            $userId = auth()->id();
            $voteValue = $request->input('value');
            
            if (!in_array($voteValue, [1, -1])) {
                return response()->json(['success' => false, 'error' => 'Invalid vote value'], 400);
            }
            
            $existingVote = CommentVote::where('comment_id', $comment->id)
                ->where('user_id', $userId)
                ->first();
            
            if ($existingVote) {
                $vote = Vote::find($existingVote->vote_id);
                
                if ($vote->value == $voteValue) {
                    $existingVote->delete();
                    $vote->delete();
                    $userVote = 0;
                } else {
                    $vote->value = $voteValue;
                    $vote->save();
                    $userVote = $voteValue;
                }
            } else {
                $vote = Vote::create([
                    'user_id' => $userId,
                    'value' => $voteValue
                ]);
                
                CommentVote::create([
                    'vote_id' => $vote->id,
                    'comment_id' => $comment->id,
                    'user_id' => $userId
                ]);
                
                (new VoteNotificationController())->store($vote);

                $userVote = $voteValue;
            }
            
            $upvotes = CommentVote::join('votes', 'comment_votes.vote_id', '=', 'votes.id')
                ->where('comment_votes.comment_id', $comment->id)
                ->where('votes.value', 1)
                ->count();
                
            $downvotes = CommentVote::join('votes', 'comment_votes.vote_id', '=', 'votes.id')
                ->where('comment_votes.comment_id', $comment->id)
                ->where('votes.value', -1)
                ->count();
            
            return response()->json([
                'success' => true,
                'userVote' => $userVote,
                'upvotes' => $upvotes,
                'downvotes' => $downvotes
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function toggleCheckmark(Request $request, News $news)
    {
        try {
            $userId = auth()->id();
            
            // Check if user is a moderator
            if (!Moderator::where('user_id', $userId)->exists()) {
                return response()->json(['success' => false, 'error' => 'Unauthorized'], 403);
            }
            
            // Check if checkmark already exists
            $existingCheckmark = $news->checkmark;
            
            if ($existingCheckmark) {
                // Remove checkmark
                $moderatorAction = $existingCheckmark->moderatorAction;
                $existingCheckmark->delete();
                $moderatorAction->delete();
                
                return response()->json([
                    'success' => true,
                    'hasCheckmark' => false
                ]);
            } else {
                // Add checkmark
                $moderatorAction = ModeratorAction::create([
                    'moderator_id' => $userId,
                    'date' => now()
                ]);
                
                Checkmark::create([
                    'moderator_action_id' => $moderatorAction->id,
                    'news_id' => $news->id
                ]);
                
                return response()->json([
                    'success' => true,
                    'hasCheckmark' => true
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

}
