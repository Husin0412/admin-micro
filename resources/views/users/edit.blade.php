@extends('layout.'.config('layout.app_name').'.app')

@section('content_body')
<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
            <form class="form-sample" action="{{ $module->permalink.'/update' }}" id="form-table" method="post"
                autocomplete="off" novalidate="novalidate" enctype="multipart/form-data" >
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
                            <label class="col-sm-3 col-form-label">Email</label>
                            <div class="col-sm-9">
                                <input type="email"
                                    class="form-control @error('email') error-input @enderror  @if(Session::has('email_exist') && !empty(Session::get('email_exist'))) error-input @endif"
                                    name="email" value="{{ old('email') ?: $data_edit['email']}}">
                                @if(Session::has('email_exist') && !empty(Session::get('email_exist'))) {!!
                                required_field(Session::get('email_exist')) !!} @endif
                                @error('email') {!! required_field($message) !!} @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Profession</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="profession"
                                    value="{{ old('profession') ?: $data_edit['profession']}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label ">Avatar</label>
                            <img src="{{$data_edit['avatar'] ?: url('assets/images/null.png')}}" alt="" class="col-sm-2 border" height="55">
                            <div class="col-sm-7">
                            <input type="hidden" name="avatar-existing" value="{{$data_edit['avatar'] ?: 'hei'}}">
                            <input type="file" class="" name="avatar" accept="image/x-png,image/jpeg,image/jpg">
                                @error('avatar') {!! required_field($message) !!} @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Password</label>
                            <div class="col-sm-9 parent-mdi">
                                <input class="form-control @error('password') error-input @enderror" type="password"
                                    name="password" value="{{ old('password') ?: ''}}" placeholder="********">
                                <div class="eye-mdi" data-name="password">
                                    <i class="mdi mdi-eye-off text-secondary" data-name="password"></i>
                                </div>
                                @error('password') {!! required_field($message) !!} @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Re Password</label>
                            <div class="col-sm-9 parent-mdi">
                                <input class="form-control @error('re-password') error-input @enderror" type="password"
                                    name="re-password" value="{{ old('re-password') ?: ''}}" placeholder="********">
                                <div class="eye-mdi" data-name="re-password">
                                    <i class="mdi mdi-eye-off text-secondary"></i>
                                </div>
                                @error('re-password') {!! required_field($message) !!} @enderror
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
@endsection