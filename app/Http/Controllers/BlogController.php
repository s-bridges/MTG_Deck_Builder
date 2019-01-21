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
        $this->middleware('auth');
    }

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

    public function randomPostHome() {
        $post = Post::orderByRaw('RAND()')->first()->toArray();
        dd($post);
        return view('welcome', $post);
    }
}


/*     $deck = Deck::where('id', $id)->where('user_id', Auth::user()->id)->first();
   
} */