<?php 
use Illuminate\Support\Facades\Http;

/* api */
/* users */ 
function register($token) {
    $url = env('SERVICE_GATEWAY_URL') . 'users/register';

    try {
        $response = Http::post($url, $token);
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

function loginUsers($token) {
    $url = env('SERVICE_GATEWAY_URL') . 'users/login';

    try {
        $response = Http::post($url, $token);
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

function logoutUsers($token) {
    $url = env('SERVICE_GATEWAY_URL') . 'users/logout';

    try {
        $response = Http::withHeaders(['Authorization' => $token])->post($url);
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

function getUsers($token) {
    $url = env('SERVICE_GATEWAY_URL') . 'users';

    try {
        $response = Http::withHeaders(['Authorization' => $token ])->get($url);
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

function getUsersAll($token) {
    $url = env('SERVICE_GATEWAY_URL') . 'users/all';

    try {
        $response = Http::withHeaders(['Authorization' => $token ])->get($url);
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


function getUsersByid($token, $id = '') {
    $url = env('SERVICE_GATEWAY_URL') . "users/select/$id";

    try {
        $response = Http::withHeaders(['Authorization' => $token ])->get($url);
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

function update_user($token, $request, $id) {
    $url = env('SERVICE_GATEWAY_URL') . "users/updates/$id";

    try {
        $response = Http::withHeaders(['Authorization' => $token ])->put($url, $request);
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

function delete_user($token, $id) {
    $url = env('SERVICE_GATEWAY_URL') . "users/$id";

    try {
        $response = Http::withHeaders(['Authorization' => $token])->delete($url);
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
function refresh_token($token) {
    $url = env('SERVICE_GATEWAY_URL') . 'refresh-tokens';

    try {
        $response = Http::post($url, $token);
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
function create_media($token, $request) {
    $url = env('SERVICE_GATEWAY_URL') . 'media';

    try {
        $response = Http::withHeaders(['Authorization' => $token ])->post($url, $request);
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

function delete_media($token, $request) {
    $url = env('SERVICE_GATEWAY_URL') . "media/deleted";

    try {
        $response = Http::withHeaders(['Authorization' => $token ])->post($url, $request);
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

/* mentors */
function get_mentors($token) {
    $url = env('SERVICE_GATEWAY_URL') . "mentors";

    try {
        $response = Http::withHeaders(['Authorization' => $token ])->get($url);
        $data = $response->json();
        $data['http_code'] = $response->getStatusCode();
        return $data;
    } catch (\Throwable $th) {
        return [
            'status' => 'error',
            'http_code' => 500,
            'message' => 'service mentors unavailable'
        ];
    }
}

function create_mentors($token, $request) {
    $url = env('SERVICE_GATEWAY_URL') . "mentors";

    try {
        $response = Http::withHeaders(['Authorization' => $token ])->post($url, $request);
        $data = $response->json();
        $data['http_code'] = $response->getStatusCode();
        return $data;
    } catch (\Throwable $th) {
        return [
            'status' => 'error',
            'http_code' => 500,
            'message' => 'service mentors unavailable'
        ];
    }
}

function detail_mentors($token, $id) {
    $url = env('SERVICE_GATEWAY_URL') . "mentors/$id";

    try {
        $response = Http::withHeaders(['Authorization' => $token ])->get($url);
        $data = $response->json();
        $data['http_code'] = $response->getStatusCode();
        return $data;
    } catch (\Throwable $th) {
        return [
            'status' => 'error',
            'http_code' => 500,
            'message' => 'service mentors unavailable'
        ];
    }
}

function update_mentors($token, $request, $id) {
    $url = env('SERVICE_GATEWAY_URL') . "mentors/$id";

    try {
        $response = Http::withHeaders(['Authorization' => $token ])->put($url, $request);
        $data = $response->json();
        $data['http_code'] = $response->getStatusCode();
        return $data;
    } catch (\Throwable $th) {
        return [
            'status' => 'error',
            'http_code' => 500,
            'message' => 'service mentors unavailable'
        ];
    }
}

function delete_mentor($token, $id) {
    $url = env('SERVICE_GATEWAY_URL') . "mentors/$id";

    try {
        $response = Http::withHeaders(['Authorization' => $token ])->delete($url);
        $data = $response->json();
        $data['http_code'] = $response->getStatusCode();
        return $data;
    } catch (\Throwable $th) {
        return [
            'status' => 'error',
            'http_code' => 500,
            'message' => 'service mentors unavailable'
        ];
    }
}

/* courses */
function get_courses($token) {
    $url = env('SERVICE_GATEWAY_URL') . "courses/all";

    try {
        $response = Http::withHeaders(['Authorization' => $token ])->get($url);
        $data = $response->json();
        $data['http_code'] = $response->getStatusCode();
        return $data;
    } catch (\Throwable $th) {
        return [
            'status' => 'error',
            'http_code' => 500,
            'message' => 'service courses unavailable'
        ];
    }
}

function create_courses($token, $request) {
    $url = env('SERVICE_GATEWAY_URL') . "courses";

    try {
        $response = Http::withHeaders(['Authorization' => $token ])->post($url, $request);
        $data = $response->json();
        $data['http_code'] = $response->getStatusCode();
        return $data;
    } catch (\Throwable $th) {
        return [
            'status' => 'error',
            'http_code' => 500,
            'message' => 'service courses unavailable'
        ];
    }
}

function detail_courses($token, $id) {
    $url = env('SERVICE_GATEWAY_URL') . "courses/$id";

    try {
        $response = Http::withHeaders(['Authorization' => $token ])->get($url);
        $data = $response->json();
        $data['http_code'] = $response->getStatusCode();
        return $data;
    } catch (\Throwable $th) {
        return [
            'status' => 'error',
            'http_code' => 500,
            'message' => 'service courses unavailable'
        ];
    }
}

function update_courses($token, $request, $id) {
    $url = env('SERVICE_GATEWAY_URL') . "courses/$id";

    try {
        $response = Http::withHeaders(['Authorization' => $token ])->put($url, $request);
        $data = $response->json();
        $data['http_code'] = $response->getStatusCode();
        return $data;
    } catch (\Throwable $th) {
        return [
            'status' => 'error',
            'http_code' => 500,
            'message' => 'service courses unavailable'
        ];
    }
}

function delete_courses($token, $id) {
    $url = env('SERVICE_GATEWAY_URL') . "courses/$id";

    try {
        $response = Http::withHeaders(['Authorization' => $token ])->delete($url);
        $data = $response->json();
        $data['http_code'] = $response->getStatusCode();
        return $data;
    } catch (\Throwable $th) {
        return [
            'status' => 'error',
            'http_code' => 500,
            'message' => 'service courses unavailable'
        ];
    }
}

/* chapter */
function get_chapters($token, $param) {
    $url = env('SERVICE_GATEWAY_URL') . "chapters";

    try {
        $response = Http::withHeaders(['Authorization' => $token ])->get($url, $param);
        $data = $response->json();
        $data['http_code'] = $response->getStatusCode();
        return $data;
    } catch (\Throwable $th) {
        return [
            'status' => 'error',
            'http_code' => 500,
            'message' => 'service chapters unavailable'
        ];
    }
}

function detail_chapters($token, $id = null) {
    $url = env('SERVICE_GATEWAY_URL') . "chapters/$id";

    try {
        $response = Http::withHeaders(['Authorization' => $token ])->get($url);
        $data = $response->json();
        $data['http_code'] = $response->getStatusCode();
        return $data;
    } catch (\Throwable $th) {
        return [
            'status' => 'error',
            'http_code' => 500,
            'message' => 'service chapters unavailable'
        ];
    }
}

function create_chapters($token, $request) {
    $url = env('SERVICE_GATEWAY_URL') . "chapters";

    try {
        $response = Http::withHeaders(['Authorization' => $token ])->post($url, $request);
        $data = $response->json();
        $data['http_code'] = $response->getStatusCode();
        return $data;
    } catch (\Throwable $th) {
        return [
            'status' => 'error',
            'http_code' => 500,
            'message' => 'service chapters unavailable'
        ];
    }
}

function update_chapters($token, $request, $id) {
    $url = env('SERVICE_GATEWAY_URL') . "chapters/$id";

    try {
        $response = Http::withHeaders(['Authorization' => $token ])->put($url, $request);
        $data = $response->json();
        $data['http_code'] = $response->getStatusCode();
        return $data;
    } catch (\Throwable $th) {
        return [
            'status' => 'error',
            'http_code' => 500,
            'message' => 'service chapters unavailable'
        ];
    }
}

function delete_chapters($token, $id) {
    $url = env('SERVICE_GATEWAY_URL') . "chapters/$id";

    try {
        $response = Http::withHeaders(['Authorization' => $token ])->delete($url);
        $data = $response->json();
        $data['http_code'] = $response->getStatusCode();
        return $data;
    } catch (\Throwable $th) {
        return [
            'status' => 'error',
            'http_code' => 500,
            'message' => 'service chapters unavailable'
        ];
    }
}

/*img course*/
function create_img_courses($token, $request) {
    $url = env('SERVICE_GATEWAY_URL') . "image-courses";

    try {
        $response = Http::withHeaders(['Authorization' => $token ])->post($url, $request);
        $data = $response->json();
        $data['http_code'] = $response->getStatusCode();
        return $data;
    } catch (\Throwable $th) {
        return [
            'status' => 'error',
            'http_code' => 500,
            'message' => 'service image course unavailable'
        ];
    }
} 

function delete_img_courses($token, $request) {
    $url = env('SERVICE_GATEWAY_URL') . "image-courses";

    try {
        $response = Http::withHeaders(['Authorization' => $token ])->delete($url, $request);
        $data = $response->json();
        $data['http_code'] = $response->getStatusCode();
        return $data;
    } catch (\Throwable $th) {
        return [
            'status' => 'error',
            'http_code' => 500,
            'message' => 'service image course unavailable'
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

/* range number */
if(! function_exists('range_number'))
{
    function range_number($min, $max) 
    {
        foreach (range($min, $max) as $number) {
            echo $number;
        }
    }
}

/**/
function cutText($text)
{
    if(strlen($text) > 28)
    {
        $review = substr(preg_replace('/\s+/', ' ', $text), 0, 30) . ' ...';
        return $review;
    }
    return $text;
} 