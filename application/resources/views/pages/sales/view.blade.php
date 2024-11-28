@extends('layout.wrapper') 
@section('content')
<div class="container-fluid">
    <div class="row my-4">
        <div onclick="printContentPDF('sales_detail_page', 'Sales Detail')" class="btn btn-info">PRINT</div>
    </div>
    <div class="row" id="sales_detail_page">
        <h2>Sales Details Page</h2>
        <div class="col-md-12">
            <div class="font-size: 1rem;">
                <strong>Customer Name:</strong>{{$sale->customers->customer_name}}
            </div>
            <div class="my-2">
                <h3>Products:</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Stock Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sale->products as $product)
                            <tr>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->pivot->quantity }}</td>
                                <td>{{ $product->pivot->stock_type }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection