@extends('layout.wrapper')
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection
@section('content')
    <div class="container">
        <h1>Create Manufacture Plan</h1>
        <form method="POST" action="{{ route('admin.manufacture_plan.store') }}" id="create-product-form">
            @csrf
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="manufacture_plan_id">Plan ID</label>
                        <input type="text" class="form-control" id="manufacture_plan_id" name="manufacture_plan_id"
                            required>
                        @error('manufacture_plan_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="start_date">Plan Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" required>
                        @error('start_date')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    <div class="form-group">
                        <label for="product_name">Product Name</label>
                        <select class="form-control" id="product_name" name="product_name" required>
                            <option value="">Select Product</option>
                            @foreach ($products as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('product_name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="color">Color</label>
                        <input type="text" class="form-control" id="color" name="color" readonly>
                        @error('color')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="size">Size</label>
                        <input type="text" class="form-control" id="size" name="size" readonly>
                        @error('size')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="quantity">Product Quantity</label>
                        <input type="number" class="form-control" id="product_quantity" name="product_quantity" required>
                        @error('quantity')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>


            </div>

            <br>
            <table class="w-full border-collapse border border-zinc-600 mb-4 table-center" id="raw-materials-table">
                <h3 class="text-center">Raw Materials</h3>
                <thead>
                    <tr>
                        <th class="border border-zinc-300 p-2">S.N.</th>
                        <th class="border border-zinc-300 p-2">Items Name</th>
                        <th class="border border-zinc-300 p-2">Warehouse</th>
                        <th class="border border-zinc-300 p-2">QTY</th>
                        <th class="border border-zinc-300 p-2">Unit</th>
                        <th class="border border-zinc-300 p-2">Action</th>

                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border border-zinc-300 p-2">1</td>
                        <td class="border border-zinc-300 p-2">
                            <select class="form-control raw-item-select" name="raw_items[0][item_id]" required>
                                <option value="" id="raw_item">Select Item</option>
                                @foreach ($rawItems as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }} /
                                        {{ $item->warehouses->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="border border-zinc-300 p-2">
                            <select class="form-control warehouse-select" name="raw_items[0][warehouse]" required>
                                <option value="">Select Warehouse</option>
                                <!-- Warehouses will be dynamically populated -->
                            </select>
                        </td>
                        <td class="border border-zinc-300 p-2">
                            <input type="number" class="form-control raw-quantity" name="raw_items[0][quantity]"
                                min="1" required>
                        </td>
                        <td class="border border-zinc-300 p-2">
                            <select class="form-control raw-unit" name="raw_items[0][unit]" required>
                                <option value="">Select Unit</option>
                                <!-- Units will be dynamically populated -->
                            </select>
                        </td>
                        <td class="border border-zinc-300 p-2">
                            <button type="button" class="btn btn-danger remove-row">Remove row</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <button type="button" class="btn btn-primary" id="add-row">Add Item</button>
            <br>
            <br>
            <button type="submit" class="btn btn-primary">Create</button>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.raw-item-select').select2();
            let rowCount = 1;
            const rawItemsQuantities = @json($rawItemsQuantities);

            $('#add-row').click(function() {
                const newRow = `
                <tr>
                    <td class="border border-zinc-300 p-2">${rowCount + 1}</td>
                    <td class="border border-zinc-300 p-2">
                        <select class="form-control raw-item-select" name="raw_items[${rowCount}][item_id]" required>
                            <option value="">Select Item</option>
                            @foreach ($rawItems as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}/{{ $item->warehouses->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="border border-zinc-300 p-2">
                        <select class="form-control warehouse-select" name="raw_items[${rowCount}][warehouse]" required>
                            <option value="">Select Warehouse</option>
                            <!-- Warehouses will be dynamically populated -->
                        </select>
                    </td>
                    <td class="border border-zinc-300 p-2">
                        <input type="number" class="form-control raw-quantity" name="raw_items[${rowCount}][quantity]" min="1" required>
                    </td>
                    <td class="border border-zinc-300 p-2">
                        <select class="form-control raw-unit" name="raw_items[${rowCount}][unit]" required>
                            <option value="">Select Unit</option>
                            <!-- Units will be dynamically populated -->
                        </select>
                    </td>
                    <td class="border border-zinc-300 p-2">
                        <button type="button" class="btn btn-danger remove-row">Remove row</button>
                    </td>
                </tr>
            `;
                $('#raw-materials-table tbody').append(newRow);
                rowCount++;
                $('.raw-item-select').select2();
            });



            $(document).on('change', '.raw-item-select', function() {
                const selectedItemId = $(this).val();
                const quantityInput = $(this).closest('tr').find('.raw-quantity');
                const warehouseSelect = $(this).closest('tr').find('.warehouse-select');
                const rawUnit = $(this).closest('tr').find('.raw-unit');

                // Check if quantity is available in rawItemsQuantities object
                if (rawItemsQuantities[selectedItemId]) {
                    quantityInput.val(rawItemsQuantities[selectedItemId]);
                } else {
                    quantityInput.val('');
                }

                if (selectedItemId) {
                    // Fetch and populate warehouse options
                    $.ajax({
                        method: 'POST',
                        url: '{{ route('admin.rawItem.get_warehouse') }}',
                        data: {
                            'id': selectedItemId
                        },
                        success: function(resp) {
                            warehouseSelect.html(resp);
                        }
                    });

                    // Fetch and populate unit options
                    $.ajax({
                        method: 'POST',
                        url: '{{ route('admin.rawItem.get_unit') }}',
                        data: {
                            'id': selectedItemId
                        },
                        success: function(resp) {
                            rawUnit.html(resp);
                        }
                    });
                } else {
                    warehouseSelect.html('<option value="">Select Warehouse</option>');
                    rawUnit.html('<option value="">Select Unit</option>');
                }
            });



            $(document).ready(function() {
                // Existing initialization code...
                $('.raw-quantity').on('input', function() {
                    let quantity = parseFloat($(this).val());
                    if (quantity < 0) {
                        alert('Your stock is negative,Please sure that stock should be positive');
                    }
                });

                $('#create-product-form').submit(function(e) {
                    e.preventDefault(); // Prevent default form submission

                    // Check for duplicate item name and warehouse combinations
                    let itemWarehousePairs = [];
                    let duplicateFound = false;

                    $('#raw-materials-table tbody tr').each(function() {
                        let itemName = $(this).find('.raw-item-select').val();
                        let warehouseName = $(this).find('.warehouse-select').val();
                        let pair = itemName + '-' + warehouseName;

                        if (itemWarehousePairs.includes(pair)) {
                            duplicateFound = true;
                            return false; // Exit the each loop
                        }
                        itemWarehousePairs.push(pair);
                    });

                    if (duplicateFound) {
                        alert(
                            'Duplicate item name and warehouse combinations found. Please ensure all combinations are unique.'
                        );
                        return; // Prevent form submission
                    }

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

                // Other existing code...
            });

            $(document).on('click', '.remove-row', function() {
                $(this).closest('tr').remove();
                rowCount--;
                updateRowIndices();
            });
            $('#product_name').change(function() {
                var productId = $(this).val();

                // Clear previous values
                $('#color').val('');
                $('#size').val('');

                // Fetch color and size data via AJAX
                $.ajax({
                    url: '{{ route('admin.fetch.product.details') }}',
                    type: 'GET',
                    data: {
                        id: productId
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#color').val(response.data.color);
                            $('#size').val(response.data.size);
                        } else {
                            // Handle error or no data scenario
                            console.error('Error fetching data');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            });
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    </script>
@endsection
