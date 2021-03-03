<div class="row {{ $val->parent_id > 0 ? 'div-child' : ($val->mod_name !== 'Master' ? 'div-parent' : '') }}">
    <div class="col-lg">
        <div class="form-group mt-2">
            <label for=""
                class="control-label {{ $val->parent_id > 0 ? 'label-child' : 'label-parent' }}">{{ $val->mod_name }}</label>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-lg">
        <div class="form-group mb-1">
            <label class="form-check-label"> View </label>
        </div>
        <div class="form-radio form-radio-flat controls">
            <label class="form-check-label ml-4">
                <input type="checkbox" class="toggle-switch-checkbox toggle-switch-primary"
                    id="{{ 'view-'.$val->modid }}" data-parent="{{ $val->parent_id }}"
                    is-parent="{{ $val->parent_id == 0 ? 'true' : 'false' }}"
                    {{ $val->parent_id == 0 ? '' : 'disabled' }} name="module[view][{{ $val->modid }}]"
                    {{ old('module.view.'.$val->modid) || ($_roles!==null && isset($_roles->view) && in_array($val->modid, explode(',',$_roles->view))) ? 'checked' : '' }}>
                <i class="input-helper"></i>
            </label>
        </div>
    </div>
</div>

@if($val->mod_permalink)
<div class="row mb-4">
    <div class="col-xl-2">
        <div class="form-group">
            <label class="form-check-label"> Create </label>
        </div>
        <div class="form-group">
            <div class="form-radio form-radio-flat" id="">
                <label class="form-check-label">
                    <input type="checkbox" class="toggle-switch-checkbox toggle-switch-success"
                        id="{{ 'create-'.$val->modid }}" data-parent="{{ $val->parent_id }}"
                        name="module[create][{{ $val->modid }}]" disabled
                        {{ old('module.create.'.$val->modid) || ($_roles!=null && isset($_roles->create) && in_array($val->modid, explode(',',$_roles->create))) ? 'checked' : '' }}>
                    <i class="input-helper"></i>
                </label>
            </div>
        </div>
    </div>

    <div class="col-xl-2">
        <div class="form-group">
            <label class="form-check-label"> Alter </label>
        </div>
        <div class="form-radio form-radio-flat" id="">
            <label class="form-check-label">
                <input type="checkbox" class="toggle-switch-checkbox toggle-switch-warning"
                    id="{{ 'alter-'.$val->modid }}" data-parent="{{ $val->parent_id }}"
                    name="module[alter][{{ $val->modid }}]" disabled
                    {{ old('module.alter.'.$val->modid) || ($_roles!=null && isset($_roles->alter) && in_array($val->modid, explode(',',$_roles->alter))) ? 'checked' : '' }}>
                <i class="input-helper"></i>
            </label>
        </div>
    </div>

    <div class="col-xl-2">
        <div class="form-group">
            <label class="form-check-label"> Drop </label>
        </div>
        <div class="form-radio form-radio-flat" id="">
            <label class="form-check-label">
                <input type="checkbox" class="toggle-switch-checkbox toggle-switch-danger"
                    id="{{ 'drop-'.$val->modid }}" data-parent="{{ $val->parent_id }}"
                    name="module[drop][{{ $val->modid }}]" disabled
                    {{ old('module.drop.'.$val->modid) || ($_roles!=null && isset($_roles->drop) && in_array($val->modid, explode(',',$_roles->drop))) ? 'checked' : '' }}>
                <i class="input-helper"></i>
            </label>
        </div>
    </div>
</div>
@endif