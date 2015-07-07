<?php namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use ResetsPasswords;

    public function __construct(Guard $auth, PasswordBroker $passwords)
    {
        $this->auth = $auth;
        $this->passwords = $passwords;
    }

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

    public function update(Request $request)
    {

        $user = User::find(Auth::user()->id);
        $user->name = $request->input('username');
        $password = Hash::make($request->input('password'));
        $user->email = $request->input('email');
        $user->save();

        return response()->json(['id' => $user->id, 'username' => $user->name, 'email' => $user->email]);
    }

    public function login(Request $request)
    {
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

    public function logout()
    {
        Auth::logout();

        $result['status'] = 'OK';

        return response()->json($result);
    }

    public function remind(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);

        $response = $this->passwords->sendResetLink($request->only('email'), function($m)
        {
            $m->subject($this->getEmailSubject());
        });

        $result = [];
        switch ($response)
        {
            case PasswordBroker::RESET_LINK_SENT:
                $result['status'] = 'OK';
                break;

            case PasswordBroker::INVALID_USER:
                $result['status'] = 'failed';
                $result['error'] = trans($response);
        }

        return response()->json($result);
    }

    public function reset(Request $request)
    {
        try {
            $this->validate($request, [
                'token'    => 'required',
                'email'    => 'required|email',
                'password' => 'required|confirmed',
            ]);
        } catch (HttpResponseException $e) {
            $result['status'] = 'failed';
            $result['error'] = "validation error";

            return response()->json($result);
        }

        $credentials = $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );

        $response = $this->passwords->reset($credentials, function($user, $password)
        {
            $user->password = bcrypt($password);
            $user->save();
        });

        $result = [];
        switch ($response)
        {
            case PasswordBroker::PASSWORD_RESET:
                $result['status'] = 'OK';
                break;

            default:
                $result['status'] = 'failed';
                $result['error'] = trans($response);
        }

        return response()->json($result);
    }
}
