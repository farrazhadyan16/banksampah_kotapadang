<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware("guest");
    }

    /**
     * Tambahkan method ini!
     */
    public function showRegistrationForm()
    {
        return view("auth.register"); // ini akan pakai Blade yang kamu buat
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            "name" => ["required", "string", "max:255"],
            "last_name" => ["required", "string", "max:255"],
            "email" => [
                "required",
                "string",
                "email",
                "max:255",
                "unique:users",
            ],
            "password" => ["required", "string", "min:8", "confirmed"],
            "no_hp" => ["required", "digits_between:10,15"],
            "alamat" => ["required", "string"],
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            "name" => $data["name"],
            "last_name" => $data["last_name"],
            "email" => $data["email"],
            "no_hp" => $data["no_hp"],
            "alamat" => $data["alamat"],
            // WAJIB hash!
            "password" => bcrypt($data["password"]),
            "role" => "nasabah",
            "saldo" => 0,
        ]);
    }
}
