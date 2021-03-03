<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    // use AuthenticatesUsers;

    protected $redirectTo = '/';

    public function __construct() {
        $this->middleware('guest')->except('logout');
    }

    public function username() {
        return 'username';
    }

    public function login(Request $request) {
        if($request->isMethod('post')) {

            $data = $request->all();

            $response = json_decode(\json_encode(loginUsers($data)));

            if($response->status === "success") {

                $user = json_decode(\json_encode(getUsers($response->data->token)));

                if($user->data->role == "student") {
                    return \view('error-page.400', ['response' => 'error', 'error_code' => 401, 'message' => 'you dont have access to this page']);
                }             

                session([
                    // 'session_guid' => $user->guid,
                    'session_user' => $user->data,
                    'user_token' => $response->data->token,
                    'user_refresh_token' => $response->data->refresh_token
                ]);

                return redirect('/')->with(['response' => 'success', 'message' => ' Wellcome back '.$user->data->name.' '])
                    ->header('Cache-Control', 'no-chache, no-store, max-age=0, must-revalidate')
                    ->header('Pragma', 'no-cache')
                    ->header('Expires', \gmdate('D, d M Y H:i:s').' GMT');

            } elseif($response->status === "error") {
                if($response->message === "service user unavailable" || $response->message === 'service unavailable') {
                    return view('error-page.500', ['response' => 'error', 'error_code' => $response->http_code, 'message' => $response->message]);
                }

                if($response->message === "user not found" ) {
                    $response->message = (object)[
                        "message" => $response->message,
                        "field" => "email"
                    ];
                }

                if($response->message === "wrong password") {
                    $response->message = (object) [
                        "message" => $response->message,
                        "field" => "password"
                    ];
                }

                if(is_array($response->message)) {
                    if($response->message[0]->field === "password") {
                        $response->message = (object) [
                            "message" => $response->message[0]->message,
                            "field" => "password"
                        ];
                    }
                }
    
                return \redirect()->back()->with('error', $response->message);
            }
        }
        return \view('authentication.login');
    }


    public function logout() {

        $response = \json_decode(json_encode(logoutUsers(\session('user_token'))));

        if($response->message === "jwt expired" && $response->http_code === 403) {
            \session()->forget(['user_token','user_refresh_token','session_user']);
            return \redirect()->route('login')->with(['response' => 'success', 'message' => 'Logout Success.. see you again.']);
        }

        if($response->status === "success" && $response->http_code === 200) {
            \session()->forget(['user_token','user_refresh_token','session_user']);
            return \redirect()->route('login')->with(['response' => 'success', 'message' => 'Logout Success.. see you again.']);
        } elseif($response->status === "error" && $response->http_code === 500 ) {
            return \view('error-page.500', ['error' => $response->message]);
        }

    }
}


