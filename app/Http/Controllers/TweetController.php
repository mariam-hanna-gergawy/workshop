<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Tweet;

class TweetController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function index(Tweet $tweet) {
        return responder()->success($tweet->followingTweets(Auth::user())->paginate(), function ($followingTweet) {
                    return [
                        'user' => $followingTweet->user->name,
                        'user_image' => $followingTweet->user->image,
                        'content' => $followingTweet->content,
                        'created_at' => $followingTweet->created_at,
                    ];
                })->respond();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Illuminate\Http\JsonResponse  
     */
    public function store(Request $request) {
        $request->validate([
            'content' => 'required|max:140'
        ]);
        Auth::user()->tweets()->create($request->all());
        return responder()->success()->respond();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tweet  Tweet
     * @return Illuminate\Http\JsonResponse
     */
    public function destroy(Tweet $tweet) {
        $tweet->delete();
        return responder()->success()->respond();
    }

}
