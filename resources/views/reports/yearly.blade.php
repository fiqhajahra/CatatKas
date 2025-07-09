@extends('layouts.app')

@section('title', 'Laporan Tahunan')

@section('content')
<div class="space-y-6">
    <!-- Header dan Filter -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Laporan Tahun: {{ $summary['year'] }}</h2>
                <p class="text-sm text-gray-500 mt-1">Ringkasan transaksi untuk tahun yang dipilih.</p>
            </div>

            <form action="{{ route('reports.yearly') }}" method="GET" class="flex items-center space-x-2 mt-4 md:mt-0">
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
            <p class="text-sm font-medium text-gray-600 mb-1">Saldo Akhir Tahun</p>
            <p class="text-2xl font-bold {{ $summary['balance'] >= 0 ? 'text-blue-600' : 'text-red-600' }}">Rp {{ number_format($summary['balance'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <p class="text-sm font-medium text-gray-600 mb-1">Jumlah Transaksi</p>
            <p class="text-2xl font-bold text-gray-800">{{ number_format($summary['transaction_count'], 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Monthly Breakdown Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-900">Rincian per Bulan</h3>
            <p class="text-sm text-gray-600 mt-1">Klik pada bulan untuk melihat detail transaksi</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full table-fixed divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th scope="col" class="w-1/5 px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-r border-gray-200">
                            Bulan
                        </th>
                        <th scope="col" class="w-1/5 px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider border-r border-gray-200">
                            Pemasukan
                        </th>
                        <th scope="col" class="w-1/5 px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider border-r border-gray-200">
                            Pengeluaran
                        </th>
                        <th scope="col" class="w-1/5 px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider border-r border-gray-200">
                            Saldo Bersih
                        </th>
                        <th scope="col" class="w-1/5 px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Jml. Transaksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($monthlyData as $index => $data)
                    @php
                    $balance = $data['income'] - $data['expense'];
                    $rowClass = $index % 2 === 0 ? 'bg-white' : 'bg-gray-50';
                    @endphp
                    <tr class="{{ $rowClass }} hover:bg-blue-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 border-r border-gray-100">
                            <a href="{{ route('reports.monthly', ['month' => $data['month_number'], 'year' => $year]) }}"
                                class="flex items-center text-blue-600 hover:text-blue-800 hover:underline transition-colors duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $data['month'] }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600 text-right border-r border-gray-100">
                            <span class="inline-flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                                </svg>
                                Rp {{ number_format($data['income'], 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600 text-right border-r border-gray-100">
                            <span class="inline-flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                                </svg>
                                Rp {{ number_format($data['expense'], 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-right border-r border-gray-100">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $balance >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                @if($balance >= 0)
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                                @else
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                </svg>
                                @endif
                                Rp {{ number_format($balance, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $data['count'] }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gradient-to-r from-gray-100 to-gray-200 border-t-2 border-gray-300">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 border-r border-gray-300">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                Total Keseluruhan
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-700 text-right border-r border-gray-300">
                            Rp {{ number_format($summary['total_income'], 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-red-700 text-right border-r border-gray-300">
                            Rp {{ number_format($summary['total_expense'], 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-right border-r border-gray-300">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold {{ $summary['balance'] >= 0 ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800' }}">
                                Rp {{ number_format($summary['balance'], 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-700 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-200 text-gray-800">
                                {{ number_format($summary['transaction_count'], 0, ',', '.') }}
                            </span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Additional Info -->
    <div class="bg-blue-50 rounded-xl p-4 border border-blue-200">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-sm text-blue-800">
                <strong>Tips:</strong> Klik pada nama bulan untuk melihat detail transaksi bulanan. Warna hijau menunjukkan surplus, merah menunjukkan defisit.
            </p>
        </div>
    </div>
</div>
@endsection