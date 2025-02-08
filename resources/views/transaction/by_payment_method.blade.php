<x-layout>
    <h3 class="page-title">Transaction data based on payment method</h3>
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
                                        <th>Payment Method</th>
                                        <th>Transaction Status</th>
                                        <th>Total Amount</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody style="background-color: #F5F5F5; color: #000;"> 
                                    @forelse($paginatedTransactions as $sales)
                                        <tr>
                                            <td class="text-center fw-bold">{{ $loop->iteration }}</td> 
                                            <td class="fw-semibold">{{ $sales['payment_method'] }}</td>
                                            <td>
                                                <span class="badge rounded-pill 
                                                    {{ $sales['transaction_status'] == 'refund' ? 'bg-warning' : 
                                                    ($sales['transaction_status'] == 'settlement' ? 'bg-success' : 'bg-danger') }}">
                                                    {{ ucfirst($sales['transaction_status']) }}
                                                </span>
                                            </td>
                                            <td class="text-end fw-bold">
                                                Rp {{ number_format((float) $sales['total_sales'], 0, ',', '.') }}
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('transactions.detailpayment', ['payment_method' => $sales['payment_method'], 'transaction_status' => $sales['transaction_status']]) }}" 
                                                   class="btn btn-sm" 
                                                   style="background-color: #000; color: #FFF; border-radius: 8px;">
                                                    View
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center fw-bold text-muted">No Transactions Found</td>
                                        </tr>
                                    @endforelse
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