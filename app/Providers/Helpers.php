<?php 
use Illuminate\Support\Facades\Http;

/* api */
/* users */ 
function register($params) {
    $url = env('SERVICE_GATEWAY_URL') . 'users/register';

    try {
        $response = Http::post($url, $params);
        $data = $response->json();
        $data['http_code'] = $response->getStatusCode();
        return $data;
    } catch (\Throwable $th) {
        return [
            'status' => 'error',
            'http_code' => 500,
            'message' => 'service user unavailable'
        ];
    }
} 

function loginUsers($params) {
    $url = env('SERVICE_GATEWAY_URL') . 'users/login';

    try {
        $response = Http::post($url, $params);
        $data = $response->json();
        $data['http_code'] = $response->getStatusCode();
        return $data;
    } catch (\Throwable $th) {
        return [
            'status' => 'error',
            'http_code' => 500,
            'message' => 'service user unavailable'
        ];
    }
} 

function logoutUsers($params) {
    $url = env('SERVICE_GATEWAY_URL') . 'users/logout';

    try {
        $response = Http::withHeaders(['Authorization' => $params])->post($url);
        $data = $response->json();
        $data['http_code'] = $response->getStatusCode();
        return $data;
    } catch (\Throwable $th) {
        return [
            'status' => 'error',
            'http_code' => 500,
            'message' => 'service user unavailable'
        ];
    }
}

function getUsers($params) {
    $url = env('SERVICE_GATEWAY_URL') . 'users';

    try {
        $response = Http::withHeaders(['Authorization' => $params ])->get($url);
        $data = $response->json();
        $data['http_code'] = $response->getStatusCode();
        return $data;
    } catch (\Throwable $th) {
        return [
            'status' => 'error',
            'http_code' => 500,
            'message' => 'service user unavailable'
        ];
    }
}

function getUsersAll($params) {
    $url = env('SERVICE_GATEWAY_URL') . 'users/all';

    try {
        $response = Http::withHeaders(['Authorization' => $params ])->get($url);
        $data = $response->json();
        $data['http_code'] = $response->getStatusCode();
        return $data;
    } catch (\Throwable $th) {
        return [
            'status' => 'error',
            'http_code' => 500,
            'message' => 'service user unavailable'
        ];
    }
}


function getUsersByid($params, $id = '') {
    $url = env('SERVICE_GATEWAY_URL') . "users/select/$id";

    try {
        $response = Http::withHeaders(['Authorization' => $params ])->get($url);
        $data = $response->json();
        $data['http_code'] = $response->getStatusCode();
        return $data;
    } catch (\Throwable $th) {
        return [
            'status' => 'error',
            'http_code' => 500,
            'message' => 'service user unavailable'
        ];
    }
}

function update_user($params, $request, $id) {
    $url = env('SERVICE_GATEWAY_URL') . "users/updates/$id";

    try {
        $response = Http::withHeaders(['Authorization' => $params ])->put($url, $request);
        $data = $response->json();
        $data['http_code'] = $response->getStatusCode();
        return $data;
    } catch (\Throwable $th) {
        return [
            'status' => 'error',
            'http_code' => 500,
            'message' => 'service user unavailable'
        ];
    }
}

function delete_user($params, $id) {
    $url = env('SERVICE_GATEWAY_URL') . "users/$id";

    try {
        $response = Http::withHeaders(['Authorization' => $params])->delete($url);
        $data = $response->json();
        $data['http_code'] = $response->getStatusCode();
        return $data;
    } catch (\Throwable $th) {
        return [
            'status' => 'error',
            'http_code' => 500,
            'message' => 'service user unavailable'
        ];
    }
}

/* token */ 
function refresh_token($params) {
    $url = env('SERVICE_GATEWAY_URL') . 'refresh-tokens';

    try {
        $response = Http::post($url, $params);
        $data = $response->json();
        $data['http_code'] = $response->getStatusCode();
        return $data;
    } catch (\Throwable $th) {
        return [
            'status' => 'error',
            'http_code' => 500,
            'message' => 'service user unavailable'
        ];
    }
}

/* media */ 
function create_media($params, $request) {
    $url = env('SERVICE_GATEWAY_URL') . 'media';

    try {
        $response = Http::withHeaders(['Authorization' => $params ])->post($url, $request);
        $data = $response->json();
        $data['http_code'] = $response->getStatusCode();
        return $data;
    } catch (\Throwable $th) {
        return [
            'status' => 'error',
            'http_code' => 500,
            'message' => 'service media unavailable'
        ];
    }
}

function delete_media($params, $request) {
    $url = env('SERVICE_GATEWAY_URL') . "media/deleted";

    try {
        $response = Http::withHeaders(['Authorization' => $params ])->post($url, $request);
        $data = $response->json();
        $data['http_code'] = $response->getStatusCode();
        return $data;
    } catch (\Throwable $th) {
        return [
            'status' => 'error',
            'http_code' => 500,
            'message' => 'service media unavailable'
        ];
    }
}

/* print for required fields */
if( ! function_exists('required_field'))
{
    function required_field($message) 
    {
        return '<div class="invalid-feedback" style="display:block">'.$message.'</div>';
    }
} 
