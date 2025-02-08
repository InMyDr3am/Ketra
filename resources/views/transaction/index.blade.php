<x-layout>
    <h3 class="page-title">All Data Transaction</h3>
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
                                <tbody style="background-color: #F5F5F5; color: #000;"> 
                                    @forelse($paginatedTransactions as $transaction)
                                        @include('transaction.m-detail-alldata')
                                        <tr>
                                            <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                            <td>{{ $transaction['order_id'] }}</td>
                                            <td>{{ $transaction['product']['name'] ? $transaction['product']['name'] : ' - Unknown -' }}</td>
                                            <td>{{ $transaction['product']['device_id'] }}</td>
                                            {{-- <td>{{ $transaction['product']['column'] ? $transaction['product']['column'] : ' - Unknown - ' }}</td>
                                            <td>{{ $transaction['product']['sku'] ? $transaction['product']['sku'] : ' - Unknown - ' }}</td> --}}
                                            <td class="text-end fw-bold">
                                                Rp {{ number_format((float) $transaction['amount'], 0, ',', '.') }}
                                            </td>
                                            <td>{{ $transaction['payment']['method'] }}</td>
                                            <td class="text-center">
                                                <span class="badge rounded-pill 
                                                    {{ $transaction['transaction_status'] == 'refund' ? 'bg-warning' : 
                                                    ($transaction['transaction_status'] == 'settlement' ? 'bg-success' : 'bg-danger') }}">
                                                    {{ ucfirst($transaction['transaction_status']) }}
                                                </span>
                                            </td>
                                            {{-- <td>{{ number_format($transaction['refund']['amount'], 0, ',', '.') }}</td> --}}
                                            <td class="text-center">
                                                <button class="btn btn-sm" 
                                                    style="background-color: #000; color: #FFF; border-radius: 8px;"
                                                    data-toggle="modal" data-target="#modal-detAllData{{ $transaction['order_id'] }}">
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