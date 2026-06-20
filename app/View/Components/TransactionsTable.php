<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TransactionsTable extends Component
{
    public $transactions;

    public function __construct($transactions)
    {
        $this->transactions = $transactions;
    }

    public function render(): View|Closure|string
    {
        return view('components.transactions-table');
    }
}