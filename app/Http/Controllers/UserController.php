<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function showNasabah()
    {
        $listnasabah = User::where('role', 'nasabah')->get();
        return view('admin.user_nasabah', compact('listnasabah'));
    }
    public function showAdmin()
    {
        $listadmin = User::where('role', 'admin')->get();
        return view('admin.user_admin', compact('listadmin'));
    }
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email', // otomatis validasi mengandung '@'
            'no_hp' => 'required|numeric|digits_between:10,15',
            'alamat' => 'required'
        ], [
            'email.email' => 'Format email tidak valid.',
            'no_hp.digits_between' => 'No HP Minimal 10-15 Angka.',
        ]);
    
        $user = User::findOrFail($id);
    
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat
        ]);
    
        return redirect()->back()->with('success', 'Data berhasil diperbarui!');
    }
        public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'Data nasabah berhasil dihapus!');
    }

public function createAdmin()
{
    if (auth()->user()->role !== 'super_admin') {
        abort(403, 'Akses ditolak.');
    }

    return view('admin.create_admin');
}

public function createNasabah()
{
    if (!in_array(auth()->user()->role, ['admin', 'super_admin'])) {
        abort(403, 'Akses ditolak.');
    }

    return view('admin.create_nasabah');
}

public function store(Request $request)
{
    $validated = $request->validate([
        'name'      => 'required|string|max:255',
        'last_name' => 'nullable|string|max:255',
        'email'     => 'required|email|unique:users,email',
        'no_hp'     => 'required|numeric|digits_between:10,15',
        'alamat'    => 'required|string|max:255',
        'password'  => 'required|string|min:6',
        'role'      => 'required|in:nasabah,admin', // hanya dua opsi valid
    ]);

    $user = User::create([
        'name'      => $validated['name'],
        'last_name' => $validated['last_name'] ?? null,
        'email'     => $validated['email'],
        'no_hp'     => $validated['no_hp'],
        'alamat'    => $validated['alamat'],
        'password'  => $validated['password'],
        'role'      => $validated['role'],
    ]);

    // Redirect sesuai role
    if ($validated['role'] === 'nasabah') {
        return redirect()->route('nasabah.show')->with('success', 'Nasabah baru berhasil ditambahkan!');
    }

    return redirect()->route('admin.show')->with('success', 'Admin baru berhasil ditambahkan!');
}




}