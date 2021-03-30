@extends('layout.'.config('layout.app_name').'.app')

@section('content_style')

@endsection

@section('content_body')

<div class="table-responsive">
    <form action="{{ $module->permalink }}" id="form-table" method="post" autocomplete="off"> @csrf
        <table class="table table-bordered table-hover data-table">
            <thead class="bg-indigo">
                <tr>
                    <th rowspan="2" class="text-white" style="vertical-align: middle">
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
                    <th rowspan="2" class="text-white" style="vertical-align: middle">Name</th>
                    <th rowspan="2" class="text-white" style="vertical-align: middle">Thumbnail</th>
                    <th rowspan="2" class="text-white" style="vertical-align: middle">Type</th>
                    <th rowspan="2" class="text-white" style="vertical-align: middle">Status</th>
                    <th rowspan="2" class="text-white" style="vertical-align: middle">Price</th>
                    <th rowspan="2" class="text-white" style="vertical-align: middle">Level</th>
                    <th rowspan="2" class="text-white" style="vertical-align: middle">Certificate</th>
                    <th rowspan="2" class="text-white" style="vertical-align: middle">description</th>
                    <th rowspan="2" class="text-white" style="vertical-align: middle">Mentor</th>
                    <th rowspan="2" class="text-white" style="vertical-align: middle">Create Date</th>
                    <th colspan="3" class="text-white" style="text-align: center; vertical-align: middle">addition</th>
                </tr>
                <tr>
                    <th class="text-white" style="vertical-align: middle">Image Course</th>
                    <th class="text-white" style="vertical-align: middle">Chapter</th>
                    <th class="text-white" style="vertical-align: middle">Lesson</th>
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
                    <td class="text-capitalize">{{$val['name']}}</td>
                    <td><img src="{{$val['thumbnail']}}" alt="thumbnail"></td>
                    <td class="text-capitalize">{!! $val['type'] !!}</td>
                    <td class="text-capitalize">{!! $val['status'] !!}</td>
                    <td class="price-input-Rp"> {{$val['price'].'00'}} </td>
                    <td class="text-capitalize"> {!! $val['level'] !!} </td>
                    <td class="text-capitalize"> {!! $val['certificate'] !!} </td>
                    <td class="text-capitalize"> {!! $val['description'] !!} </td>
                    <td class="text-capitalize"> {{$val['mentors']}} </td>
                    <td>{{$val['created_at']}}</td>
                    <td>{{$val['img_course']}}</td>
                    <td>{{$val['chapter']}}</td>
                    <td>{{$val['lesson']}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </form>
</div>

@endsection

@section('content_script')

@endsection