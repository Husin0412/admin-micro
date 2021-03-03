<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Models\PageModel;
use App\Models\QueryModel;
use DB;

class UserGroupController extends Controller
{
    public $viewdata = [];

    protected $mod_alias = 'user-group';

    public function __construct() {
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

        $get_data = DB::table('user_group');

        $this->viewdata['get_data'] = $get_data;
        $this->viewdata['toolbar'] = true;
        $this->viewdata['page_title'] = __('user group');

        return view('user-group.index', $this->viewdata);
    }

    public function add() {
        $this->page->blocked_page($this->mod_alias, 'create');
        $this->viewdata['toolbar_save'] = true;
        $this->viewdata['page_title'] = 'add user group';
        return view('user-group.add', $this->viewdata);
    }

    public function save(Request $request) {
        $this->page->blocked_page($this->mod_alias, 'create');

        $_validates = ['group_name' => 'required'];

        $validator = Validator::make($request->all(), $_validates);

        if($validator->fails())
        {
            return \redirect($this->module->permalink.'/add')
                ->withErrors($validator)
                ->withInput();
        }

        $_roles = null;
        if($request->filled('module'))
        {
            foreach($request->input('module') as $idx => $val) {
                $_roles[$idx] = \implode(',', array_keys($val));
            }
        }

        /*check group name*/
        
        $group_name = DB::table('user_group')->where(['gname' => ucwords($request->input('group_name'))])->select('gname')->count();

        if($group_name > 0 ) 
        {
            return \redirect($this->module->permalink.'/add')
                ->with(['response' => 'error', 'message' => 'Group name '.ucwords($request->input('group_name')).' already exists'])
                ->withInput();
        }

        $_inserted = [
            'gname' => ucwords($request->input('group_name')),
            'roles' => \json_encode($_roles),
            'created_at' => date('Y-m-d H:i:s')
        ];

        $insert = DB::table('user_group')->insertGetId($_inserted);

        if(!$insert) 
        {
            return \redirect($this->module->permalink.'/add')->with(['response' => 'error', 'message' => 'Failed to insert new data to Storage'])->withInput();
        }

        return \redirect($this->module->permalink)->with(['response' => 'success', 'message' => 'New Group '.ucwords($request->input('group_name')).' has been saved']);

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
        $data_edit = DB::table('user_group')->where(['guid' => $data_id])->first();

        if(!isset($data_edit->gname))
        {
            return \redirect($this->module->permalink)->with(['response' => 'error', 'message' => 'Data id not found']);
        }

        $this->viewdata['data_edit'] = $data_edit;
        $this->viewdata['toolbar_save'] = true;
        $this->viewdata['page_title'] = "edit user group";

        return view('user-group.edit', $this->viewdata);
    }

    public function update(Request $request) {
        $this->page->blocked_page($this->mod_alias,'alter');

        $data_id = session('data_id');

        if(!$data_id) 
        {
            return \redirect($this->module->permalink.'/edit')->with(['response' => 'error', 'message' => 'Data id not found']);
        }

        /* get data ID */
        $data_edit = DB::table('user_group')->where(['guid' => $data_id])->first();

        if(!isset($data_edit->gname))
        {
            return \redirect($this->module->permalink.'/edit')->with(['response' => 'error', 'message' => 'Data id not found']);
        }

        $_validates = [
            'group_name' => 'required'
        ];

        $validator = Validator::make($request->all(), $_validates);

        if($validator->fails())
        {
            return \redirect($this->module->permalink.'/edit')
            ->withErrors($validator)
            ->withInput();
        }

        $_roles = null;
        if($request->filled('module'))
        {
            foreach($request->input('module') as $idx => $val)
            {
                $_roles[$idx] = implode(',',array_keys($val));
            }
        }

        /* check exists group name */
        $group_name = DB::table('user_group')
            ->where('gname', \ucwords($request->input('group_name')))
            ->where('guid', '!=', $data_edit->guid)
            ->select('gname')->count();

        if($group_name > 0 )
        {
            return \redirect($this->module->permalink.'/edit')
            ->with(['response' => 'error', 'message' => 'Group name '.\ucwords($request->input('group_name')).' already exists'])
            ->withInput();
        }

        $_updated = [
            'gname' => \ucwords($request->input('group_name')),
            'roles' => \json_encode($_roles),
            'updated_at' =>  date('Y-m-d H:i:s')
        ];

        $update = DB::table('user_group')->where(['guid' => $data_edit->guid])->update($_updated);

        if(!$update)
        {
            return \redirect($this->module->permalink.'/edit')->with(['response' => 'error', 'message' => 'Failed to update data to Storage'])->withInput();
        }

        return \redirect($this->module->permalink)->with(['response' => 'success', 'message' => 'Data group '.\ucwords($request->input('group_name')).' has been updated']);
    }

    public function delete(Request $request) {
        $this->page->blocked_page($this->mod_alias, 'drop');

        if(!$request->filled('data_id')) 
        {
            return \redirect($this->module->permalink)->with(['response' => 'error', 'message' => 'Data id not found']);
        }

        $data_id = array_keys($request->input('data_id'));

        /*get data by ID*/ 
        $data_edit = DB::table('user_group')->where(['guid' => $data_id])->first();

        if(!isset($data_edit->gname))
        {
            return \redirect($this->module->permalink)->with(['response' => 'error', 'message' => 'Data id not found']);
        }

        /* cheking user on group */ 
        // $total_user = DB::table('users')->where(['guid' => $data_edit->guid])->select('id')->count();
        $total_user = $this->query->get_total_user_group($data_edit->guid);

        if($total_user > 0 )
        {
            return \redirect($this->module->permalink)->with(['response' => 'error', 'message' => 'User Group '.$data_edit->gname.' have '.$total_user.' Users']);
        }

        /* deleting the user group */ 
        $delete = DB::table('user_group')->where(['guid' => $data_edit->guid])->delete();

        if(!$delete)
        {
            return \redirect($this->module->permalink)->with(['response' => 'error', 'message' => 'Failed to delete data to Storage'])->withInput();
        }

        return \redirect($this->module->permalink)->with(['response' => 'success', 'message' => 'Data group '.$data_edit->gname.' has been deleted']);
    }
}