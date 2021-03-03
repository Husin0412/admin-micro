<?php

namespace App\Http\Middleware;

use Closure;
use Throwable;
use Illuminate\Http\Request;

class AuthorizerUsers
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
 
        if(session('session_user')) {
            $data = [
                'refresh_token' => session('user_refresh_token'),
                'email' => session('session_user')->email,
            ];

            $get_token = refresh_token($data);
           
            if($get_token['status'] === "success") {
                session([
                    'user_token' =>$get_token['data']['token']
                ]);
            }

            if($get_token['status'] === "error" && $get_token['message'] === "service user unavailable" && $get_token['http_code'] === 500) {
                return \redirect()->route('500', ['response' => 'error', 'error_code' => $get_token['http_code'], 'message' => $get_token['message']]);
            } else if($get_token['status'] === "error" && $get_token['message'] === "invalid token" && $get_token['http_code'] === 400) {
                return \redirect()->route('login')->with(['expired' => 'Your session has expired. Please login back. !']);
            }

            return $next($request);
        }
        return redirect()->route('login')->with(['expired' => 'Your session has expired. Please login back. !']);
    }
}
