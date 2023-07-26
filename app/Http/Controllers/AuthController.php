<?php

/**
 * Auth controller class for maintaining authorization and registration
 */
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Illuminate\Support\Facades\Hash;
use App\Http\Helpers\ResponseTrait;

/**
 * Auth controller class for maintaining authorization and registration
 * of front end user
 */
class AuthController extends Controller {

    use ResponseTrait;

    /**
     * User Signup
     * @param Request $request
     * @return type
     */
    public function signup(Request $request)
    {
        $validations = config('api_validations.front_signup_validation');
        $validation = Validator::make($request->all(), $validations);
        if ($validation->fails()) {
            return $this->validationError($validation);
        }

        try {
            $password = base64_encode($request->password);
            $user = User::create([
                        "name"      => $request->name,
                        "email"     => $request->email,
                        "password"  => $password
                    ]);
            $accessToken = $this->getAuthToken($user);
            
            return $this->success(['user' => $user, 'token' => $accessToken], 'USER_LOGGEDIN');
        } catch (\Exception $e) {
            return $this->error('USER_REGISTERATION_ERROR');
        }
    }

    /**
     * Authenticate user SignIn
     * @param Request $request
     * @return type
     */
    public function signIn(Request $request)
    {
        try {            
            $validations = config('api_validations.front_signin_validation');
            $validation = Validator::make($request->all(), $validations);
            if ($validation->fails()) {
                return $this->validationError($validation);
            }
            
            $user = User::where([
                'email'     => $request->email,
                'password'  => base64_encode($request->password)
            ])->first();
            $accessToken = $this->getAuthToken($user);            
            
            return $this->success(['user' => $user, 'token' => $accessToken], 'USER_LOGGEDIN');
        } catch (Exception $e) {
            return $this->error('USER_LOGGEDIN_ERROR');
        }
    }

    /**
     * Logout
     * @param Request $request
     * @return type
     */
    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->token()->revoke();
            return $this->success([], 'USER_LOGGED_OUT');
        }
        return $this->error('USER_DOESNT_EXIST');
    }

    /**
     * Generate access token
     * @param type $username
     * @param type $password
     * @return type
     */
    private function getAuthToken($user)
    {
        $token = $user->createToken($user->email);
 
        return ['token' => $token->plainTextToken];
    }
    
    /**
     * User middle failed
     * @return type
     */
    public function loginError()
    {
        $meta = [
            'status' => false,
            'message' => 'Unauthorized',
            'message_code' => 'UNAUTHORIZED',
            'status_code' => 401
        ];

        return response()->json(['meta' => $meta], 401);
    }
}
