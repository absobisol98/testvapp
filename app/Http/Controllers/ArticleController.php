<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog\Post;

class ArticleController extends Controller
{
    //


    public function viewArticle($slug){

        $selected_article = Post::where('slug', $slug)->first();
        $articles = Post::orderBy('created_at', 'desc')->get();


        return view('articles.view-article', ['article' => $selected_article, 'articles' => $articles]);
    }
}
