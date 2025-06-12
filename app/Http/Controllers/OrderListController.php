<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setoran;

class OrderListController extends Controller
{
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Completed,Processing,Rejected',
        ]);

        $order = Setoran::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        $order->refresh();

        return redirect()->route('orderlist')->with('success', 'Status berhasil diperbarui.');
    }

    public function showOrderList(Request $request)
    {
        $query = Setoran::with('user');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'id_riwayat');
        $sortDirection = $request->input('sort_direction', 'desc');

        if (in_array($sortBy, ['id_riwayat', 'id_nasabah', 'created_at'])) {
            $query->orderBy($sortBy, $sortDirection);
        }

        $orders = $query->paginate(10)->appends($request->all());

        return view('orderlist', compact('orders'));
    }
}