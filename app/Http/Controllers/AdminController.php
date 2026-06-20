<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $transactions = DB::table('transactions')
            ->orderBy('id', 'desc')
            ->limit(20)
            ->get();

        $transactionIds = $transactions->pluck('id');

        $items = DB::table('transaction_items')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->select(
                'transaction_items.transaction_id',
                'products.name',
                'transaction_items.qty',
                'transaction_items.price',
                'transaction_items.subtotal'
            )
            ->whereIn('transaction_items.transaction_id', $transactionIds)
            ->get()
            ->groupBy('transaction_id');

        foreach ($transactions as $transaction) {
            $transaction->items = $items->get($transaction->id, collect());
        }

        return view('admin.dashboard', compact('transactions'));
    }

    public function deleteTransaction($id)
    {
        DB::beginTransaction();

        try {
            $items = DB::table('transaction_items')
                ->where('transaction_id', $id)
                ->get();

            foreach ($items as $item) {
                DB::table('products')
                    ->where('id', $item->product_id)
                    ->increment('stock', $item->qty);
            }

            DB::table('transaction_items')
                ->where('transaction_id', $id)
                ->delete();

            DB::table('transactions')
                ->where('id', $id)
                ->delete();

            DB::commit();

            return back()->with('success', 'Transaksi dihapus dan stok dikembalikan');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }
}
