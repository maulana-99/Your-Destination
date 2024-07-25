<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h1>Destinations Report</h1>
    <p>Date Range: {{ $start_date }} to {{ $end_date }}</p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Nama Tempat</th>
                <th>Suhu</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $entry)
                <tr>
                    <td>{{ $entry['id'] }}</td>
                    <td>{{ $entry['tanggal'] }}</td>
                    <td>{{ $entry['waktu'] }}</td>
                    <td>{{ $entry['nama_tempat'] }}</td>
                    <td>{{ $entry['suhu'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No destinations found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <script>
        window.onload = function () {
            window.print();
        }
    </script>
</body>

</html>