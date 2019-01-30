<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Deck;
use App\User;
use App\Card;
use App\Sideboard;
use App\Post;
use App\Comment;
use Auth;
use JavaScript;

class BlogController extends Controller
{
    //
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function allBlogs() {
        $posts = Post::latest()->get();
        $data = collect(['posts' => $posts]);
        return view('all-blogs', ['data' => $data]);
    }

    public function showBlog($slug) {
        // get post by slug
        $post = Post::where('slug', $slug)->with('user')->with('comments')->with('comments.user')->first()->toArray();
        $data = collect(['post' => $post]);
        return view('blog-post', ['data' => $data]);
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

    // need a funtion called saveComment
    public function saveComment() {
        $check = Auth::check();
        if ($check) {
            // if not logged in, return unautorized or not found response below, 
            // get request data from submit on modal, this request consists of comment title, post_id, and comment body
            $comment = $this->request->all();
            // need to add user id as key value pair on comment, you'll get this from Auth::user()->id, almost, you need to set user_id on $comment
            $comment['user_id'] = Auth::user()->id; // id is a property, not a method/function, we had parens
            // find or fail based on post_id passed along with request
            $post = Post::findOrFail($comment['post_id']); //<-- changed from blog_id
            // create comment
            $created = Comment::create($comment);
            if ($created) {
                // messaging that the shit actually happened, for now we can just take a dump
                return response()->json(['status' => true, 'message' => 'Saved Successfully!', 'payload' => $created->toArray()]);
            } else {
                // we can get more detailed with an error message later on, like actually outputting it here
                return response()->json(['status' => false, 'message' => 'Sync Failed, please reload the page']);
            }
        }
        // same default response if the auth check fails essentially haha
        return response('Unauthorized or resource not found', 419)->header('Content-Type', 'text/plain');
    }

    // need a function call deleteComment, this will look similar to deletePost
    public function deleteComment($id) {
        $user_id = Auth::user()->id;

        // query if original user, if not, then query returns empty
        $comment = Comment::where('id', $id)->where('user_id', $user_id)->first();
            if (!isset($comment)) {
            // run second query if they are an admin
            //secondf wuery
            $admin = Auth::user()->isAdmin();
            if($admin){
                $comment = Comment::findOrFail($id);
            }
        }
            // check if they are admin, then run this if they aren't orignal user
        // do this if there is a comment, otherwise, throw 404 or similar
            // delete if comment exists
        if (isset($comment)){
            $deleted = $comment->delete();
            if ($deleted) {
                return response()->json(['status' => true, 'message' => 'Deleted Successfully!']);
            } else {
                return response()->json(['status' => false, 'message' => json_encode($deleted)]);
            }
        }
        
        // default returned response, no need for conditional as if the others above apply, those will be returned first and the rest of the code
        // unexecuted
        // something like below should work...we don't have to get too hung up on this just yet, but figured I'd mention it
        return response('Unauthorized or resource not found', 419)->header('Content-Type', 'text/plain');
    }
    
    public function likeComment($id) {
        $check = Auth::check();

        // query if original user, if not, then query returns empty
        $comment = Comment::findOrFail($id);
        if(isset($comment)){
            $updated = $comment->update(['likes' => $comment->likes + 1]);
            if ($updated) {
                return response()->json(['status' => true, 'message' => 'Liked Successfully!']);
            } else {
                return response()->json(['status' => false, 'message' => json_encode($deleted)]);
            }
        }
        
        // default returned response, no need for conditional as if the others above apply, those will be returned first and the rest of the code
        // unexecuted
        // something like below should work...we don't have to get too hung up on this just yet, but figured I'd mention it
        return response('Unauthorized or resource not found', 419)->header('Content-Type', 'text/plain');
    }
}

