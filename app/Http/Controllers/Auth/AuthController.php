<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use SocialAuth;
use SocialNorm\Exceptions\ApplicationRejectedException;
use SocialNorm\Exceptions\InvalidAuthorizationCodeException;

class AuthController extends Controller
{

    /**
     * Create a new authentication controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function signin() {
        return view('auth.signin');
    }

    /**
     * @param string $provider
     */
    public function authorize($provider = null)
    {
        return SocialAuth::authorize($provider);
    }

    /**
     * @param string $provider
     */
    public function login($provider = null)
    {

        try {
            SocialAuth::login($provider, function($user, $details) use($provider) {
                $user->username = $details->nickname;
                $user->name = $details->full_name;
                $user->email = $details->email;
                $user->avatar = $details->avatar;
                $user->provider = $provider;
                $user->save();
            });
        } catch (ApplicationRejectedException $e) {
            // User rejected application
            die('You rejected the permission');
        } catch (InvalidAuthorizationCodeException $e) {
            // Authorization was attempted with invalid
            // code,likely forgery attempt
            die('could not validate the authentication');
        }
        return Redirect::intended();
    }

    public function logout() {
        Auth::logout();

        return redirect()->action('WelcomeController@index');
    }





}
