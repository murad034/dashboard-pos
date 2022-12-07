<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.AdminLTE._includes._head')
</head>
<body
    class="hold-transition skin-{{ \App\Models\Config::find(1)->skin }} {{ \App\Models\Config::find(1)->layout }} sidebar-mini">
<style>
    #menu_sup_corpo {
        background-color: #d2d6de;
        margin-bottom: 0;
        padding-bottom: 0;
        z-index: 1;
        margin-top: 20px;
    }

    #menu_sup_corpo .navbar-header.a {
        color: #fff;
    }
</style>
<div class="wrapper">

    @include('layouts.AdminLTE._includes._menu_superior')


    @include('layouts.AdminLTE._includes._menu_lateral')

    <div class="content-wrapper">
        <nav class="navbar navbar-expand-lg" id="menu_sup_corpo">
            <div class="container-fluid" style="padding-left:15px;">
                <a href="" class="navbar-brand" id="" style="color:#222d32;'"><i
                        class="fa fa-@yield('icon_page')"></i> @yield('title')</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto">

                        @yield('menu_pagination')

                    </ul>
                </div>
            </div>
        </nav>

        @if(Session::has('flash_message'))

            <div class="{{ Session::get('flash_message')['class'] }}" style="padding: 10px 20px;" id="flash_message">
                <div style="color: #fff; display: inline-block; margin-right: 10px;">
                    {!! Session::get('flash_message')['msg'] !!}
                </div>
            </div>

        @endif

        <section class="content">
            <div class="row">
                <div class="col-md-12">

                    @yield('content')

                </div>
            </div>
        </section>

    </div>

    @include('layouts.AdminLTE._includes._footer')

</div>

@include('layouts.AdminLTE._includes._script_footer')
<script>
    let databaseName = '{{DB::connection('mongodb')->getDatabaseName()}}';
</script>
</body>
</html>
