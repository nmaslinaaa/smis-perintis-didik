<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pusat Tuisyen Perintis Didik</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&family=Nunito:wght@700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #eb8686, #f6ec8d);
            display: flex;
            align-items: center;
            padding-top: 36px;
            flex-direction: column;
        }
        .login-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
        }
        .logo {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .logo img {
            width: 150px;
            margin-right: 10px;
        }
        .logotext h1 {
            font-size: 24px;
            margin: 0;
            font-weight: 600;
            color: #000;
        }
        .logotext2 h1{
            padding-left: 4em;
        }
        .login-box {
            background-color: white;
            border-radius: 50px;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 350px;
        }
        h3 {
            font-family: 'Nunito', sans-serif;
            color: #d22e2e;
            font-size: 40px;
            margin-top: 0;
            margin-bottom: 23px;
            font-weight: 700;
        }
        .loginform p{
            font-size: 15px;
            margin: 0;
            text-align: left;
            padding-left: 14px;
        }
        .formbottom {
            margin-top: 3px;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding-top: 15px;
            padding-bottom: 15px;
            margin: 10px 0;
            border: 1px solid #ffffff;
            border-radius: 25px;
            font-size: 15px;
            background-color: #e2e2e2;
            text-align: center;
        }
        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #d22e2e;
        }
        .password-container {
            position: relative;
            width: 100%;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .password-container input[type="password"],
        .password-container input[type="text"] {
            flex: 1;
            padding-top: 15px;
            padding-bottom: 15px;
            padding-right: 15px;
            margin: 10px 0;
            border: 1px solid #ffffff;
            border-radius: 25px;
            font-size: 15px;
            background-color: #e2e2e2;
            text-align: center;
        }
        .password-container input[type="password"]:focus,
        .password-container input[type="text"]:focus {
            outline: none;
            border-color: #d22e2e;
        }
        .password-toggle-btn {
            background: #e2e2e2;
            border: 1px solid #ffffff;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            margin: 10px 0;
        }
        .password-toggle-btn:hover {
            background: #d22e2e;
            color: white;
        }
        .password-toggle-btn i {
            color: #666;
            font-size: 14px;
        }
        .password-toggle-btn:hover i {
            color: white;
        }
        a {
            color: #d22e2e;
            font-size: 14px;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        button {
            background-color: #d22e2e;
            color: white;
            padding: 15px;
            width: 100%;
            border: none;
            border-radius: 25px;
            font-size: 18px;
            cursor: pointer;
            margin-top: 55px;
        }
        button:hover {
            background-color: #b02828;
        }
        p {
            font-size: 14px;
            margin-top: 10px;
        }
        p a {
            color: #d22e2e;
        }
        p a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div>
        <div class="logo">
            <img src="../images/smislogo.png" alt="Logo">
            <div class="logotext">
                <div class="logotext1">
                    <h1>Pusat Tuisyen</h1>
                </div>
                <div class="logotext2">
                    <h1>Perintis Didik</h1>
                </div>
            </div>
        </div>

        <div class="login-container">
        <div class="login-box">
            <h3>Login</h3>
            @if(session('login_error'))
                <div style="color: #fff; background: #d22e2e; border-radius: 8px; padding: 10px; margin-bottom: 15px; font-size: 15px;">
                    {{ session('login_error') }}
                </div>
            @endif
            <form action="/login" method="POST">
                @csrf
                <div class="loginform">
                    <div class="form-group">
                        <p>Username</p>
                        <input type="text" name="username" placeholder="please enter your username" required>
                    </div>
                    <div class="form-group">
                        <p>Password</p>
                        <div class="password-container">
                            <input type="password" name="password" id="password" placeholder="please enter you password" required>
                            <button type="button" id="togglePassword" class="password-toggle-btn">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="formbottom">
                        {{-- <a href="#">Forgot Password?</a> --}}
                        <div class="form-group">
                             <button type="submit">Log In</button>
                        </div>
                    </div>

                </div>
            </form>
            <p>Do not have an account? <a href="/signup">Sign Up</a></p>
        </div>
    </div>
    </div>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function (e) {
            e.preventDefault(); // Prevent form submission
            // toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

            // toggle the eye / eye slash icon
            const icon = this.querySelector('i');
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>
