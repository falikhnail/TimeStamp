<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class UserControler extends Controller
{
    function index() {
        return view('user.index', [
           "data_jabatan" => Jabatan::all(),
           "data_user" => User::all(),
            'title' => 'User',
        ]);
    }

    function tambah() {
        return view('user.tambah', [
           "data_jabatan" => Jabatan::all(),
            'title' => 'Tambah User',
        ]);
    }

    function store(Request $request) {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'username' => 'required|max:255|unique:users',
            'password' => 'required|min:6|max:255',
            'tgl_lahir' => 'required',
            'is_admin' => 'required',
            'jabatan_id' => 'required',
        ]);

        $validatedData['password'] = Hash::make($validatedData['password']);
        User::create($validatedData);
        return redirect('/index-user')->with('success', 'Data Berhasil di Tambahkan');
    }

    function edit($id) {

        return view('user.edit', [
            'title' => 'Detail User',
            'data_user' => User::find($id),
            'data_jabatan' => Jabatan::all(),
        ]);
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'tgl_lahir' => 'required|date',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'password' => 'nullable|string|', // Password boleh kosong, tapi jika ada harus sesuai aturan
            'is_admin' => 'required|',
            'jabatan_id' => 'required', // Sesuaikan dengan nama tabel
        ]);

        // Temukan pengguna berdasarkan ID
        $user = User::findOrFail($id);

        // Update data pengguna
        $user->name = $validatedData['name'];
        $user->tgl_lahir = $validatedData['tgl_lahir'];
        $user->username = $validatedData['username'];
        if ($request->filled('password')) {
            $user->password = Hash::make($validatedData['password']); // Hash password jika diisi
        }
        $user->is_admin = $validatedData['is_admin'];
        $user->jabatan_id = $validatedData['jabatan_id']; // Sesuaikan dengan nama kolom di tabel
        $user->save();

        // Redirect dengan pesan sukses
        return redirect()->route('index-user')->with('success', 'User berhasil diperbarui');
    }

    public function destroy($id)
    {
        // Temukan user berdasarkan ID
        $user = User::find($id);

        if (!$user) {
            // Redirect atau tampilkan pesan error jika user tidak ditemukan
            return Redirect::back()->withErrors(['User not found.']);
        }

        // Periksa apakah pengguna yang sedang login mencoba menghapus akun mereka sendiri
        if (Auth::id() == $user->id) {
            return Redirect::back()->withErrors(['Maaf Anda Sedang Login Sebagai Akun Tersebut.']);
        }

        // Hapus user
        $user->delete();

        // Redirect ke halaman daftar user dengan pesan sukses
        return Redirect::route('index-user')->with('success', 'User successfully deleted.');
    }
}
