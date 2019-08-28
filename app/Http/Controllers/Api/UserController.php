<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
//use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\User;

class UserController extends Controller {

    /**
     * Store User and return access token
     * @param Request $request
     * @param User $user
     * @return Illuminate\Http\JsonResponse
     */
    public function store(Request $request, User $user) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users|email',
            'password' => 'required|min:6',
            'image' => 'nullable|image'
        ]);
        $user->store($request->toArray(), $request->file('image'));
        return responder()->success(['access_token' => $user->accessToken()])->respond();
    }

    /**
     * login
     * @param Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function login(Request $request) {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return responder()->error('invalid_user', trans('app.invalid_user'))->respond();
        }
        return responder()->success(['access_token' => Auth::user()->accessToken()])->respond();
    }

    /**
     * Follow another user if not followed 
     * @param User $user
     * @return Illuminate\Http\JsonResponse
     */
    public function follow(User $user) {
        if ($user->follow(Auth::user())) {
            return responder()->success()->respond();
        }
        return responder()->error($user->getErrorCode(), trans('app.' . $user->getErrorCode()))->respond();
    }

}
