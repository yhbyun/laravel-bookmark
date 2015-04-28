<?php namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UserController extends Controller {

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $username = $request->input('username');
        $password = Hash::make($request->input('password'));
        $email = $request->input('email');

        $user = User::where('name', $username)->orWhere('email', $email)->first();
        if ($user) {
            return response()->json(['error' => 'There is already an account with that e-mail or username']);
        }

        $user = new User();
        $user->name = $username;
        $user->password = $password;
        $user->email = $email;
        $user->save();

        Auth::login($user);

        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request) {

        $user = User::find(Auth::user()->id);
        $user->name = $request->input('username');
        $password = Hash::make($request->input('password'));
        $user->email = $request->input('email');
        $user->save();

        return response()->json(['id' => $user->id, 'username' => $user->name, 'email' => $user->email]);
    }

    public function login(Request $request) {
        $username = $request->input('username');
        $password = $request->input('password');

        if (Auth::attempt(['name' => $username, 'password' => $password])) {

            Auth::user()->last_login_at = Carbon::now();
            Auth::user()->save();

            return response()->json(Auth::user());
        } else {
            App::abort(401, 'You are not authorized.');
        }
    }

    public function logout() {
        Auth::logout();
    }

    public function remind(Request $request) {
        $credentials = ['email' => $request->input('email')];
        Password::remind($credentials, function($message, $user) {
            $message->from('i@rivario.com', 'rivario.com');
            $message->subject('Your Password Reminder');
        });

        $result = [];
        if (Session::has('error')) {
            $result['status'] = 'failed';
            $result['error'] = trans(Session::get('reason'));
        } elseif (Session::has('success')) {
            $result['status'] = 'OK';
        }

        return response()->json($result);
    }

    public function reset(Request $request) {
        $credentials = ['email' => $request->input('email')];

        Password::reset($credentials, function($user, $password) {
            $user->password = Hash::make($password);
            $user->save();
        });

        $result = [];
        if (Session::has('error')) {
            $result['status'] = 'failed';
            $result['error'] = trans(Session::get('reason'));
        } else {
            $result['status'] = 'OK';
        }

        return response()->json($result);
    }
}
