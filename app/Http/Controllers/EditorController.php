<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Deck;
use App\Card;
use App\Post;
use App\PowerLevel;
use Auth;
use Log;
use Validator;
use GuzzleHttp\Client;

class EditorController extends Controller
{
        
    public function __construct(Request $request)
        {
            $this->middleware('auth');
            $this->request = $request;
        }

    public function editor()
    {
        $users = User::with('decks')->withCount('decks')->get();
        $data = collect(['users' => $users, 'editor' => Auth::user()]);
        return view('editor-page', ['data' => $data]);
    }


    public function createBlogPost()
    {
        return view('blog');
    }

    // used for displaying edited blog
    public function editBlogPost($slug)
    {
        $post = Post::where('slug', $slug)->with('user')->first();
        if ($post) {
            return view('blog', $post->toArray()); 
        }
        return redirect('/404');
    }

    public function saveBlogPost()
    {
        $data = $this->request->all();
        $data['slug'] = preg_replace('/\s+/', '-', strtolower($data['title']));
        // make sure title and slug are unique within the DB
        $validation = Validator::make($data, $this->blogValidation());
        if ($validation->passes()) {
            // save image 
            $image = $data['input_img'];
            $image_name = time() . '-' . $image->getClientOriginalName();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $image_name);
            $data['image_url'] = $image_name;
            $data['user_id'] = Auth::user()->id;

            $saved = Post::create($data);
            if ($saved) {
                // messaging that the shit actually happened, for now we can just take a dump
                return response()->json(['status' => true, 'message' => 'Saved Successfully!', 'payload' => $saved]);
            } else {
                // we can get more detailed with an error message later on, like actually outputting it here
                return response()->json(['status' => false, 'message' => 'Post save failed, please reload the page']);
            }
        }
        // if there were errors from validation, return them
        return response()->json([
            'success' => false,
            'message' => json_decode($validation->errors())
        ], 422);
    }
    public function blogValidation($is_edit = false) {
        if ($is_edit) {
            return [
                'title' => 'required|max:255',
                'content' => 'required'
            ];
        } else {
            return [
                'slug' => 'required|unique:posts|max:255',
                'title' => 'required|unique:posts|max:255',
                'input_img' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'content' => 'required'
            ];
        }
    }
    public function updateBlogPost()
    {
        // dd($this->request);
        $data = $this->request->all();
        $image_set = isset($data['input_img']) && $data['input_img'] != "undefined";
        // make sure title and slug are unique within the DB
        $validation = Validator::make($data, $this->blogValidation($data['is_edit']));
        if ($validation->passes()) {
            // save image 
            if ($image_set) {
                $image = $data['input_img'];
                $image_name = time() . '-' . $image->getClientOriginalName();
                $destinationPath = public_path('/images');
                $image->move($destinationPath, $image_name);
                $data['image_url'] = $image_name;
            }
            $updatedData = $data;
            if (!$image_set) {
                // unset the image
                unset($data['image_url']);
            }
            if(isset($image_name)) {
                $updatedData['image_url'] = $image_name;
            }
            // update by using the slug since slug is unique
            $saved = Post::updateOrCreate(
                ['slug' => $data['slug']],
                $updatedData
            );
            if ($saved) {
                // messaging that the shit actually happened, for now we can just take a dump
                return response()->json(['status' => true, 'message' => 'Saved Successfully!', 'payload' => $saved]);
            } else {
                // we can get more detailed with an error message later on, like actually outputting it here
                return response()->json(['status' => false, 'message' => 'Post save failed, please reload the page']);
            }
        }
        // if there were errors from validation, return them
        return response()->json([
            'success' => false,
            'message' => json_decode($validation->errors())
        ], 422);
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
}