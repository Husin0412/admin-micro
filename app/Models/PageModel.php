<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class PageModel extends Model
{
    use HasFactory;

    protected $tbl_user_group = "user_group";
    protected $tbl_module = 'module';

    public function __construct() {

    }

    public function viewdata() {
        return [
            'site_name' => config('layout.app_name')
        ];
    }

    public function user_data() {
        $user = \session('session_user');
        $usergroup = DB::table($this->tbl_user_group)->where(['guid' => session('session_user')->guid])->first();
        $user->gname = $usergroup->gname;
        $user->roles = $usergroup->roles;
        unset($user->created_at, $user->updated_at);
        return $user;
    }

    public function get_module($where = '', $select = '*', $total_sub = false) {
        $query = DB::table($this->tbl_module.' AS mod')->orderBy('mod.mod_order','asc')->selectRaw($select);

        if(isset($where['field']) && isset($where['value'])) {
            $query->whereIn('mod.'.$where['field'], $where['value']);
        }

        if($total_sub === true) {
            $query->selectRaw('(SELECT COUNT(modid) FROM '.$this->tbl_module.' WHERE parent_id = mod.modid) AS total_sub');
        }

        $query->where(['mod.published' => 'y']);

        return $query;
    } 

    public function fetch_role($role = 'view', $module = '') {
        $data_user = $this->user_data();
    
        $_roles = is_object($data_user) && $data_user->roles ? json_decode($data_user->roles) : null;

        $role_mod = null;
        $roles = [];

        if(isset($_roles)) {
            switch($role)
            {
                case 'create' : 
                    $roles = isset($_roles->create) ? explode(',', $_roles->create) : [];
                break;
                case 'alter' :
                    $roles = isset($_roles->alter) ? explode(',', $_roles->alter) : [];
                break;
                case 'drop' :
                    $roles = isset($_roles->drop) ? explode(',', $_roles->drop) : [];
                break;
                default :
                    $roles = isset($_roles->view) ? explode(',', $_roles->view) : [];
            }
        }

        if(isset($module) && is_object($module)) {
            return in_array($module->modid, $roles) ?: false;
        }

        return $roles;

    }


    public function get_modid($alias) {
        $get_module = $this->get_module(['field' => 'mod_alias', 'value' => [$alias]], 'modid,parent_id,mod_permalink');
        if($get_module->count() === 0) {
            return 0;
        }

        $_module = $get_module->first();
        return (Object) [
            'parent' =>$_module->parent_id,
            'modid' => $_module->modid,
            'permalink' => $_module->mod_permalink
        ];

    }
    

    public function blocked_page($alias = '', $role = 'view') {
        if(!$alias) {
            // return view('error-page.400');
            \abort(400);
        }

        $get_modid = $this->get_modid($alias);

        if(is_integer($get_modid) && $get_modid <= 0) {
            // return view('error-page.400', ['response' => 'error', 'error_code' => '404', 'message' => 'The Module '.$alias.' Not Found']);
            abort(403, 'The Module '.$alias.' Not Found');
        }

        if($this->fetch_role($role, $this->get_modid($alias)) === false) {
            // return view('error-page.500', ['response' => 'error', 'error_code' => '403', 'message' => 'You do not have permission to access "'.url()->current().'" on this server']);
            abort(403, 'You do not have permission to access "'.url()->current().'" on this server');
        }
        return true;

    }


    public function module_list($parent_id = 0, $modid = '', $roles = []) {
        $get_module = $this->get_module(['field' => 'parent_id', 'value' => [$parent_id]], 'modid,parent_id,mod_name,mod_alias,mod_permalink,mod_icon', TRUE);

        $route = request()->route()->getAction();
        $prefix = substr_replace($route['prefix'],'',0,1);
        
        $_module = $_views = '';

        if($get_module->count() > 0 ) {
            foreach($get_module->get() as $val) {
                if($this->fetch_role('view', $val->modid)) {
                    $_modules = (object) [
                        'modid' => $val->modid,
                        'parent_id' => $val->parent_id,
                        'mod_name' => $val->mod_name,
                        'mod_icon' => $val->mod_icon,
                        'mod_permalink' => $val->mod_permalink
                    ];

                    $_views .= view('layout.'.config('layout.app_name').'.partials.module', ['val' => $_modules, '_roles' => $roles]);

                    if($val->total_sub > 0 ) {
                        $_views .= $this->module_list($val->modid, $modid, $roles);
                    }
                }
            }
        }
        return $_views;
    }


    public function module_sidebar($parent_id = 0, $module = '') {
        $get_module = $this->get_module(['field' => 'parent_id', 'value' => [$parent_id]], 'modid, parent_id, mod_name, mod_alias, mod_permalink, mod_icon', true);

        $get_mdl = $this->get_module();

        $template = null;

        $urlside = url()->current();

        $route = request()->route()->getAction(); 
        $prefix = \substr_replace($route['prefix'],'',0,1);
        $freg = preg_match("/".$prefix."/i", $urlside);

        $active = 'null';

        !$prefix ? $active = 'active' : $active = null;

        $template .= '<li class="nav-item"> <a class="nav-link '.$active.'" href="'.url('/').'"><i class="mdi mdi-home-circle mr-2"></i><span class="menu-title">Dashboard</span></a></li>';

        if($get_module->count() > 0 ) {
            foreach($get_module->get() as $val) 
            {
                if($this->fetch_role('view', (object) ['modid' => $val->modid]) === true)
                {
                    if(!$val->mod_permalink)
                    {
                        $val->mod_permalink = 'javascript:void(0)';
                    }

                    if($val->parent_id === 0)
                    {
                        $active = null;
                        
                        $module && $val->modid === $module->parent ? $active = 'active' : $active = null;
                        // $val->mod_alias == 'Equipment' ? $inquiries = '<span class="label label-important">'.rand(10,100).'</span>' : $inquiries = null;
                        $template .= '<li class="nav-item"> <a class="nav-link '.$active.'" data-toggle="collapse" href="#'.$val->mod_name.'" aria-expanded="false" aria-controls="'.$val->mod_name.'"><i class="'.$val->mod_icon.' mr-2"></i><span class="menu-title">'.$val->mod_name.'</span><i class="menu-arrow"></i></a>';
                        $template .= '<div class="collapse" id="'.$val->mod_name.'"><ul class="nav flex-column sub-menu">';
                    }

                    if($val->total_sub > 0 ) {
                        foreach($get_mdl->get() as $value) 
                        {
                       
                            if($this->fetch_role('view', (object) ['modid' => $value->modid]) === true)
                            {
                                if($val->modid === $value->parent_id) 
                                {     
                                    $module && $value->modid == $module->modid ? $active = 'active' : $active = null;
                                    // $value->mod_alias === 'inquiries' ? $inquiries = '<span class="label label-important" style="margin-left: 42px;">'.rand(10,100).'</span></a>' : $inquiries = null;
                                    $template .= '<li class="nav-item '.$active.'"> <a class="nav-link '.$active.'" href="'.($value->mod_permalink).'">'.$value->mod_name.'</a></li>';
                                }
                            }
                        }
                    }

                    $template .= '</ul>';
                    $template .= '</div>';
                    $template .= '</li>';

                }
            }
        }

        return $template;

    }


}
