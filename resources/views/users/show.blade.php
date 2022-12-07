@extends('layouts.AdminLTE.index')

@section('icon_page', 'eye')

@section('title', 'View User')

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
                        <div class="col-lg-3 text-center">
                            <br>
                            <img src="{{ asset($user->avatar) }}" class="profile-user-img img-responsive img-circle">
                            <h3 class="profile-username text-center">
                                {{ $user->name }}
                            </h3>
                            @if($user->active == true)
                                <span class="label label-success">Active</span>
                            @else
                                <span class="label label-danger">Inactive</span>
                            @endif
                        </div>
                        <div class="col-lg-9">
                            <div class="attachment">
                                <h4><b>E-mail: </b></h4>
                                <span>{{ $user->email }}</span>
                                <h4><b>Permission Group</b></h4>
                                @foreach($roles as $role)
                                    @if(in_array($role->role_id, $roles_ids))
                                        <span class="label label-primary">{{ $role->name }}</span>
                                    @endif
                                @endforeach
                                <br><br>
                                <p class="help-block"><i class="fa fa-clock-o"></i> Created
                                    on: {{$user->created_at->format('d/m/Y H:i') }}</p>
                                <p class="help-block"><i class="fa fa-refresh"></i> Last
                                    update: {{$user->updated_at->format('d/m/Y H:i') }}</p>
                                <br>
                                <div class="float-end">
                                    <a href="{{ route('user.edit.password', $user->user_id) }}"
                                       title="Change Password {{ $user->name }}">
                                        <button type="button" class="btn btn-primary btn-sm btn-flat"><i
                                                class="fa fa-key"></i> Change Password
                                        </button>
                                    </a>
                                    <a href="{{ route('user.edit', $user->user_id) }}" title="Edit {{ $user->name }}">
                                        <button type="button" class="btn btn-warning btn-sm btn-flat"><i
                                                class="fa fa-pen"></i> Edit
                                        </button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection
