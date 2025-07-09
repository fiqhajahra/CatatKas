@extends('layouts.app')

@section('title', 'Laporan Bulanan')

@section('content')
<div class="space-y-6">
    <!-- Header dan Filter -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Laporan Bulan: {{ $summary['month'] }}</h2>
                <p class="text-sm text-gray-500 mt-1">Detail transaksi untuk bulan yang dipilih.</p>
            </div>

            <form action="{{ route('reports.monthly') }}" method="GET" class="flex items-center space-x-2 mt-4 md:mt-0">
                <select name="month" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                        </option>
                        @endfor
                </select>
                <select name="year" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    @for ($y = now()->year; $y >= now()->year - 5; $y--)
                    <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors text-sm font-medium">
                    Lihat
                </button>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <p class="text-sm font-medium text-gray-600 mb-1">Total Pemasukan</p>
            <p class="text-2xl font-bold text-green-600">Rp {{ number_format($summary['total_income'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <p class="text-sm font-medium text-gray-600 mb-1">Total Pengeluaran</p>
            <p class="text-2xl font-bold text-red-600">Rp {{ number_format($summary['total_expense'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <p class="text-sm font-medium text-gray-600 mb-1">Saldo Bulan Ini</p>
            <p class="text-2xl font-bold {{ $summary['balance'] >= 0 ? 'text-blue-600' : 'text-red-600' }}">Rp {{ number_format($summary['balance'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <p class="text-sm font-medium text-gray-600 mb-1">Jumlah Transaksi</p>
            <p class="text-2xl font-bold text-gray-800">{{ $summary['transaction_count'] }}</p>
        </div>
    </div>

    <!-- Transaction List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Daftar Transaksi</h3>
        </div>

        <div class="space-y-4 p-6">
            @forelse ($groupedTransactions as $date => $transactions)
            <div class="py-4">
                @php
                // --- Blok Penerjemah Manual ---
                $carbonDate = \Carbon\Carbon::parse($date);
                $dayName = $carbonDate->format('l');
                $monthName = $carbonDate->format('F');

                $daysInIndonesian = [
                'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
                'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat',
                'Saturday' => 'Sabtu'
                ];

                $monthsInIndonesian = [
                'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret',
                'April' => 'April', 'May' => 'Mei', 'June' => 'Juni', 'July' => 'Juli',
                'August' => 'Agustus', 'September' => 'September', 'October' => 'Oktober',
                'November' => 'November', 'December' => 'Desember'
                ];

                $translatedDay = $daysInIndonesian[$dayName] ?? $dayName;
                $translatedMonth = $monthsInIndonesian[$monthName] ?? $monthName;

                $indonesianDate = $translatedDay . ', ' . $carbonDate->format('d') . ' ' . $translatedMonth . ' ' . $carbonDate->format('Y');
                @endphp
                <h4 class="text-md font-semibold text-gray-600 pb-3 border-b border-gray-200 mb-3">
                    {{ $indonesianDate }}
                </h4>
                <ul class="space-y-3">
                    @foreach ($transactions as $transaction)
                    <li class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50">
                        <div class="flex items-center">
                            <div class="mr-4">
                                @if ($transaction->type == \App\Enums\TransactionType::Income)
                                <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                    <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                </div>
                                @else
                                <div class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center">
                                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6" />
                                    </svg>
                                </div>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">{{ $transaction->description }}</p>
                                @php
                                $type = '';
                                if($transaction->type->value === 'income'){
                                $type = 'Pendapatan';
                                } else {
                                $type = 'Pengeluaran';
                                }
                                @endphp
                                <p class="text-xs text-gray-500">{{ $type }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold {{ $transaction->type == \App\Enums\TransactionType::Income ? 'text-green-600' : 'text-red-600' }}">
                                {{ $transaction->type == \App\Enums\TransactionType::Income ? '+' : '-' }} Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                            </p>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            @empty
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada transaksi</h3>
                <p class="mt-1 text-sm text-gray-500">Tidak ada data transaksi yang ditemukan untuk periode ini.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection