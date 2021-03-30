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
                <th style="" class="text-white">Lesson</th>
                <th style="" class="text-white">Chapter</th>
                <th style="" class="text-white">Course</th>
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
                            <input type="radio" class="child-check" name="data_id[{{$val['chapter_id']}}]"
                                id="{{ 'child-'.$val['chapter_id'] }}">
                            <i class="input-helper"></i></label>
                    </div>
                    @else
                    {{$no++}}
                    @endif
                </td>
                <td>
                    <select class="form-control text-capitalize parent-option" name="lesson_id[]">
                        <option selected value="" class="select-chapter">select lesson</option>
                        @foreach($val['lesson'] as $keys => $values)
                        <option value="{{$values['id']}}" class="child-option" id="{{'option-'.$values['id']}}">
                            {{$values['name']}} </option>
                        @endforeach
                    </select>
                </td>
                <td class="text-capitalize">
                    <div class="dropdown">
                        <button class="form-control dropdown-toggle" type="button" id="dropdownMenuButton1"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> {{$val['chapter_name']}}
                        </button>
                        <div class="dropdown-menu w-full" aria-labelledby="dropdownMenuButton1" style="">
                            <h6 class="dropdown-header">Lessons Video</h6>
                            <div class="dropdown-divider"></div>
                            @foreach($val['lesson'] as $keys => $values)
                            <div class="dropdown-item div-video d-flex justify-content-lg-between hover-pointer">
                                <span class="my-auto" data-video="{{$values['video']}}"> {{$values['name']}} </span>
                                <img class="div-video-img" src="{{ asset('assets/images/svg/icon.play.svg') }}"
                                    alt="play" style="height:28px">
                            </div>
                            @endforeach
                        </div>
                    </div>
                </td>
                <td class="text-capitalize">{{$val['course_name']}}</td>
                <td class="text-capitalize">{{$val['mentor_name']}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</form>

@endsection

@section('content_script')

@endsection