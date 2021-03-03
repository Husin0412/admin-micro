<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PageModel;
use App\Models\QueryModel;
use Carbon\Carbon;

class StudentController extends Controller
{
    public $viewdata = [];

    protected $mod_alias = 'student';

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

    public function index()
    {
       $this->page->blocked_page($this->mod_alias);

       session()->forget(['data_id']);

       $get_data = getUsersAll(\session('user_token'));

       if($get_data['status'] === 'error') {
           if($get_data['http_code'] === 500 && $get_data['message'] === 'service user unavailable') 
           {
               return redirect()->route('500', [['response' => 'error', 'error_code'] => $get_data['http_code'], 'message' => $get_data['message']]);
           }
           dd($get_data);
       }

       $data = [];
       foreach($get_data['data'] as $key => $val)
       {
           if($val['role'] === 'student') 
           {
               $_data = [
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

       $this->viewdata['data'] = $data;
       $this->viewdata['toolbar_view'] = true;
       $this->viewdata['page_title'] = 'student';

       return view('student.index', $this->viewdata);

    }

    public function details(Request $request)
    {
        $this->page->blocked_page($this->mod_alias);

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

        

        dd($request->all());
    }

}
