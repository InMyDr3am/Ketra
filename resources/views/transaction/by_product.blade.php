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
                    <th>Product Name</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($salesSummary as $sales)
                    <tr>
                        <td>{{ $sales['product_name'] }}</td>
                        <td>{{ $sales['total_sales'] }}</td>
                        <td>
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