@extends('layout.wrapper')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h1>Product and their Warehouse</h1>
                <div id="success-message" class="alert alert-success d-none"></div>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Warehouse Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->warehouse->name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
