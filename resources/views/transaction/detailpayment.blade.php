<x-layout>
    <h3 class="page-title">Transaction Details for {{ $paymentMethod }} with Transaction Status ({{ ucfirst($transactionStatus) }})</h3>
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
                                        <th>Order ID</th>
                                        <th>Payment Method</th>
                                        <th>Total Amount</th>
                                        <th>Transaction Time</th>
                                    </tr>
                                </thead>
                                <tbody style="background-color: #F5F5F5; color: #000;">
                                    @foreach($paginatedTransactions as $transaction)
                                        <tr>
                                            <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                            <td>{{ $transaction['order_id'] }}</td>
                                            <td>{{ $transaction['payment_method'] }}</td>
                                            <td class="text-end fw-bold">
                                                Rp {{ number_format((float) $transaction['amount'], 0, ',', '.') }}
                                            </td>
                                            <td>{{ $transaction['transaction_time'] ?? 'N/A' }}</td>
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
                            <a href="{{ route('transactions.bypaymentmethod') }}" class="btn btn-dark mt-3">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
