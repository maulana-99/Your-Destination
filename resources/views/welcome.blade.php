<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
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

        .welcome-container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 40px;
            width: 600px;
            border: 2px solid #000000;
            box-shadow: 5px 5px 0 0 #000000;
            text-align: center;
            border-radius: 15px;
        }

        .welcome-container h1 {
            margin-bottom: 20px;
            font-size: 2.5em;
        }

        .welcome-container p {
            margin-bottom: 20px;
            font-size: 1.2em;
        }

        .enter-button {
            padding: 10px 25px;
            font-size: 1.2em;
            color: white;
            background-color: #008CBA;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .enter-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="welcome-container">
        <h1>Selamat Datang di Aplikasi Destinations Anda</h1>
        <p>Kelola destinasi Anda dengan efisien dan mudah.</p>
        <a href="/login" class="enter-button">Masuk</a>
    </div>

</body>

</html>