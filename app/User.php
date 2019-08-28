<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use \Illuminate\Http\UploadedFile;
use App\Tweet;
use App\UserFollower;

class User extends Authenticatable {

    use Notifiable,
        HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password'//, 'image'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * error code which represent error occured
     * @var string 
     */
    protected $errorCode;

    /**
     * User's Tweets
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function tweets() {
        return $this->hasMany(Tweet::class);
    }

    /**
     * Following Users
     * @return type
     */
    public function following() {
        return $this->belongsToMany(User::class, 'user_followers', 'follower_id', 'user_id')->withTimestamps();
    }

    /**
     * get followin users ids
     * @return type
     */
    public function followingIds() {
        return $this->following()->pluck('user_id');
    }

    /**
     * Mutator called when set password attribute
     * @param string $value
     */
    public function setPasswordAttribute($value) {
        if ($value) {
            $this->attributes['password'] = Hash::make($value);
        }
    }

    /**
     * Accessor called when retrieving image attribute
     * @param string $value
     * @return string
     */
    public function getImageAttribute($value) {
        return $value ? url('users/' . $value) : null;
    }

    /**
     * store new user
     * @param array $data
     * @param UploadedFile $image
     * @return boolean
     */
    public function store($data, UploadedFile $image) {
        $this->fill($data);
        if ($image) {
            $this->uploadImage($image);
        }
        return $this->save();
    }

    /**
     * Upload User Image
     * @param UploadedFile $image
     * @return boolean
     */
    public function uploadImage(UploadedFile $image) {
        $this->image = time() . '-' . str_random(5) . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('/users'), $this->image);
        return true;
    }

    /**
     * generate user access token
     * @return string
     */
    public function accessToken() {
        return $this->createToken('Personal Access Token')->accessToken;
    }

    /**
     * follow user if not followed ($this reoresents follower user)
     * @param \App\User $user to be followed
     * @return boolean
     */
    public function follow(User $user) {
        if ($user->following->contains($this->id)) {
            $this->errorCode = 'already_followed';
            return FALSE;
        }
        $user->following()->attach($this->id);
        return TRUE;
    }

    /**
     * get error code
     * @return string
     */
    public function getErrorCode() {
        return $this->errorCode;
    }

}
