@extends('layout.wrapper')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2>Transfer Details</h2>
            <a href="{{ route('warehouse.transfer.index') }}" class="btn btn-info">Back to Transfers</a>
            <div onclick="handlePrint()" class="btn btn-primary">Print</div>
        </div>
    </div>
    <div id="warehouse_show">
        <div class="row mb-4 my-2">
            <div class="col-md-12">
                <div>
                    <div >
                        <h5 class="card-title">Transfer Information</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <p><strong>From Warehouse:</strong> {{ optional($transfer->fromWarehouse)->name ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>To Warehouse:</strong> {{ optional($transfer->toWarehouse)->name ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-4">
                            <p><strong>Transfer Date:</strong>
                            {{ optional($transfer->created_at)->format('Y-m-d H:i') ?? 'N/A' }}</p>
                            </div>
                        </div>
                       
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Transferred Raw Items</h5>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Item Name</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rawItems as $item)
                                    <tr>
                                        <td>{{ optional($item->transferable)->name ?? 'N/A' }}</td>
                                        <td>{{ $item->quantity ?? 'N/A' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">No raw items transferred</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Transferred Products</h5>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                    @dd($product->transferable->name)
                                    <tr>
                                        <td>{{ optional($product->transferable)->name ?? 'N/A' }}</td>
                                        <td>{{ $product->quantity ?? 'N/A' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">No products transferred</td>
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
@endsection

@section('css')
<style>
    .table thead th,
    .table th,
    .table td {
        border: 1px solid #ccc !important;
    }
</style>
@endsection

@push('script')
    <script>
        function handlePrint() {
            printContentPDF('warehouse_show', 'Warehouse Transfer Details');
        }
    </script>
@endpush