@extends('layout.'.config('layout.app_name').'.app')

@section('content_style')

@endsection

@section('content_body')
<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
            <form class="form-sample" action="{{ $module->permalink.'/save' }}" id="form-table" method="post"
                autocomplete="off" novalidate="novalidate" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label required">Name</label>
                            <div class="col-sm-9">
                                <input type="text"
                                    class="form-control @error('name') error-input @enderror @if(Session::has('name_exist') && !empty(Session::get('name_exist'))) error-input @endif"
                                    name="name" value="{{ old('name') ?: ''}}">
                                @if(Session::has('name_exist') && !empty(Session::get('name_exist'))) {!!
                                required_field(Session::get('name_exist')) !!} @endif
                                @error('name') {!! required_field($message) !!} @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label required">Courses</label>
                            <div class="col-sm-9">
                                <div class="dropdown text-capitalize">
                                    <button
                                        class="btn btn-sm btn-with dropdown-toggle dropdown-parent form-control @error('course_id') error-input @enderror"
                                        type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        select course
                                        <i class="mdi mdi-18px mdi-chevron-down float-right"
                                            style="position: absolute; right: 0px;"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdown_user"
                                        style="border: 1px solid rgb(210 210 210); width: 100%;">
                                        <input type="search" class="form-control search" placeholder="search.."
                                            autofocus="autofocus">
                                        <div class="menuItems">
                                            <!--  -->
                                        </div>
                                    </div>
                                </div>
                                @error('course_id') {!! required_field($message) !!} @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label required">Video</label>
                            <div class="col-sm-9">
                                <input type="text" name="video" class="form-control @error('video') error-input @enderror" value="{{old('video')}}" >
                                @error('video') {!! required_field($message) !!} @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label required">Chapter</label>
                            <div class="col-sm-9">
                                <select class="form-control @error('chapter_id') error-input @enderror text-capitalize"
                                    name="chapter_id" id="select-chapter">
                                    <option value="" id="primary-option">select chapter</option>
                                </select>
                                @error('chapter_id') {!! required_field($message) !!} @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('content_script')
<script>
$(document).ready(function() {
    /**/
    $('.dropdown').each(function(index, dropdown) {
        //Find the input search box
        let search = $(dropdown).find('.search');
        //Capture the event when user types into the search box
        $(search).on('input', function() {
            $('.dropdown-item').remove()
            $('.dropdown_empty').remove()
            if ($(search).val() !== "") {
                $.ajax({
                    type: "post",
                    url: '{{env('SERVICE_GATEWAY_URL')}}' + 'courses/search',
                    headers: {
                        "Authorization": '{{session("user_token")}}'
                    },
                    data: {
                        data: $(search).val().trim().toLowerCase()
                    },
                    cache: false,
                    success: function(resp) {
                        if (resp.data.length === 0) {
                            $('.dropdown_empty').remove()
                            $('.dropdown-menu').append(
                                '<div class="dropdown-header dropdown_empty">No entry found </div>'
                                )
                        }
                        $('.dropdown-item').remove()
                        for (i = 0; i < resp.data.length; i++) {
                            $('.menuItems').append(
                                '<input type="button" class="dropdown-item" type="button" value="' +
                                resp.data[i]['name'] + '" data-id="' + resp
                                .data[i]['id'] + '" />');
                        }
                    },
                    error: function(err) {
                        console.log("error")
                    }
                })
            }
        });
        //For every word entered by the user, check if the symbol starts with that word
        //If it does show the symbol, else hide it
        //If the user clicks on any item, set the title of the button as the text of the item
        $(dropdown).find('.dropdown-menu').find('.menuItems').on('click', '.dropdown-item', function() {
            $(dropdown).find('.dropdown-parent').text($(this)[0].value);
            $(dropdown).find('.dropdown-parent').append(
                '<input type="hidden" name="course_id" value="' + $(this)[0].dataset.id +
                '"/>');
            $(dropdown).find('.dropdown-parent').dropdown('toggle');
            var course_ids = $(this)[0].dataset.id;
            /*added chapter*/ 
            $.ajax({
                    type: "get",
                    url: '{{env('SERVICE_GATEWAY_URL')}}' + 'chapters',
                    headers: {
                        "Authorization": '{{session("user_token")}}'
                    },
                    data: { course_id: $(this)[0].dataset.id },
                    cache: false,
                    success: function(resp) {
                        if(resp.data.length > 0) {
                            $('.item-option').remove()
                            $('#primary-option').html("select chapter")
                            resp.data.map(function(item, index) {
                                $('#select-chapter').append('<option class="item-option" value="'+item.id+'">'+item.name+'</option>')
                            })
                        } else {
                           $('.item-option').remove()
                           $('#primary-option').html("the chapter is still empty")
                        }
                    },
                    error: function(err) { console.log("error", err) }
                })
        })

        /*old*/
        @if(old('course_id'))
        @php
        $_course = detail_courses(session('user_token'), old('course_id'));
        if ($_course['status'] === "success") {
            $course = $_course['data'];
        }
        @endphp
        $(dropdown).find('.dropdown-parent').text('{{$course["name"]}}');
        $(dropdown).find('.dropdown-parent').append(
            '<input type="hidden" name="course_id" value="{{$course["id"]}}"/>');
        @endif
    });

});
</script>
@endsection