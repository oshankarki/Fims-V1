@extends('layout.wrapper')

@section('content')
    <div class="container-fluid">
        <div class="my-4">
            <h2>Warehouse Details</h2>
        </div>
        @if ($warehouse->products->isNotEmpty())
            <div class="row">
                <div class="col-md-12">
                    <h4>Products</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Quantity</th>
                                    <th>Color</th>
                                    <th>Size</th>
                                    <th>Actual Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($warehouse->products as $product)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->quantity }}</td>
                                        <td>{{ $product->color }}</td>
                                        <td>{{ $product->size }}</td>
                                        <td>{{ $product->actual_quantity }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No Data Found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        @if ($warehouse->rawItems->isNotEmpty())
            <div class="row">
                <div class="col-md-12">
                    <h4>Raw Items</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Actual Quantity</th>
                                    <th>Size</th>
                                    <th>Color</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($warehouse->rawItems as $info)
                                    <tr>
                                        <td>{{ $info->name }}</td>
                                        <td>{{ $info->actual_quantity }}</td>
                                        <td>{{ $info->color }}</td>
                                        <td>{{ $info->size }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
        @if ($warehouse->stichings->isNotEmpty())
            <div class="row">
                <div class="col-md-12">
                    <h4>Stiching Products</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Plan ID</th>
                                    <th>Name</th>
                                    <th>Quantity</th>
                                    <th>Color</th>
                                    <th>Size</th>
                                    <th>Actual Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($warehouse->stichings as $product)
                                    <tr>
                                        <td>{{ $product->plans->manufacture_plan_id }}</td>
                                        <td>{{ $product->output_name }}</td>
                                        <td>{{ $product->output_quantity }}</td>
                                        <td>{{ $product->plans->color }}</td>
                                        <td>{{ $product->plans->size }}</td>
                                        <td>{{ $product->output_actual_quantity }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No Data Found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
