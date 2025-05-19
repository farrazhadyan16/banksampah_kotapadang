<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SetorSampah;


class OrderListController extends Controller
{
public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:Completed,Processing,Rejected',
    ]);

    $order = SetorSampah::findOrFail($id);
    $order->status = $request->status;
    $order->save();

    $order->refresh();

    return redirect()->route('orderlist')->with('success', 'Status berhasil diperbarui.');
}
public function showOrderList(Request $request)
    {
        $query = SetorSampah::with('user');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by tanggal (ubah 'created_at' jika kolomnya berbeda)
        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }



        // Ambil parameter sort_by dan sort_direction, dengan default
        $sortBy = $request->input('sort_by', 'id_riwayat');
        $sortDirection = $request->input('sort_direction', 'desc');

        // Validasi kolom sorting yang diizinkan
        if (in_array($sortBy, ['id_riwayat', 'id_nasabah', 'created_at'])) {
            $query->orderBy($sortBy, $sortDirection);
        }

        $orders = $query->paginate(10)->appends($request->all());

        return view('orderlist', compact('orders'));
    }

}