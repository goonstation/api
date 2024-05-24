<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class GameAuthController extends Controller
{
    public function show()
    {
        return view('game-auth');
    }

    public function login(Request $request)
    {
      $validateUser = Validator::make($request->all(), 
      [
          'email' => 'required|email',
          'password' => 'required'
      ]);

      if ($validateUser->fails()){
          return response()->json([
              'status' => false,
              'message' => 'validation error',
              'errors' => $validateUser->errors()
          ], 401);
      }

      if (!Auth::attempt($request->only(['email', 'password']))) {
        return response()->json([
          'status' => false,
          'message' => 'Email & Password does not match with our record.',
        ], 401);
      }

      $user = User::where('email', $request->email)->first();

      return response()->json([
        'status' => true,
        'message' => 'User Logged In Successfully',
      ], 200);
    }
}
