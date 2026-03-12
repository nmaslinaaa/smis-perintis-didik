<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form - Pusat Tuisyen Perintis Didik</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&family=Nunito:wght@700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #eb8686, #f6ec8d);  /* Gradient colors */
            display: flex;
            align-items: center;
            padding-top: 36px;
            flex-direction: column; /* Align logo and form vertically */
        }
        .signup-container {
            display: flex;
            flex-direction: column;  /* Stack logo and form */
            align-items: center;
            width: 100%;
        }
        .logo {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .logo img {
            width: 150px;  /* Adjust logo size */
            margin-right: 10px;  /* Space between logo and text */
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
        .signup-box {
            background-color: white;
            border-radius: 50px;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 450px;
        }
        h3 {
            font-family: 'Nunito', sans-serif;
            color: #d22e2e;
            font-size: 40px;
            margin-top: 0;
            margin-bottom: 23px;
            font-weight: 700;
        }
        .signupform p{
            font-size: 15px;
            margin: 0;
            text-align: left;
            padding-left: 14px;
        }
        .formbottom {
            margin-top: 3px;
        }
        input[type="text"],
        input[type="password"],
        input[type="email"] {
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
        input[type="password"]:focus,
        input[type="email"]:focus {
            outline: none;
            border-color: #d22e2e;
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
        .error-message {
            color: #fff;
            background: #d22e2e;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 15px;
            font-size: 15px;
        }
        .success-message {
            color: #fff;
            background: #28a745;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 15px;
            font-size: 15px;
        }
    </style>
</head>
<body>
    <div>
        <div class="logo">
            <img src="../images/smislogo.png" alt="Logo">  <!-- Logo Image -->
            <div class="logotext">
                <div class="logotext1">
                    <h1>Pusat Tuisyen</h1>
                </div>
                <div class="logotext2">
                    <h1>Perintis Didik</h1>
                </div>
            </div>
        </div>

        <div class="signup-container">
        <div class="signup-box">
            <h3>Registration Form</h3>
            @if(session('error'))
                <div class="error-message">
                    {{ session('error') }}
                </div>
            @endif
            @if(session('success'))
                <div class="success-message">
                    {{ session('success') }}
                </div>
            @endif
            <form action="/signup" method="POST">
                @csrf
                <div class="signupform">
                    <div class="form-group">
                        <p>Fullname</p>
                        <input type="text" name="fullname" placeholder="Enter your full name" required value="{{ old('fullname') }}">
                        @error('fullname')
                            <div style="color: #d22e2e; font-size: 12px; margin-top: 5px; text-align: left; padding-left: 14px;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <p>Username</p>
                        <input type="text" name="username" placeholder="Choose a username" required value="{{ old('username') }}">
                        @error('username')
                            <div style="color: #d22e2e; font-size: 12px; margin-top: 5px; text-align: left; padding-left: 14px;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <p>Password</p>
                        <input type="password" name="password" placeholder="Create a strong password" required>
                        @error('password')
                            <div style="color: #d22e2e; font-size: 12px; margin-top: 5px; text-align: left; padding-left: 14px;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <p>Email (Optional)</p>
                        <input type="email" name="email" placeholder="Enter your email address" value="{{ old('email') }}">
                        @error('email')
                            <div style="color: #d22e2e; font-size: 12px; margin-top: 5px; text-align: left; padding-left: 14px;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <p>Phone Number</p>
                        <input type="text" name="phone" placeholder="e.g. 0123456789" required value="{{ old('phone') }}">
                        @error('phone')
                            <div style="color: #d22e2e; font-size: 12px; margin-top: 5px; text-align: left; padding-left: 14px;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="formbottom">
                        <div class="form-group">
                             <button type="submit">Sign Up</button>
                        </div>
                    </div>
                </div>
            </form>
            <p>Already have an account? <a href="/login">Log-In</a></p>
        </div>
    </div>
    </div>
</body>
</html>
