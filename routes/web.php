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

Route::get("/search", [
    App\Http\Controllers\SearchController::class,
    "index",
])->name("search");

Route::get("/profile", "ProfileController@index")->name("profile");
Route::put("/profile", "ProfileController@update")->name("profile.update");

//sampah
Route::get("/sampah", [SampahController::class, "show"])->name("sampah.show");

Route::delete("/sampah/{id}", [SampahController::class, "destroy"])->name(
    "sampah.destroy"
);
Route::put("/sampah/{id}", [SampahController::class, "update"])->name(
    "sampah.update"
);

Route::middleware(["auth"])->group(function () {
    // Admin
    Route::get("/admin", [UserController::class, "adminIndex"])->name(
        "admin.index"
    );
    Route::get("/admin/create", [UserController::class, "adminCreate"])->name(
        "admin.create"
    );
    Route::post("/admin", [UserController::class, "adminStore"])->name(
        "admin.store"
    );

    // Nasabah
    Route::get("/nasabah", [UserController::class, "nasabahIndex"])->name(
        "nasabah.index"
    );
    Route::get("/nasabah/create", [
        UserController::class,
        "nasabahCreate",
    ])->name("nasabah.create");
    Route::post("/nasabah", [UserController::class, "nasabahStore"])->name(
        "nasabah.store"
    );

    // Umum (edit/hapus)
    Route::put("/user/{id}", [UserController::class, "update"])->name(
        "user.update"
    );
    Route::delete("/user/{id}", [UserController::class, "destroy"])->name(
        "user.destroy"
    );
});

//tarik
Route::get("/tarik-saldo", [TarikSaldoController::class, "show"])->name(
    "tarik.show"
);
Route::post("/tarik-saldo", [TarikSaldoController::class, "store"])->name(
    "tarik.store"
);

//riwayat
Route::get("/riwayat", [RiwayatController::class, "show"])->name(
    "riwayat.show"
);

//nota
Route::get("/nota/{id}", [NotaController::class, "show"])->name("nota.show");

//orderlist
Route::get("/orderlist", [OrderListController::class, "show"])->name(
    "orderlist.show"
);
Route::put("/orderlist/{id}/status", [
    OrderListController::class,
    "updateStatus",
])->name("orderlist.updateStatus");

//setoran
Route::get("/setoran", [SetoranController::class, "show"])->name("setoran");

Route::post("/setoran/konfirmasi", [
    SetoranController::class,
    "Setorankonfirmasi",
])->name("setoran.konfirmasi");

Route::post("/konfirmasi", [SetoranController::class, "konfirmasi"])->name(
    "final.konfirmasi"
);