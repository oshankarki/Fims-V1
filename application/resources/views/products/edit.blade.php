@extends('layout.wrapper')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h1>Edit Product</h1>
                <form method="POST" action="{{ route('admin.products.update', $product->id) }}">
                    @csrf
                    @method('PUT') <!-- This is required for Laravel to recognize the PUT request -->
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $product->name }}"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="unit">Unit</label>
                        <select class="form-control" id="unit" name="unit" required>
                            <option value="{{ $product->unit_id }}">{{ $product->unit->unit_name }}</option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit->unit_id }}">{{ $unit->unit_name }}</option>
                            @endforeach
                        </select>
                        @error('unit')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="color">Color</label>
                        <input type="text" class="form-control" value="{{ $product->color }}" id="color"
                            name="color" required>
                    </div>

                    <div class="form-group">
                        <label for="size">size</label>
                        <input type="text" class="form-control" value="{{ $product->size }}" id="size"
                            name="size" required>
                    </div>

                    <div class="form-group">
                        <label for="actual_quantity">Actual Quantity</label>
                        <input type="text" class="form-control" value="{{ $product->actual_quantity }}"
                            id="actual_quantity" name="actual_quantity" readonly>
                    </div>

                    <div class="form-group">
                        <label for="stock_a">Stock A</label>
                        <input type="number" value="{{ $product->stock_a }}" class="form-control" id="stock_a"
                            name="stock_a" readonly />
                    </div>

                    <div class="form-group">
                        <label for="stock_b">Stock B</label>
                        <input type="number" value="{{ $product->stock_b }}" class="form-control" id="stock_b"
                            name="stock_b" readonly>
                    </div>

                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Submit the form via AJAX when it is submitted
            $('#edit-product-form').submit(function(e) {
                e.preventDefault(); // Prevent default form submission

                // Submit the form via AJAX
                $.ajax({
                    type: 'PUT', // Use PUT method for updating the resource
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        alert(response.message);
                        // You may want to redirect to another page or update the UI after successful update
                    },
                    error: function(xhr, status, error) {
                        // Handle errors if any
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
@endsection
