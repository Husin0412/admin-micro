@extends('layout.'.config('layout.app_name').'.app')

@section('content_style')

@endsection

@section('content_body')

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
                <th style="" class="text-white">Course Name</th>
                <th style="" class="text-white">Course Thumbnail</th>
                <th style="" class="text-white">Chapter</th>
                <th style="" class="text-white">Mentor</th>
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
                            <input type="radio" class="child-check" name="data_id[{{$val['course_id']}}]"
                                id="{{ 'child-'.$val['course_id'] }}">
                            <i class="input-helper"></i></label>
                    </div>
                    @else
                    {{$no++}}
                    @endif
                </td>
                <td> <img src="{{$val['thumbnail_course']}}" alt="course img"></td>
                <td class="text-capitalize">{{$val['name_course']}}</td>
                <td>
                <select class="form-control text-capitalize parent-option" name="chapter_id[]" >
                <option selected value="" class="select-chapter">select chapter</option>
                @foreach($val['chapter'] as $key => $value)
                <option value="{{$value['id']}}" class="child-option" id="{{'option-'.$value['id']}}"> {{$value['name']}} </option>
                @endforeach
                </select>
                </td>
                <td>{{$val['mentor_name']}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</form>

@endsection

@section('content_script')

@endsection