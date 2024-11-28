@extends('layout.wrapper')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h1>Products</h1>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <div class="row">
                    <div class="col">

                    </div>
                    <div class="col">
                        <form action="{{ route('product.search') }}" method="GET">
                            <label for="searchTerm">Search Product:</label>
                            <input type="text" id="searchTerm" name="searchTerm" required>
                            <button class="btn btn-primary">
                                Search
                            </button>
                        </form>
                    </div>
                </div>
                <h1>Search Results</h1>
                <p>You have searched for the keyword: <b>{{ $searchTerm }}</b> and found the following results.</p>
                <p>Total number of results: <b>{{ $results->count() }}</b></p>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Unit</th>
                                <th>Warehouse</th>
                                <th>Color</th>
                                <th>Size</th>
                                <th>Actual Quantity</th>
                                <th>Stock A</th>
                                <th>Stock B</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($results->isEmpty())
                                <h1>No results found.</h1>
                            @else
                                @foreach ($results as $product)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->unit->unit_name }}</td>
                                        <td>{{ $product->warehouse->name }}</td>
                                        <td>{{ $product->color }}</td>
                                        <td>{{ $product->size }}</td>
                                        <td>{{ $product->stock_a + $product->stock_b }}</td>
                                        <td>{{ $product->stock_a }}</td>
                                        <td>{{ $product->stock_b }}</td>

                                        <td>
                                            <!-- <a href="{{ route('admin.products.show', $product->id) }}"
                                                                                                                        class="btn btn-info btn-sm">View</a> -->
                                            <a href="{{ route('admin.products.edit', $product->id) }}"
                                                class="btn btn-primary btn-sm">Edit</a>
                                            <form action="{{ route('admin.products.destroy', $product->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
