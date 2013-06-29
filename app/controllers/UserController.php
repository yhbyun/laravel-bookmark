<?php

class UserController extends \BaseController {

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$username = Input::get('username');
		//$password = md5($username . Input::get('password') . $this->salt);
		$password = Hash::make(Input::get('password'));
		$email = Input::get('email');

		$user = User::where('username', $username)->orWhere('email', $email)->first();
		if ($user) {
			return Response::json(array('error' => 'There is already an account with that e-mail or username'));
		}

		$user = new User();
		$user->username = $username;
		$user->password = $password;
		$user->email = $email;
		$user->save();

		Auth::login($user);

		return Response::json($user);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update() {

		$user = User::find(Auth::user()->id);
		$user->username = Input::get('username');
		$password = Hash::make(Input::get('password'));
		$user->email = Input::get('email');
		$user->save();

		return Response::json(array('id' => $user->id, 'username' => $user->username, 'email' => $user->email));
	}

	public function login() {
		$username = Input::get('username');
		$password = Input::get('password');

		if (Auth::attempt(array('username' => $username, 'password' => $password))) {

			Auth::user()->last_login = new Datetime;
			Auth::user()->save();

			return Response::json(Auth::user());
		} else {
			App::abort(401, 'You are not authorized.');
		}
	}

	public function logout() {
		Auth::logout();
	}

	public function remind() {
		$credentials = array('email' => Input::get('email'));
		Password::remind($credentials, function($message, $user) {
			$message->from('i@rivario.com', 'rivario.com');
			$message->subject('Your Password Reminder');
		});

		$result = array();
		if (Session::has('error')) {
			$result['status'] = 'failed';
			$result['error'] = trans(Session::get('reason'));
		} elseif (Session::has('success')) {
			$result['status'] = 'OK';
		}

		return Response::json($result);
	}

	public function reset() {
		$credentials = array('email' => Input::get('email'));

		Password::reset($credentials, function($user, $password) {
			$user->password = Hash::make($password);
			$user->save();
		});

		$result = array();
		if (Session::has('error')) {
			$result['status'] = 'failed';
			$result['error'] = trans(Session::get('reason'));
		} else {
			$result['status'] = 'OK';
		}

		return Response::json($result);
	}
}