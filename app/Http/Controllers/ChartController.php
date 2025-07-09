<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Enums\TransactionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class ChartController extends Controller
{
    /**
     * Get monthly chart data for the last 6 months
     */
    public function monthlyChart(): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        
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
        
        return response()->json([
            'labels' => $monthlyData->pluck('label'),
            'income' => $monthlyData->pluck('income'),
            'expense' => $monthlyData->pluck('expense'),
        ]);
    }
    
    /**
     * Get yearly trend chart data for the last 12 months
     */
    public function yearlyTrendChart(): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        
        $yearlyData = collect();
        
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            
            $transactions = $user->transactions()
                ->whereYear('transactions_date', $date->year)
                ->whereMonth('transactions_date', $date->month)
                ->get();
                
            $income = $transactions->where('type', TransactionType::Income)->sum('amount');
            $expense = $transactions->where('type', TransactionType::Expense)->sum('amount');
                
            $yearlyData->push([
                'label' => $date->format('M'),
                'income' => $income,
                'expense' => $expense,
                'balance' => $income - $expense,
            ]);
        }
        
        return response()->json([
            'labels' => $yearlyData->pluck('label'),
            'income' => $yearlyData->pluck('income'),
            'expense' => $yearlyData->pluck('expense'),
            'balance' => $yearlyData->pluck('balance'),
        ]);
    }
    
    /**
     * Get pie chart data for transaction distribution
     */
    public function distributionChart(): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        
        $totalIncome = $user->getTotalIncome();
        $totalExpense = $user->getTotalExpense();
        
        return response()->json([
            'labels' => ['Pemasukan', 'Pengeluaran'],
            'data' => [$totalIncome, $totalExpense],
            'total' => $totalIncome + $totalExpense,
        ]);
    }
    
    /**
     * Get summary statistics
     */
    public function summaryStats(): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        
        // Get data for last 12 months to calculate averages
        $twelveMonthsData = collect();
        
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            
            $transactions = $user->transactions()
                ->whereYear('transactions_date', $date->year)
                ->whereMonth('transactions_date', $date->month)
                ->get();
                
            $income = $transactions->where('type', TransactionType::Income)->sum('amount');
            $expense = $transactions->where('type', TransactionType::Expense)->sum('amount');
            
            $twelveMonthsData->push([
                'income' => $income,
                'expense' => $expense,
                'balance' => $income - $expense,
            ]);
        }
        
        // Calculate averages
        $avgIncome = $twelveMonthsData->avg('income');
        $avgExpense = $twelveMonthsData->avg('expense');
        $avgBalance = $twelveMonthsData->avg('balance');
        
        // Get total transaction count
        $totalTransactions = $user->transactions()->count();
        
        return response()->json([
            'avgIncome' => $avgIncome,
            'avgExpense' => $avgExpense,
            'avgBalance' => $avgBalance,
            'totalTransactions' => $totalTransactions,
        ]);
    }
    
    /**
     * Get chart data for specific date range
     */
    public function dateRangeChart(Request $request): JsonResponse
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
        
        /** @var User $user */
        $user = Auth::user();
        
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        
        $transactions = $user->transactions()
            ->whereBetween('transactions_date', [$startDate, $endDate])
            ->get();
            
        // Group by day if range is <= 31 days, otherwise group by month
        $daysDiff = $startDate->diffInDays($endDate);
        
        if ($daysDiff <= 31) {
            // Group by day
            $data = collect();
            $current = $startDate->copy();
            
            while ($current <= $endDate) {
                $dayTransactions = $transactions->filter(function($transaction) use ($current) {
                    return $transaction->transactions_date->isSameDay($current);
                });
                
                $data->push([
                    'label' => $current->format('d/m'),
                    'income' => $dayTransactions->where('type', TransactionType::Income)->sum('amount'),
                    'expense' => $dayTransactions->where('type', TransactionType::Expense)->sum('amount'),
                ]);
                
                $current->addDay();
            }
        } else {
            // Group by month
            $data = collect();
            $current = $startDate->copy()->startOfMonth();
            
            while ($current <= $endDate) {
                $monthTransactions = $transactions->filter(function($transaction) use ($current) {
                    return $transaction->transactions_date->year == $current->year &&
                           $transaction->transactions_date->month == $current->month;
                });
                
                $data->push([
                    'label' => $current->format('M Y'),
                    'income' => $monthTransactions->where('type', TransactionType::Income)->sum('amount'),
                    'expense' => $monthTransactions->where('type', TransactionType::Expense)->sum('amount'),
                ]);
                
                $current->addMonth();
            }
        }
        
        return response()->json([
            'labels' => $data->pluck('label'),
            'income' => $data->pluck('income'),
            'expense' => $data->pluck('expense'),
            'period' => $daysDiff <= 31 ? 'daily' : 'monthly',
        ]);
    }
    
    /**
     * Get top expense categories (if you have categories)
     */
    public function topExpenseCategories(): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        
        // Since we don't have categories, let's group by description patterns
        $expenses = $user->transactions()
            ->where('type', TransactionType::Expense)
            ->get()
            ->groupBy('description')
            ->map(function($group) {
                return [
                    'description' => $group->first()->description,
                    'total' => $group->sum('amount'),
                    'count' => $group->count(),
                ];
            })
            ->sortByDesc('total')
            ->take(5)
            ->values();
        
        return response()->json([
            'labels' => $expenses->pluck('description'),
            'data' => $expenses->pluck('total'),
        ]);
    }
    
    /**
     * Get monthly comparison with previous year
     */
    public function monthlyComparison(): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        
        $currentYear = now()->year;
        $previousYear = $currentYear - 1;
        
        $comparisonData = collect();
        
        for ($month = 1; $month <= 12; $month++) {
            // Current year data
            $currentYearTransactions = $user->transactions()
                ->whereYear('transactions_date', $currentYear)
                ->whereMonth('transactions_date', $month)
                ->get();
                
            // Previous year data
            $previousYearTransactions = $user->transactions()
                ->whereYear('transactions_date', $previousYear)
                ->whereMonth('transactions_date', $month)
                ->get();
            
            $comparisonData->push([
                'month' => Carbon::create($currentYear, $month)->format('M'),
                'currentYearIncome' => $currentYearTransactions->where('type', TransactionType::Income)->sum('amount'),
                'currentYearExpense' => $currentYearTransactions->where('type', TransactionType::Expense)->sum('amount'),
                'previousYearIncome' => $previousYearTransactions->where('type', TransactionType::Income)->sum('amount'),
                'previousYearExpense' => $previousYearTransactions->where('type', TransactionType::Expense)->sum('amount'),
            ]);
        }
        
        return response()->json([
            'labels' => $comparisonData->pluck('month'),
            'currentYear' => [
                'income' => $comparisonData->pluck('currentYearIncome'),
                'expense' => $comparisonData->pluck('currentYearExpense'),
            ],
            'previousYear' => [
                'income' => $comparisonData->pluck('previousYearIncome'),
                'expense' => $comparisonData->pluck('previousYearExpense'),
            ],
            'currentYearLabel' => $currentYear,
            'previousYearLabel' => $previousYear,
        ]);
    }
}