<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Destinations</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            background-color: #ffffff;
            /* White background */
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
            width: 600px;
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
        }

        .btn-primary {
            background-color: #008CBA;
            /* Blue background */
        }

        .btn-secondary {
            background-color: #FFD700;
            /* Gold background */
        }

        .btn-danger {
            background-color: #f44336;
            /* Red background */
        }

        .btn:hover {
            opacity: 0.8;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #000000;
            /* Black border */
            padding: 10px;
            text-align: left;
        }

        .alert {
            color: #FF0000;
            margin-bottom: 20px;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            color: #0000FF;
            /* Blue text */
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Your Destinations</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('data.index') }}" method="GET" class="mb-4">
            <div class="form-group">
                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" name="start_date" value="{{ $start_date }}" class="form-control">
            </div>
            <div class="form-group">
                <label for="end_date">End Date:</label>
                <input type="date" id="end_date" name="end_date" value="{{ $end_date }}" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('data.print', ['start_date' => $start_date, 'end_date' => $end_date]) }}" target="_blank"
                class="btn btn-secondary">Print</a>
        </form>

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tanggal</th>
                    <th>Waktu</th>
                    <th>Nama Tempat</th>
                    <th>Suhu Tubu</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $entry)
                    @if(isset($entry['id']))
                        <tr>
                            <td>{{ $entry['id'] }}</td>
                            <td>{{ $entry['tanggal'] }}</td>
                            <td>{{ $entry['waktu'] }}</td>
                            <td>{{ $entry['nama_tempat'] }}</td>
                            <td>{{ $entry['suhu'] }}°C</td>
                            <td>
                                <a href="{{ route('data.edit', $entry['id']) }}" class="btn btn-primary">Edit</a>
                                <form action="{{ route('data.delete', $entry['id']) }}" method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus fasilitas ini?');"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>

                            </td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="6">Invalid entry found.</td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="6">No destinations found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <a href="{{ route('data.create') }}">Create New Destination</a>

        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
    </div>
</body>

</html>