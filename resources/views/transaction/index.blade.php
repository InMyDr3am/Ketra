<x-layout>
    {{-- <x-slot:title>{{ $title }}</x-slot:title> --}}
    <h3 class="page-title">All Transaction Data</h3>
    <div class="mb-2 align-items-center">
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row mt-1 align-items-center">
                    <div class="col-12 text-left pl-4">
                        <div class="card-body">
                            <!-- Menampilkan Pesan Error Jika API Gagal -->
                            @if(session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif
        <!-- Tabel Transaksi -->
        <table class="table table-hover align-middle shadow-sm rounded overflow-hidden">
            <thead style="background-color: #4A4A4A; color: #FFF;"> <!-- Abu-abu gelap -->
                <tr>
                    <th>No. </th>
                    <th>Order ID</th>
                    <th>Product Name</th>
                    <th>Device ID</th>
                    {{-- <th>Column</th>
                    <th>SKU</th> --}}
                    <th>Amount</th>
                    <th>Payment Method</th>
                    <th>Status</th>
                    <th>Action</th>
                    {{-- <th>Refund Amount</th> --}}
                </tr>
            </thead>
            <tbody style="background-color: #F5F5F5; color: #000;"> <!-- Putih ke abu-abu -->
                @forelse($transactions as $transaction)
                    <tr>
                        <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                        <td>{{ $transaction['order_id'] }}</td>
                        <td>{{ $transaction['product']['name'] ? $transaction['product']['name'] : ' - Unknown -' }}</td>
                        <td>{{ $transaction['product']['device_id'] }}</td>
                        {{-- <td>{{ $transaction['product']['column'] ? $transaction['product']['column'] : ' - Unknown - ' }}</td>
                        <td>{{ $transaction['product']['sku'] ? $transaction['product']['sku'] : ' - Unknown - ' }}</td> --}}
                        <td>{{ number_format($transaction['payment']['amount'], 0, ',', '.') }}</td>
                        <td>{{ $transaction['payment']['method'] }}</td>
                        <td>
                            <span class="badge 
                                {{ $transaction['transaction_status'] == 'refunded' ? 'bg-warning' : 'bg-success' }}">
                                {{ ucfirst($transaction['transaction_status']) }}
                            </span>
                        </td>
                        {{-- <td>{{ number_format($transaction['refund']['amount'], 0, ',', '.') }}</td> --}}
                        <td class="text-center">
                            <button class="btn btn-sm" 
                                style="background-color: #000; color: #FFF; border-radius: 8px;">
                                Detail
                            </button>
                        </td>
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