@extends('base')

@section('content')
<div class="container p-5">
    <div class="d-flex justify-content-center align-items-center" style="height: 80vh;">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-8">
                            <h4>Signup</h4>
                        </div>
                        <div class="col-md-4">
                            <a href="/login">
                                <button type="button" class="btn btn-dark float-right">Login</button>
                            </a>
                        </div>
                    </div>
                </div>

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
                
                <form id="register-form" method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name" class="form-control-label">Name</label>
                                    <input id="name" class="form-control" type="name" name="name" value="{{ old('name') }}" required autofocus>
                                    @error('name')
                                        <span role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
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
                                    <input id="password" class="form-control" type="password" name="password" required>

                                    <span class="mb-1 toggle-btn" id="toggle-password" title="Preview password">
                                        <i class="fa fa-eye-slash" aria-hidden="true"></i>
                                    </span>

                                    @error('password')
                                        <span role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="ml-3 mb-3" id="password-strength"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="terms_and_condition" name="terms_and_condition" value="1" {{ old('terms_and_condition')==1 ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="terms_and_condition">
                                        I accept the Terms and Conditions.
                                    </label>
                                </div>
                            </div>
                        </div><br>
                        <button type="submit" class="btn btn-primary">Register</button>
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

    //****************************************************************
    //Preview password
    //****************************************************************
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


    //****************************************************************
    //Password Check
    //****************************************************************
    const passwordInput = document.getElementById('password');
    const passwordStrength = document.getElementById('password-strength');
    const passwordForm = document.getElementById('register-form');

    passwordInput.addEventListener('input', function () {
        const password = passwordInput.value;

        const minLength = 8;
        const hasLower = /[a-z]/.test(password);
        const hasUpper = /[A-Z]/.test(password);
        const hasSpecial = /[!@#$%^&*()_+{}\[\]:;<>,.?~\\-]/.test(password);

        const isStrong = password.length >= minLength && hasLower && hasUpper && hasSpecial;

        if (isStrong) {
            passwordStrength.textContent = 'Password is strong!';
            passwordStrength.style.color = 'green';
        } else {
            passwordStrength.textContent = `Password must contain 8 characters, one lowercase letter, one uppercase letter and one special character`;
            passwordStrength.style.color = 'red';
        }
    });

    passwordForm.addEventListener('submit', function (e) {
        const password = passwordInput.value;
        const isStrong = password.length >= 8 && /[a-z]/.test(password) && /[A-Z]/.test(password) && /[!@#$%^&*()_+{}\[\]:;<>,.?~\\-]/.test(password);

        if (!isStrong) {
            e.preventDefault(); // Prevent form submission if password is not strong
            alert('Please enter a strong password.');
        }
    });
        
});
</script>