<x-layout>
    <h3 class="page-title">Transaction data based on product and transaction status</h3>
    <div class="mb-2 align-items-center">
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row mt-1 align-items-center">
                    <div class="col-12 text-left pl-4">
                        <div class="card-body">
                            @if(session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif
                            <table class="table table-hover align-middle shadow-sm rounded overflow-hidden">
                                <thead style="background-color: #4A4A4A; color: #FFF;"> 
                                    <tr>
                                        <th class="text-center">No.</th>
                                        <th>Product Name</th>
                                        <th>Transaction Status</th>
                                        <th>Total Amount</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody style="background-color: #F5F5F5; color: #000;"> 
                                    @foreach($paginatedTransactions as $summary)
                                        <tr>
                                            <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                            <td class="fw-semibold">{{ $summary['product_name'] ? $summary['product_name'] : 'Unknown Product'}} </td>
                                            <td>
                                                <span class="badge rounded-pill 
                                                    {{ $summary['transaction_status'] == 'refund' ? 'bg-warning' : 
                                                    ($summary['transaction_status'] == 'settlement' ? 'bg-success' : 'bg-danger') }}">
                                                    {{ ucfirst($summary['transaction_status']) }}
                                                </span>
                                            </td>
                                            <td class="text-end fw-bold">
                                                Rp {{ number_format((float) $summary['total_sales'], 0, ',', '.') }}
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('transactions.detail', ['product_name' => $summary['product_name'], 'transaction_status' => $summary['transaction_status']]) }}" 
                                                   class="btn btn-sm" 
                                                   style="background-color: #000; color: #FFF; border-radius: 8px;">
                                                    View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-between align-items-center my-3">
                                <p class="mb-0">
                                    Showing {{ $paginatedTransactions->firstItem() }} - {{ $paginatedTransactions->lastItem() }} 
                                    of {{ $paginatedTransactions->total() }} transactions
                                </p>
                            </div>
                            <div class="d-flex justify-content-center mt-3">
                                {{ $paginatedTransactions->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>