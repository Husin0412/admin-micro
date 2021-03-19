<?php

namespace Modules\ServiceCourse\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\PageModel;
use App\Models\QueryModel;
use Carbon\Carbon;

class LessonsController extends Controller
{
    public $viewdata = [];

    protected $mod_alias = 'lessons';

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
        // ['chapter_id' => 4]
        $lessons = get_lessons(session('user_token'));
        
        if($lessons['status'] === "error") 
        {
          return redirect()->route('500', ['response' => $lessons['status'], 'error_code' => $lessons['http_code'], 'message' => $lessons['message']]);
        }

        $_data = array();
        foreach($lessons['data'] as $key => $val)
        {
            $chapters = detail_chapters(session('user_token'), $val['chapter_id']);
            if($chapters['status'] === "error") 
            {
              return redirect()->route('500', ['response' => $chapters['status'], 'error_code' => $chapters['http_code'], 'message' => $chapters['message']]);
            }

            $courses = detail_courses(session('user_token'), $chapters['data']['course_id']);
            if($courses['status'] === "error") 
            {
              return redirect()->route('500', ['response' => $courses['status'], 'error_code' => $courses['http_code'], 'message' => $courses['message']]);
            }

            $mentor = detail_mentors(session('user_token'), $courses['data']['mentor_id']);
            if($mentor['status'] === "error") 
            {
              return redirect()->route('500', ['response' => $mentor['status'], 'error_code' => $mentor['http_code'], 'message' => $mentor['message']]);
            }

            $data = [
                "id" => $val['id'],
                "name" => $val['name'],
                "video" => $val['video'],
                "chapter_id" => $val['chapter_id'],
                "chapter_name" => $chapters['data']['name'],
                "course_id" => $chapters['data']['course_id'],
                "course_name" => $courses['data']['name'],
                "mentor_id" => $courses['data']['mentor_id'],
                "mentor_name" => $mentor['data']['name'],
                'created_at' => Carbon::parse($val['created_at'])->format('M-d-Y h:m A'),
            ];
            array_push($_data, $data);
        }

        $this->viewdata['data'] = $_data;
        $this->viewdata['toolbar'] = true;
        $this->viewdata['page_title'] = 'lessons';
        return view('servicecourse::Lessons.index', $this->viewdata);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function add()
    {
        $this->page->blocked_page($this->mod_alias, 'create');

        $this->viewdata['toolbar_save'] = true;
        $this->viewdata['page_title'] = 'add lessons';

        return view('servicecourse::Lessons.add', $this->viewdata);
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
            'course_id' => 'required|integer',
            'video' => 'required|string',
            'chapter_id' => 'required|integer',
        ];

        $validator = Validator::make($request->all(), $_validates);

        if($validator->fails())
        {
            return \redirect($this->module->permalink.'/add')
                ->withErrors($validator)
                ->withInput();
        }

        $name_exist = $this->query->lesson_exist(ucwords($request->input('name')), ['chapter_id' => $request->input('chapter_id')]);

        if($name_exist['name'] > 0 ) 
        {
            $name_exist = $name_exist['name'] > 0 ? 'Name '.ucwords($request->input('name')).' already exists' : null;
            return \redirect($this->module->permalink.'/add')
                ->with(['name_exist' => $name_exist])
                ->withInput();
        }

        $data = [
            'name' => ucwords($request->input('name')),
            'video' => $request->input('video'),
            'chapter_id' => $request->input('chapter_id'),
        ];

        $lessons = create_lessons(session('user_token'), $data);

        if($lessons['status'] === "error") 
        {
          return redirect()->route('500', ['response' => $lessons['status'], 'error_code' => $lessons['http_code'], 'message' => $lessons['message']]);
        }

        return \redirect($this->module->permalink)->with(['response' => 'success', 'message' => 'New Lessons '.ucwords($request->input('name')).' has been saved']);

    }

    /**
     * Show the specified resource.
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
        $data_edit = detail_lessons(session('user_token'), $data_id);
        if($data_edit['status'] === "error") 
        {
          return redirect()->route('500', ['response' => $data_edit['status'], 'error_code' => $data_edit['http_code'], 'message' => $data_edit['message']]);
        }

        $this->viewdata['data_edit'] = $data_edit['data'];
        $this->viewdata['toolbar_save'] = true;
        $this->viewdata['page_title'] = "edit lessons";

        return view('servicecourse::Lessons.edit', $this->viewdata);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request)
    {
        $this->page->blocked_page($this->mod_alias,'alter');

        $_validates = [
            'name' => 'required|min:5|max:40',
            'video' => 'required|string',
        ];

        $validator = Validator::make($request->all(), $_validates);

        if($validator->fails())
        {
            return \redirect($this->module->permalink.'/edit')
                ->withErrors($validator)
                ->withInput();
        }

        $name_exist = $this->query->lesson_exist(ucwords($request->input('name')), ['chapter_id' => $request->input('chapter_id')], $request->input('id'));

        if($name_exist['name'] > 0 ) 
        {
            $name_exist = $name_exist['name'] > 0 ? 'Name '.ucwords($request->input('name')).' already exists' : null;
            return \redirect($this->module->permalink.'/edit')
                ->with(['name_exist' => $name_exist])
                ->withInput();
        }

        $data = [
            'name' => ucwords($request->input('name')),
            'video' => $request->input('video')
        ];

        $response_update = update_lessons(session('user_token'), $data, $request->input('id'));

        if($response_update['status'] === "error") 
        {
            return redirect()->route('500', ['response' => $response_update['status'], 'error_code' => $response_update['http_code'], 'message' => $response_update['message']]);
        }

        return \redirect($this->module->permalink)->with(['response' => 'success', 'message' => 'Lessons '.\ucwords($request->input('name')).' has been updated ']);
       
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
        $data_edit = detail_lessons(session('user_token'), $data_id);
        if($data_edit['status'] === "error") 
        {
          return redirect()->route('500', ['response' => $data_edit['status'], 'error_code' => $data_edit['http_code'], 'message' => $data_edit['message']]);
        }

        /* deleting */ 
        $delete = delete_lessons(session('user_token'), $data_id);
        if($delete['status'] === "error") 
        {
          return redirect()->route('500', ['response' => $delete['status'], 'error_code' => $delete['http_code'], 'message' => $delete['message']]);
        }

        return \redirect($this->module->permalink)->with(['response' => 'success', 'message' => 'Lessons '.$data_edit['data']['name'].' has been deleted']);
    }
}
