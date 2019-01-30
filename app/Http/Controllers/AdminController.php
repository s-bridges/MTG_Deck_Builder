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

class AdminController extends Controller
{
    const TCGPLAYER = 'https://api.tcgplayer.com/token';

    public function __construct(Request $request)
    {
        /* $tcg_client_id = env('TCG_CLIENT_ID', '');
        $tcg_client_secret = env('TCG_CLIENT_SECRET', '');
        $token = env('ACCESS_TOKEN', ''); */
        $this->middleware('auth');
        $this->request = $request;
        // $this->client = $client;
        
        // dd($this->client);
        // $client = new Client([
        //     // Base URI is used with relative requests
        //     'base_uri' => 'http://httpbin.org',
        //     // You can set any number of default request options.
        //     'timeout'  => 2.0,
        // ]);
        // $response = $this->client->request('POST', self::TCGPLAYER, [
        //     'form_params' => [
        //         'grant_type' => 'client_credentials', 
        //         'client_id' => $tcg_client_id,
        //         'client_secret' => $tcg_client_secret,
        //     ]
        // ]);

        // $response = $client->request('GET', 'https://api.tcgplayer.com/v1.17.0/catalog/categories', [
        //     'headers' => [
        //         'Authorization' => 'Bearer ' . $token,
        //         'Accept'        => 'application/json',
        //     ]
        // ]);

        // $client = new Client(['base_uri' => 'https://api.tcgplayer.com/v1.17.0/catalog/categories']);
        // $client->request('GET', '/get', ['auth' => ['username', 'password']]);

        // $headers = [
        //     'Authorization:' => 'Bearer ' . $token,
        //     'Accept'        => 'application/json',
        // ];
        // $token = (string) 'Bearer ' . $token;
      /*   $response = $client->request('GET', 'https://api.tcgplayer.com/v1.17.0/catalog/categories', [
            'headers' => ['Authorization' => 'Bearer ' . $token]
        ]);
        $response = json_decode($response->getBody()->getContents(), true);
        dd($response);
 */
    }

    public function admin()
    {
        // get deck of the week
        $dotw = Deck::where('deck_of_the_week', 1)->first();
        $user = User::all();
        // get all users and decks if they have them, as well as a total count for decks a user has
        $users = User::with('decks')->withCount('decks')->get();
        $data = collect(['users' => $users, 'admin' => Auth::user(), 'dotw' => $dotw->id, 'user' => $user]);
        return view('admin-page', ['data' => $data]);
    }

    public function deckOfTheWeekSave()
    {
        $deck_to_update = (int) $this->request->input('deck_id');
        // findOrFail
        $deck = Deck::where('id', $deck_to_update)->firstOrFail();

        $deckOfWeek = Deck::where('deck_of_the_week', 1)->first();
        if ($deckOfWeek){
            $deckOfWeek->deck_of_the_week = 0;
            $deckOfWeek->save();
        }

        // set deck_of_the_week to 1
        $deck->deck_of_the_week = 1;
        // model->save()
        $saved = $deck->save();
        if ($saved) {
            return response()->json(['status' => true, 'message' => 'Saved Successfully!']);
        }
    }

    public function importCards()
    {
        $cards = Card::all();
        $cards = $cards->pluck('multiverse_id');
        $cards->each(function($item, $key){
            // file get contents based on $item
            $homepage = file_get_contents("http://gatherer.wizards.com/Handlers/Image.ashx?multiverseid=" . $item . "&type=card");
            $path = public_path('images/cards/' . $item . '.jpg'); 
            $saved = file_put_contents($path, $homepage);
            if (!$saved) {
                Log::error('Card image with ID:' . $item . ' not saved!');
            }
        });
    }

    public function changeUserType()
    {
        //here is the function to get all users, and then select specific user and give/revoke admin access
        $user_to_update = $this->request->input();

        // findOrFail
        $user = User::where('id', $user_to_update)->firstOrFail();

        $userEditorType = User::where('type', 'default')->first();
        if ($userEditorType){
            $userEditorType->type = 'default';
            $userEditorType->save();
        }

        // set user_type to editor
        $user->type = 'editor';
        // model->save()
        $saved = $user->save();
        if ($saved) {
            return response()->json(['status' => true, 'message' => 'Saved Successfully!']);
        }
    }

    public function connect()
    {
        //get bearer token

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

    public function powerLevels()
    {
        // get all cards
        $cards = Card::with('power_levels')->get()->toArray();
        // var_dump($cards);
        $power_levels = PowerLevel::select('id','name')->get();
        // make power level look like relationship
        $p_levels = collect($power_levels)->map(function($level, $key){
            return [
                'name' => $level['name'],
                'id' => $level['id'],
                'pivot' => [
                    'ranking' => 1.0
                ]
            ];
        })->keyBy('name');

        // transform power_levels to have levels if they didn't have them already
        $cards = collect($cards)->map(function($card, $key) use ($p_levels){
            $card['power_levels'] = collect($card['power_levels'])->keyBy('id');
            foreach ($p_levels as $level) {
                // if card doesn't have ranking
                if ($card['power_levels']->has($level['id']) === false){
                    $card['power_levels'][$level['id']] = $level;
                }
            }
            return $card;
        });

        $data = collect(['cards' => $cards, 'power_levels' => $power_levels->keyBy('name')]);
        return view('power-levels', ['data' => $data]);
    }

    public function syncPowerLevels()
    {
        $cards = $this->request->all();
        if (count($cards) > 0 ) {
            $power_levels = PowerLevel::select('id','name')->get();
            $synched_success = [];
            // transform cards coming in that are being synced with power levels
            $power_levels->each(function($level, $key) use($cards, &$synched_success){
                $updated = $cards[$level->name];
                // remove level id
                unset($updated['id']);
                // remove level name
                unset($updated['name']);
                $synched = $level->cards()->syncWithoutDetaching($updated);
                $synched_success[] = [
                    'updated' => $synched['updated'],
                    'attached' => $synched['attached']
                ];
            });
            if (count($synched_success) > 0) {
                // messaging that the shit actually happened, for now we can just take a dump
                return response()->json(['status' => true, 'message' => 'Ranking Successfully!', 'payload' => $power_levels->keyBy('name')]);
            } else {
                // we can get more detailed with an error message later on, like actually outputting it here
                return response()->json(['status' => false, 'message' => 'No cards rankings were added.']);
            }
        }
        // we can get more detailed with an error message later on, like actually outputting it here
        return response()->json(['status' => false, 'message' => 'No cards rankings were added.']);
    }
}