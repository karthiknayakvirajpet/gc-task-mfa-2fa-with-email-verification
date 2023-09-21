@extends('base')

@section('content')
<div class="container p-5">
    <div class="row justify-content-md-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-8">
                            <h4>Two Factor Authentication</h4>
                        </div>
                        <div class="col-md-4">
                            <a href="/logout">
                                <button type="button" id="add-project" class="btn btn-danger float-right">Logout</button>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <p>Two factor authentication (2FA) strengthens access security by requiring two methods (also referred to as factors) to verify your identity.</p>

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($data['user']->loginSecurity == null)
                        <form class="form-horizontal" method="POST" action="{{ route('generate2faSecret') }}">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    Generate Secret Key to Enable 2FA
                                </button>
                            </div>
                        </form>
                    @elseif(!$data['user']->loginSecurity->google2fa_enable)
                        1. Scan this QR code with your Google Authenticator App. Alternatively, you can use the code: <code>{{ $data['secret'] }}</code><br/>
                        <img src="data:image/svg+xml,{{ rawurlencode($data['google2fa_url']) }}" alt="" width="25%">
                        <br/><br/>
                        2. Enter the pin from Google Authenticator app:<br/><br/>
                        <form class="form-horizontal" method="POST" action="{{ route('enable2fa') }}">
                            {{ csrf_field() }}
                            <div class="form-group{{ $errors->has('verify-code') ? ' has-error' : '' }}">
                                <label for="secret" class="control-label">Authenticator Code</label>
                                <input id="secret" type="password" class="form-control col-md-4" name="secret" required>
                                @if ($errors->has('verify-code'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('verify-code') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-primary">
                                Enable 2FA
                            </button>
                        </form>
                    @elseif($data['user']->loginSecurity->google2fa_enable)
                        <div class="alert alert-success">
                            2FA is currently <strong>enabled</strong> on your account.
                        </div>
                        <p>If you are looking to disable Two Factor Authentication. Please confirm your password and Click Disable 2FA Button.</p>
                        <form class="form-horizontal" method="POST" action="{{ route('disable2fa') }}">
                            {{ csrf_field() }}
                            <div class="form-group{{ $errors->has('current-password') ? ' has-error' : '' }}">
                                <label for="change-password" class="control-label">Current Password</label>
                                    <input id="current-password" type="password" class="form-control col-md-4" name="current-password" required>
                                    @if ($errors->has('current-password'))
                                        <span class="help-block">
                                    <strong>{{ $errors->first('current-password') }}</strong>
                                    </span>
                                    @endif
                            </div>
                            <button type="submit" class="btn btn-primary ">Disable 2FA</button>
                            <br><br><br>

                            <a href="/dashboard">
                                <button type="button" class="btn btn-warning float-left">Go to Dashboard</button>
                            </a>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection