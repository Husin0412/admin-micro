@extends('layout.'.config('layout.app_name').'.app')

@section('content_style')

@endsection

@section('content_body')
@php use Carbon\Carbon; @endphp

<form action="{{ $module->permalink }}" id="form-table" method="post" autocomplete="off"> @csrf
    <table class="table table-bordered table-hover data-table">
        <thead class="bg-indigo">
            <tr>
                <th style="" class="text-white">
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
                <th style="" class="text-white">Profile</th>
                <th style="" class="text-white">Name</th>
                <th style="" class="text-white">Email</th>
                <th style="" class="text-white">Profession</th>
                <th style="" class="text-white">Join Date</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($data as $key => $val)
            <tr>
                <td>
                    @if($page->fetch_role('alter', $module) === TRUE || $page->fetch_role('drop', $module) ===
                    TRUE)
                    <div class="form-radio form-radio-flat">
                        <label class="form-check-label">
                            <input type="radio" class="child-check" name="data_id[{{$val['id']}}]"
                                id="{{ 'child-'.$val['id'] }}">
                            <i class="input-helper"></i></label>
                    </div>
                    @else
                    {{$no++}}
                    @endif
                </td>
                <td><img src="{{$val['profile']}}" alt=""></td>
                <td> {{$val['name']}} </td>
                <td> {{$val['email']}} </td>
                <td>{{$val['profession']}}</td>
                <td>{{Carbon::parse($val['created_at'])->format('M-d-Y h:m A')}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</form>

@endsection

@section('content_script')

@endsection