@extends('layout.'.config('layout.app_name').'.app')

@section('content_style')
<script src="https://cdn.tiny.cloud/1/mmgi5oqjdywhngo8rmbcwu1wv8xpwqnnczaoez1slsxnk0pc/tinymce/5/tinymce.min.js"
    referrerpolicy="origin"></script>
@endsection

@section('content_body')
<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
            <form class="form-sample" action="{{ $module->permalink.'/update' }}" id="form-table" method="post"
                autocomplete="off" novalidate="novalidate" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{$data_edit['id']}}">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Name</label>
                            <div class="col-sm-9">
                                <input type="text"
                                    class="form-control @error('name') error-input @enderror @if(Session::has('name_exist') && !empty(Session::get('name_exist'))) error-input @endif"
                                    name="name" value="{{ old('name') ?: $data_edit['name']}}">
                                @if(Session::has('name_exist') && !empty(Session::get('name_exist'))) {!!
                                required_field(Session::get('name_exist')) !!} @endif
                                @error('name') {!! required_field($message) !!} @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Certificate</label>
                            <div class="col-sm-9">
                                <select class="form-control @error('certificate') error-input @enderror text-capitalize"
                                    name="certificate">
                                    <option value="">select certificate</option>
                                    <option value="v1" @if( old('certificate') && old('certificate') !=="" &&
                                        old('certificate')==='v1' ) selected @elseif($data_edit['certificate'] === 1 ) selected @endif>yes</option>
                                    <option value="v0" @if( old('certificate') && old('certificate') !=="" &&
                                        old('certificate')==='v0' ) selected @elseif($data_edit['certificate'] === 0 ) selected @endif>no</option>
                                </select>
                                @error('certificate') {!! required_field($message) !!} @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Thumbnail</label>
                            <img src="{{$data_edit['thumbnail'] ?: url('assets/images/null.png')}}" alt=""
                                class="col-sm-2 border" height="55">
                            <div class="col-sm-7">
                                <input type="hidden" name="thumbnail-existing" value="{{$data_edit['thumbnail']}}">
                                <input type="file" name="thumbnail" accept="image/x-png,image/jpeg,image/jpg">
                                @error('thumbnail') {!! required_field($message) !!} @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Type</label>
                            <div class="col-sm-9">
                                <select
                                    class="form-control @error('type') error-input @enderror text-capitalize select-type"
                                    name="type">
                                    <option value="">select type</option>
                                    @foreach(config('items.type') as $key => $val)
                                    <option value="{{$val}}" @if( old('type') && old('type') !=="" && old('type')===$val
                                        ) selected @elseif($data_edit['type'] === $val ) selected @endif>{{$val}}</option>
                                    @endforeach
                                </select>
                                @error('type') {!! required_field($message) !!} @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Status</label>
                            <div class="col-sm-9">
                                <select class="form-control @error('status') error-input @enderror text-capitalize"
                                    name="status">
                                    <option value="">select status</option>
                                    @foreach(config('items.status') as $key => $val)
                                    <option value="{{$val}}" @if( old('status') && old('status') !=="" &&
                                        old('status')===$val ) selected @elseif($data_edit['status'] === $val ) selected @endif>{{$val}}</option>
                                    @endforeach
                                </select>
                                @error('status') {!! required_field($message) !!} @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Price</label>
                            <div class="col-sm-9">
                                <input type="text" name="price"
                                    class="form-control price-input-Rp @error('price') error-input @enderror"
                                    value="{{old('price') ?? $data_edit['price'].'00' }}"
                                    placeholder="Rp 0.00" @if(old('type') || $data_edit['type'] !== "premium") disabled @endif>
                                @error('price') {!! required_field($message) !!} @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Level</label>
                            <div class="col-sm-9">
                                <select class="form-control @error('level') error-input @enderror text-capitalize"
                                    name="level">
                                    <option value="">select level</option>
                                    @foreach(config('items.level') as $key => $val)
                                    <option value="{{$val}}" @if( old('level') && old('level') !=="" &&
                                        old('level')===$val ) selected @elseif($data_edit['level'] === $val ) selected @endif>{{$val}}</option>
                                    @endforeach
                                </select>
                                @error('level') {!! required_field($message) !!} @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Mentors</label>
                            <div class="col-sm-9">
                                <div class="dropdown text-capitalize">
                                    <button
                                        class="btn btn-sm btn-with dropdown-toggle dropdown-parent form-control @error('mentor_id') error-input @enderror"
                                        type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        select mentors
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
                                @error('mentor_id') {!! required_field($message) !!} @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="some-textarea form-control" rows="10">{{old('description') ?: $data_edit['description'] }} </textarea>
                    @error('description') {!! required_field($message) !!} @enderror
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
    // $('.some-textarea').wysihtml5();
    tinymce.init({
        selector: '.some-textarea',
        plugins: 'a11ychecker advcode casechange formatpainter linkchecker autolink lists checklist media mediaembed pageembed permanentpen powerpaste table advtable tinycomments tinymcespellchecker',
        toolbar: 'a11ycheck addcomment showcomments casechange checklist code formatpainter pageembed permanentpen table',
        toolbar_mode: 'floating',
        tinycomments_mode: 'embedded',
        tinycomments_author: 'Author name',
    });

    /* - */
    $('.select-type').on('change', function(e) {
        var optionSelected = $("option:selected", this);
        var valueSelected = this.value;
        if (valueSelected === 'premium') {
            $('.price-input-Rp').prop('disabled', false);
        } else if (valueSelected != 'premium') {
            $('.price-input-Rp').prop('disabled', true);
            $('.price-input-Rp').val("");
        }
    });

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
                    url: '{{env('SERVICE_GATEWAY_URL')}}' + 'mentors/search',
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
                '<input type="hidden" name="mentor_id" value="' + $(this)[0].dataset.id +
                '"/>');
            $(dropdown).find('.dropdown-parent').dropdown('toggle');
        })

        /*old*/
        @if(old('mentor_id') || $data_edit['mentor_id'])
        @php
        $mentor_id = old('mentor_id') ? old('mentor_id') : $data_edit['mentor_id'];
        $_mentor = detail_mentors(session('user_token'), $mentor_id);
        if ($_mentor['status'] === "success") {
            $mentor = $_mentor['data'];
        }
        @endphp
        $(dropdown).find('.dropdown-parent').text('{{$mentor["name"]}}');
        $(dropdown).find('.dropdown-parent').append(
            '<input type="hidden" name="mentor_id" value="{{$mentor["id"]}}"/>');
        @endif
    });

});
</script>
@endsection