@extends('layout.'.config('layout.app_name').'.app')

@section('content_body')
<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
            <form class="form-sample" action="{{ $module->permalink.'/save' }}" id="form-table" method="post"
                autocomplete="off">
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
                            <label class="col-sm-3 col-form-label">Email</label>
                            <div class="col-sm-9">
                                <input type="email"
                                    class="form-control @error('email') error-input @enderror  @if(Session::has('email_exist') && !empty(Session::get('email_exist'))) error-input @endif"
                                    name="email" value="{{ old('email') ?: ''}}">
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
                                    value="{{ old('profession') ?: ''}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Roles</label>
                            <div class="col-sm-9">
                                <select class="form-control @error('roles') error-input @enderror" name="roles">
                                    <option value="">select roles</option>
                                    @foreach($roles as $key => $val_roles)
                                    <option value="{{$val_roles}}" @if(old('roles') && old('roles') !=="" &&
                                        old('roles')===$val_roles ) selected @endif>{{$val_roles}}</option>
                                    @endforeach
                                </select>
                                @error('roles') {!! required_field($message) !!} @enderror
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
                                    name="password" value="{{ old('password') ?: ''}}">
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
                                    name="re-password" value="{{ old('re-password') ?: ''}}">
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