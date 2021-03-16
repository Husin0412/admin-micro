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
        $ch = [
            'course_id' => 1
        ];
        $data = get_chapters(session('user_token'), $ch);
        // if($data['status'] === "error")
        // {
        //     return redirect()->route('500', ['response' => $data['status'], 'error_code' => $data['http_code'], 'message' => $data['message']]);
        // }
// dd($data);
        // $this->viewdata['data'] = $_data;
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

        $name_exist = $this->query->chapter_exist(ucwords($request->input('name')));

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
    public function show($id)
    {
        return view('servicecourse::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('servicecourse::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
