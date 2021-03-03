@extends('layout.'.config('layout.app_name').'.app')

@section('content_style')

@endsection

@section('content_body')

@if($get_data->count() > 0 )
<form action="{{ $module->permalink.'/add' }}" id="form-table" method="post" autocomplete="off"> @csrf
    <table class="table table-bordered table-hover data-table">
        <thead class="bg-indigo">
            <tr>
                <th rowspan="2" style="vertical-align: middle" class="text-white">
                    @if($page->fetch_role('alter', $module) === TRUE || $page->fetch_role('drop', $module) ===
                    TRUE)
                    <a href="javascript:void(0)" class="" onclick="radioNetral()"> <i
                            class="mdi mdi-18px mdi-minus-circle-outline text-white"></i> <span class="text-white">
                            Remove </span>
                    </a>
                    @else
                    #
                    @endif
                </th>
                <th rowspan="2" style="font-size:100%; vertical-align: middle" class="text-white">Group Name</th>
                <th rowspan="2" style="font-size:100%; vertical-align: middle" class="text-white">Total User</th>
                <th colspan="4" style="font-size:100%;" class="text-white">Module</th>
            </tr>
            <tr>
                <th style="font-size:100%;" class="text-white">View</th>
                <th style="font-size:100%;" class="text-white">Create</th>
                <th style="font-size:100%;" class="text-white">Alter</th>
                <th style="font-size:100%;" class="text-white">Drop</th>
            </tr>
        </thead>
        <tbody>
            {{--  name="data_id[{{ $val->guid }}]" id="{{ 'child-'.$val->guid }}" --}}

            @php $no = 1; @endphp
            @foreach($get_data->get() as $key => $val)
            @php $_roles = $val->roles ? json_decode($val->roles) : null @endphp
            <tr>
                <td>
                    @if($page->fetch_role('alter', $module) === TRUE || $page->fetch_role('drop', $module) ===
                    TRUE)
                    <div class="form-radio form-radio-flat">
                        <label class="form-check-label">
                            <input type="radio" class="child-check" name="data_id[{{$val->guid}}]"
                                id="{{ 'child-'.$val->guid }}">
                            <i class="input-helper"></i></label>
                    </div>
                    @else
                    {{$no++}}
                    @endif
                </td>
                <td> {{$val->gname}} </td>
                <td> {{$query->get_total_user_group($val->guid)}} </td>
                <td class="center">
                    {{ $_roles != null && isset($_roles->view) ? count(explode(',', $_roles->view)) : 0 }}</td>
                <td class="center">
                    {{ $_roles != null && isset($_roles->create ) ? count(explode(',', $_roles->create)) : 0 }}</td>
                <td class="center">
                    {{ $_roles != null && isset($_roles->alter ) ? count(explode(',', $_roles->alter)) : 0 }}</td>
                <td class="center">
                    {{ $_roles != null && isset($_roles->drop ) ? count(explode(',', $_roles->drop)) : 0 }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</form>
@else
<p class="card-description">Empty ..</p>
@endif

@endsection

@section('content_script')

@endsection