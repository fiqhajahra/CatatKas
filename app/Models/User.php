<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\TransactionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // relationship
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    // get user's income transactions
    public function incomeTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class)
            ->where('type', TransactionType::Income);
    }

    // get user's income transactions
    public function expenseTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class)
            ->where('type', TransactionType::Expense);
    }

    // get total user's income
    public function getTotalIncome(): float
    {
        return $this->incomeTransactions()->sum('amount');
    }

    // get total user's expense
    public function getTotalExpense(): float
    {
        return $this->expenseTransactions()->sum('amount');
    }

    // get total user's balance
    public function getTotalBalance(): float
    {
        return $this->getTotalIncome() - $this->getTotalExpense();
    }

    // get user's transactios for current month
    public function getCurrentMonthTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class)
            ->whereYear('transactions_date', now()->year())
            ->whereMonth('transactions_date', now()->month());
    }

    // get user's income transactions for current month
    public function getCurrentMonthIncomeTransactions(): HasMany
    {
        return $this->getCurrentMonthTransactions()
            ->where('type', TransactionType::Income);
    }

    // get user's Expense transactions for current month
    public function getCurrentMonthExpenseTransactions(): HasMany
    {
        return $this->getCurrentMonthTransactions()
            ->where('type', TransactionType::Expense);
    }

    // total Income amount
    public function getCurrentMonthTotalIncome(): float
    {
        return $this->getCurrentMonthTransactions()
            ->where('type', TransactionType::Income)
            ->sum('amount');
    }

    // total Expense amount
    public function getCurrentMonthTotalExpense(): float
    {
        return $this->getCurrentMonthTransactions()
            ->where('type', TransactionType::Expense)
            ->sum('amount');
    }
}
