<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PageModel;
use App\Models\QueryModel;

class DashboarController extends Controller
{
    public $viewdata = [];
    protected $mod_alias = '/';

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
        // dd($this->module);
        return view('dashboard.index', $this->viewdata);
    }
}
