<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
	/*
	|--------------------------------------------------------------------------
	| Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles authenticating users for the application and
	| redirecting them to your home screen. The controller uses a trait
	| to conveniently provide its functionality to your applications.
	|
	*/

	use AuthenticatesUsers;


	/**
	 * Handle a login request to the application.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
	 *
	 * @throws \Illuminate\Validation\ValidationException
	 */
	public function login(Request $request)
	{
		$this->validateLogin($request);



		// If the class is using the ThrottlesLogins trait, we can automatically throttle
		// the login attempts for this application. We'll key this by the username and
		// the IP address of the client making these requests into this application.
		if ($this->hasTooManyLoginAttempts($request)) {
			$this->fireLockoutEvent($request);

			return $this->sendLockoutResponse($request);
		}
		$this->createSessionForDashboard($request);

		if ($this->attemptLogin($request)) {

			return $this->sendLoginResponse($request);
		}

		// If the login attempt was unsuccessful we will increment the number of attempts
		// to login and redirect the user back to the login form. Of course, when this
		// user surpasses their maximum number of attempts they will get locked out.
		$this->incrementLoginAttempts($request);

		return $this->sendFailedLoginResponse($request);
	}


	/**
	 * Log the user out of the application.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function logout(Request $request)
	{
		$this->guard()->logout();

		$request->session()->invalidate();

		session_start();
		session_destroy();

		return redirect('/');
	}

	private function createSessionForDashboard(Request $request)
	{
		session_start();
		$user = User::where('email', $request->Input('email'))->first();

		$role = $user->role;
		if($role=="Platform-Admin"){
			$_SESSION['orgkey'] = "";
			$_SESSION['orgname'] = "";
			$_SESSION['org_name'] = "";
		}
		else{
			$_SESSION['orgkey'] = $user->org_key;
			$_SESSION['orgname'] = $user->org_name;
			$_SESSION['org_name'] = $user->org_name;
		}

		$_SESSION['user_role'] = $role;
		$_SESSION['userkey'] = $user->user_key;
		$_SESSION['userid'] = $user->id;
		$_SESSION['user_email'] = $user->email;

		$user->RegisterVisit();


	}


	/**
	 * Where to redirect users after login.
	 *
	 * @var string
	 */
	protected $redirectTo = '/admin/org.php';

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest')->except('logout');
	}
}
