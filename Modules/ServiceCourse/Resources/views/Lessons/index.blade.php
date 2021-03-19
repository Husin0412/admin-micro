@extends('layout.'.config('layout.app_name').'.app')

@section('content_style')

@endsection

@section('content_body')

<!-- <div id="overlay">
    <div class="video-convert">
        <iframe id="ytplayer" type="text/html" width="90%" height="100%"
            src="http://www.youtube.com/embed/BGyuKpfMB9E" frameborder="0" allowfullscreen></iframe>
    </div>
</div> -->

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
                <th style="" class="text-white">Create Date</th>
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
                <td class="text-capitalize">
                    <div class="div-video d-flex justify-content-lg-between hover-pointer">
                        <span class="my-auto" data-video="{{$val['video']}}"> {{$val['name']}} </span>
                        <img class="div-video-img" src="{{ asset('assets/images/svg/icon.play.svg') }}" alt="play"
                            style="height:28px">
                    </div>
                </td>
                <td class="text-capitalize">{{$val['chapter_name']}}</td>
                <td class="text-capitalize">{{$val['course_name']}}</td>
                <td class="text-capitalize">{{$val['mentor_name']}}</td>
                <td class="">{{$val['created_at']}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</form>

@endsection

@section('content_script')

@endsection