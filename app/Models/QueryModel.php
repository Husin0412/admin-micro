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
        
        $key_name = 'name'; $key_email = 'email'; $val_name = $name; $val_email = $email;
        if($id) { $key_name = 'id'; $key_email = 'id'; $val_name = $id; $val_email = $id; }

        $_name = []; $_email = [];
        foreach($get_user['data'] as $key => $val) {
            if($val['name'] === $name && $val[$key_name] != $val_name) {
               array_push($_name, $val['id']);
            } 
            if($val['email'] === $email && $val[$key_email] != $val_email) {
                array_push($_email, $val['id']);
            }
        }
        $__name = count($_name);
        $__email = count($_email);
        $response = ['name' => $__name,'email' => $__email];

        return $response;
    }

} 
