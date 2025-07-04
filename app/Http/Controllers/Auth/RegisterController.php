<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
class RegisterController extends Controller
{
    protected $redirectTo = RouteServiceProvider::HOME;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware("guest");
    }
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
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
            "no_hp" => ["required", "digits_between:10,15"], // hanya angka dan minimal 10 digit
        ]);
    }
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            "name" => $data["name"],
            "last_name" => $data["last_name"],
            "email" => $data["email"],
            "no_hp" => $data["no_hp"],
            "alamat" => $data["alamat"],
            "password" => $data["password"],
            "role" => "nasabah", // role otomatis diset ke nasabah
            "saldo" => 0, // bisa default ke 0
        ]);
    }
}
