<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use ApiResponser;
    
    /**
     * @param Request $request
     * 
     * @return ApiResponser $response
    */
    public function login(LoginRequest $request)
    {
        $user = User::where('username', $request->username)->orWhere('email', $request->username)->first();
        if ($user) {
            if(Hash::check($request->password, $user->password)){
                unset($user->password);
                $user->token = $user->createToken('API Token')->plainTextToken;
                return $this->success($user, 'Logged In Successfully.');
            }
        }
        return $this->error('Sorry, wrong email or password. Please try again.', 401);
    }

    /**
     * @param Request $request
     * 
     * @return ApiResponser $response
    */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->success([], 'Logged Out Successfully');
    }

    /**
     * @param Request $request
     * 
     * @return ApiResponser $response
    */
    public function signup(SignupRequest $request)
    {
        $user = new User();
        $user->username = $request->username;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        return $this->success($user, 'User Created Successfully.');
    }
}
