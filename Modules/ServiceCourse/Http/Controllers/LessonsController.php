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
        session()->forget(['lesson_id']);

        $chapters = detail_chapters(session('user_token'));
        if($chapters['status'] === "error") 
        {
            return redirect()->route('500', ['response' => $chapters['status'], 'error_code' => $chapters['http_code'], 'message' => $chapters['message']]);
        }

        $_data = array();
        foreach($chapters['data'] as $key => $val_chapter)
        {
            $courses = detail_courses(session('user_token'), $val_chapter['course_id']);
            if($courses['status'] === "error") 
            {
              return redirect()->route('500', ['response' => $courses['status'], 'error_code' => $courses['http_code'], 'message' => $courses['message']]);
            }

            $mentors = detail_mentors(session('user_token'), $courses['data']['mentor_id']);
            if($mentors['status'] === "error") 
            {
              return redirect()->route('500', ['response' => $mentors['status'], 'error_code' => $mentors['http_code'], 'message' => $mentors['message']]);
            }

            $lessons = get_lessons(session('user_token'), ['chapter_id' => $val_chapter['id']]);
            if($lessons['status'] === "error") 
            {
              return redirect()->route('500', ['response' => $lessons['status'], 'error_code' => $lessons['http_code'], 'message' => $lessons['message']]);
            }

            if(!empty($lessons['data']))
            {
                $data = [
                    "id" => 1,
                    "chapter_id" => $val_chapter['id'],
                    "chapter_name" => $val_chapter['name'],
                    "course_id" => $courses['data']['id'],
                    "course_name" => $courses['data']['name'],
                    "mentor_id" => $mentors['data']['id'],
                    "mentor_name" => $mentors['data']['name'],
                    "lesson" => $lessons['data']
                ];
                array_push($_data, $data);
            }

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

        $lesson_id = null;
        foreach($request->input('lesson_id') as $val)
        {
            if(!empty($val))
            {
                $lesson_id = (int)($val);
            }
        }

        if(empty($lesson_id) && !session('lesson_id'))
        {
            return redirect($this->module->permalink)->with(['response' => 'error', 'message' => 'Lesson id not found']);
        }

        if(!session('lesson_id'))
        {
            session(['lesson_id' => $lesson_id]);
        } 
        else
        {
            $lesson_id = \session('lesson_id');
        } 

        /* get data by ID */ 
        $data_edit = detail_lessons(session('user_token'), $lesson_id);
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

        $lesson_id = null;
        foreach($request->input('lesson_id') as $val)
        {
            if(!empty($val))
            {
                $lesson_id = (int)($val);
            }
        }

        if(empty($lesson_id))
        {
            return redirect($this->module->permalink)->with(['response' => 'error', 'message' => 'Lesson id not found']);
        }

        /*get data by ID*/ 
        $data_edit = detail_lessons(session('user_token'), $lesson_id);
        if($data_edit['status'] === "error") 
        {
          return redirect()->route('500', ['response' => $data_edit['status'], 'error_code' => $data_edit['http_code'], 'message' => $data_edit['message']]);
        }

        /* deleting */ 
        $delete = delete_lessons(session('user_token'), $lesson_id);
        if($delete['status'] === "error") 
        {
          return redirect()->route('500', ['response' => $delete['status'], 'error_code' => $delete['http_code'], 'message' => $delete['message']]);
        }

        return \redirect($this->module->permalink)->with(['response' => 'success', 'message' => 'Lessons '.$data_edit['data']['name'].' has been deleted']);
    }
}
