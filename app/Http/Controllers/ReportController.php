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

        // Filter by cashier name if provided
        if ($request->filled('kasir_name')) {
            $query->where('kasir_name', $request->kasir_name);
        }

        // Filter by date range if provided
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $transactions = $query->get();

        // Get transaction items
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

        // Get list of all cashiers for filter dropdown
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
        // Get current month by default, or specified month
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        // Get all transactions for the specified month
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        $transactions = DB::table('transactions')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        // Group transactions by cashier
        $cashierPerformance = [];
        foreach ($transactions as $transaction) {
            if (!isset($cashierPerformance[$transaction->kasir_name])) {
                $cashierPerformance[$transaction->kasir_name] = [
                    'kasir_name' => $transaction->kasir_name,
                    'total_transactions' => 0,
                    'total_sales' => 0,
                    'total_items' => 0,
                    'average_transaction' => 0,
                    'transactions' => []
                ];
            }

            $cashierPerformance[$transaction->kasir_name]['total_transactions']++;
            $cashierPerformance[$transaction->kasir_name]['total_sales'] += $transaction->total;
            $cashierPerformance[$transaction->kasir_name]['transactions'][] = $transaction;
        }

        // Calculate average and total items
        foreach ($cashierPerformance as &$performance) {
            if ($performance['total_transactions'] > 0) {
                $performance['average_transaction'] = round($performance['total_sales'] / $performance['total_transactions']);
            }

            // Get total items sold by this cashier
            $transactionIds = collect($performance['transactions'])->pluck('id');
            $totalItems = DB::table('transaction_items')
                ->whereIn('transaction_id', $transactionIds)
                ->sum('qty');
            $performance['total_items'] = $totalItems;
        }

        // Sort by total sales descending
        uasort($cashierPerformance, function ($a, $b) {
            return $b['total_sales'] <=> $a['total_sales'];
        });

        // Calculate overall statistics
        $totalSales = array_sum(array_column($cashierPerformance, 'total_sales'));
        $totalTransactions = array_sum(array_column($cashierPerformance, 'total_transactions'));
        $averageSales = $totalTransactions > 0 ? round($totalSales / $totalTransactions) : 0;

        // Get available months for filter
        $availableMonths = DB::table('transactions')
            ->selectRaw('DISTINCT YEAR(created_at) as year, MONTH(created_at) as month')
            ->orderByRaw('YEAR(created_at) DESC, MONTH(created_at) DESC')
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
        $year = $request->input('year', now()->year);

        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        // Get all transactions for this cashier in the specified month
        $transactions = DB::table('transactions')
            ->where('kasir_name', $kasirName)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get transaction items
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

        // Calculate statistics
        $totalSales = $transactions->sum('total');
        $totalTransactions = $transactions->count();
        $averageTransaction = $totalTransactions > 0 ? round($totalSales / $totalTransactions) : 0;
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
}
