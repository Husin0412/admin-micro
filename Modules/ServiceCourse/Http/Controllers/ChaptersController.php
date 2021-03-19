<?php

namespace Modules\ServiceCourse\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\PageModel;
use App\Models\QueryModel;
use Carbon\Carbon;

class ChaptersController extends Controller
{
    public $viewdata = [];

    protected $mod_alias = 'chapters';

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
        session()->forget(['chapter_id']);

        $course = get_courses(session('user_token'));
        if($course['status'] === "error")
        {
            return redirect()->route('500', ['response' => $course['status'], 'error_code' => $course['http_code'], 'message' => $course['message']]);
        }

        $_data = array();
        foreach($course['data'] as $key => $val)
        {
            $chapter = get_chapters(session('user_token'), ['course_id' => $val['id']]);
            if($chapter['status'] === "error")
            {
                return redirect()->route('500', ['response' => $chapter['status'], 'error_code' => $chapter['http_code'], 'message' => $chapter['message']]);
            }

            $mentor = detail_mentors(session('user_token'),  $val['mentor_id']);
            if($mentor['status'] === "error")
            {
                return redirect()->route('500', ['response' => $mentor['status'], 'error_code' => $mentor['http_code'], 'message' => $mentor['message']]);
            }

            $data = array(
                'course_id' => $val['id'],
                'name_course' => $val['name'],
                'thumbnail_course' => $val['thumbnail'],
                'chapter' => $chapter['data'],
                'mentor_name' => $mentor['data']['name']
            );
            array_push($_data, $data);
        }

        $this->viewdata['data'] = $_data;
        $this->viewdata['toolbar'] = true;
        $this->viewdata['page_title'] = 'chapters';

        return view('servicecourse::Chapters.index', $this->viewdata);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function add()
    {
        $this->page->blocked_page($this->mod_alias, 'create');

        $this->viewdata['toolbar_save'] = true;
        $this->viewdata['page_title'] = 'add chapters';

        return view('servicecourse::Chapters.add', $this->viewdata);
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
        ];

        $validator = Validator::make($request->all(), $_validates);

        if($validator->fails())
        {
            return \redirect($this->module->permalink.'/add')
                ->withErrors($validator)
                ->withInput();
        }

        $name_exist = $this->query->chapter_exist(ucwords($request->input('name')), $request->input('course_id'));

        if($name_exist['name'] > 0 ) 
        {
            $name_exist = $name_exist['name'] > 0 ? 'Name '.ucwords($request->input('name')).' already exists' : null;
            return \redirect($this->module->permalink.'/add')
                ->with(['name_exist' => $name_exist])
                ->withInput();
        }

        $data = [
            'name' => ucwords($request->input('name')),
            'course_id' => $request->input('course_id'),
        ];

        $chapter = create_chapters(session('user_token'), $data);

        if($chapter['status'] === "error") 
        {
          return redirect()->route('500', ['response' => $chapter['status'], 'error_code' => $chapter['http_code'], 'message' => $chapter['message']]);
        }

