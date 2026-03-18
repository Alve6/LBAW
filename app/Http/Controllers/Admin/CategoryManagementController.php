<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryManagementController extends Controller
{
    public function index()
    {
        $tags = Category::where('name', 'NOT LIKE', '~%')
                        ->orderBy('name', 'asc')
                        ->get();
        
        $suggestedTags = Category::where('name', 'LIKE', '~%')
                                 ->orderBy('name', 'asc')
                                 ->get();

        return view('pages.admin.tags', [
            'tags' => $tags, 
            'suggestedTags' => $suggestedTags
        ]);
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:categories|max:50']);
        
        $category = new Category();
        $category->name = $request->name;
        $category->timestamps = false;
        $category->save();
        

        return redirect()->back()->withSuccess('Official tag created successfully!');
    }

    public function approve($category)
    {
        $tag = Category::where('name', $category)->firstOrFail();
        
        $cleanName = ltrim($tag->name, '~');

        $existing = Category::where('name', 'ILIKE', $cleanName)->first();

        if ($existing) {
            $newsItems = $tag->news;
            foreach($newsItems as $news) {
                if (!$news->categories->contains($existing->name)) {
                    $news->categories()->attach($existing->name);
                }
            }
            $tag->news()->detach();
            $tag->delete();

            return redirect()->back()->withSuccess("Suggestion merged with existing tag '{$cleanName}'.");
        } else {
            $tag->update(['name' => $cleanName]);
            return redirect()->back()->withSuccess("Tag approved: {$cleanName}");
        }
    }

    public function update(Request $request, Category $category)
    {
        $tag = Category::where('name', $category)->firstOrFail();

        if (str_starts_with($request->name, '~')) {
             return redirect()->back()->withErrors('Tag name cannot start with "~".');
        }

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:50',
                \Illuminate\Validation\Rule::unique('categories', 'name')->ignore($tag->name, 'name')
            ]
        ]);

        $tag->update(['name' => $request->name]);

        return redirect()->back()->withSuccess('Tag updated successfully!');
    }

    public function destroy($category)
    {
        $tag = Category::where('name', $category)->firstOrFail();
        
        $tag->news()->detach(); 
        $tag->delete();

        return redirect()->back()->withSuccess('Tag deleted successfully!');
    }
}