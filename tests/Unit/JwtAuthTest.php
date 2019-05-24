<?php

namespace Tests\Unit;

use App\Http\Controllers\AuthController;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use JWTAuth;
use Tests\TestCase;

class JwtAuthTest extends TestCase {

	use DatabaseTransactions; // WARNING: this will truncate all data after running any test uses db transaction

	private $user;

	protected function setUp() : void {

		parent::setUp();
		$this->user = factory(User::class)->make();
	}

	public function test_failure_reset_password() {

		$this->user = User::first();
		$token = JWTAuth::attempt(['email' => $this->user->email, 'password' => '123457']);

		$this->expectException(\Illuminate\Validation\ValidationException::class);

		$this
		->withHeaders([
			'accept' => 'application/json',
			'cache-control' => 'no-cache',
			'authorization' => 'Bearer '.$token,
		])
		->post('/api/v1/auth/reset-password', [
			'password' => 'abc'
		]);
	}

	public function test_successful_reset_password() {

		$this->user = User::first();
		$token = JWTAuth::attempt(['email' => $this->user->email, 'password' => '123457']);

		$this
		->withHeaders([
			'accept' => 'application/json',
			'cache-control' => 'no-cache',
			'authorization' => 'Bearer '.$token,
		])
		->post('/api/v1/auth/reset-password', [
			'password' => '123457',
			'password_confirmation' => '123457',
		])
		->assertOk()
		->assertJsonStructure(['ok', 'token_type', 'expires_in', 'access_token']);
	}

	public function test_successful_get_info() {

		$this->user = User::first();
		$token = JWTAuth::attempt(['email' => $this->user->email, 'password' => '123457']);

		$this
		->withHeaders([
			'accept' => 'application/json',
			'cache-control' => 'no-cache',
			'authorization' => 'Bearer '.$token,
		])
		->get('/api/v1/auth/me')
		->assertOk()
		->assertJsonStructure(['ok', 'user']);
	}

	public function test_failure_get_info() {

		$this
		->withHeaders([
			'accept' => 'application/json',
			'cache-control' => 'no-cache',
			'authorization' => 'invalid token',
		])
		->get('/api/v1/auth/me')
		->assertStatus(401);
	}

	public function test_successful_logout() {

		$this->user = User::first();
		$token = JWTAuth::attempt(['email' => $this->user->email, 'password' => '123457']);

		$this
		->withHeaders([
			'cache-control' => 'no-cache',
			'accept' => 'application/json',
			'authorization' => 'Bearer '.$token,
		])
		->post('/api/v1/auth/logout')
		->assertOk()
		->assertJson([
			'ok' => true,
			'message' => 'Successfully logged out'
		]);
	}

	public function test_failure_logout() {

		$this
		->withHeaders([
			'cache-control' => 'no-cache',
			'accept' => 'application/json',
			'authorization' => 'Bearer invalidtoken',
		])
		->post('/api/v1/auth/logout')
		->assertStatus(401);
	}

	public function test_successful_register() {

		$res = $this
		->withHeaders([
			'accept' => 'application/json',
			'cache-control' => 'no-cache',
		])
		->post('/api/v1/auth/register', [
			'email' => $this->user->email,
			'name' => $this->user->name,
			'password' => $this->user->password,
		])
		->assertOk()
		->assertJsonStructure(['ok', 'token_type', 'expires_in', 'access_token']);
	}

	public function test_register_validation() {

		$this->expectException(\Illuminate\Validation\ValidationException::class);

		$this
		->withHeaders([
			'accept' => 'application/json',
			'cache-control' => 'no-cache',
		])
		->post('/api/v1/auth/register', [
			'email' => 'fakemail',
			'name' => $this->user->name,
			'password' => '123',
		]);
	}

	public function test_successful_login() {

		$user = User::first();

		$this
		->withHeaders([
			'accept' => 'application/json',
			'Cache-Control' => 'no-cache',
		])
		->post('/api/v1/auth/login', [
			'email' => $user->email,
			'password' => '123457',
		])
		->assertOk()
		->assertJsonStructure(['ok', 'token_type', 'expires_in', 'access_token']);
	}

	public function test_failure_login() {

		$this
		->withHeaders([
			'accept' => 'application/json',
			'Cache-Control' => 'no-cache',
		])
		->post('/api/v1/auth/login', [
			'email' => 'kiendp@gmail.com',
			'password' => '1234577',
		])
		->assertStatus(401)
		->assertJsonStructure(['ok', 'message'])
		->assertJson([
			'ok' => false,
			'message' => 'Unauthorized',
		]);
	}

	public function test_login_validation() {

		$this->expectException(\Illuminate\Validation\ValidationException::class);

		$this
		->withHeaders([
			'accept' => 'application/json',
			'Cache-Control' => 'no-cache',
		])
		->post('/api/v1/auth/login', [
			'email' => 'kiendpgmail.com',
			'password' => '1246',
		]);
	}

	/** This is a delegate for all 'not allowed method' exceptions */
	public function test_not_allowed_method_login() {

		$this->expectException(\Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException::class);
		// $this->expectExceptionMessage('The GET method is not supported for this route. Supported methods: POST.');

		$this
		->withHeaders([
			'accept' => 'application/json',
			'Cache-Control' => 'no-cache',
		])
		->get('/api/v1/auth/login', [
			'email' => 'kiendp@gmail.com',
			'password' => '123457',
		]);
	}
}
