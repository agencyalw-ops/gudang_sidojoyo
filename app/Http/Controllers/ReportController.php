<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Show cashier history with filtering options
     */
    public function kasirHistory(Request $request)
    {
        $query = DB::table('transactions')
            ->orderBy('created_at', 'desc');

        if ($request->filled('kasir_name')) {
            $query->where('kasir_name', $request->kasir_name);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $transactions = $query->get();

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

        $cashiers = DB::table('transactions')
            ->distinct()
            ->pluck('kasir_name')
            ->sort();

        return view('reports.kasir-history', compact('transactions', 'cashiers'));
    }

    /**
     * Show monthly cashier performance report
     */
    public function kasirPerformance(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year  = $request->input('year', now()->year);

        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate   = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        $transactions = DB::table('transactions')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $cashierPerformance = [];

        foreach ($transactions as $transaction) {

            $name = $transaction->kasir_name;

            if (!isset($cashierPerformance[$name])) {
                $cashierPerformance[$name] = [
                    'kasir_name' => $name,
                    'total_transactions' => 0,
                    'total_sales' => 0,
                    'total_items' => 0,
                    'average_transaction' => 0,
                    'transactions' => []
                ];
            }

            $cashierPerformance[$name]['total_transactions']++;
            $cashierPerformance[$name]['total_sales'] += (float) $transaction->total;
            $cashierPerformance[$name]['transactions'][] = $transaction;
        }

        foreach ($cashierPerformance as &$performance) {

            if ($performance['total_transactions'] > 0) {
                $performance['average_transaction'] =
                    (int) round(
                        $performance['total_sales'] / $performance['total_transactions']
                    );
            }

            $transactionIds = collect($performance['transactions'])->pluck('id');

            $performance['total_items'] = DB::table('transaction_items')
                ->whereIn('transaction_id', $transactionIds)
                ->sum('qty');
        }

        uasort($cashierPerformance, function ($a, $b) {
            return $b['total_sales'] <=> $a['total_sales'];
        });

        $totalSales = array_sum(array_map('floatval', array_column($cashierPerformance, 'total_sales')));
        $totalTransactions = array_sum(array_column($cashierPerformance, 'total_transactions'));
        $averageSales = $totalTransactions > 0 ? (int) round($totalSales / $totalTransactions) : 0;

        // PostgreSQL SAFE VERSION
        $availableMonths = DB::table('transactions')
            ->selectRaw('EXTRACT(YEAR FROM created_at) as year, EXTRACT(MONTH FROM created_at) as month')
            ->distinct()
            ->orderByRaw('EXTRACT(YEAR FROM created_at) DESC')
            ->orderByRaw('EXTRACT(MONTH FROM created_at) DESC')
            ->limit(12)
            ->get();

        return view('reports.kasir-performance', compact(
            'cashierPerformance',
            'month',
            'year',
            'totalSales',
            'totalTransactions',
            'averageSales',
            'availableMonths'
        ));
    }

    /**
     * Show detailed cashier performance for a specific cashier
     */
    public function kasirDetail($kasirName, Request $request)
    {
        $month = $request->input('month', now()->month);
        $year  = $request->input('year', now()->year);

        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate   = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        $transactions = DB::table('transactions')
            ->where('kasir_name', $kasirName)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
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

        $totalSales = (float) $transactions->sum('total');
        $totalTransactions = $transactions->count();

        $averageTransaction = $totalTransactions > 0
            ? (int) round($totalSales / $totalTransactions)
            : 0;

        $totalItems = DB::table('transaction_items')
            ->whereIn('transaction_id', $transactionIds)
            ->sum('qty');

        return view('reports.kasir-detail', compact(
            'kasirName',
            'transactions',
            'month',
            'year',
            'totalSales',
            'totalTransactions',
            'averageTransaction',
            'totalItems'
        ));
    }

    /**
     * CANCEL TRANSACTION + RESTORE STOCK (SAFE VERSION)
     */
    public function cancelTransaction($transactionId)
    {
        DB::beginTransaction();

        try {

            $transaction = DB::table('transactions')
                ->where('id', $transactionId)
                ->first();

            if (!$transaction) {
                return back()->with('error', 'Transaction not found');
            }

            if (($transaction->status ?? 'completed') === 'cancelled') {
                return back()->with('error', 'Already cancelled');
            }

            $items = DB::table('transaction_items')
                ->where('transaction_id', $transactionId)
                ->get();

            foreach ($items as $item) {
                DB::table('products')
                    ->where('id', $item->product_id)
                    ->increment('stock', (int) $item->qty);
            }

            DB::table('transactions')
                ->where('id', $transactionId)
                ->update([
                    'status' => 'cancelled',
                    'updated_at' => now()
                ]);

            DB::commit();

            return back()->with('success', 'Transaction cancelled & stock restored');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }
}