        return \redirect($this->module->permalink)->with(['response' => 'success', 'message' => 'New Chapter '.ucwords($request->input('name')).' has been saved']);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Request $request)
    {
        $this->page->blocked_page($this->mod_alias,'alter');

        if(!$request->filled('chapter_id') && !session('chapter_id'))
        {
            return redirect($this->module->permalink)->with(['response' => 'error', 'message' => 'Chapter id not found']);
        }

        if(!session('chapter_id'))
        {
            foreach($request->input('chapter_id') as $key => $val)
            {
                if(!empty($val))
                {
                    $chapter_id = (int)$val;
                    session(['chapter_id' => $chapter_id]);
                }
            }
        } 
        else
        {
            $chapter_id = \session('chapter_id');
        } 

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
        $detail_chapter = detail_chapters(session('user_token'), $chapter_id);
        $detail_course = detail_courses(session('user_token'), $data_id);

        if($detail_chapter["status"] === "error")
        {
            return redirect()->route('500', ['response' => $detail_chapter["status"], 'error_code' => $detail_chapter['http_code'], 'message' => $detail_chapter['message']]);
        }
        if($detail_course["status"] === "error")
        {
            return redirect()->route('500', ['response' => $detail_course["status"], 'error_code' => $detail_course['http_code'], 'message' => $detail_course['message']]);
        }

        if($data_id !== $detail_chapter['data']['course_id'])
        {
            return redirect($this->module->permalink)->with(['response' => 'error', 'message' => 'Chapter & course do not match']);
        }

        $data_edit = array(
            'id' => $detail_chapter['data']['id'],
            'name' => $detail_chapter['data']['name'],
            'course_id' => $detail_chapter['data']['course_id'],
            'course_name' => $detail_course['data']['name'],
            'created_at' => $detail_chapter['data']['created_at']
        );

        $this->viewdata['data_edit'] = $data_edit;
        $this->viewdata['toolbar_save'] = true;
        $this->viewdata['page_title'] = "edit chapters";

        return view('servicecourse::Chapters.edit', $this->viewdata);
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
        ];

        $validator = Validator::make($request->all(), $_validates);

        if($validator->fails())
        {
            return \redirect($this->module->permalink.'/edit')
                ->withErrors($validator)
                ->withInput();
        }

        $name_exist = $this->query->chapter_exist(ucwords($request->input('name')), $request->input('course_id'), $request->input('id'));

        if($name_exist['name'] > 0 ) 
        {
            $name_exist = $name_exist['name'] > 0 ? 'Name '.ucwords($request->input('name')).' already exists' : null;
            return \redirect($this->module->permalink.'/edit')
                ->with(['name_exist' => $name_exist])
                ->withInput();
        }

        $data = [
            'name' => ucwords($request->input('name')),
        ];

        $response_update = update_chapters(session('user_token'), $data, $request->input('id'));

        if($response_update['status'] === "error") 
        {
            return redirect()->route('500', ['response' => $response_update['status'], 'error_code' => $response_update['http_code'], 'message' => $response_update['message']]);
        }

        return \redirect($this->module->permalink)->with(['response' => 'success', 'message' => 'Chapters '.\ucwords($request->input('name')).' has been updated ']);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function delete(Request $request)
    {
        $this->page->blocked_page($this->mod_alias,'drop');

        if(!$request->filled('chapter_id') && !session('chapter_id'))
        {
            return redirect($this->module->permalink)->with(['response' => 'error', 'message' => 'Chapter id not found']);
        }

        if(!session('chapter_id'))
        {
            foreach($request->input('chapter_id') as $key => $val)
            {
                if(!empty($val))
                {
                    $chapter_id = (int)$val;
                    session(['chapter_id' => $chapter_id]);
                }
            }
        } 
        else
        {
            $chapter_id = \session('chapter_id');
        } 

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
        $detail_chapter = detail_chapters(session('user_token'), $chapter_id);

        if($detail_chapter["status"] === "error")
        {
            return redirect()->route('500', ['response' => $detail_chapter["status"], 'error_code' => $detail_chapter['http_code'], 'message' => $detail_chapter['message']]);
        }

        if($data_id !== $detail_chapter['data']['course_id'])
        {
            return redirect($this->module->permalink)->with(['response' => 'error', 'message' => 'Chapter & course do not match']);
        }

        $delete = delete_chapters(session('user_token'), $chapter_id);

        if(isset($delete['status']) && $delete['status'] === "error")
        {
            return redirect()->route('500', ['response' => $delete['status'], 'error_code' => $delete['http_code'], 'message' => $delete['message']]);
        }

        return \redirect($this->module->permalink)->with(['response' => 'success', 'message' => 'Chapters '.$detail_chapter['data']['name'].' has been deleted ']);
   
    }
}
