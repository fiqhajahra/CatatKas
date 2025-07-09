<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Enums\TransactionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use app\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        
        // Data untuk kartu statistik
        $totalBalance = $user->getTotalBalance();
        $totalIncome = $user->getTotalIncome();
        $totalExpense = $user->getTotalExpense();
        
        // Data transaksi bulan ini
        $currentMonthTransactions = $user->getCurrentMonthTransactions()->get();
        $currentMonthIncome = $user->getCurrentMonthTransactions()
            ->where('type', TransactionType::Income)
            ->sum('amount');
        $currentMonthExpense = $user->getCurrentMonthTransactions()
            ->where('type', TransactionType::Expense)
            ->sum('amount');
        
        // Transaksi terbaru (5 terakhir)
        $recentTransactions = $user->transactions()
            ->latest()
            ->limit(5)
            ->get();
        
        // Data untuk grafik bulanan (6 bulan terakhir)
        $monthlyData = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $income = $user->transactions()
                ->where('type', TransactionType::Income)
                ->whereYear('transactions_date', $date->year)
                ->whereMonth('transactions_date', $date->month)
                ->sum('amount');
            $expense = $user->transactions()
                ->where('type', TransactionType::Expense)
                ->whereYear('transactions_date', $date->year)
                ->whereMonth('transactions_date', $date->month)
                ->sum('amount');
            
            $monthlyData->push([
                'month' => $date->format('M Y'),
                'income' => $income,
                'expense' => $expense,
                'balance' => $income - $expense
            ]);
        }
        
        return view('dashboard', compact(
            'totalBalance',
            'totalIncome', 
            'totalExpense',
            'currentMonthIncome',
            'currentMonthExpense',
            'recentTransactions',
            'monthlyData'
        ));
    }
}