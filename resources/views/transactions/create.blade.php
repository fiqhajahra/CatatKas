@extends('layouts.app')

@section('title', 'Tambah Transaksi Baru')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 md:p-8">

        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Formulir Transaksi Baru</h2>
            <a href="{{ route('transactions.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 transition-colors">
                &larr; Kembali ke Daftar Transaksi
            </a>
        </div>

        <form action="{{ route('transactions.store') }}" method="POST" id="transactionForm">
            @csrf

            <div class="space-y-6">
                <!-- Jenis Transaksi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Transaksi</label>
                    <div class="flex items-center space-x-4">
                        <label for="type_income" class="flex items-center p-3 border rounded-lg cursor-pointer transition-all {{ old('type', 'expense') == 'income' ? 'bg-green-50 border-green-400' : 'border-gray-300' }}">
                            <input type="radio" id="type_income" name="type" value="income" class="h-4 w-4 text-green-600 border-gray-300 focus:ring-green-500" {{ old('type', 'expense') == 'income' ? 'checked' : '' }}>
                            <span class="ml-3 text-sm font-medium text-gray-900">Pemasukan</span>
                        </label>
                        <label for="type_expense" class="flex items-center p-3 border rounded-lg cursor-pointer transition-all {{ old('type', 'expense') == 'expense' ? 'bg-red-50 border-red-400' : 'border-gray-300' }}">
                            <input type="radio" id="type_expense" name="type" value="expense" class="h-4 w-4 text-red-600 border-gray-300 focus:ring-red-500" {{ old('type', 'expense') == 'expense' ? 'checked' : '' }}>
                            <span class="ml-3 text-sm font-medium text-gray-900">Pengeluaran</span>
                        </label>
                    </div>
                    @error('type')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jumlah -->
                <div>
                    <label for="amount_display" class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="text-gray-400 sm:text-sm font-medium">Rp</span>
                        </div>
                        <!-- Input untuk display (dengan format) -->
                        <input type="text" id="amount_display"
                            class="block w-full rounded-md border-gray-300 pl-10 pr-3 py-2 text-gray-900 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm"
                            placeholder="0"
                            inputmode="numeric"
                            onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                            oninput="formatCurrency(this)"
                            value="{{ old('amount') ? number_format(old('amount'), 0, ',', '.') : '' }}">

                        <!-- Hidden input untuk nilai asli (tanpa format) -->
                        <input type="hidden" name="amount" id="amount_hidden" value="{{ old('amount') }}">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Masukkan angka tanpa titik atau koma</p>
                    @error('amount')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" id="description" rows="3"
                        class="block w-full rounded-md border-gray-300 py-2 px-3 text-gray-900 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm"
                        placeholder="Contoh: Pembayaran tagihan listrik bulan Juli">{{ old('description') }}</textarea>
                    @error('description')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Transaksi -->
                <div>
                    <label for="transactions_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Transaksi</label>
                    <input type="date" name="transactions_date" id="transactions_date" value="{{ old('transactions_date', date('Y-m-d')) }}"
                        class="block w-full rounded-md border-gray-300 py-2 px-3 text-gray-900 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm">
                    @error('transactions_date')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="mt-8 pt-6 border-t border-gray-200 flex items-center justify-end space-x-4">
                <a href="{{ route('transactions.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors text-sm font-medium">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors text-sm font-medium">
                    Simpan Transaksi
                </button>
            </div>
        </form>

    </div>
</div>

<script>
    function formatCurrency(input) {
        // Hapus semua karakter non-digit
        let value = input.value.replace(/\D/g, '');

        // Update hidden input dengan nilai asli (tanpa format)
        document.getElementById('amount_hidden').value = value;

        // Format dengan separator ribuan untuk display
        if (value) {
            value = parseInt(value).toLocaleString('id-ID');
        }

        // Update tampilan input
        input.value = value;
    }

    // Pastikan form mengirim data yang bersih saat submit
    document.getElementById('transactionForm').addEventListener('submit', function(e) {
        const displayInput = document.getElementById('amount_display');
        const hiddenInput = document.getElementById('amount_hidden');

        // Pastikan hidden input memiliki nilai yang bersih
        const cleanValue = displayInput.value.replace(/\D/g, '');
        hiddenInput.value = cleanValue;

        // Validasi client-side
        if (!cleanValue || cleanValue === '0') {
            e.preventDefault();
            alert('Jumlah transaksi harus diisi dan lebih dari 0');
            return false;
        }
    });
</script>
@endsection