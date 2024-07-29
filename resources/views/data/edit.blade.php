<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Destination</title>
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
            width: 400px;
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

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #000000;
            /* Black border */
            box-sizing: border-box;
            /* Include padding and border in width */
        }

        .btn {
            padding: 10px;
            background-color: #FFD700;
            /* Gold background */
            border: none;
            color: #000000;
            /* Black text */
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
        }

        .btn:hover {
            opacity: 0.8;
        }

        .alert {
            color: #FF0000;
            margin-bottom: 20px;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #000000;
            /* Black text */
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Edit Destination</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('data.update', $destination['id']) }}" method="POST"
            onsubmit="return confirm('Apakah Anda yakin ingin mengubah data ini?');">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="tanggal">Tanggal:</label>
                <input type="date" id="tanggal" name="tanggal" class="form-control"
                    value="{{ $destination['tanggal'] }}" required>
            </div>
            <div class="form-group">
                <label for="waktu">Waktu:</label>
                <input type="time" id="waktu" name="waktu" class="form-control" value="{{ $destination['waktu'] }}"
                    required>
            </div>
            <div class="form-group">
                <label for="nama_tempat">Nama Tempat:</label>
                <input type="text" id="nama_tempat" name="nama_tempat" class="form-control"
                    value="{{ $destination['nama_tempat'] }}" required>
            </div>
            <div class="form-group">
                <label for="suhu">Suhu Tubuh (°C):</label>
                <input type="number" id="suhu" name="suhu" class="form-control" min="0" max="60"
                    value="{{ $destination['suhu'] }}" required>
            </div>
            <button type="submit" class="btn">Update</button>
        </form>
        <a href="{{ route('data.index') }}">Cancel</a>
    </div>
</body>

</html>