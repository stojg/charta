<?php namespace App\Http\Controllers\Auth;

use App\AuthenticateUser;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
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
	 * @param  \Illuminate\Contracts\Auth\Guard $auth
	 * @param  \Illuminate\Contracts\Auth\Registrar $registrar
	 */
	public function __construct(Guard $auth, Registrar $registrar)
	{
		$this->auth = $auth;
		$this->registrar = $registrar;
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

//	/**
//	 * Redirect the user to the GitHub authentication page.
//	 *
//	 * @return Response
//	 */
//	public function redirectToProvider()
//	{
//		return Socialite::driver('github')->redirect();
//	}
//
//	/**
//	 * Obtain the user information from GitHub.
//	 *
//	 * @return Response
//	 */
//	public function handleProviderCallback()
//	{
//		$user = Socialite::driver('github')->user();
//
//		// $user->token;
//	}

}
