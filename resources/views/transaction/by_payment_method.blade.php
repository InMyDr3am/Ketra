<x-layout>
    {{-- <x-slot:title>{{ $title }}</x-slot:title> --}}
    <h3 class="page-title">Transaction data based on transaction method</h3>
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
                            {{-- <table class="table table-bordered table-striped">
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
                            </table> --}}
                            <table class="table table-hover align-middle shadow-sm rounded overflow-hidden">
                                <thead style="background-color: #4A4A4A; color: #FFF;"> <!-- Abu-abu gelap -->
                                    <tr>
                                        <th class="text-center">No.</th>
                                        <th class="text-center">Payment Method</th>
                                        <th class="text-center">Total</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody style="background-color: #F5F5F5; color: #000;"> <!-- Putih ke abu-abu -->
                                    @forelse($salesSummary as $sales)
                                        <tr>
                                            <td class="text-center fw-bold">{{ $loop->iteration }}</td> <!-- Nomor Urut -->
                                            <td class="fw-semibold">{{ $sales['payment_method'] }}</td>
                                            <td class="text-end fw-bold">{{ number_format($sales['total_sales'], 0, ',', '.') }}</td>
                                            <td class="text-center">
                                                <button class="btn btn-sm" 
                                                    style="background-color: #000; color: #FFF; border-radius: 8px;">
                                                    View
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center fw-bold text-muted">No Transactions Found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>