<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QueryModel extends Model
{
    use HasFactory;

    protected $tbl_user_group = "user_group";
    protected $tbl_module = 'module';

    public function get_total_user_group($guid) {
        $get_user = getUsersAll(session('user_token'));

        if($get_user['status'] === "error")
        {
            abort($get_user['http_code'], $get_user['message']);
            // return \redirect()->route('500', ['response' => 'error', 'error_code' => $get_user['http_code'], 'message' => $get_user['message']]);
        } 

        $query = [];
        foreach($get_user['data'] as $key => $val) {
            if($val['guid'] === $guid) {
               array_push($query, $val['id']);
            }
        }
        return count($query);
    }

    public function get_user_exist($name = '', $email = '', $id = null) {
        $get_user = getUsersAll(session('user_token'));
        
        if($get_user['status'] === "error")
        {
            abort($get_user['http_code'], $get_user['message']);
            // return \redirect()->route('500', ['response' => 'error', 'error_code' => $get_user['http_code'], 'message' => $get_user['message']]);
        } 

        $_name = []; $_email = [];

        if($id) 
        {
            foreach($get_user['data'] as $key => $val) {
                if($val['name'] === $name && $val['id'] != $id) {
                   array_push($_name, $val['id']);
                } 
                if($val['email'] === $email && $val['id'] != $id) {
                    array_push($_email, $val['id']);
                }
            }
        }
        else
        {
            foreach($get_user['data'] as $key => $val) {
                if($val['name'] === $name) {
                   array_push($_name, $val['id']);
                } 
                if($val['email'] === $email) {
                    array_push($_email, $val['id']);
                }
            }
        }

        $__name = count($_name);
        $__email = count($_email);
        $response = ['name' => $__name,'email' => $__email];

        return $response;
    }

    public function get_mentor_exist($name = '', $email = '', $id = null)
    {
        $get_mentor = get_mentors(session('user_token'));
        
        if($get_mentor['status'] === "error")
        {
            abort($get_mentor['http_code'], $get_mentor['message']);
            // return \redirect()->route('500', ['response' => 'error', 'error_code' => $get_mentor['http_code'], 'message' => $get_mentor['message']]);
        } 

        $_name = []; $_email = [];

        if($id) 
        {
            foreach($get_mentor['data'] as $key => $val) {
                if($val['name'] === $name && $val['id'] != $id) {
                   array_push($_name, $val['id']);
                } 
                if($val['email'] === $email && $val['id'] != $id) {
                    array_push($_email, $val['id']);
                }
            }
        }
        else
        {
            foreach($get_mentor['data'] as $key => $val) {
                if($val['name'] === $name) {
                   array_push($_name, $val['id']);
                } 
                if($val['email'] === $email) {
                    array_push($_email, $val['id']);
                }
            }
        }

        $__name = count($_name);
        $__email = count($_email);
        $response = ['name' => $__name,'email' => $__email];

        return $response;
    }

    function courses_exist($name = '', $id = null)
    {
        $get_course = get_courses(session('user_token'));
        
        if($get_course['status'] === "error")
        {
            abort($get_course['http_code'], $get_course['message']);
            // return \redirect()->route('500', ['response' => 'error', 'error_code' => $get_mentor['http_code'], 'message' => $get_mentor['message']]);
        } 

        $_name = [];
        // dd($get_course['data']['data']);
        if($id) 
        {
            foreach($get_course['data'] as $key => $val) {
                if($val['name'] === $name && $val['id'] != $id) {
                   array_push($_name, $val['id']);
                } 
            }
        }
        else
        {
            foreach($get_course['data'] as $key => $val) {
                if($val['name'] === $name) {
                   array_push($_name, $val['id']);
                } 
            }
        }

        $__name = count($_name);
        $response = ['name' => $__name];

        return $response;
    }

    function chapter_exist($name = '', $courseId = null, $id = null)
    {
        $get_chapters = get_chapters(session('user_token'), ['course_id' => $courseId]);

        if($get_chapters['status'] === "error")
        {
            abort($get_chapters['http_code'], $get_chapters['message']);
            // return \redirect()->route('500', ['response' => 'error', 'error_code' => $get_mentor['http_code'], 'message' => $get_mentor['message']]);
        } 

        $_name = [];
        // dd($get_chapters['data']['data']);
        if($id) 
        {
            foreach($get_chapters['data'] as $key => $val) {
                if($val['name'] === $name && $val['id'] != $id) {
                   array_push($_name, $val['id']);
                } 
            }
        }
        else
        {
            foreach($get_chapters['data'] as $key => $val) {
                if($val['name'] === $name) {
                   array_push($_name, $val['id']);
                } 
            }
        }

        $__name = count($_name);
        $response = ['name' => $__name];

        return $response;
    }

    function lesson_exist($name = '', $chapterId = null, $id = null)
    {
        $get_lessons = get_lessons(session('user_token'), $chapterId);

        if($get_lessons['status'] === "error")
        {
            abort($get_lessons['http_code'], $get_lessons['message']);
            // return \redirect()->route('500', ['response' => 'error', 'error_code' => $get_mentor['http_code'], 'message' => $get_mentor['message']]);
        } 

        $_name = [];
        // dd($get_lessons['data']['data']);
        if($id) 
        {
            foreach($get_lessons['data'] as $key => $val) {
                if($val['name'] === $name && $val['id'] != $id) {
                   array_push($_name, $val['id']);
                } 
            }
        }
        else
        {
            foreach($get_lessons['data'] as $key => $val) {
                if($val['name'] === $name) {
                   array_push($_name, $val['id']);
                } 
            }
        }

        $__name = count($_name);
        $response = ['name' => $__name];

        return $response;
    }

} 
