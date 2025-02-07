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

        <!-- Tabel Transaksi -->
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Payment Method</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($salesSummary as $sales)
                    <tr>
                        <td>{{ $sales['payment_method'] }}</td>
                        <td>{{ number_format($sales['total_sales'], 0, ',', '.') }}</td>
                        <td>Detail</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">No Transactions Found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layout>