<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Deck;
use App\User;
use App\Card;
use App\Sideboard;
use App\Post;
use Auth;
use JavaScript;

class BlogController extends Controller
{
    //
    public function allBlogs() {
        $posts = Post::all()->toArray();
        $data = collect(['posts' => $posts]);
        return view('all-blogs', ['data' => $data]);
    }

    public function showBlog($slug) {
        // get post by slug
        $post = Post::where('slug', $slug)->with('user')->first()->toArray();
        return view('blog-post', $post);
    }
}