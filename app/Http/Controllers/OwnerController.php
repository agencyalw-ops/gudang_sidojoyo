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

        foreach ($transactions as $transaction) {

            $transaction->items = DB::table('transaction_items')
                ->join(
                    'products',
                    'transaction_items.product_id',
                    '=',
                    'products.id'
                )
                ->where(
                    'transaction_items.transaction_id',
                    $transaction->id
                )
                ->select(
                    'products.name',
                    'transaction_items.qty',
                    'transaction_items.price',
                    'transaction_items.subtotal'
                )
                ->get();
        }

        return view(
            'owner.dashboard',
            compact('transactions')
        );
    }
}