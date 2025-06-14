<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SampahController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TarikSaldoController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\NotaController;
use App\Http\Controllers\OrderListController;
use App\Http\Controllers\SetoranController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;

Route::get("/", function () {
    return view("welcome");
});

Auth::routes();

Route::get("/home", "HomeController@index")->name("home");

Route::get("/profile", "ProfileController@index")->name("profile");
Route::put("/profile", "ProfileController@update")->name("profile.update");

Route::get("/about", function () {
    return view("about");
})->name("about");

Route::get("/sampah", [SampahController::class, "index"])->name("sampah.index");
/* Route::get('/sampah/edit/{id}', [SampahController::class, 'edit'])->name('sampah.edit');
 Route::put('/sampah/update/{id}', [SampahController::class, 'update'])->name('sampah.update'); */
Route::delete("/sampah/{id}", [SampahController::class, "destroy"])->name(
    "sampah.destroy"
);
Route::put("/sampah/{id}", [SampahController::class, "update"])->name(
    "sampah.update"
);

Route::get("/user-nasabah", [UserController::class, "showNasabah"])->name(
    "user.nasabah"
);
Route::put("/user/{id}", [UserController::class, "update"])->name(
    "user.update"
);
Route::delete("/user/{id}", [UserController::class, "destroy"])->name(
    "user.destroy"
);
Route::get("/user-admin", [UserController::class, "showAdmin"])->name(
    "user.admin"
);

Route::get("/admin/create", [UserController::class, "createAdmin"])->name(
    "admin.create"
);
Route::post("/admin/store", [UserController::class, "store"])->name(
    "admin.store"
);
Route::get("/admin", [UserController::class, "showAdmin"])->name("admin.show");

Route::middleware(["auth"])->group(function () {
    // Tampilkan form create nasabah
    Route::get("/nasabah/create", [
        UserController::class,
        "createNasabah",
    ])->name("nasabah.create");
    // Simpan nasabah
    Route::post("/nasabah", [UserController::class, "store"])->name(
        "nasabah.store"
    );
    // Halaman index nasabah
    Route::get("/nasabah", [UserController::class, "showNasabah"])->name(
        "nasabah.show"
    );
});

Route::get("/tarik-saldo", [TarikSaldoController::class, "index"])->name(
    "tarik.index"
);
Route::post("/tarik-saldo", [TarikSaldoController::class, "store"])->name(
    "tarik.store"
);
Route::get("/nota-tarik/{id}", [TarikSaldoController::class, "nota"])->name(
    "tarik.nota"
);

Route::get("/riwayat", [RiwayatController::class, "index"])->name(
    "riwayat.index"
);

Route::get("/nota/{id}", [NotaController::class, "show"])->name("nota.show");

Route::get("/orderlist", [OrderListController::class, "showOrderList"])->name(
    "orderlist"
);
Route::put("/orderlist/{id}/status", [
    OrderListController::class,
    "updateStatus",
])->name("orderlist.updateStatus");

Route::get("/setoran", [SetoranController::class, "create"])->name("setoran");

Route::post("/setoran/konfirmasi", [
    SetoranController::class,
    "konfirmasiSetor",
])->name("setoran.konfirmasi");
Route::post("/setoran/simpan", [SetoranController::class, "simpan"])->name(
    "setoran.simpan"
);

Route::post("/setor/konfirmasi", [
    SetoranController::class,
    "konfirmasiSetor",
])->name("setor.konfirmasi");

Route::post("/konfirmasi", [SetoranController::class, "konfirmasi"])->name(
    "setoran.konfirmasi"
);
// web.php
Route::post("/simpan", [SetoranController::class, "simpan"])->name(
    "setoran.simpan"
);

Route::post("/setoran/konfirmasi", [
    SetoranController::class,
    "konfirmasiSetor",
])->name("setor.konfirmasi");
