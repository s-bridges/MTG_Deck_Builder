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
    public function __construct(Request $request)
    {
        
    }

    public function allBlogs() {
        $posts = Post::latest()->get();
        $data = collect(['posts' => $posts]);
        return view('all-blogs', ['data' => $data]);
    }

    public function showBlog($slug) {
        // get post by slug
        $post = Post::where('slug', $slug)->with('user')->first()->toArray();
        return view('blog-post', $post);
    }
    
    public function deletePost($id) {
        $check = Auth::check();
        $post = Post::where('id', $id);
        $deleted = $post->delete();
        if ($deleted) {
            return response()->json(['status' => true, 'message' => 'Deleted Successfully!']);
        } else {
            return response()->json(['status' => false, 'message' => json_encode($deleted)]);
        }
    }

    public function randomPost() {
        $post = Post::orderByRaw('RAND()')->first()->toArray();
        return view('welcome', [ 'post' => $post ]);
    }
}

