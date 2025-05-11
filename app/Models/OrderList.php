<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\SetorSampah;

class OrderList extends Model
{
    public function showOrderList()
{
    $orders = SetorSampah::with('user')->get();

    return view('orderlist', compact('orders'));
}
}