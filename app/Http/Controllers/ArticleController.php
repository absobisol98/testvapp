<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog\Post;

class ArticleController extends Controller
{
    public function viewStories(Request $request){

            $search = $request->input('search');

            $articles = Post::query()
                ->when($search, function ($query, $search) {
                    return $query->where('title', 'like', '%' . $search . '%');
                })
                ->latest()
                ->paginate(7)
                ->withQueryString(); // This preserves the search parameter in pagination links

            return view('articles.view-stories', compact('articles'));




    }


    public function viewArticle($slug){

        $selected_article = Post::where('slug', $slug)->first();
        $articles = Post::orderBy('created_at', 'desc')->get();


        return view('articles.view-article', ['article' => $selected_article, 'articles' => $articles]);
    }
}
