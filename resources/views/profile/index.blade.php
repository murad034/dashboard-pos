@extends('layouts.AdminLTE.index')

@section('icon_page', 'user')

@section('title', 'User Profile')

@section('content')

    <div class="row">
        <div class="col-md-3">
            <div class="box box-primary">
                <div class="box-body box-profile">
                    @if(file_exists(Auth::user()->avatar))
                        <img src="{{ asset(Auth::user()->avatar) }}" class="profile-user-img img-responsive img-circle">
                    @else
                        <img src="{{ asset('img/config/nopic.png') }}"
                             class="profile-user-img img-responsive img-circle">
                    @endif
                    <h3 class="profile-username text-center">
                        @if(Auth::user('name'))
                            {{ Auth::user()->name }}
                        @endif
                    </h3>
                    @foreach($roles as $role)
                        @if(in_array($role->role_id, $roles_ids))
                            <div class="text-center"><span class="label label-primary">{{ $role->name }}</span></div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile"
                                type="button" role="tab" aria-controls="profile" aria-selected="true">Home
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password"
                                type="button" role="tab" aria-controls="password" aria-selected="false">Profile
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="avatar-tab" data-bs-toggle="tab" data-bs-target="#avatar"
                                type="button" role="tab" aria-controls="avatar" aria-selected="false">Contact
                        </button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <form action="{{ route('profile.update.profile',$user->user_id) }}" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" name="_method" value="put">
                            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                <label for="nome">Name</label>
                                <input type="text" name="name" class="form-control" maxlength="30" minlength="4"
                                       placeholder="Name" required="" value="{{$user->name}}">
                                @if($errors->has('name'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                                <label for="nome">E-mail</label>
                                <input type="email" name="email" class="form-control" placeholder="E-mail" required=""
                                       value="{{$user->email}}">
                                @if($errors->has('email'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-primary" style="margin-top:10px;"><i
                                        class="fa fa-fw fa-save"></i> Save
                                    Profile
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                        <form action="{{ route('profile.update.password',$user->user_id) }}" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" name="_method" value="put">
                            <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                                <label for="nome">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Password"
                                       minlength="6" required="">
                                @if($errors->has('password'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('password-confirm') ? 'has-error' : '' }}">
                                <label for="nome">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control"
                                       placeholder="Confirm Password" minlength="6" required="">
                                @if($errors->has('password-confirm'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('password-confirm') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-primary" style="margin-top:10px;"><i
                                        class="fa fa-fw fa-save"></i> Save
                                    Password
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="avatar" role="tabpanel" aria-labelledby="avatar-tab">
                        <form action="{{ route('profile.update.avatar',$user->user_id) }}" method="post"
                              enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="_method" value="put">
                            <div class="form-group {{ $errors->has('avatar') ? 'has-error' : '' }}">
                                <label>Avatar</label>
                                <input type="file" class="form-control-file" name="avatar">
                                @if($errors->has('avatar'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('avatar') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-primary" style="margin-top:10px;"><i
                                        class="fa fa-fw fa-save"></i> Save
                                    Avatar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
