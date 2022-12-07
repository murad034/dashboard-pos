<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="csrf-token" content="{{ csrf_token() }}"/>
<title>
    {!! \App\Models\Config::find(1)->app_name_abv !!} | @yield('title')
</title>
<link rel="shortcut icon" href="{{ asset(\App\Models\Config::find(1)->logo_icon) }}" type="image/x-icon"/>
<!-- Tell the browser to be responsive to screen width -->
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<!-- Bootstrap 3.3.7 -->
{{--<link rel="stylesheet" href="{{ asset('assets/adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">--}}
<!-- Bootstrap 5.0 -->
<link href="{{ asset('dist/css/bootstrap.css') }}" rel="stylesheet">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{ asset('dist/css/all.css') }}">
{{--<link rel="stylesheet" href="{{ asset('assets/adminlte/bower_components/font-awesome/css/font-awesome.min.css') }}">--}}
<!-- Ionicons -->
<link rel="stylesheet" href="{{ asset('assets/adminlte/bower_components/Ionicons/css/ionicons.min.css') }}">
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('assets/adminlte/bower_components/select2/dist/css/select2.min.css') }}">
<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('assets/adminlte/dist/css/AdminLTE.min.css') }}">
<link rel="stylesheet" href="{{ asset('dist/css/AdminLTE_add.css') }}">
{{--<link rel="stylesheet" href="{{ asset('dist/css/AdminLTE.min.css') }}">--}}
<!-- adminlte Skins. -->
<link rel="stylesheet" href="{{ asset('assets/adminlte/dist/css/skins/_all-skins.min.css') }}">
<!-- Morris chart -->
{{--<link rel="stylesheet" href="{{ asset('assets/adminlte/bower_components/morris.js/morris.css') }}">--}}
<!-- jvectormap -->
<link rel="stylesheet" href="{{ asset('assets/adminlte/bower_components/jvectormap/jquery-jvectormap.css') }}">
<!-- Date Picker -->
<link rel="stylesheet"
      href="{{ asset('assets/adminlte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
<!-- Daterange picker -->
<link rel="stylesheet"
      href="{{ asset('assets/adminlte/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
<!-- bootstrap wysihtml5 - text editor -->
<link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/iCheck/square/blue.css') }}">
<link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}">
<!-- Google Font -->
<link rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
{{-- select 2--}}
<link href="{{ asset('dist/css/select2.min.css') }}" rel="stylesheet"/>

<!-- CSS Custom -->
{{--<link rel="stylesheet" href="{{ asset('assets/custom/style.css') }}">--}}
<!-- jQuery 3 -->
{{--<script src="{{ asset('assets/adminlte/bower_components/jquery/dist/jquery.min.js') }}"></script>--}}
<script src="{{ asset('dist/js/jquery-3.5.1.min.js') }}"></script>
<script src="{{ asset('dist/js/jquery.mCustomScrollbar.concat.min.js') }}"></script>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>

<link href="https://code.jquery.com/ui/1.13.0/themes/smoothness/jquery-ui.css" media="all"
      rel="stylesheet" type="text/css">
{{--data Table CSS--}}
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables/css/dataTables.bootstrap.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css"/>
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables/css/buttons.dataTables.min.css') }}">

<link rel="stylesheet" href="{{ asset('dist/css/animate.min.css') }}">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>

<!-- Font Awesome -->
{{--<link rel="stylesheet" href="{{ asset('dist/css/all.css') }}">--}}
<!-- AdminLTE Skins. Choose a skin from the css/skins
     folder instead of downloading all of them to reduce the load. -->
<link rel="stylesheet" href="{{ asset('dist/css/skins/skin-blue.min.css') }}">

<link rel="stylesheet" href="{{ asset('dist/css/sweetalert.css') }}">

<link rel="stylesheet" href="{{ asset('dist/css/fakeloader.css') }}">
<link rel="stylesheet" href="{{ asset('dist/css/jquery.mCustomScrollbar.min.css') }}"/>
<link href="{{ asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet"/>


<div id="fakeloader-overlay" class="visible incoming">
    <div class="loader-wrapper-outer">
        <div class="loader-wrapper-inner">
            <span class="logo-lg">
                <img style="width:225px;" src="{{ asset(\App\Models\Config::find(1)->logo_icon)}}" alt="favicon">
            </span>
            <Br><Br>
            <div class="loader"></div>
        </div>
    </div>
</div>
<style>
    .link_menu_page {
        color: #222d32;
    }

    .caixa-alta {
        text-transform: uppercase;
    }

    .caixa-baixa {
        text-transform: lowercase;
    }

    .input-text-center {
        text-align: center;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: dodgerblue !important;
    }
</style>

<script>
    let csrfToken = "{{ csrf_token() }}";
    let base_url = '{{ URL::asset('') }}';
    $(function () {
        $.fn.datepicker.dates['pt-br'] = {
            days: ["Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado"],
            daysShort: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sáb"],
            daysMin: ["Do", "Se", "Te", "Qu", "Qu", "Se", "Sa"],
            months: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"],
            monthsShort: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
            today: "Hoje",
            monthsTitle: "Meses",
            clear: "Limpar",
            format: "dd/mm/yyyy"
        };
    });
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        }
    });

</script>

@yield('layout_css')
