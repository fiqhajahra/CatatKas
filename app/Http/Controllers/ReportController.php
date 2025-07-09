<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Enums\TransactionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        // Get basic statistics
        $totalBalance = $user->getTotalBalance();
        $totalIncome = $user->getTotalIncome();
        $totalExpense = $user->getTotalExpense();
        $totalTransactions = $user->transactions()->count();

        // Get current month statistics
        $currentMonthIncome = $user->getCurrentMonthTotalIncome();
        $currentMonthExpense = $user->getCurrentMonthTotalExpense();
        $currentMonthBalance = $currentMonthIncome - $currentMonthExpense;

        // Get monthly data for the last 6 months
        $monthlyData = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);

            $transactions = $user->transactions()
                ->whereYear('transactions_date', $date->year)
                ->whereMonth('transactions_date', $date->month)
                ->get();

            $monthlyData->push([
                'label' => $date->format('M'),
                'income' => $transactions->where('type', TransactionType::Income)->sum('amount'),
                'expense' => $transactions->where('type', TransactionType::Expense)->sum('amount'),
            ]);
        }

        // Get yearly trend data for the last 12 months
        $yearlyTrendData = collect();
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);

            $transactions = $user->transactions()
                ->whereYear('transactions_date', $date->year)
                ->whereMonth('transactions_date', $date->month)
                ->get();

            $income = $transactions->where('type', TransactionType::Income)->sum('amount');
            $expense = $transactions->where('type', TransactionType::Expense)->sum('amount');

            $yearlyTrendData->push([
                'label' => $date->format('M'),
                'income' => $income,
                'expense' => $expense,
                'balance' => $income - $expense,
            ]);
        }

        // Calculate averages for summary cards
        $avgIncome = $yearlyTrendData->avg('income');
        $avgExpense = $yearlyTrendData->avg('expense');
        $avgBalance = $yearlyTrendData->avg('balance');

        // Get recent transactions for quick overview
        $recentTransactions = $user->transactions()
            ->latest()
            ->take(5)
            ->get();

        $data = [
            'totalBalance' => $totalBalance,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'totalTransactions' => $totalTransactions,
            'currentMonthIncome' => $currentMonthIncome,
            'currentMonthExpense' => $currentMonthExpense,
            'currentMonthBalance' => $currentMonthBalance,
            'monthlyData' => $monthlyData,
            'yearlyTrendData' => $yearlyTrendData,
            'avgIncome' => $avgIncome,
            'avgExpense' => $avgExpense,
            'avgBalance' => $avgBalance,
            'recentTransactions' => $recentTransactions,
        ];

        return view('reports.index', $data);
    }

    public function monthly(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $month = $request->month ?? now()->month;
        $year = $request->year ?? now()->year;

        $transactions = $user->transactions()
            ->whereYear('transactions_date', $year)
            ->whereMonth('transactions_date', $month)
            ->latest()
            ->get();

        $summary = [
            'month' => Carbon::create($year, $month)->format('F Y'),
            'total_income' => $transactions->where('type', TransactionType::Income)->sum('amount'),
            'total_expense' => $transactions->where('type', TransactionType::Expense)->sum('amount'),
            'transaction_count' => $transactions->count(),
            'income_count' => $transactions->where('type', TransactionType::Income)->count(),
            'expense_count' => $transactions->where('type', TransactionType::Expense)->count(),
        ];

        $summary['balance'] = $summary['total_income'] - $summary['total_expense'];

        // Grup transaksi berdasarkan tanggal
        $groupedTransactions = $transactions->groupBy(function ($transaction) {
            return $transaction->transactions_date->format('Y-m-d');
        });

        return view('reports.monthly', compact('summary', 'groupedTransactions', 'month', 'year'));
    }

    public function yearly(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $year = $request->year ?? now()->year;

        $transactions = $user->transactions()
            ->whereYear('transactions_date', $year)
            ->latest()
            ->get();

        $summary = [
            'year' => $year,
            'total_income' => $transactions->where('type', TransactionType::Income)->sum('amount'),
            'total_expense' => $transactions->where('type', TransactionType::Expense)->sum('amount'),
            'transaction_count' => $transactions->count(),
        ];

        $summary['balance'] = $summary['total_income'] - $summary['total_expense'];

        // Data per bulan
        $monthlyData = collect();
        for ($month = 1; $month <= 12; $month++) {
            $monthTransactions = $transactions->filter(function ($transaction) use ($month) {
                return $transaction->transactions_date->month == $month;
            });

            $monthlyData->push([
                'month' => Carbon::create($year, $month)->format('F'),
                'month_number' => $month,
                'income' => $monthTransactions->where('type', TransactionType::Income)->sum('amount'),
                'expense' => $monthTransactions->where('type', TransactionType::Expense)->sum('amount'),
                'count' => $monthTransactions->count(),
            ]);
        }

        return view('reports.yearly', compact('summary', 'monthlyData', 'year'));
    }

    public function dateRange(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $startDate = $request->start_date ?? now()->startOfMonth();
        $endDate = $request->end_date ?? now()->endOfMonth();

        $transactions = $user->transactions()
            ->whereBetween('transactions_date', [$startDate, $endDate])
            ->latest()
            ->get();

        $summary = [
            'start_date' => Carbon::parse($startDate)->format('d F Y'),
            'end_date' => Carbon::parse($endDate)->format('d F Y'),
            'total_income' => $transactions->where('type', TransactionType::Income)->sum('amount'),
            'total_expense' => $transactions->where('type', TransactionType::Expense)->sum('amount'),
            'transaction_count' => $transactions->count(),
        ];

        $summary['balance'] = $summary['total_income'] - $summary['total_expense'];

        // Grup transaksi berdasarkan tanggal
        $groupedTransactions = $transactions->groupBy(function ($transaction) {
            return $transaction->transactions_date->format('Y-m-d');
        });

        return view('reports.date-range', compact('summary', 'groupedTransactions', 'startDate', 'endDate'));
    }
}
