<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use App\Models\PageModel;
use App\Models\QueryModel;
use Carbon\Carbon;
use DB;

class UserController extends Controller
{
    public $viewdata = [];

    protected $mod_alias = 'users';

    public function __construct()
    {
        $this->page = new PageModel();
        $this->viewdata = $this->page->viewdata();
        $this->viewdata['page'] = $this->page;

        $this->query = new QueryModel();
        $this->viewdata['query'] = $this->query;

        $this->module = $this->page->get_modid($this->mod_alias);
        $this->viewdata['module'] = $this->module;
    }

    public function index() {
        $this->page->blocked_page($this->mod_alias);

        session()->forget(['data_id']);
 
        $get_data = getUsersAll(\session('user_token'));

        if($get_data['status'] === 'error') {
            // if($get_data['http_code'] === 500 && $get_data['message'] === 'service user unavailable') 
            // {
            //     return redirect()->route('500', ['response' => 'error', 'error_code' => $get_data['http_code'], 'message' => $get_data['message']]);
            // }
            return redirect()->route('500', ['response' => 'error', 'error_code' => $get_data['http_code'], 'message' => $get_data['message']]);
        }

        $data = [];
        foreach($get_data['data'] as $key => $val)
        {
            if(session('session_user')->role === "supersu")
            {
                if($val['role'] !== "student") 
                {
                    $_data = 
                    [
                        'id' => $val['id'],
                        'name' => $val['name'],
                        'email' => $val['email'],
                        'role' => $val['role'],
                        'profession' => $val['profession'],
                        'avatar' => $val['avatar'] ?: 'assets/images/null.png',
                        'created_at' => Carbon::parse($val['created_at'])->format('M-d-Y h:m A'),
                    ];
                    \array_push($data, $_data);
                }
            } 
            else 
            {
                if($val['guid'] === \session('session_user')->guid) 
                {
                    $_data = 
                    [
                        'id' => $val['id'],
                        'name' => $val['name'],
                        'email' => $val['email'],
                        'role' => $val['role'],
                        'profession' => $val['profession'],
                        'avatar' => $val['avatar'] ?: 'assets/images/null.png',
                        'created_at' => Carbon::parse($val['created_at'])->format('M-d-Y h:m A'),
                    ];
                    \array_push($data, $_data);
                }
            }
        }
        
        $this->viewdata['data'] = $data;
        $this->viewdata['toolbar'] = true;
        $this->viewdata['page_title'] = 'users';
        
        return view('users.index', $this->viewdata);
 
    }

    public function add() 
    {
        $this->page->blocked_page($this->mod_alias, 'create');

        $roles = (session('session_user')->role === "supersu") ? [
            0 => 'supersu',
            1 => 'admin'
        ] : ((session('session_user')->role === "admin") ? [
            0 => 'admin'
        ] : []);

        $this->viewdata['roles'] = $roles;
        $this->viewdata['toolbar_save'] = true;
        $this->viewdata['page_title'] = 'add users';

        return view('users.add', $this->viewdata);
    }

    public function save(Request $request)
    {
        $this->page->blocked_page($this->mod_alias, 'create');

        $_validates = [
            'name' => 'required|min:5|max:40',
            'email' => 'required|email',
            'roles' => 'required|string',
            'password' => 'required|min:6',
            're-password' => [
                'required',
                'min:6',
                'required_with:password',
                'same:password'
            ],
        ];

        $validator = Validator::make($request->all(), $_validates);

        if($validator->fails())
        {
            return \redirect($this->module->permalink.'/add')
                ->withErrors($validator)
                ->withInput();
        }

        $user_exist = $this->query->get_user_exist(ucwords($request->input('name')), $request->input('email'));

        if($user_exist['name'] > 0 || $user_exist['email'] > 0 ) 
        {
            $name_exist = $user_exist['name'] > 0 ? 'Name '.ucwords($request->input('name')).' already exists' : null;
            $email_exist = $user_exist['email'] > 0 ? 'Email '.$request->input('email').' already exists' : null;
            return \redirect($this->module->permalink.'/add')
                ->with(['name_exist' => $name_exist, 'email_exist' => $email_exist])
                ->withInput();
        }

        $guid = ($request->input('roles') === 'supersu') ? 1 : (($request->input('roles') === 'admin') ? 2 : 0);

        $data = [
            'name' => ucwords($request->input('name')),
            'email' => $request->input('email'),
            'profession' => $request->input('profession') ?? null,
            'password' => $request->input('password'),
            'role' => $request->input('roles'),
            'guid' => $guid
        ];

        $response = register($data);

        if($response['status'] === "error") 
        {
            if(\is_array($response))
            {
                $response['message'] = $response['message'][0]['message'];
            }
            return redirect()->route('500', ['response' => 'error', 'error_code' => $response['http_code'], 'message' => $response['message']]);
        }

        return \redirect($this->module->permalink)->with(['response' => 'success', 'message' => 'New Users '.ucwords($request->input('name')).' has been saved']);
    }

