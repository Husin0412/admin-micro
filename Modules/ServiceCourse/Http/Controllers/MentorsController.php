<?php

namespace Modules\ServiceCourse\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\PageModel;
use App\Models\QueryModel;
use Carbon\Carbon;

class MentorsController extends Controller
{
    public $viewdata = [];

    protected $mod_alias = 'mentors';

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
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $this->page->blocked_page($this->mod_alias);

        session()->forget(['data_id']);

        $data = get_mentors(session('user_token'));

        if($data['status'] === "error")
        {
            return redirect()->route('500', ['response' => $data['status'], 'error_code' => $data['http_code'], 'message' => $data['message']]);
        }

        $this->viewdata['data'] = $data['data'];
        $this->viewdata['toolbar'] = true;
        $this->viewdata['page_title'] = 'mentors';

        return view('servicecourse::mentors.index', $this->viewdata);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function add()
    {
        $this->page->blocked_page($this->mod_alias, 'create');

        $this->viewdata['toolbar_save'] = true;
        $this->viewdata['page_title'] = 'add mentors';

        return view('servicecourse::mentors.add', $this->viewdata);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function save(Request $request)
    {
        $this->page->blocked_page($this->mod_alias, 'create');

        $_validates = [
            'name' => 'required|min:5|max:40',
            'email' => 'required|email'
        ];

        $validator = Validator::make($request->all(), $_validates);

        if($validator->fails())
        {
            return \redirect($this->module->permalink.'/add')
                ->withErrors($validator)
                ->withInput();
        }

        $mentor_exist = $this->query->get_mentor_exist(ucwords($request->input('name')), $request->input('email'));

        if($mentor_exist['name'] > 0 || $mentor_exist['email'] > 0 ) 
        {
            $name_exist = $mentor_exist['name'] > 0 ? 'Name '.ucwords($request->input('name')).' already exists' : null;
            $email_exist = $mentor_exist['email'] > 0 ? 'Email '.$request->input('email').' already exists' : null;
            return \redirect($this->module->permalink.'/add')
                ->with(['name_exist' => $name_exist, 'email_exist' => $email_exist])
                ->withInput();
        }

        if($request->hasFile('profile'))
        {
            $path = $request->file('profile');
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
  
            $profile = $response['data']['image'];
        }

        $data = [
            'name' => ucwords($request->input('name')),
            'email' => $request->input('email'),
            'profession' => $request->input('profession') ?: null,
            'profile' => $profile ?? null,
        ];

        $mentor = create_mentors(session('user_token'), $data);

        if($mentor['status'] === "error") 
        {
          return redirect()->route('500', ['response' => $mentor['status'], 'error_code' => $mentor['http_code'], 'message' => $mentor['message']]);
        }

        return \redirect($this->module->permalink)->with(['response' => 'success', 'message' => 'New Mentors '.ucwords($request->input('name')).' has been saved']);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Request $request)
    {
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
        $data_edit = detail_mentors(session('user_token'), $data_id);

        if($data_edit["status"] === "error")
        {
            return redirect()->route('500', ['response' => $data_edit["status"], 'error_code' => $data_edit['http_code'], 'message' => $data_edit['message']]);
        }
        $this->viewdata['data_edit'] = $data_edit['data'];
        $this->viewdata['toolbar_save'] = true;
        $this->viewdata['page_title'] = "edit mentors";

        return view('servicecourse::mentors.edit', $this->viewdata);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request)
    {
        $this->page->blocked_page($this->mod_alias, 'alter');

        $_validates = ['name' => 'required|min:5|max:40','email' => 'required|email'];

        $validator = Validator::make($request->all(), $_validates);

        if($validator->fails())
        {
            return \redirect($this->module->permalink.'/edit')
                ->withErrors($validator)
                ->withInput();
        }

        $mentor_exist = $this->query->get_mentor_exist(ucwords($request->input('name')), $request->input('email'), $request->input('id'));

        if($mentor_exist['name'] > 0 || $mentor_exist['email'] > 0 ) 
        {
            $name_exist = $mentor_exist['name'] > 0 ? 'Name '.ucwords($request->input('name')).' already exists' : null;
            $email_exist = $mentor_exist['email'] > 0 ? 'Email '.$request->input('email').' already exists' : null;
            return \redirect($this->module->permalink.'/edit')
                ->with(['name_exist' => $name_exist, 'email_exist' => $email_exist])
                ->withInput();
        }

        if($request->hasFile('profile'))
        {
          $path = $request->file('profile');
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

          $profile = $response['data']['image'];
        } 

        if($request->input('profile-existing') && !isset($profile))
        {
            $profile = $request->input('profile-existing');
        }
        else if($request->input('profile-existing') && isset($profile))
        {
            $data_image = [ 'image' => $request->input('profile-existing') ];
            $delete_media = delete_media(session('user_token'), $data_image);
        }

        $data = [
            'name' => ucwords($request->input('name')),
            'email' => $request->input('email'),
            'profession' => $request->input('profession') ?: null,
            'profile' => $profile ?: null
        ];

        $response_update = update_mentors(session('user_token'), $data, $request->input('id'));

        if($response_update['status'] === "error") 
        {
            return redirect()->route('500', ['response' => $response_update['status'], 'error_code' => $response_update['http_code'], 'message' => $response_update['message']]);
        }

        return \redirect($this->module->permalink)->with(['response' => 'success', 'message' => 'Mentors '.\ucwords($request->input('name')).' has been updated ']);

    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
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
        $data_edit = detail_mentors(session('user_token'), $data_id);

        if($data_edit['status'] === "error") 
        {
            return redirect()->route('500', ['response' => $data_edit['status'], 'error_code' => $data_edit['http_code'], 'message' => $data_edit['message']]);
        }

        /* delete profile */ 
        if($data_edit['data']['profile'])
        {
            $data_image = [ 'image' => $data_edit['data']['profile'] ];
            $delete_media = delete_media(session('user_token'), $data_image);

            // if($delete_media['status'] === "error")
            // {
            //     return redirect()->route('500', ['response' => $delete_media['status'], 'error_code' => $delete_media['http_code'], 'message' => $delete_media['message']]);
            // }
        }

        $delete = delete_mentor(session('user_token'), $data_id);

        if(isset($delete['status']) && $delete['status'] === "error")
        {
            return redirect()->route('500', ['response' => $delete['status'], 'error_code' => $delete['http_code'], 'message' => $delete['message']]);
        }

        return \redirect($this->module->permalink)->with(['response' => 'success', 'message' => 'Mentors '.$data_edit['data']['name'].' has been deleted ']);

    }
}
