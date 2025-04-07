<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Expense Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f5f5f5;
            padding: 20px;
        }

        .login-card {
            background: white;
            border-radius: 20px;
            padding: 3rem 2rem;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
        }

        .brand {
            text-align: center;
            margin-bottom: 1rem;
        }

        .brand-logo {
            width: 50px;
            height: 50px;
            background: #000;
            border-radius: 50%;
            margin: 0 auto 1rem;
        }

        .brand h1 {
            font-size: 1.75rem;
            color: #1a1a1a;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .brand p {
            color: #666;
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 1rem;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 2px solid #e1e1e1;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #000;
            box-shadow: 0 0 0 4px rgba(0, 0, 0, 0.1);
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .remember-me input[type="checkbox"] {
            width: 16px;
            height: 16px;
            border-radius: 4px;
        }

        .remember-me label {
            color: #666;
            font-size: 0.9rem;
        }

        .forgot-password {
            color: #000;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .login-btn {
            width: 100%;
            padding: 1rem;
            background: #000;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .login-btn:hover {
            background: #333;
            transform: translateY(-2px);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .social-login {
            margin-top: 2rem;
            text-align: center;
        }

        .social-login p {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            position: relative;
        }

        .social-login p::before,
        .social-login p::after {
            content: "";
            position: absolute;
            top: 50%;
            width: 45%;
            height: 1px;
            background: #e1e1e1;
        }

        .social-login p::before {
            left: 0;
        }

        .social-login p::after {
            right: 0;
        }

        .social-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .social-btn {
            width: 50px;
            height: 50px;
            border: 2px solid #e1e1e1;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .social-btn:hover {
            border-color: #000;
            background: #f5f5f5;
        }

        .signup-link {
            text-align: center;
            margin-top: 1.5rem;
        }

        .signup-link a {
            color: #000;
            text-decoration: none;
            font-weight: 600;
        }

        .signup-link a:hover {
            text-decoration: underline;
        }

        .wayjob {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .wayjob img {
            width: 250px;
            margin-bottom: 0.5rem;
        }

        .error {
            color: #dc3545;
            font-size: 0.85rem;
            margin-top: 0.5rem;
            display: none;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            20%,
            60% {
                transform: translateX(-5px);
            }

            40%,
            80% {
                transform: translateX(5px);
            }
        }

        .shake {
            animation: shake 0.5s ease;
        }
    </style>
</head>

<body>
    <div class="login-card">
        <div class="brand">
            <div class="wayjob"><img src="wayjoblogo.png" alt=""></div>
            <h1>Sign-up</h1>
            <p>Enter your details to create an account</p>
        </div>

        <form id="loginForm" method="post" action="{{route('register')}}">
            @csrf
            <div class="form-group">
                <label for="email">Name</label>
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                    value="{{ old('name') }}" required autocomplete="name" autofocus>

                @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="gross_salary">Gross Salary</label>
                <input id="gross_salary" type="text" class="form-control @error('gross_salary') is-invalid @enderror" name="gross_salary"
                    value="{{ old('gross_salary') }}" required autocomplete="gross_salary" autofocus>

                @error('gross_salary')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                    value="{{ old('email') }}" required autocomplete="email">

                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                    name="password" required autocomplete="new-password">

                @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="password">Confirm Password</label>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                <div class="error" id="passwordError"></div>
            </div>

            <!-- <div class="remember-forgot">
                    <div class="remember-me">
                        <input type="checkbox" id="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    <a href="#" class="forgot-password">Forgot password?</a>
                </div> -->

            <button type="submit" class="login-btn" id="loginButton">
                Sign up
            </button>
        </form>

        <!-- <div class="social-login">
                <p>Or continue with</p>
                <div class="social-buttons">
                    <div class="social-btn">G</div>
                    <div class="social-btn">A</div>
                    <div class="social-btn">F</div>
                </div>
            </div> -->

        <div class="signup-link">
            <p>Already have an account? <a href="{{route('login')}}">Sign In</a></p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>