<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    // Tampilkan daftar nasabah
    public function nasabahIndex()
    {
        $listnasabah = User::where("role", "nasabah")->get();
        return view("admin.user_nasabah", compact("listnasabah"));
    }

    // Tampilkan daftar admin
    public function adminIndex()
    {
        $listadmin = User::where("role", "admin")->get();
        return view("admin.user_admin", compact("listadmin"));
    }

    // Tampilkan form tambah admin
    public function adminCreate()
    {
        if (auth()->user()->role !== "super_admin") {
            abort(403, "Akses ditolak.");
        }

        return view("admin.create_admin");
    }

    // Tampilkan form tambah nasabah
    public function nasabahCreate()
    {
        if (!in_array(auth()->user()->role, ["admin", "super_admin"])) {
            abort(403, "Akses ditolak.");
        }

        return view("admin.create_nasabah");
    }

    // Simpan admin atau nasabah baru
    public function adminStore(Request $request)
    {
        return $this->store($request);
    }

    public function nasabahStore(Request $request)
    {
        return $this->store($request);
    }

    // Fungsi umum untuk menyimpan user
    private function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|string|max:255",
            "last_name" => "nullable|string|max:255",
            "email" => "required|email|unique:users,email",
            "no_hp" => "required|numeric|digits_between:10,15",
            "alamat" => "required|string|max:255",
            "password" => "required|string|min:6",
            "role" => "required|in:nasabah,admin", // hanya dua opsi yang valid
        ]);

        User::create([
            "name" => $validated["name"],
            "last_name" => $validated["last_name"] ?? null,
            "email" => $validated["email"],
            "no_hp" => $validated["no_hp"],
            "alamat" => $validated["alamat"],
            "password" => $validated["password"],
            "role" => $validated["role"],
        ]);

        // Redirect sesuai role
        if ($validated["role"] === "nasabah") {
            return redirect()
                ->route("nasabah.index")
                ->with("success", "Nasabah baru berhasil ditambahkan!");
        }

        return redirect()
            ->route("admin.index")
            ->with("success", "Admin baru berhasil ditambahkan!");
    }

    // Update user (admin/nasabah)
    public function update(Request $request, $id)
    {
        $validated = $request->validate(
            [
                "name" => "required",
                "email" => "required|email",
                "no_hp" => "required|numeric|digits_between:10,15",
                "alamat" => "required",
            ],
            [
                "email.email" => "Format email tidak valid.",
                "no_hp.digits_between" => "No HP Minimal 10-15 Angka.",
            ]
        );

        $user = User::findOrFail($id);
        $user->update($validated);

        return redirect()->back()->with("success", "Data berhasil diperbarui!");
    }

    // Hapus user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()
            ->back()
            ->with("success", "Data pengguna berhasil dihapus!");
    }
}
