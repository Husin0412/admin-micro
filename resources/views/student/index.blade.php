@extends('layout.'.config('layout.app_name').'.app')

@section('content_style')

@endsection

@section('content_body')

<form action="{{ $module->permalink }}" id="form-table" method="post" autocomplete="off"> @csrf
    <table class="table table-bordered table-hover data-table">
        <thead class="bg-indigo">
            <tr>
                <th style="" class="text-white">
                    <a href="javascript:void(0)" class="" onclick="radioNetral()"> <i
                            class="mdi mdi-18px mdi-minus-circle-outline text-white"></i> <span class="text-white">
                            Remove </span>
                    </a>
                </th>
                <th style="" class="text-white">Avatar</th>
                <th style="" class="text-white">Name</th>
                <th style="" class="text-white">Email</th>
                <th style="" class="text-white">Role</th>
                <th style="" class="text-white">Profession</th>
                <th style="" class="text-white">Join Date</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($data as $key => $val)
            <tr>
                <td>
                    <div class="form-radio form-radio-flat">
                        <label class="form-check-label">
                            <input type="radio" class="child-check" name="data_id[{{$val['id']}}]"
                                id="{{ 'child-'.$val['id'] }}">
                            <i class="input-helper"></i></label>
                    </div>
                </td>
                <td class=""><img src="{{$val['avatar']}}" alt=""></td>
                <td> {{$val['name']}} </td>
                <td> {{$val['email']}} </td>
                <td class=""> <span class="badge badge-secondary">{{$val['role']}}</span></td>
                <td class="">{{$val['profession']}}</td>
                <td class="">{{$val['created_at']}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</form>


@endsection

@section('content_script')

@endsection