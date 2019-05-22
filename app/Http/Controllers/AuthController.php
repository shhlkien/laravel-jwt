<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
	public function __construct() {
		// $this->middleware('jwt.verify', ['except' => ['login', 'register']]);
	}

	public function resetPassword(Request $req) {

		$req->validate([
			'password' => 'required|string|min:6|confirmed',
		]);

		$user = JWTAuth::parseToken()->user();
		$user->password = bcrypt($req->password);
		$user->save();

		$token = JWTAuth::parseToken()->refresh();

		return $this->respondWithToken($token);
	}

	public function register(Request $req) {

		$req->validate([
			'name' => 'required|string',
			'email' => 'required|email|unique:users',
			'password' => 'required|string|min:6'
		]);

		$user = new User();
		$user->name = $req->name;
		$user->email = $req->email;
		$user->password = bcrypt($req->password);
		$user->save();

		return $this->login($req);
	}

	public function login(Request $req) {

		$req->validate([
			'email' => 'required|email',
			'password' => 'required|string|min:6'
		]);

		$credentials = $req->only('email', 'password');
		$token = JWTAuth::attempt($credentials);

		if (!$token) {
			return response()->json([
				'ok' => false,
				'error' => 'Unauthorized'
			], 401);
		}

		return $this->respondWithToken($token);
	}

	public function me() {

		$user = JWTAuth::parseToken()->user();

		return response()->json([
			'ok' => true,
			'user' => $user,
			// 'payload' => JWTAuth::parseToken()->payload()
		]);
	}

	public function logout() {

		JWTAuth::parseToken()->invalidate();

		return response()->json([
			'ok' => true,
			'message' => 'Successfully logged out'
		]);
	}

	public function refresh() {

		return $this->respondWithToken(JWTAuth::parseToken()->refresh());
	}

	protected function respondWithToken($token) {

		return response()->json([
			'ok' => true,
			'access_token' => $token,
			'token_type' => 'Bearer',
    		'expires_in' => JWTAuth::factory()->getTTL() * 60
		]);
	}
}