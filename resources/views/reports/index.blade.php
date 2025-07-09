@extends('layouts.app')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-2">Laporan Keuangan</h2>
                <p class="text-purple-100">Analisis dan visualisasi data keuangan Anda</p>
            </div>
            <div class="hidden md:block">
                <svg class="h-20 w-20 text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('reports.monthly') }}" class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Laporan Bulanan</h3>
                    <p class="text-sm text-gray-600">Analisis transaksi per bulan</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-lg">
                    <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
        </a>

        <a href="{{ route('reports.yearly') }}" class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Laporan Tahunan</h3>
                    <p class="text-sm text-gray-600">Analisis transaksi per tahun</p>
                </div>
                <div class="p-3 bg-green-100 rounded-lg">
                    <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
        </a>

        <a href="{{ route('reports.date-range') }}" class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Laporan Periode</h3>
                    <p class="text-sm text-gray-600">Analisis transaksi per periode</p>
                </div>
                <div class="p-3 bg-purple-100 rounded-lg">
                    <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
            </div>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Pemasukan vs Pengeluaran (6 Bulan Terakhir)</h3>
            <div class="h-80">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Distribusi Jenis Transaksi</h3>
            <div class="h-80">
                <canvas id="pieChart"></canvas>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Tren Keuangan (12 Bulan Terakhir)</h3>
        <div class="h-96">
            <canvas id="trendChart"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Rata-rata Pemasukan</p>
                    <p class="text-xl font-bold text-green-600" id="avgIncome">Memuat...</p>
                </div>
                <div class="p-3 bg-green-100 rounded-lg">
                    <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Rata-rata Pengeluaran</p>
                    <p class="text-xl font-bold text-red-600" id="avgExpense">Memuat...</p>
                </div>
                <div class="p-3 bg-red-100 rounded-lg">
                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Transaksi</p>
                    <p class="text-xl font-bold text-blue-600" id="totalTransactions">Memuat...</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-lg">
                    <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Saldo Rata-rata</p>
                    <p class="text-xl font-bold text-purple-600" id="avgBalance">Memuat...</p>
                </div>
                <div class="p-3 bg-purple-100 rounded-lg">
                    <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Helper Functions ---
        const formatCurrency = (value) => {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(value);
        };

        const formatMillion = (value) => 'Rp ' + (value / 1000000).toFixed(0) + 'M';

        // --- Chart Initialization Functions ---

        // Monthly Income vs Expense Chart (Bar)
        const initMonthlyChart = (data) => {
            const ctx = document.getElementById('monthlyChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Pemasukan',
                        data: data.income,
                        backgroundColor: 'rgba(34, 197, 94, 0.8)',
                        borderColor: 'rgba(34, 197, 94, 1)',
                        borderWidth: 1
                    }, {
                        label: 'Pengeluaran',
                        data: data.expense,
                        backgroundColor: 'rgba(239, 68, 68, 0.8)',
                        borderColor: 'rgba(239, 68, 68, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top' },
                        tooltip: {
                            callbacks: {
                                label: (context) => `${context.dataset.label}: ${formatCurrency(context.parsed.y)}`
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { callback: (value) => formatMillion(value) }
                        }
                    }
                }
            });
        };

        // Transaction Distribution Chart (Doughnut)
        const initPieChart = (data) => {
            const ctx = document.getElementById('pieChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: data.labels,
                    datasets: [{
                        data: data.data,
                        backgroundColor: ['rgba(34, 197, 94, 0.8)', 'rgba(239, 68, 68, 0.8)'],
                        borderColor: ['rgba(34, 197, 94, 1)', 'rgba(239, 68, 68, 1)'],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' },
                        tooltip: {
                            callbacks: {
                                label: (context) => {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((context.parsed / total) * 100).toFixed(1);
                                    return `${context.label}: ${formatCurrency(context.parsed)} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        };

        // Financial Trend Chart (Line)
        const initTrendChart = (data) => {
            const ctx = document.getElementById('trendChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Pemasukan',
                        data: data.income,
                        borderColor: 'rgba(34, 197, 94, 1)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        tension: 0.4,
                        fill: true
                    }, {
                        label: 'Pengeluaran',
                        data: data.expense,
                        borderColor: 'rgba(239, 68, 68, 1)',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.4,
                        fill: true
                    }, {
                        label: 'Saldo',
                        data: data.balance,
                        borderColor: 'rgba(147, 51, 234, 1)',
                        backgroundColor: 'rgba(147, 51, 234, 0.1)',
                        tension: 0.4,
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { position: 'top' },
                        tooltip: {
                            callbacks: {
                                label: (context) => `${context.dataset.label}: ${formatCurrency(context.parsed.y)}`
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { callback: (value) => formatMillion(value) }
                        }
                    }
                }
            });
        };

        // Update Summary Cards
        const updateSummaryStats = (data) => {
            document.getElementById('avgIncome').textContent = formatCurrency(data.avgIncome);
            document.getElementById('avgExpense').textContent = formatCurrency(data.avgExpense);
            document.getElementById('avgBalance').textContent = formatCurrency(data.avgBalance);
            document.getElementById('totalTransactions').textContent = data.totalTransactions.toLocaleString('id-ID');
        };

        // --- Data Fetching and Initialization ---
        const fetchData = async (url) => {
            const response = await fetch(url);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return await response.json();
        };

        // Use Promise.all to fetch all data concurrently
        Promise.all([
            fetchData('{{ route("charts.monthly") }}'),
            fetchData('{{ route("charts.distribution") }}'),
            fetchData('{{ route("charts.yearly-trend") }}'),
            fetchData('{{ route("charts.summary-stats") }}')
        ]).then(([monthlyData, distributionData, yearlyData, summaryData]) => {
            initMonthlyChart(monthlyData);
            initPieChart(distributionData);
            initTrendChart(yearlyData);
            updateSummaryStats(summaryData);
        }).catch(error => {
            console.error("Gagal memuat data laporan:", error);
            // Optionally, display an error message to the user on the page
        });
    });
</script>
@endpush