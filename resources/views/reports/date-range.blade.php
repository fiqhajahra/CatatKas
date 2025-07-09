@extends('layouts.app')

@section('title', 'Laporan per Periode')

@section('content')
<div class="space-y-6">
    <!-- Header dan Filter -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Laporan per Periode</h2>
                <p class="text-sm text-gray-500 mt-1">Pilih rentang tanggal untuk melihat laporan.</p>
            </div>

            <form action="{{ route('reports.date-range') }}" method="GET" class="flex flex-col md:flex-row items-stretch md:items-center gap-2 mt-4 md:mt-0">
                <div>
                    <label for="start_date" class="sr-only">Tanggal Mulai</label>
                    <input type="date" name="start_date" id="start_date" value="{{ \Carbon\Carbon::parse($startDate)->format('Y-m-d') }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                </div>
                <div class="flex items-center justify-center text-gray-500">
                    <span>-</span>
                </div>
                <div>
                    <label for="end_date" class="sr-only">Tanggal Akhir</label>
                    <input type="date" name="end_date" id="end_date" value="{{ \Carbon\Carbon::parse($endDate)->format('Y-m-d') }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                </div>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors text-sm font-medium">
                    Tampilkan
                </button>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Periode: {{ $summary['start_date'] }} &mdash; {{ $summary['end_date'] }}</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm font-medium text-gray-600 mb-1">Total Pemasukan</p>
                <p class="text-2xl font-bold text-green-600">Rp {{ number_format($summary['total_income'], 0, ',', '.') }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm font-medium text-gray-600 mb-1">Total Pengeluaran</p>
                <p class="text-2xl font-bold text-red-600">Rp {{ number_format($summary['total_expense'], 0, ',', '.') }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm font-medium text-gray-600 mb-1">Saldo Periode Ini</p>
                <p class="text-2xl font-bold {{ $summary['balance'] >= 0 ? 'text-blue-600' : 'text-red-600' }}">Rp {{ number_format($summary['balance'], 0, ',', '.') }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm font-medium text-gray-600 mb-1">Jumlah Transaksi</p>
                <p class="text-2xl font-bold text-gray-800">{{ $summary['transaction_count'] }}</p>
            </div>
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
                                <p class="text-xs text-gray-500">{{ ucfirst($transaction->type->value) }}</p>
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