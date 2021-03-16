<?php

namespace Modules\ServiceCourse\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\PageModel;
use App\Models\QueryModel;
use Carbon\Carbon;

class CoursesController extends Controller
{
    public $viewdata = [];

    protected $mod_alias = 'courses';

    public function __construct()
    {
        $this->page = new PageModel();
        $this->viewdata = $this->page->viewdata();
        $this->viewdata['page'] = $this->page;

        $this->query = new QueryModel();
        $this->viewdata['query'] = $this->query;

        $this->module = $this->page->get_modid($this->mod_alias);
        $this->viewdata['mod_alias'] = $this->mod_alias;
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

        $data = get_courses(session('user_token'));
        if($data['status'] === "error")
        {
            return redirect()->route('500', ['response' => $data['status'], 'error_code' => $data['http_code'], 'message' => $data['message']]);
        }

        $_data = [];
        foreach($data['data'] as $key => $val)
        {
            $type = $val['type'] === 'free' ? '<span class="badge badge-secondary"> '. $val['type'] .' </span>' 
                : '<span class="badge badge-dark"> '. $val['type'] .' </span>';
            $status = $val['status'] === 'draft' ? '<span class="badge badge-secondary"> '. $val['status'] .' </span>' 
                : '<span class="badge badge-dark"> '. $val['status'] .' </span>';

            if($val['level'] === "all-level")
            {
                $level = '<span class="text-success"> '. $val['level'] .' </span>';
            }
            else if($val['level'] === "beginner")
            {
                $level = '<span class="text-primary"> '. $val['level'] .' </span>';
            }
            else if($val['level'] === "intermediate")
            {
                $level = '<span class="text-warning"> '. $val['level'] .' </span>';
            }
            else if($val['level'] === "advance")
            {
                $level = '<span class="text-danger"> '. $val['level'] .' </span>';
            }
            else 
            {
               $level = '<span class="text-dark"> '. $val['level'] .' </span>';
            }

            $certificate = $val['certificate'] === 1 ? '<span class="badge badge-dark"> Available </span>' 
                : '<span class="badge badge-secondary"> Not Available </span>';
            $_mentors = detail_mentors(session('user_token'), $val['mentor_id']);
            $mentors = $_mentors['status'] === "success" ? $_mentors['data']['name'] : "";

            $item = [
                'id' => $val['id'],
                'name' => $val['name'],
                'thumbnail' => $val['thumbnail'],
                'type' => $type,
                'status' => $status,
                'price' => $val['price'],
                'level' => $level,
                'certificate' => $certificate,
                'description' =>  cutText($val['description']),
                'mentor_id' => $val['mentor_id'],
                'mentors' => $mentors,
                'created_at' => Carbon::parse($val['created_at'])->format('M-d-Y h:m A'),
            ];

            array_push($_data, $item);
        }

        $this->viewdata['data'] = $_data;
        $this->viewdata['toolbar'] = true;
        $this->viewdata['toolbar_image'] = true;
        $this->viewdata['page_title'] = 'courses';

        return view('servicecourse::Courses.index', $this->viewdata);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function add()
    {
        $this->page->blocked_page($this->mod_alias, 'create');

        $this->viewdata['toolbar_save'] = true;
        $this->viewdata['page_title'] = 'add course';

        return view('servicecourse::Courses.add', $this->viewdata);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function save(Request $request)
    {
        $this->page->blocked_page($this->mod_alias, 'create');

        if($request->input('type') === "premium") {
            $validate_mentor = 'required|not_in:Rp 0.00';
            $_price = explode(".",ltrim($request->input('price'), "Rp "));
            $price = str_replace(" ", "", str_replace (",", " ", $_price[0]));
        }
        else 
        {
            $validate_mentor = 'string';
            $price = 0;
        }

        $_validates = [
            'name' => 'required|min:5|max:40',
            'certificate' => 'required',
            'type' => 'required|string',
            'status' => 'required|string',
            'level' => 'required|string',
            'mentor_id' => 'required|integer',
            'price' => $validate_mentor,
        ];

        $validator = Validator::make($request->all(), $_validates);

        if($validator->fails())
        {
            return \redirect($this->module->permalink.'/add')
                ->withErrors($validator)
                ->withInput();
        }

        $name_exist = $this->query->courses_exist(ucwords($request->input('name')));

        if($name_exist['name'] > 0 ) 
        {
            $name_exist = $name_exist['name'] > 0 ? 'Name '.ucwords($request->input('name')).' already exists' : null;
            return \redirect($this->module->permalink.'/add')
                ->with(['name_exist' => $name_exist])
                ->withInput();
        }

        $certificate = explode("v",$request->input('certificate'));
        // dd($certificate[1]);
        if($request->hasFile('thumbnail'))
        {
            $path = $request->file('thumbnail');
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
  
            $thumbnail = $response['data']['image'];
        }

        $data = [
            'name' => ucwords($request->input('name')),
            'certificate' => $certificate[1],
            'type' => $request->input('type'),
            'status' => $request->input('status'),
            'level' => $request->input('level'),
            'mentor_id' => $request->input('mentor_id'),
            'description' => $request->input('description') ?? null,
            'thumbnail' => $thumbnail ?? null,
            'price' => $price
        ];

        $course = create_courses(session('user_token'), $data);

        if($course['status'] === "error") 
        {
          return redirect()->route('500', ['response' => $course['status'], 'error_code' => $course['http_code'], 'message' => $course['message']]);
        }

        return \redirect($this->module->permalink)->with(['response' => 'success', 'message' => 'New Courses '.ucwords($request->input('name')).' has been saved']);

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
        $data_edit = detail_courses(session('user_token'), $data_id);

        if($data_edit["status"] === "error")
        {
            return redirect()->route('500', ['response' => $data_edit["status"], 'error_code' => $data_edit['http_code'], 'message' => $data_edit['message']]);
        }
        $this->viewdata['data_edit'] = $data_edit['data'];
        $this->viewdata['toolbar_save'] = true;
        $this->viewdata['page_title'] = "edit courses";

        return view('servicecourse::Courses.edit', $this->viewdata);
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

        if($request->input('type') === "premium") {
            $validate_mentor = 'required|not_in:Rp 0.00';
            $_price = explode(".",ltrim($request->input('price'), "Rp "));
            $price = str_replace(" ", "", str_replace (",", " ", $_price[0]));
        }
        else 
        {
            $validate_mentor = 'string';
            $price = 0;
        }

        $_validates = [
            'name' => 'required|min:5|max:40',
            'certificate' => 'required',
            'type' => 'required|string',
            'status' => 'required|string',
            'level' => 'required|string',
            'mentor_id' => 'required|integer',
            'price' => $validate_mentor,
        ];

        $validator = Validator::make($request->all(), $_validates);

        if($validator->fails())
        {
            return \redirect($this->module->permalink.'/edit')
                ->withErrors($validator)
                ->withInput();
        }

        $name_exist = $this->query->courses_exist(ucwords($request->input('name')), $request->input('id'));

        if($name_exist['name'] > 0 ) 
        {
            $name_exist = $name_exist['name'] > 0 ? 'Name '.ucwords($request->input('name')).' already exists' : null;
            return \redirect($this->module->permalink.'/edit')
                ->with(['name_exist' => $name_exist])
                ->withInput();
        }

        $certificate = explode("v",$request->input('certificate'));

        if($request->hasFile('thumbnail'))
        {
          $path = $request->file('thumbnail');
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

          $thumbnail = $response['data']['image'];
        } 

        if($request->input('thumbnail-existing') && !isset($thumbnail))
        {
            $thumbnail = $request->input('thumbnail-existing');
        } 
        else if($request->input('thumbnail-existing') && isset($thumbnail))
        {
            $data_image = [ 'image' => $request->input('thumbnail-existing') ];
            $delete_media = delete_media(session('user_token'), $data_image);
        }

        $data = [
            'name' => ucwords($request->input('name')),
            'certificate' => $certificate[1],
            'type' => $request->input('type'),
            'status' => $request->input('status'),
            'level' => $request->input('level'),
            'mentor_id' => $request->input('mentor_id'),
            'description' => $request->input('description') ?? null,
            'thumbnail' => $thumbnail ?? null,
            'price' => $price
        ];

        $response_update = update_courses(session('user_token'), $data, $request->input('id'));

        if($response_update['status'] === "error") 
        {
            return redirect()->route('500', ['response' => $response_update['status'], 'error_code' => $response_update['http_code'], 'message' => $response_update['message']]);
        }

        return \redirect($this->module->permalink)->with(['response' => 'success', 'message' => 'Courses '.\ucwords($request->input('name')).' has been updated ']);
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
        $data_edit = detail_courses(session('user_token'), $data_id);

        if($data_edit['status'] === "error") 
        {
            return redirect()->route('500', ['response' => $data_edit['status'], 'error_code' => $data_edit['http_code'], 'message' => $data_edit['message']]);
        }

        /* delete thumbnail */ 
        if($data_edit['data']['thumbnail'])
        {
            $data_image = [ 'image' => $data_edit['data']['thumbnail'] ];
            $delete_media = delete_media(session('user_token'), $data_image);
        }

        $delete = delete_courses(session('user_token'), $data_id);

        if(isset($delete['status']) && $delete['status'] === "error")
        {
            return redirect()->route('500', ['response' => $delete['status'], 'error_code' => $delete['http_code'], 'message' => $delete['message']]);
        }

        return \redirect($this->module->permalink)->with(['response' => 'success', 'message' => 'Courses '.$data_edit['data']['name'].' has been deleted ']);
    }

    public function addImage()
    {
        $this->page->blocked_page($this->mod_alias, 'create');

        $this->viewdata['toolbar_save'] = true;
        $this->viewdata['page_title'] = 'add image';

        return view('servicecourse::Courses.addImage', $this->viewdata);
    }

    public function saveImage(Request $request)
    {
        $this->page->blocked_page($this->mod_alias, 'create');

        $_validates = [
            'image' => 'required',
            'course_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $_validates);

        if($validator->fails())
        {
            return \redirect($this->module->permalink.'/image')
                ->withErrors($validator)
                ->withInput();
        }

        $req = $request->all(); 
        $count = count($req['image']);

        if(is_array($req['image']))
        {
            foreach($req['image'] as $key => $val)
            {
                $path = $val;
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
        
                $image_course = $response['data']['image'];
                
                $data = [
                    'course_id' => $req['course_id'],
                    'image' => $image_course,
                ];
        
                $resp_img_course = create_img_courses(session('user_token'), $data);
        
                if($resp_img_course['status'] === "error") 
                {
                  return redirect()->route('500', ['response' => $resp_img_course['status'], 'error_code' => $resp_img_course['http_code'], 'message' => $resp_img_course['message']]);
                }
                
            }
            return \redirect($this->module->permalink)->with(['response' => 'success', 'message' => 'New '.$count.' Image Course has been saved']);
        }
        
    }
}