    public function edit(Request $request) {
        $this->page->blocked_page($this->mod_alias,'alter');

        if(!$request->filled('data_id') && !session('data_id'))
        {
            return redirect($this->module->permalink)->with(['response' => 'error', 'message' => 'Data id not found']);
        }

        if(!session('data_id'))
        {
            $data_id = array_keys($request->input('data_id'));
            $data_id = $data_id[0];
            session(['data_id' => $data_id]);
        } 
        else
        {
            $data_id = \session('data_id');
        } 

        /* get data by ID */ 
        $data_edit = getUsersByid(session('user_token'), $data_id);
        
        if($data_edit["status"] === "error")
        {
            return redirect()->route('500', ['response' => 'error', 'error_code' => $data_edit['http_code'], 'message' => $data_edit['message']]);
        }

        $this->viewdata['data_edit'] = $data_edit['data'];
        $this->viewdata['toolbar_save'] = true;
        $this->viewdata['page_title'] = "edit users";

        return view('users.edit', $this->viewdata);
    }

    public function update(Request $request)
    {
        $this->page->blocked_page($this->mod_alias, 'alter');

        $_validates = ['name' => 'required|min:5|max:40','email' => 'required|email'];

        if($request->input('password'))
        {
            $_validates = [
                'name' => 'required|min:5|max:40',
                'email' => 'required|email',
                'password' => 'min:6',
                're-password' => [
                    'min:6',
                    'required_with:password',
                    'same:password'
                ]
            ];
        }

        $validator = Validator::make($request->all(), $_validates);

        if($validator->fails())
        {
            return \redirect($this->module->permalink.'/edit')
                ->withErrors($validator)
                ->withInput();
        }

        $user_exist = $this->query->get_user_exist(ucwords($request->input('name')), $request->input('email'), $request->input('id'));

        if($user_exist['name'] > 0 || $user_exist['email'] > 0 ) 
        {
            $name_exist = $user_exist['name'] > 0 ? 'Name '.ucwords($request->input('name')).' already exists' : null;
            $email_exist = $user_exist['email'] > 0 ? 'Email '.$request->input('email').' already exists' : null;
            return \redirect($this->module->permalink.'/add')
                ->with(['name_exist' => $name_exist, 'email_exist' => $email_exist])
                ->withInput();
        }

        if($request->hasFile('avatar'))
        {
          $path = $request->file('avatar');
          $extension = $path->getClientOriginalExtension();
          $type = pathinfo($path, PATHINFO_EXTENSION);

          $data = file_get_contents($path);
          $base64 = 'data:image/' . $extension . ';base64,' . base64_encode($data);
          $image = [
              'image' => $base64
          ];

          $response = create_media(session('user_token'), $image);

          if($response['status'] === "error") 
          {
            return redirect()->route('500', ['response' => $response['status'], 'error_code' => $response['http_code'], 'message' => $response['message']]);
          }

          $avatar = $response['data']['image'];
        } 

        if($request->input('avatar-existing') && !isset($avatar))
        {
            $avatar = $request->input('avatar-existing');
        }

        if($request->input('avatar-existing') && isset($avatar))
        {
            $data_image = [ 'image' => $request->input('avatar-existing') ];
            $delete_media = delete_media(session('user_token'), $data_image);
        }

        $data = [
            'name' => ucwords($request->input('name')),
            'email' => $request->input('email'),
            'profession' => $request->input('profession') ?: null,
            'avatar' => $avatar ?: null
        ];

        if($request->input('password')) 
        {
            $data = [
                'name' => ucwords($request->input('name')),
                'email' => $request->input('email'),
                'profession' => $request->input('profession') ?: null,
                'password' => $request->input('password'),
                'avatar' => $avatar ?: null
            ];
        }

        $response_update = update_user(session('user_token'), $data, $request->input('id'));

        if($response_update['status'] === "error") 
        {
            return redirect()->route('500', ['response' => $response_update['status'], 'error_code' => $response_update['http_code'], 'message' => $response_update['message']]);
        }

        return \redirect($this->module->permalink)->with(['response' => 'success', 'message' => 'Users '.\ucwords($request->input('name')).' has been updated ']);
    }

    public function delete(Request $request)
    {
        $this->page->blocked_page($this->mod_alias, 'drop');
        
        if(!$request->filled('data_id')) 
        {
            return \redirect($this->module->permalink)->with(['response' => 'error', 'message' => 'Data id not found']);
        }

        $data_id = array_keys($request->input('data_id'));
        $data_id = $data_id[0];

        /*get data by ID*/ 
        $data_edit = getUsersByid(session('user_token'), $data_id);

        if($data_edit['status'] === "error") 
        {
            if($data_edit['message'] === "user not found")
            {
                return \redirect($this->module->permalink)->with(['response' => 'error', 'message' => $data_edit['message']]);
            }
            return redirect()->route('500', ['response' => $data_edit['status'], 'error_code' => $data_edit['http_code'], 'message' => $data_edit['message']]);
        }

        /* delete avatar */ 
        if($data_edit['data']['avatar'])
        {
            $data_image = [ 'image' => $data_edit['data']['avatar'] ];
            $delete_media = delete_media(session('user_token'), $data_image);

            // if($delete_media['status'] === "error")
            // {
            //     return redirect()->route('500', ['response' => $delete_media['status'], 'error_code' => $delete_media['http_code'], 'message' => $delete_media['message']]);
            // }
        }

        $delete = delete_user(session('user_token'), $data_id);

        if(isset($delete['status']) && $delete['status'] === "error")
        {
            return redirect()->route('500', ['response' => $delete['status'], 'error_code' => $delete['http_code'], 'message' => $delete['message']]);
        }

        return \redirect($this->module->permalink)->with(['response' => 'success', 'message' => 'Users '.$data_edit['data']['name'].' has been deleted ']);
    }

}