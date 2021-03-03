@if(isset($toolbar) && $toolbar == true)
<div class="actions float-right">
    @if($page->fetch_role('create', $module) == true)
    <a href="javascript:" class="action-hedaer btn-add mr-3" data-link="{{ $module->permalink.'/add' }}"
        data-bs-toggle="tooltip" data-bs-placement="top" title="Create"><i
            class="mdi mdi-18px mdi-plus-circle-outline"></i> </a>
    @endif
    @if($page->fetch_role('alter', $module) == true )
    <a href="javascript:" class="action-hedaer btn-edit mr-3" data-link="{{ $module->permalink.'/edit' }}"
        data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><i class="mdi mdi-18px mdi-pencil-outline"></i>
    </a>
    @endif
    @if($page->fetch_role('drop', $module) == true )
    <a href="javascript:" class="action-hedaer btn-delete" data-link="{{ $module->permalink.'/delete' }}"
        data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><i
            class="mdi mdi-18px mdi-trash-can-outline "></i> </a>
    @endif
</div>

@endif
@if(isset($toolbar_save) && $toolbar_save == TRUE)
<div class="actions float-right">
    @if($page->fetch_role('create', $module) == TRUE || $page->fetch_role('alter', $module) == TRUE)
    <a href="javascript:" class="action-hedaer {{ isset($btn_save) ? $btn_save : 'btn-save' }} mr-3"
        data-bs-toggle="tooltip" data-bs-placement="top" title="Save">
        <i class="mdi mdi-18px mdi-content-save-outline"></i>
    </a>
    @endif
    <a href="javascript:" class="action-hedaer btn-cancel" data-link="{{ $module->permalink }}" data-bs-toggle="tooltip"
        data-bs-placement="top" title="Cancel">
        <i href="javascript:" class="mdi mdi-18px mdi-cancel"></i>
    </a>
</div>
@endif

@if(isset($toolbar_view) && $toolbar_view == TRUE)
<div class="actions float-right">
    <a href="javascript:" class="action-hedaer btn-details mr-3" data-link="{{ $module->permalink.'/details' }}"
        data-bs-toggle="tooltip" data-bs-placement="top" title="Details">
        <i class="mdi mdi-18px mdi-details"></i>
    </a>
</div>
@endif