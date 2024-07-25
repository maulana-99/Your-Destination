<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataController extends Controller
{
    public function index(Request $request)
    {
        $userId = session('id');
        if (!$userId) {
            return redirect()->route('login')->withErrors(['login' => 'You must be logged in to view your destinations.']);
        }

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $data = $this->getDataFromFile($userId);

        if ($startDate && $endDate) {
            $data = array_filter($data, function ($entry) use ($startDate, $endDate) {
                return $entry['tanggal'] >= $startDate && $entry['tanggal'] <= $endDate;
            });
        }

        // Debugging: Log the data
        \Log::info('Data sent to view:', $data);

        return view('data.index', ['data' => $data, 'start_date' => $startDate, 'end_date' => $endDate]);
    }


    public function create()
    {
        return view('data.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'waktu' => 'required',
            'nama_tempat' => 'required|string',
            'suhu' => 'required|integer'
        ]);

        $userId = session('id');
        if (!$userId) {
            return redirect()->route('login')->withErrors(['login' => 'You must be logged in to create a destination.']);
        }

        $destinasi = [
            'id' => $this->getNextId(),
            'user_id' => $userId,
            'tanggal' => $request->tanggal,
            'waktu' => $request->waktu,
            'nama_tempat' => $request->nama_tempat,
            'suhu' => $request->suhu
        ];

        $this->saveDestinationToFile($destinasi);

        return redirect()->route('data.index')->with('success', 'Destination created successfully.');
    }

    public function edit($id)
    {
        $userId = session('id');
        if (!$userId) {
            return redirect()->route('login')->withErrors(['login' => 'You must be logged in to edit a destination.']);
        }

        $data = $this->getDataFromFile($userId);
        $destination = $data[$id] ?? null;

        if (!$destination || $destination['user_id'] != $userId) {
            return redirect()->route('data.index')->withErrors(['edit' => 'You do not have permission to edit this destination.']);
        }

        return view('data.edit', ['destination' => $destination]);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'waktu' => 'required',
            'nama_tempat' => 'required|string',
            'suhu' => 'required|integer'
        ]);

        $userId = session('id');
        if (!$userId) {
            return redirect()->route('login')->withErrors(['login' => 'You must be logged in to update a destination.']);
        }

        $data = $this->getDataFromFile($userId);
        $destination = $data[$id] ?? null;

        if (!$destination || $destination['user_id'] != $userId) {
            return redirect()->route('data.index')->withErrors(['edit' => 'You do not have permission to update this destination.']);
        }

        $destination['tanggal'] = $request->tanggal;
        $destination['waktu'] = $request->waktu;
        $destination['nama_tempat'] = $request->nama_tempat;
        $destination['suhu'] = $request->suhu ;

        $this->updateDestinationToFile($destination);

        return redirect()->route('data.index')->with('success', 'Destination updated successfully.');
    }

    public function destroy($id)
    {
        $userId = session('id');
        if (!$userId) {
            return redirect()->route('login')->withErrors(['login' => 'You must be logged in to delete a destination.']);
        }
    
        $file = storage_path('app/users.txt');
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $newContent = [];
        $destinasiSection = false;
    
        foreach ($lines as $line) {
            if (trim($line) == '[DESTINASI]') {
                $destinasiSection = true;
                $newContent[] = $line;
                continue;
            }
    
            if ($destinasiSection) {
                list($entryId, $entryUserId) = explode(',', $line, 3);
                if ($entryId == $id && $entryUserId == $userId) {
                    continue; // Skip this line to delete it
                }
            }
    
            $newContent[] = $line;
        }
    
        file_put_contents($file, implode(PHP_EOL, $newContent) . PHP_EOL);
    
        return redirect()->route('data.index')->with('success', 'Destination deleted successfully.');
    }
    


    public function print(Request $request)
    {
        $userId = session('id');
        if (!$userId) {
            return redirect()->route('login')->withErrors(['login' => 'You must be logged in to view your destinations.']);
        }

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $data = $this->getDataFromFile($userId);

        if ($startDate && $endDate) {
            $data = array_filter($data, function ($entry) use ($startDate, $endDate) {
                return $entry['tanggal'] >= $startDate && $entry['tanggal'] <= $endDate;
            });
        }

        return view('data.print', ['data' => $data, 'start_date' => $startDate, 'end_date' => $endDate]);
    }

    private function getNextId()
    {
        $file = storage_path('app/users.txt');
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $destinasiSection = false;
        $maxId = 0;

        foreach ($lines as $line) {
            if (trim($line) == '[DESTINASI]') {
                $destinasiSection = true;
                continue;
            }

            if ($destinasiSection) {
                $parts = explode(',', $line);
                $id = (int) trim($parts[0]);
                if ($id > $maxId) {
                    $maxId = $id;
                }
            }
        }

        return $maxId + 1;
    }

    private function saveDestinationToFile($destinasi)
    {
        $file = storage_path('app/users.txt');
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $newContent = [];
        $destinasiSection = false;

        foreach ($lines as $line) {
            $newContent[] = $line;

            if (trim($line) == '[DESTINASI]') {
                $destinasiSection = true;
                continue;
            }

            if ($destinasiSection) {
                $newContent[] = "{$destinasi['id']},{$destinasi['user_id']},{$destinasi['tanggal']},{$destinasi['waktu']},{$destinasi['nama_tempat']},{$destinasi['suhu']}";
                $destinasiSection = false; // Add only once
            }
        }

        file_put_contents($file, implode(PHP_EOL, $newContent));
    }

    private function updateDestinationToFile($destinasi)
    {
        $file = storage_path('app/users.txt');
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $newContent = [];
        $destinasiSection = false;

        foreach ($lines as $line) {
            if (trim($line) == '[DESTINASI]') {
                $destinasiSection = true;
                $newContent[] = $line;
                continue;
            }

            if ($destinasiSection && strpos($line, "{$destinasi['id']},") === 0) {
                $newContent[] = "{$destinasi['id']},{$destinasi['user_id']},{$destinasi['tanggal']},{$destinasi['waktu']},{$destinasi['nama_tempat']},{$destinasi['suhu']}";
            } else {
                $newContent[] = $line;
            }
        }

        file_put_contents($file, implode(PHP_EOL, $newContent));
    }

    private function getDataFromFile($userId)
    {
        $file = storage_path('app/users.txt');
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $data = [
            'user' => [],
            'destinasi' => []
        ];
        $currentSection = null;

        foreach ($lines as $line) {
            if (trim($line) == '[USER]') {
                $currentSection = 'user';
                continue;
            }

            if (trim($line) == '[DESTINASI]') {
                $currentSection = 'destinasi';
                continue;
            }

            if ($currentSection == 'user' && strpos($line, ',') !== false) {
                list($id, $name, $nik) = explode(',', $line);
                $data['user'][trim($id)] = [
                    'id' => trim($id),
                    'name' => trim($name),
                    'nik' => trim($nik)
                ];
            }

            if ($currentSection == 'destinasi' && strpos($line, ',') !== false) {
                list($id, $user_id, $tanggal, $waktu, $nama_tempat, $suhu) = explode(',', $line);
                if ($user_id == $userId) {
                    $data['destinasi'][trim($id)] = [
                        'id' => trim($id),
                        'user_id' => trim($user_id),
                        'tanggal' => trim($tanggal),
                        'waktu' => trim($waktu),
                        'nama_tempat' => trim($nama_tempat),
                        'suhu' => trim($suhu)
                    ];
                }
            }
        }

        return $data['destinasi'] ?? [];
    }

    private function writeDataToFile($data)
    {
        $file = storage_path('app/users.txt');
        $newLines = [];
        $newLines[] = '[USER]';
        foreach ($data['user'] as $user) {
            $newLines[] = "{$user['id']},{$user['name']},{$user['nik']}";
        }
        $newLines[] = '[DESTINASI]';
        foreach ($data['destinasi'] as $entry) {
            $newLines[] = "{$entry['id']},{$entry['user_id']},{$entry['tanggal']},{$entry['waktu']},{$entry['nama_tempat']},{$entry['suhu']}";
        }

        file_put_contents($file, implode("\n", $newLines) . "\n");
    }
}
