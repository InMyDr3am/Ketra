<div class="modal fade" id="modal-detAllData{{ $transaction['order_id'] }}" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><b>Detail Transaction</b></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {{-- <form action="/inventory/{{ $buyer->id }}" method="POST" enctype="multipart/form-data"> --}}
                <div class="modal-body">
                    @csrf
                    @method('put')
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Order ID</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="name" required value="{{ $transaction['order_id'] }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Product Name</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="name" required value="{{ $transaction['product']['name'] ? $transaction['product']['name'] : ' - Unknown -' }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Device ID</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="name" required value="{{ $transaction['product']['device_id'] }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Column</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="name" required value="{{ $transaction['product']['column'] ? $transaction['product']['column'] : ' - Unknown - ' }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>SKU</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="name" required value="{{ $transaction['product']['sku'] ? $transaction['product']['sku'] : ' - Unknown - ' }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Amount</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="name" required value="{{ number_format($transaction['payment']['amount'], 0, ',', '.') }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Payment Method</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="name" required value="{{ $transaction['payment']['method'] }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Status</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="name" required value="{{ ucfirst($transaction['transaction_status']) }}" readonly>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-sm-12">
                            <div class="form-group">
                                <label>Refund Amount</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="name" required value="{{ number_format($transaction['payment']['amount'], 0, ',', '.') }}">
                                </div>
                            </div>
                        </div> --}}
                        
                    </div>
                    {{-- <button type="submit" class="btn btn-primary mt-2 float-right">Simpan Perubahan</button> --}}
                </div>
            {{-- </form> --}}
        </div>
        <!-- /.modal-content -->
    </div>
      <!-- /.modal-dialog -->
</div>
    
