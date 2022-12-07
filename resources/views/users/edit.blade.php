@extends('layouts.AdminLTE.index')

@section('icon_page', 'pen')

@section('title', 'Edit User')

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
                        <form action="{{ route('user.update',$user->user_id) }}" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" name="_method" value="put">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                        <label for="nome">Name</label>
                                        <input type="text" name="name" class="form-control" maxlength="30" minlength="4"
                                               placeholder="Name" required="" autofocus value="{{$user->name}}">
                                        @if($errors->has('name'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                                        <label for="nome">E-mail</label>
                                        <input type="email" name="email" class="form-control" placeholder="E-mail"
                                               required="" value="{{$user->email}}">
                                        @if($errors->has('email'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group {{ $errors->has('roles') ? 'has-error' : '' }}">
                                        <label for="nome">Permission Group</label>
                                        <select name="roles[]" class="form-control select2" multiple="multiple"
                                                data-placeholder="Permission Group">
                                            @foreach($roles as $role)
                                                @if($role->role_id != 1)
                                                    @if(in_array($role->role_id, $roles_ids))
                                                        <option value="{{ $role->role_id}}"
                                                                selected="true"> {{ $role->name}} </option>
                                                    @else
                                                        <option value="{{ $role->role_id}}"> {{ $role->name}} </option>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </select>
                                        @if($errors->has('roles'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('roles') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>
                                            <input type="hidden" name="active" value="0">
                                            <input type="checkbox" name="active" value="1" class="minimal" id="icheck"
                                                   @if($user->active == true)
                                                   checked
                                                @endif
                                            >
                                            Active
                                        </label>
                                    </div>
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

@section('layout_js')
    <script>

        $(function () {
            $('.select2').select2({
                "language": {
                    "noResults": function () {
                        return "No records found.";
                    }
                }
            });

            $('#icheck').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue'
            });
        });

    </script>

@endsection
