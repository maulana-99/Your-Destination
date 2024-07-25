<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (session()->has('id')) {
            return redirect('/data');
        }

        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'nik' => 'required',
            'nama' => 'required'
        ]);

        $users = $this->getUsersFromFile();

        foreach ($users as $user) {
            if ($user['nik'] == $request->nik && $user['nama'] == $request->nama) {
                // Login berhasil, simpan informasi pengguna ke sesi
                session(['id' => $user['id'], 'nama' => $user['nama']]);
                return redirect('/data');
            }
        }

        // Login gagal
        return back()->withErrors(['login' => 'NIK atau nama tidak cocok.']);
    }

    public function showRegisterForm()
    {
        if (session()->has('id')) {
            return redirect('/data');
        }

        return view('register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'nik' => 'required'
        ]);

        // Path ke file
        $filePath = storage_path('app/users.txt');

        // Baca file
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $userSectionStarted = false;
        $userSectionEnded = false;
        $newContent = '';
        $existingUsers = [];

        // Iterasi baris untuk memisahkan bagian [USER] dan [DESTINASI]
        foreach ($lines as $line) {
            if (trim($line) === '[USER]') {
                $userSectionStarted = true;
                $newContent .= $line . "\n";
                continue;
            } elseif (trim($line) === '[DESTINASI]') {
                $userSectionEnded = true;
                $newContent .= $line . "\n";
                continue;
            }

            if ($userSectionStarted && !$userSectionEnded) {
                $newContent .= $line . "\n";
                list($id, $nama, $nik) = explode(',', $line);
                $existingUsers[] = [
                    'id' => trim($id),
                    'nama' => trim($nama),
                    'nik' => trim($nik)
                ];
            } elseif ($userSectionEnded) {
                $newContent .= $line . "\n";
            }
        }

        // Cek apakah NIK atau Nama sudah ada
        foreach ($existingUsers as $user) {
            if ($user['nik'] == $request->nik) {
                return back()->withErrors(['register' => 'NIK sudah ada.']);
            }
        }

        // Menentukan ID baru
        $lastId = 0;
        foreach ($existingUsers as $user) {
            if ((int) $user['id'] > $lastId) {
                $lastId = (int) $user['id'];
            }
        }

        $newId = $lastId + 1;
        $newUserLine = "$newId,{$request->nama},{$request->nik}\n";

        // Tambahkan pengguna baru sebelum bagian [DESTINASI]
        if (!$userSectionEnded) {
            $newContent .= $newUserLine;
        } else {
            $newContent = str_replace('[USER]', "[USER]\n$newUserLine", $newContent);
        }

        // Simpan kembali ke file
        file_put_contents($filePath, $newContent);

        // Pendaftaran berhasil, lanjutkan login
        session(['id' => $newId, 'nama' => $request->nama]);
        return redirect('/data');
    }



    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()->route('login');
    }

    private function getUsersFromFile()
    {
        $file = storage_path('app/users.txt');
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $users = [];
        $userSection = false;

        foreach ($lines as $line) {
            if (trim($line) == '[USER]') {
                $userSection = true;
                continue;
            } elseif (trim($line) == '[DESTINASI]') {
                break;
            }

            if ($userSection) {
                list($id, $nama, $nik) = explode(',', $line);
                $users[] = [
                    'id' => trim($id),
                    'nama' => trim($nama),
                    'nik' => trim($nik)
                ];
            }
        }

        return $users;
    }
}
