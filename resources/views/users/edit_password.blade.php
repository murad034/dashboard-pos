@extends('layouts.AdminLTE.index')

@section('icon_page', 'pen')

@section('title', 'Edit User Password')

@section('menu_pagination')

    <li role="presentation" class="nav-item">
        <a href="{{ route('user') }}" class="nav-link link_menu_page">
            <i class="fa fa-user"></i> Users
        </a>
    </li>

@endsection

@section('content')
    @if ($user->user_id != 1)
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ route('user.update.password',$user->user_id) }}" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" name="_method" value="put">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                                        <label for="nome">Password</label>
                                        <input type="password" name="password" class="form-control"
                                               placeholder="Password" minlength="6" required="">
                                        @if($errors->has('password'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
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
                                </div>
                                <div class="col-lg-12"></div>
                                <div class="col-lg-6">
                                    <p class="text-muted"><b><i class="fa fa-warning"></i></b> Editing password
                                        <b>{{ $user->name }}</b>.</p>
                                </div>
                                <div class="col-lg-6">
                                    <button type="submit" class="btn btn-primary float-end"><i
                                            class="fa fa-fw fa-save"></i> Save
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
