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
                <th style="" class="text-white">Name</th>
                <th style="" class="text-white">Thumbnail</th>
                <th style="" class="text-white">Type</th>
                <th style="" class="text-white">Status</th>
                <th style="" class="text-white">Price</th>
                <th style="" class="text-white">Level</th>
                <th style="" class="text-white">Certificate</th>
                <th style="" class="text-white">description</th>
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
            </tr>
            @endforeach
        </tbody>
    </table>
</form>

@endsection

@section('content_script')

@endsection