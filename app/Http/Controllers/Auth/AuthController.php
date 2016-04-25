<?php namespace App\Http\Controllers\Auth;

use App\AuthenticateUser;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;

class AuthController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Registration & Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new users, as well as the
	| authentication of existing users. By default, this controller uses
	| a simple trait to add these behaviors. Why don't you explore it?
	|
	*/

	use AuthenticatesAndRegistersUsers;

	/**
	 * Create a new authentication controller instance.
	 *
	 */
	public function __construct()
	{
		$this->middleware('guest', ['except' => 'getLogout']);
	}

	/**
	 * @param AuthenticateUser $authenticateUser
	 * @param Request $request
	 * @param null $provider
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function login(AuthenticateUser $authenticateUser, Request $request, $provider = null) {
		return $authenticateUser->execute($request->all(), $this, $provider);
	}

	/**
	 * @param $user
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function userHasLoggedIn($user) {
		\Session::flash('message', 'Welcome, ' . $user->username);
		return redirect('/home');
	}

}
