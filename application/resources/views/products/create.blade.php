@extends('layout.wrapper')

@section('content')
    <div class="container">
        <h1>Create Product</h1>
        <form method="POST" action="{{ route('admin.products.store') }}" id="create-product-form">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- <div class="form-group">
                <label for="quantity">Quantity</label>
                <input type="text" class="form-control" id="quantity" name="quantity" required>
                @error('quantity')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div> -->

            <div class="form-group">
                <label for="color">Color</label>
                <input type="text" class="form-control" id="color" name="color" required>
                @error('color')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="size">size</label>
                <input type="text" class="form-control" id="size" name="size" required>
                @error('size')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="image">image</label>
                <input type="file" class="form-control" id="image" name="image">
                @error('image')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- <div class="form-group">
                <label for="actual_quantity">Actual Quantity</label>
                <input type="text" class="form-control" id="actual_quantity" name="actual_quantity">
                @error('actual_quantity')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div> -->

            <!-- <div class="form-group">
                <label for="stock_a">Stock A</label>
                <input type="number" class="form-control" id="stock_a" name="stock_a">
                @error('stock_a')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div> -->
<!-- 
            <div class="form-group">
                <label for="stock_b">Stock B</label>
                <input type="number" class="form-control" id="stock_b" name="stock_b">
                @error('stock_b')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div> -->

            <div class="form-group">
                <label for="unit">Unit</label>
                <select class="form-control" id="unit" name="unit_id" required>
                    <option value="">Select Unit</option>
                    @foreach($units as $unit)
                        <option value="{{$unit->unit_id}}">{{$unit->unit_name}}</option>
                    @endforeach
                </select>
                @error('unit_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="warehouse">warehouse</label>
                <select class="form-control" id="warehouse" name="warehouse_id" required>
                    <option value="">Select Unit</option>
                    @foreach($warehouses as $warehouse)
                        <option value="{{$warehouse->id}}">{{$warehouse->name}}</option>
                    @endforeach
                </select>
                @error('unit_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>


            <button type="submit" class="btn btn-primary">Create</button>
        </form>
    </div>
@endsection

<script>
    $(document).ready(function() {
        $('#create-product-form').submit(function(e) {
            e.preventDefault(); // Prevent default form submission

            // Submit the form via AJAX
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    // Show success message
                    alert(response.message);

                    // Redirect to the specified URL
                    window.location.href = response.redirect_url;
                },
                error: function(xhr, status, error) {
                    // Handle errors if any
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>