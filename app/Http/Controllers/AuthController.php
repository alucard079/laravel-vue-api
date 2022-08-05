<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Notifications\VerifyUserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Notification;

class AuthController extends Controller
{   
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     */
    public function login(LoginRequest $request) {
        if($request->validated()) {
            if(! auth()->attempt($request->validated())){
                return response()->json(['error' => 'Wrong credentials'], 401);
            }
            
            if(\is_null(auth()->user()->email_verified_at))  {
                return response()->json(['error' => 'Verify your email first'], 400);
            } else {    
                $accessToken = auth()->user()->createToken('authToken')->accessToken;
            }
            $user = auth()->user();

            $permissions = $user->getPermissionsViaRoles();
            
            $user->permissions = $permissions->pluck('name');

            return response(['user' => $user, 'access_token' => $accessToken], 200);
        }
    }

    /**
     * Handle a register request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        if($user) {
            $details = [
            'greeting' => 'Hi, ' . $user->name,
                'body' => 'We received your registration to our system.',
                'below' =>
                    'Click the button, to verify your email',
                'action_text' => 'Click to Verify',
                'action_url' => url('/verify/'.$user->email)
            ];
            $user->notify(new VerifyUserNotification($details));
            return response()->json([
                'message' => 'User Created Successfully!',
            ], 200);
        }
    }

    /**
     * Handle a verify request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     */
    public function verify($email) {
        if($email) {
            $user = User::where('email',$email)->first();
            $user->email_verified_at = date("Y-m-d h:i:s");
            $user->save();

            return response()->json([
                'message' => 'User verified!',
            ], 200);
        }
    }
    /**
     * Handle a logout request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     */
    public function logout (Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }


}
