<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Transaction;

class TransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $transaction = $this->route('transaction');

        // Jika ada transaction di route (untuk update/show/delete)
        if ($transaction) {
            return $this->user()->can('update', $transaction);
        }

        // Jika tidak ada transaction di route (untuk create/store)
        return $this->user()->can('create', Transaction::class);
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        if ($this->has('amount')) {
            // Hapus separator ribuan dari amount sebelum validasi
            $cleanAmount = preg_replace('/[^\d]/', '', $this->amount);
            $this->merge([
                'amount' => $cleanAmount
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:1|max:99999999999', // Minimal 1 agar tidak 0
            'description' => 'required|string|max:255',
            'transactions_date' => 'required|date|before_or_equal:today'
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'type.required' => 'Tipe transaksi harus dipilih.',
            'type.in' => 'Tipe transaksi harus berupa income atau expense.',
            'amount.required' => 'Jumlah transaksi harus diisi.',
            'amount.numeric' => 'Jumlah transaksi harus berupa angka.',
            'amount.min' => 'Jumlah transaksi harus lebih dari 0.',
            'amount.max' => 'Jumlah transaksi terlalu besar.',
            'description.required' => 'Deskripsi transaksi harus diisi.',
            'description.max' => 'Deskripsi transaksi tidak boleh lebih dari 255 karakter.',
            'transactions_date.required' => 'Tanggal transaksi harus diisi.',
            'transactions_date.date' => 'Tanggal transaksi harus berupa format tanggal yang valid.',
            'transactions_date.before_or_equal' => 'Tanggal transaksi tidak boleh melebihi hari ini.'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'type' => 'tipe transaksi',
            'amount' => 'jumlah',
            'description' => 'deskripsi',
            'transactions_date' => 'tanggal transaksi'
        ];
    }
}
