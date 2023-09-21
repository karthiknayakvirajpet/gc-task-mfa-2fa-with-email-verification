@extends('base')

@section('content')
<div class="container p-5">
    <div class="d-flex justify-content-center align-items-center" style="height: 80vh;">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-8">
                            <h4>Login</h4>
                        </div>
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="email" class="form-control-label">Email</label>
                                    <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus>
                                    @error('email')
                                        <span role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="password" class="form-control-label">Password</label>
                                    <input id="password" class="form-control" type="password" name="password"required>

                                    <span class="mb-1 toggle-btn" id="toggle-password" title="Preview password">
                                        <i class="fa fa-eye-slash" aria-hidden="true"></i>
                                    </span>

                                    @error('password')
                                        <span role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Login</button>
                        <br><br>

                        New user ?
                        <a href="/register">
                            <button type="button" class="btn btn-warning">Signup</button>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.4.0.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {

    //Preview password
    $('#toggle-password').click(function () {
        var passwordInput = $('#password');
        var icon = $(this).find('i');

        if (passwordInput.attr('type') === 'password') {
            passwordInput.attr('type', 'text');
            icon.removeClass('fa fa-eye-slash').addClass('fa fa-eye');
        } else {
            passwordInput.attr('type', 'password');
            icon.removeClass('fa fa-eye').addClass('fa fa-eye-slash');
        }
    });
});
</script>