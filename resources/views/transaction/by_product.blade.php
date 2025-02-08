<x-layout>
    {{-- <x-slot:title>{{ $title }}</x-slot:title> --}}
    {{-- <h1 class="page-title">Sales Summary per Product & Status</h1> --}}
    <h3 class="page-title">Transaction data based on product and transaction status</h3>
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
                            <!-- Tabel Ringkasan Penjualan Berdasarkan Status -->
                            {{-- <table class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Transaction Status</th>
                                        <th>Total Amount</th>
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
                            </table> --}}
                            <table class="table table-hover align-middle shadow-sm rounded overflow-hidden">
                                <thead style="background-color: #4A4A4A; color: #FFF;"> <!-- Abu-abu gelap -->
                                    <tr>
                                        <th class="text-center">No.</th>
                                        <th class="text-center">Product Name</th>
                                        <th class="text-center">Transaction Status</th>
                                        <th class="text-center">Total Amount</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody style="background-color: #F5F5F5; color: #000;"> <!-- Putih ke abu-abu -->
                                    @foreach($salesSummary as $summary)
                                        <tr>
                                            <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                            <td class="fw-semibold">{{ $summary['product_name'] ? $summary['product_name'] : 'Unknown Product'}} </td>
                                            <td class="text-center">
                                                <span class="badge rounded-pill 
                                                    {{ $summary['transaction_status'] == 'refund' ? 'bg-warning' : 
                                                    ($summary['transaction_status'] == 'settlement' ? 'bg-success' : 'bg-danger') }}">
                                                    {{ ucfirst($summary['transaction_status']) }}
                                                </span>
                                            </td>
                                            <td class="text-end fw-bold">{{ number_format($summary['total_sales'], 0, ',', '.') }}</td>
                                            <td class="text-center">
                                                <button class="btn btn-sm" 
                                                    style="background-color: #000; color: #FFF; border-radius: 8px;">
                                                    View
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>