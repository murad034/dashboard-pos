<style>
    .dropdown-toggle:hover {
        background: transparent !important;
    }

    .main-header .sidebar-toggle:before {
        content: none !important;
    }

    .select2-container {
        margin-right: 12px;
    }
</style>
<header class="main-header">
    <a href="{{ route('home') }}" class="logo" style="background-color: transparent !important;">
        <div class="logo-lg" style="text-align: center; height: 100%">
            {{-- <lottie-player src="https://assets6.lottiefiles.com/packages/lf20_qv6hu7jh.json"--}}
            {{-- background="rgba(0, 0, 0, 0)"--}}
            {{-- speed="1" style="    width: 35%;--}}
            {{-- height: auto;--}}
            {{-- float: left;--}}
            {{-- margin-left: 10px;--}}
            {{-- margin-top: -15px;" loop autoplay>--}}
            {{-- </lottie-player>--}}
{{--            <img style="width:35%; height: auto; float:left;" src="{{ asset(\App\Models\Config::find(1)->logo_icon)}}" alt="favicon">--}}
            <img style="width: 90%;float: left;margin-top: 8px; height: 80%;" src="{{ asset(\App\Models\Config::find(1)->logo_internal) }}" alt="imreke-logo">
            {{-- <img style="width: 50%;float: left;margin-top: 8px;"--}}
            {{-- src="{{ asset('img//imreke-logo-transp-e-grad-accent-btns-small-white.png') }}" alt="imreke-logo">--}}
        </div>
    </a>
    <nav class="navbar navbar-static-top">
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="fa fa-bars"></span>
        </a>
        <div>
            {{-- <a data-simpletooltip-text="New Brand"  class="js-simple-tooltip profile-btn" onclick="addNewBrand()" data-bs-toggle="offcanvas" href="#offcanvasBrand" aria-controls="offcanvasBrand"  style="background: purple;--}}
            {{-- padding: 0px 14px 3px 14px;--}}
            {{-- margin-right: 15px;--}}
            {{-- font-size: 18px;--}}
            {{-- position: relative;"  >--}}
            {{-- <i class="fa fa-plus"></i>--}}
            {{-- </a>--}}
            {{-- <select class="brand" id="brand_change" name="brand" style="width: 200px;    margin-right: 12px;" onchange="changeBrand(this)">--}}
            {{-- @foreach(Auth::user()->findBrands() as $brand)--}}
            {{-- @if ($brand->brandid === Auth::user()->default_brand)--}}
            {{-- <option selected value="{{ $brand->brandid }}">{{ $brand->brandident }}</option>--}}
            {{-- @else--}}
            {{-- <option value="{{ $brand->brandid }}">{{ $brand->brandident }}</option>--}}
            {{-- @endif--}}
            {{-- @endforeach--}}
            {{-- </select>--}}
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="{{ asset(Auth::user()->avatar) }}" class="user-image">

                            <span class="hidden-xs">
                                @if(Auth::user('name'))
                                {{ Auth::user()->name }}
                                @endif
                            </span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="user-header">
                                <img src="{{ asset(Auth::user()->avatar) }}" class="img-circle">
                                <p>
                                    @if(Auth::user('name'))
                                    {{ Auth::user()->name }}
                                    @endif
                                    <small>Member Since {{ Auth::user()->created_at->format('M Y') }}</small>
                                </p>
                            </li>
                            <li class="user-footer">
                                <div class="pull-left" style="float: left;">
                                    <a href="{{ route('profile') }}" class="btn btn-default btn-flat">Profile</a>
                                </div>
                                <div class="pull-right" style="float: right;">
                                    <a href="{{ route('logout') }}" class="btn btn-default btn-flat" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>


    </nav>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasBrand" aria-labelledby="offcanvasBrand" style="width: 600px;">
        <div class="offcanvas-header">
            <h4 style="margin-top:0;font-size: 30px;" class="offcanvas-title" id="modaltitle">
                New Customer
            </h4>
            <!--            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>-->
        </div>
        <form id="brandForm">
            <div class="offcanvas-body">
                <div class="row" style="margin-bottom: 20px; padding:0;">
                    <div class="col-md-12">
                        <label for="brandname">Brand Name :</label>
                        <input type="text" class="customer-input form-control" id="brandname" name="brandname" placeholder="Brand Name" required>
                    </div>
                </div>
                <button type="button" class="profile-btn-snd close-animatedModal" style="border: none;margin-bottom: 50px;">
                    Close
                </button>
                <button type="button" onclick="addBrand(event)" class="profile-btn savebut" style="border: none;">Save
                </button>
            </div>
        </form>

    </div>
</header>
<script>
    $(document).ready(function() {

        $('.dropdown').on('mouseover', function() {
            $(this).find('.dropdown-menu').show();
        });
        $('.dropdown').on('mouseleave', function() {
            $(this).find('.dropdown-menu').hide();
        });

    });
</script>
