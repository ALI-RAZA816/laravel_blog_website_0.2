<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function createAccount(StoreUserRequest $request){
        try{
            $date = date('M d, y');
            $user = User::create([
                'name'=>$request->name,
                'email'=>$request->emailaddress,
                'password'=>Hash::make($request->password),
                'join_date'=>$date,
                'created_at'=>now(),
                'updated_at'=>now(),
            ]);

            return response()->json([
                'status'=>200,
                'message'=>'You created account successfully'
            ],200);

        }catch(ValidationException $e){
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }


    public function loginAccount(LoginUserRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Email or password is incorrect'], 401);
        }

        if ($user->status === 'blocked') {
            return response()->json(['message' => 'Your account is blocked'], 403);
        }
        
        $user->update(['status' => 'active']);
        $remember = $request->boolean('remember');
        if ($remember) {
            // optional: remember cookie lasts 30 days (in minutes). Default is much longer.
            Auth::guard('web')->setRememberDuration(60 * 24 * 30);
        }
        Auth::login($user, $remember);
        $request->session()->regenerate();

        return response()->json([
            'status'  => 200,
            'message' => 'Login Successful',
            'user'    => $user->only('id', 'name', 'email', 'role', 'image'),
        ], 200);
    }


    public function logoutAccount(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['status' => false, 'message' => 'No authenticated user'], 401);
        }

        User::where('id', $user->id)->where('status', '!=', 'blocked')->update(['status' => 'inactive']);

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['status' => true, 'message' => 'You logout your account'], 200);
    }
    
}
