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
                            <label class="col-sm-3 col-form-label">Name</label>
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
                            <label class="col-sm-3 col-form-label">Courses</label>
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
            </form>
        </div>
    </div>
</div>

@endsection

@section('content_script')
<script>
$(document).ready(function() {
    /*wysihtml5*/

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
        })

        /*old*/
        @if(old('course_id'))
        @php
        $_mentor = detail_chapters(session('user_token'), old('course_id'));
        if ($_mentor['status'] === "success") {
            $mentor = $_mentor['data'];
        }
        @endphp
        $(dropdown).find('.dropdown-parent').text('{{$mentor["name"]}}');
        $(dropdown).find('.dropdown-parent').append(
            '<input type="hidden" name="course_id" value="{{$mentor["id"]}}"/>');
        @endif
    });

});
</script>
@endsection