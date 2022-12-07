@section('title', 'Login')

    <!DOCTYPE html>
<html lang="en">
<head>
@include('layouts.AdminLTE._includes._head')
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('dist/css/animate/animate.css') }}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('dist/css/css-hamburgers/hamburgers.min.css') }}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('dist/css/tilt/util.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dist/css/tilt/main.css') }}">
</head>
<body>

<div class="limiter">
    <div class="container-login100">
        <div class="wrap-login100">
            {{--            <div class="login100-pic js-tilt">--}}
            <div class="login100-pic">
                <img src="{{ asset(\App\Models\Config::find(1)->logo_background) }}" alt="IMG" style="width:100%; height:100%;">
            </div>

            <form class="login100-form validate-form" method="POST" action="{{ route('login') }}">
                @csrf
                <span class="login100-form-title">
						<img src="{{ asset(\App\Models\Config::find(1)->logo_icon) }}" alt="IMG" width="162" height="162">
					</span>

                <div class="wrap-input100 validate-input" data-validate="Valid email is required: ex@abc.xyz">
                    <input class="input100" type="text" name="email" placeholder="Email" id="email"
                           value="{{ old('email') }}" autofocus required="" AUTOCOMPLETE='off'>
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
							<i class="fa fa-user" aria-hidden="true"></i>
						</span>
                </div>

                <div class="wrap-input100 validate-input" data-validate="Password is required">
                    <input class="input100" type="password" name="password" placeholder="Password" id="password"
                           required="" AUTOCOMPLETE='off'>
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
                </div>

                <div class="container-login100-form-btn">
                    <button type="submit" class="login100-form-btn">
                        Login
                    </button>
                </div>

                {{--                <div class="text-center p-t-12">--}}
                {{--						<span class="txt1">--}}
                {{--							Forgot--}}
                {{--						</span>--}}
                {{--                    <a class="txt2" href="#">--}}
                {{--                        Username / Password?--}}
                {{--                    </a>--}}
                {{--                </div>--}}

                {{--                <div class="text-center p-t-136">--}}
                {{--                    <a class="txt2" href="#">--}}
                {{--                        Create your Account--}}
                {{--                        <i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>--}}
                {{--                    </a>--}}
                {{--                </div>--}}
            </form>
        </div>
    </div>
</div>


@include('layouts.AdminLTE._includes._script_footer')

<script src="{{ asset('js/auth/login/index.js') }}"></script>

</body>

</html>
