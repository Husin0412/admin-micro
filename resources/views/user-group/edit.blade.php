@extends('layout.'.config('layout.app_name').'.app')

@section('content_body')
<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <form class="forms-sample" action="{{ $module->permalink.'/update' }}" id="form-table" method="post"
                    autocomplete="off">
                    @csrf
                    <div class="form-group row">
                        <div class="col-xl-6">
                            <label for="" class="required">Group Name</label>
                            <div class="controls">
                                <input type="text" name="group_name"
                                    class="form-control @error('group_name') error-input @enderror "
                                    value="{{ old('group_name') ?: $data_edit->gname }}" style="">
                                @error('group_name') {!! required_field($message) !!} @enderror
                            </div>
                        </div>
                    </div>
                    {!! $page->module_list(0, '', $data_edit->roles ? json_decode($data_edit->roles) : null) !!}
                    <br>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('content_script')
<script type="text/javascript">
$(function() {
    $('.toggle-switch-checkbox').click(function(e) {
        switch_checkbox.init($(this));
    });

    $('.toggle-switch-checkbox').ready(function() {
        var $this = $('.toggle-switch-checkbox');
        $this.map(function(i, v) {
            switch_checkbox.init($(this));
        });
    });

    switch_checkbox = {
        init: function($this) {
            var idArr = $this.attr('id').split('-'),
                parent = $this.attr('data-parent');
            if ($this.attr('is-parent') == 'true') {
                if ($this.prop('checked')) {
                    $('input:checkbox[data-parent=' + idArr[1] + ']').prop('disabled', false);
                } else {
                    $('input:checkbox[data-parent=' + idArr[1] + ']').prop('disabled', true);
                    $('input:checkbox[data-parent=' + idArr[1] + ']').prop('checked', false);
                }
            }

            if (idArr[0] == 'view' && $this.is(':checked') == false) {
                $('#create-' + idArr[1]).prop('checked', false);
                $('#alter-' + idArr[1]).prop('checked', false);
                $('#drop-' + idArr[1]).prop('checked', false);
            }

            if (idArr[0] != 'view' && $this.is(':checked') == true) {
                $('#view-' + idArr[1]).prop('checked', true);
            }
        }
    }
});
</script>
@endsection