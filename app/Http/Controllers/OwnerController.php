<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class OwnerController extends Controller
{
    public function dashboard()
    {
        $transactions = DB::table('transactions')
            ->orderBy('id', 'desc')
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

        return view('owner.dashboard', compact('transactions'));
    }
}
