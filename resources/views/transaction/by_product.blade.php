<x-layout>
    {{-- <x-slot:title>{{ $title }}</x-slot:title> --}}
    <h1 class="page-title">Data Buyers</h1>
    <div class="container mt-4">
        <h2 class="mb-4">Transaction List</h2>
        
        <!-- Menampilkan Pesan Error Jika API Gagal -->
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

    <!-- Tabel Ringkasan Penjualan Berdasarkan Status -->
    <h3 class="mt-5">Sales Summary per Product & Status</h3>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Product Name</th>
                <th>Transaction Status</th>
                <th>Total Sales</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($salesSummary as $summary)
                <tr>
                    <td>{{ $summary['product_name'] }}</td>
                    <td>
                        <span class="badge 
                            {{ $summary['transaction_status'] == 'refund' ? 'bg-warning' : 
                               ($summary['transaction_status'] == 'settlement' ? 'bg-success' : 'bg-danger') }}">
                            {{ ucfirst($summary['transaction_status']) }}
                        </span>
                    </td>
                    <td>{{ number_format($summary['total_sales'], 0, ',', '.') }}</td>
                    <td>Detail</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</x-layout>