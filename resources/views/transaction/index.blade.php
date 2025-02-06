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
                    <th>Order ID</th>
                    <th>Product Name</th>
                    <th>Device ID</th>
                    <th>Column</th>
                    <th>SKU</th>
                    <th>Amount</th>
                    <th>Payment Method</th>
                    <th>Status</th>
                    <th>Refund Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction['order_id'] }}</td>
                        <td>{{ $transaction['product']['name'] }}</td>
                        <td>{{ $transaction['product']['device_id'] }}</td>
                        <td>{{ $transaction['product']['column'] }}</td>
                        <td>{{ $transaction['product']['sku'] }}</td>
                        <td>{{ number_format($transaction['payment']['amount'], 0, ',', '.') }}</td>
                        <td>{{ $transaction['payment']['method'] }}</td>
                        <td>
                            <span class="badge 
                                {{ $transaction['transaction_status'] == 'refunded' ? 'bg-warning' : 'bg-success' }}">
                                {{ ucfirst($transaction['transaction_status']) }}
                            </span>
                        </td>
                        <td>{{ number_format($transaction['refund']['amount'], 0, ',', '.') }}</td>
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