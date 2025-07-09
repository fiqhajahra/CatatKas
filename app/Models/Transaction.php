<?php

namespace App\Models;

use App\Enums\TransactionType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'description',
        'transactions_date'
    ];

    // relationship
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** 
     * The Attribute should be cast
     */
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'type' => TransactionType::class,
            'transactions_date' => 'date',
        ];
    }

    // Scope a query to only include income transactions.
    public function scopeIncome($query)
    {
        return $query->where('type', TransactionType::Income);
    }

    public function scopeExpense($query)
    {
        return $query->where('type', TransactionType::Expense);
    }

    // Check if transaction is income.
    public function isIncome(): bool
    {
        return $this->type === TransactionType::Income;
    }


    // Check if transaction is expense.
    public function isExpense(): bool
    {
        return $this->type === TransactionType::Expense;
    }


    // Scope a query to filter by date range.
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('transactions_date', [$startDate, $endDate]);
    }

    public function scopeCurrentMonth($query)
    {
        return $query->whereYear('transactions_date', now()->year)
            ->whereMonth('transactions_date', now()->month);
    }

    public function scopeCurrentYear($query)
    {
        return $query->whereYear('transactions_date', now()->year);
    }

    public function scopeByMonth($query, $month, $year = null)
    {
        $year = $year ?? now()->year;
        return $query->whereYear('transactions_date', $year)
            ->whereMonth('transactions_date', $month);
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('transactions_date', 'desc')
            ->orderBy('created_at', 'desc');
    }
}
