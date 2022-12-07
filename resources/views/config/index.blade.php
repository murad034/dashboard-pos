@extends('layouts.AdminLTE.index')

@section('icon_page', 'gear')

@section('title', 'Application Settings')

@section('content')

    <div class="box box-primary">
        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <form id="configForm" method="POST">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-lg-12">
                                <h4><b><i class="fa fa-fw fa-arrow-right"></i> General information</b></h4>
                                <hr/>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group {{ $errors->has('app_name') ? 'has-error' : '' }}">
                                    <label for="nome">Application Name</label>
                                    <input type="text" name="app_name" class="form-control" maxlength="30"
                                           placeholder="Application Name" value="{{$config->app_name}}">
                                    @if($errors->has('app_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('app_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group {{ $errors->has('app_name_abv') ? 'has-error' : '' }}">
                                    <label for="nome">Short Name</label>
                                    <input type="text" name="app_name_abv" class="form-control" maxlength="5"
                                           value="{{$config->app_name_abv}}">
                                    @if($errors->has('app_name_abv'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('app_name_abv') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-7">
                            </div>
                            <div class="col-lg-12">
                                <br>
                                <h4><b><i class="fa fa-fw fa-arrow-right"></i> Captcha Login</b></h4>
                                <hr/>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group {{ $errors->has('captcha') ? 'has-error' : '' }}">
                                    <label for="nome">Captcha Login</label>
                                    <select class="form-control" name="captcha">
                                        @if($config->captcha == 'T')
                                            <option value="{{$config->captcha}}">Enable</option>
                                            <option value="F">Disable</option>
                                        @endif
                                        @if($config->captcha == 'F')
                                            <option value="{{$config->captcha}}">Disable</option>
                                            <option value="T">Enable</option>
                                        @endif
                                    </select>
                                    @if($errors->has('captcha'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('captcha') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="form-group {{ $errors->has('datasitekey') ? 'has-error' : '' }}">
                                    <label for="nome">Site Key</label>
                                    <input type="text" name="datasitekey" class="form-control" placeholder="Site Key"
                                           maxlength="40" value="{{$config->datasitekey}}">
                                    @if($errors->has('datasitekey'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('datasitekey') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="form-group {{ $errors->has('recaptcha_secret') ? 'has-error' : '' }}">
                                    <label for="nome">Key Secret</label>
                                    <input type="text" name="recaptcha_secret" class="form-control" maxlength="40"
                                           placeholder="Key Secret" value="{{$config->recaptcha_secret}}">
                                    @if($errors->has('recaptcha_secret'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('recaptcha_secret') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <br>
                                <h4><b><i class="fa fa-fw fa-arrow-right"></i> Login Options</b></h4>
                                <hr/>
                            </div>
                            {{--                            <div class="col-lg-4">--}}
                            {{--                                <div class="form-group {{ $errors->has('titulo_login') ? 'has-error' : '' }}">--}}
                            {{--                                    <label for="titulo_login">Title Login</label>--}}
                            {{--                                    <input type="text" name="titulo_login" class="form-control" maxlength="40"--}}
                            {{--                                           placeholder="Title Login" value="{{$config->titulo_login}}">--}}
                            {{--                                    @if($errors->has('titulo_login'))--}}
                            {{--                                        <span class="help-block">--}}
                            {{--                                            <strong>{{ $errors->first('titulo_login') }}</strong>--}}
                            {{--                                        </span>--}}
                            {{--                                    @endif--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}
                            <div class="col-lg-1">
                                <label>Current Login Image</label>
                                <br>
                                <img src="{{ asset($config->logo_background) }}" width="30px" class="img-thumbnail">
                                <br><br>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group {{ $errors->has('logo_background') ? 'has-error' : '' }}">
                                    <label>Image Login</label>
                                    <input type="file" class="form-control-file" name="logo_background"
                                           id="logo_background">
                                    @if($errors->has('logo_background'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('logo_background') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-lg-1">
                                <label>Internal Image</label>
                                <br>
                                <img src="{{ asset($config->logo_internal) }}" width="30px" class="img-thumbnail">
                                <br><br>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group {{ $errors->has('logo_internal') ? 'has-error' : '' }}">
                                    <label>Image Internal</label>
                                    <input type="file" class="form-control-file" name="logo_internal"
                                           id="logo_internal">
                                    @if($errors->has('logo_internal'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('logo_internal') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-4"></div>
                            <div class="col-lg-12">
                                <br>
                                <h4><b><i class="fa fa-fw fa-arrow-right"></i> Others </b></h4>
                                <hr/>
                            </div>
                            <div class="col-lg-4" style="display: flex;">
                                <div class="form-group {{ $errors->has('send_mail') ? 'has-error' : '' }}" style="flex: 1 0 auto;">
                                    <label for="send_mail">Send Email</label>
                                    <input type="email" name="send_mail" class="form-control"
                                           id="send_mail"
                                           placeholder="Email" value="{{$config->send_mail}}">
                                    <span class="help-block" id="resend-section">
                                        Haven't received the confirmation email? click <a href="#" id="resend-mail">here</a> the resend.
                                    </span>

                                    @if($errors->has('send_mail'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('send_mail') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <button class="btn btn-primary  align-middle" id="verify-mail" style="margin: 22px;">Verify
                                </button>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group {{ $errors->has('timezone') ? 'has-error' : '' }}">
                                    <label for="timezone">Time Zone</label>
                                    <select name="timezone" class="form-control" id="time_zone"></select>
                                    @if($errors->has('timezone'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('timezone') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-lg-1">
                                <label>Current Logo Icon</label>
                                <br>
                                <img src="{{ asset($config->logo_icon) }}" width="30px" class="img-thumbnail">
                                <br><br>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group {{ $errors->has('logo_icon') ? 'has-error' : '' }}">
                                    <label>Logo Icon</label>
                                    <input type="file" class="form-control-file" name="logo_icon" id="logo_icon">
                                    @if($errors->has('logo_icon'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('logo_icon') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group {{ $errors->has('hold_bsb') ? 'has-error' : '' }}">
                                    <label for="hold_bsb">BSB</label>
                                    <input type="text" name="hold_bsb" class="form-control" maxlength="40"
                                           placeholder="BSB" value="{{$config->hold_bsb}}">
                                    @if($errors->has('hold_bsb'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('hold_bsb') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group {{ $errors->has('hold_account_number') ? 'has-error' : '' }}">
                                    <label for="hold_account_number">Account Number</label>
                                    <input type="text" name="hold_account_number" class="form-control" maxlength="40"
                                           placeholder="Account Number" value="{{$config->hold_account_number}}">
                                    @if($errors->has('hold_account_number'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('hold_account_number') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group {{ $errors->has('hold_payment_fee') ? 'has-error' : '' }}">
                                    <label for="hold_payment_fee">Payment Gateway Fee %</label>
                                    <input type="text" name="hold_payment_fee" class="form-control" maxlength="40"
                                           placeholder="Fee %" value="{{$config->hold_payment_fee}}">
                                    @if($errors->has('hold_payment_fee'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('hold_payment_fee') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group {{ $errors->has('wp_url') ? 'has-error' : '' }}">
                                    <label for="wp_url">WebSite URL</label>
                                    <input type="text" name="wp_url" class="form-control" maxlength="40"
                                           placeholder="Website url" value="{{$config->wp_url}}">
                                    @if($errors->has('wp_url'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('wp_url') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group {{ $errors->has('wp_token') ? 'has-error' : '' }}">
                                    <label for="wp_token">WebSite KEY</label>
                                    <input type="text" name="wp_token" class="form-control" maxlength="40"
                                           placeholder="Website key" value="{{$config->wp_token}}">
                                    @if($errors->has('wp_token'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('wp_token') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6" style="display: flex;">
                                <div class="form-group {{ $errors->has('send_mail') ? 'has-error' : '' }}" style="flex: 1 0 auto;">
                                    <label for="api_token">API Token :</label>
                                    <input type="text" id="api_token" name="api_token" value="{{Auth::user()->api_token}}"
                                           readonly
                                           class="form-control" style="width: 100%;">
                                </div>
                                @if(Auth::user()->api_token === '')
                                    <button class="btn btn-primary"
                                            onclick="updateToken()" style="margin: 22px;">
                                        <i class="fa fa-fw fa-save"></i> New Token
                                    </button>
                                @endif
                            </div>

                            <div class="col-lg-12">
                                <hr/>
                            </div>
                            <div class="col-lg-12">
                                <button class="btn btn-primary" id="save-config"><i
                                        class="fa fa-fw fa-save"></i> Save
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        let timezoneVal = '{{$config->timezone}}';
        let configId = '{{$config->config_id}}';
        let user_id = '{{Auth::user()->user_id}}';
        let originMail = '{{$config->send_mail}}';
        let signatureId = '{{$config->signature_id}}';
        let AppName = '{{$config->app_name}}';
        let postmarkKEY = '{{ env('POSTMARK_APP_KEY')}}';
        let postmarkURL = '{{ env('POSTMARK_API_URL')}}';
    </script>
    <script src="{{ asset('/js/config/index.js') }}"></script>
@endsection
