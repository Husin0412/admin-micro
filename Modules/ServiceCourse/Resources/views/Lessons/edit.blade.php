@extends('layout.'.config('layout.app_name').'.app')

@section('content_style')

@endsection

@section('content_body')
<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
            <form class="form-sample" action="{{ $module->permalink.'/update' }}" id="form-table" method="post"
                autocomplete="off" novalidate="novalidate" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{ $data_edit['id']}}" >
                <input type="hidden" name="chapter_id" value="{{ $data_edit['chapter_id']}}" >
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label required">Name</label>
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
                            <label class="col-sm-3 col-form-label required">Video</label>
                            <div class="col-sm-9">
                                <input type="text" name="video" class="form-control @error('video') error-input @enderror" value="{{old('video') ?: $data_edit['video']}}" >
                                @error('video') {!! required_field($message) !!} @enderror
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

</script>
@endsection