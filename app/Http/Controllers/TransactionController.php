<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Enums\TransactionType;
use App\Http\Requests\TransactionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Carbon\Carbon;

class TransactionController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $query = $user->transactions();

        // Filter berdasarkan type menggunakan scope
        if ($request->filled('type')) {
            if ($request->type === 'income') {
                $query->income();
            } elseif ($request->type === 'expense') {
                $query->expense();
            }
        }

        // Filter berdasarkan tanggal menggunakan scope
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->dateRange($request->start_date, $request->end_date);
        }

        // Filter berdasarkan bulan menggunakan scope
        if ($request->filled('month')) {
            $month = $request->month;
            $year = $request->year ?? now()->year;
            $query->byMonth($month, $year);
        }

        // Search berdasarkan deskripsi
        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        // Menggunakan scope latest yang sudah ada
        $transactions = $query->latest()->paginate(15);

        // Data summary menggunakan method yang sudah ada di User model
        $summary = $this->getSummary($user, $request);

        return view('transactions.index', compact('transactions', 'summary'));
    }

    /**
     * Get summary data based on current filters
     */
    private function getSummary($user, $request)
    {
        $query = $user->transactions();

        // Apply same filters as main query
        if ($request->filled('type')) {
            if ($request->type === TransactionType::Income) {
                $query->income();
            } elseif ($request->type === TransactionType::Expense) {
                $query->expense();
            }
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->dateRange($request->start_date, $request->end_date);
        }

        if ($request->filled('month')) {
            $month = $request->month;
            $year = $request->year ?? now()->year;
            $query->byMonth($month, $year);
        }

        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        return [
            'total_income' => (clone $query)->income()->sum('amount'),
            'total_expense' => (clone $query)->expense()->sum('amount'),
            'count' => $query->count(),
            'balance' => (clone $query)->income()->sum('amount') - (clone $query)->expense()->sum('amount')
        ];
    }

    public function create()
    {
        return view('transactions.create');
    }

    public function store(TransactionRequest $request)
    {
        $validated = $request->validated();

        /** @var User $user */
        $user = Auth::user();

        $user->transactions()->create([
            'type' => $validated['type'] === 'income' ? TransactionType::Income : TransactionType::Expense,
            'amount' => $validated['amount'],
            'description' => $validated['description'],
            'transactions_date' => $validated['transactions_date'],
        ]);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil ditambahkan!');
    }

    public function show(Transaction $transaction)
    {
        $this->authorize('view', $transaction);

        return view('transactions.show', compact('transaction'));
    }

    public function edit(Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        return view('transactions.edit', compact('transaction'));
    }

    public function update(TransactionRequest $request, Transaction $transaction)
    {
        $validated = $request->validated();

        $transaction->update([
            'type' => $validated['type'] === 'income' ? TransactionType::Income : TransactionType::Expense,
            'amount' => $validated['amount'],
            'description' => $validated['description'],
            'transactions_date' => $validated['transactions_date'],
        ]);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil diperbarui!');
    }

    public function destroy(Transaction $transaction)
    {
        $this->authorize('delete', $transaction);

        $transaction->delete();

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil dihapus!');
    }

    public function export(Request $request)
    {
        // Implementasi export ke Excel/CSV
        // Bisa menggunakan package seperti Laravel Excel
    }
}
