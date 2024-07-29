<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            background-image: url(https://cdn1.epicgames.com/ue/product/Screenshot/Screenshot05-1920x1080-0f4d271d3f5c217a2a06977d706debf1.jpg?resize=1&w=1920);
            background-repeat: no-repeat;
            background-size: cover;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #ffffff;
            /* White background */
            padding: 40px;
            width: 300px;
            border: 2px solid #000000;
            /* Black border */
            box-shadow: 5px 5px 0 0 #000000;
            /* Black shadow */
        }

        h1 {
            margin-bottom: 20px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #000000;
            /* Black border */
            box-sizing: border-box;
            /* Include padding and border in width */
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #FFD700;
            /* Gold background */
            border: none;
            color: #000000;
            /* Black text */
            margin-top: 20px;
            cursor: pointer;
        }

        button:hover {
            background-color: #FFC107;
            /* Darker gold background */
        }

        .login-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #000000;
            /* Black text */
            text-decoration: none;
            font-weight: bold;
        }

        .login-link:hover {
            text-decoration: underline;
        }

        .error-messages {
            color: #FF0000;
            /* Red color for errors */
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Make a new user</h1>
        @if ($errors->any())
            <div class="error-messages">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="form-group">
                <input type="text" id="nama" name="nama" value="{{ old('nama') }}" placeholder="Nama" required>
            </div>
            <div class="form-group">
                <input type="number" id="nik" name="nik" value="{{ old('nik') }}" placeholder="NIK" required>
            </div>
            <button type="submit">Confirm</button>
        </form>
        <a href="{{ route('login') }}" class="login-link">Are you a old user?</a>
    </div>
</body>

</html>