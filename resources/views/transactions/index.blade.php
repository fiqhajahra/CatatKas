@extends('layouts.app')

@section('title', 'Semua Transaksi')

@section('content')
<div class="space-y-6">
    <!-- Action Bar -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <a href="{{ route('transactions.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Tambah Transaksi
            </a>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6a2 2 0 01-2 2H10a2 2 0 01-2-2V5z" />
                </svg>
                Dashboard
            </a>
        </div>
    </div>

    <!-- Summary Stats (hanya tampil jika ada filter dan summary) -->
    @if(request()->hasAny(['search', 'type', 'start_date', 'end_date', 'month']) && isset($summary))
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Total Transaksi -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Transaksi</p>
                    <p class="text-lg font-semibold text-blue-600">{{ $summary['count'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-lg">
                    <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Pemasukan -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Pemasukan</p>
                    <p class="text-lg font-semibold text-green-600">Rp {{ number_format($summary['total_income'] ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded-lg">
                    <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Pengeluaran -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Pengeluaran</p>
                    <p class="text-lg font-semibold text-red-600">Rp {{ number_format($summary['total_expense'] ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="p-3 bg-red-100 rounded-lg">
                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Saldo -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Saldo</p>
                    <p class="text-lg font-semibold {{ ($summary['balance'] ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        Rp {{ number_format($summary['balance'] ?? 0, 0, ',', '.') }}
                    </p>
                </div>
                <div class="p-3 {{ ($summary['balance'] ?? 0) >= 0 ? 'bg-green-100' : 'bg-red-100' }} rounded-lg">
                    <svg class="h-6 w-6 {{ ($summary['balance'] ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Filter and Search -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
        <form method="GET" action="{{ route('transactions.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari Transaksi</label>
                <input type="text"
                    name="search"
                    id="search"
                    value="{{ request('search') }}"
                    placeholder="Cari berdasarkan deskripsi..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Type Filter -->
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Jenis Transaksi</label>
                <select name="type" id="type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Jenis</option>
                    <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>Pemasukan</option>
                    <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>Pengeluaran</option>
                </select>
            </div>

            <!-- Date From -->
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Dari</label>
                <input type="date"
                    name="start_date"
                    id="start_date"
                    value="{{ request('start_date') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Date To -->
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Sampai</label>
                <input type="date"
                    name="end_date"
                    id="end_date"
                    value="{{ request('end_date') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Action Buttons -->
            <div class="md:col-span-4 flex items-center space-x-4">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Filter
                </button>
                <a href="{{ route('transactions.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Transactions Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">
                    Daftar Transaksi
                    @if(isset($transactions) && $transactions->total() > 0)
                    <span class="text-sm font-normal text-gray-500 ml-2">
                        ({{ $transactions->total() }} transaksi)
                    </span>
                    @endif
                </h3>
                @if(isset($transactions) && $transactions->count() > 0)
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500">Per halaman:</span>
                    <form method="GET" action="{{ route('transactions.index') }}" class="inline">
                        @foreach(request()->except('per_page') as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <select name="per_page" onchange="this.form.submit()" class="px-2 pr-8 py-1 border border-gray-300 rounded text-sm">
                            <option value="10" {{ request('per_page', 15) == 10 ? 'selected' : '' }}>10</option>
                            <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                            <option value="25" {{ request('per_page', 15) == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page', 15) == 50 ? 'selected' : '' }}>50</option>
                        </select>
                    </form>
                </div>
                @endif
            </div>
        </div>

        <div class="overflow-hidden">
            @if(isset($transactions) && $transactions->count() > 0)
            <!-- Desktop Table -->
            <div class="hidden md:block">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($transactions as $transaction)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($transaction->transactions_date)->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $transaction->description }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                $isIncome = (is_object($transaction->type) && $transaction->type->value === 'income') || $transaction->type === 'income';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $isIncome ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        @if($isIncome)
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                                        @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                                        @endif
                                    </svg>
                                    {{ $isIncome ? 'Pemasukan' : 'Pengeluaran' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <span class="text-sm font-semibold {{ $isIncome ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $isIncome ? '+' : '-' }} Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('transactions.edit', $transaction) }}"
                                        class="text-blue-600 hover:text-blue-700 p-1 rounded-md hover:bg-blue-50 transition-colors"
                                        title="Edit">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('transactions.destroy', $transaction) }}" class="inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            class="text-red-600 hover:text-red-700 p-1 rounded-md hover:bg-red-50 transition-colors delete-button"
                                            title="Hapus">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="md:hidden divide-y divide-gray-200">
                @foreach($transactions as $transaction)
                @php
                $isIncome = (is_object($transaction->type) && $transaction->type->value === 'income') || $transaction->type === 'income';
                @endphp
                <div class="p-6 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 {{ $isIncome ? 'bg-green-100' : 'bg-red-100' }} rounded-lg">
                                <svg class="h-5 w-5 {{ $isIncome ? 'text-green-600' : 'text-red-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    @if($isIncome)
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                                    @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                                    @endif
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $transaction->description }}</p>
                                <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($transaction->transactions_date)->format('d M Y') }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold {{ $isIncome ? 'text-green-600' : 'text-red-600' }}">
                                {{ $isIncome ? '+' : '-' }} Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between mt-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $isIncome ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $isIncome ? 'Pemasukan' : 'Pengeluaran' }}
                        </span>
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('transactions.edit', $transaction) }}"
                                class="text-blue-600 hover:text-blue-700 p-1 rounded-md hover:bg-blue-50 transition-colors"
                                title="Edit">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('transactions.destroy', $transaction) }}" class="inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                    class="text-red-600 hover:text-red-700 p-1 rounded-md hover:bg-red-50 transition-colors delete-button"
                                    title="Hapus">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="p-12 text-center">
                <svg class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <p class="text-gray-500 mb-4">
                    @if(request()->hasAny(['search', 'type', 'start_date', 'end_date', 'month']))
                    Tidak ada transaksi yang sesuai dengan filter yang dipilih
                    @else
                    Belum ada transaksi
                    @endif
                </p>
                <a href="{{ route('transactions.create') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Tambah Transaksi Pertama
                </a>
            </div>
            @endif
        </div>

        <!-- Pagination -->
        @if(isset($transactions) && $transactions->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $transactions->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <svg class="h-6 w-6 text-red-600" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Hapus Transaksi</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Apakah Anda yakin ingin menghapus transaksi ini? Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>
            <div class="items-center px-4 py-3 space-x-4">
                <button id="cancelButton" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors text-sm font-medium">
                    Batal
                </button>
                <button id="confirmDeleteButton" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors text-sm font-medium">
                    Hapus
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteModal = document.getElementById('deleteModal');
        const cancelButton = document.getElementById('cancelButton');
        const confirmDeleteButton = document.getElementById('confirmDeleteButton');
        const deleteButtons = document.querySelectorAll('.delete-button');
        let formToSubmit = null;

        // Handle delete button clicks
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                formToSubmit = this.closest('.delete-form');
                deleteModal.classList.remove('hidden');
            });
        });

        // Handle cancel button
        cancelButton.addEventListener('click', function() {
            deleteModal.classList.add('hidden');
            formToSubmit = null;
        });

        // Handle confirm delete button
        confirmDeleteButton.addEventListener('click', function() {
            if (formToSubmit) {
                formToSubmit.submit();
            }
        });

        // Close modal if clicked outside of it
        window.addEventListener('click', function(event) {
            if (event.target === deleteModal) {
                deleteModal.classList.add('hidden');
                formToSubmit = null;
            }
        });

        // Handle Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                deleteModal.classList.add('hidden');
                formToSubmit = null;
            }
        });
    });
</script>
@endpush
@endsection