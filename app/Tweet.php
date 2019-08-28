<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Tweet extends Model {

    protected $fillable = ['content'];
    protected $errors = array();

    /**
     * tweet's user
     * @return type
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * get Query Builder to retreive user following tweets
     * @param User $user
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function followingTweets(User $user) {
        return Tweet::with('user')
                        ->whereIn('user_id', $user->followingIds());
    }

    /**
     * Accessor called when created_at attribute
     * @param string $value
     * @return string
     */
    public function getCreatedAtAttribute($value) {
        return \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
        ;
    }

}
