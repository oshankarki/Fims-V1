@extends('layout.wrapper')

@section('content')
    <div class="container-fluid">
        <div class="row my-4">
            <div class="col">
                <div onclick="handlePrint()" class="btn btn-primary">Print</div>
            </div>
        </div>
        <div class="row justify-content-center" id="purchase_detail">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <h4>Purchase Details</h4>
                    </div>
                    <div class="card-body">
                        <p><strong>Purchase Date:</strong> {{ $purchase->purchase_date }}</p>
                        <p><strong>Purchased By:</strong> {{ $purchase->purchased_by }}</p>
                        <p><strong>Total Price:</strong> {{ $purchase->total_price }}</p>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Item Name</th>
                                <th scope="col">Warehouse</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchase->purchasedItems as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->itemInfos->name }}</td>
                                    <td>{{ $item->warehouses->name }}</td>
                                    <td>{{ $item->item_quantity }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        function handlePrint() {
            printContentPDF('purchase_detail', 'Purchase Detail');
        }
    </script>
@endpush