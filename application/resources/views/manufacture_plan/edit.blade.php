@extends('layout.wrapper')
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection
@section('content')
    <div class="container">
        <h1>Edit Product</h1>

        <form id="edit-product-form" method="POST" action="{{ route('admin.manufacture_plan.update', $plan->id) }}">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="manufacture_plan_id">Plan ID</label>
                        <input type="text" class="form-control" id="manufacture_plan_id" name="manufacture_plan_id"
                            value="{{ $plan->manufacture_plan_id }}" readonly>
                        @error('manufacture_plan_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="start_date">Plan Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date"
                            value="{{ $plan->start_date->format('Y-m-d') }}" readonly>
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
                        <input type="text" class="form-control" id="product_name" name="product_name"
                            value="{{ $plan->product->name }}" required readonly>
                    </div>
                </div>
                <div class="col-4" style="display: none;">
                    <div class="form-group">
                        <label for="product_id">Product ID</label>
                        <input type="text" class="form-control" id="product_id" name="product_id"
                            value="{{ $plan->product->id }}" required readonly>
                    </div>
                </div>

                <div class="col-4">
                    <div class="form-group">
                        <label for="color">Color</label>
                        <input type="text" class="form-control" id="color" name="color"
                            value="{{ old('color', $plan->color) }}" required readonly>
                        @error('color')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="size">Size</label>
                        <input type="text" class="form-control" id="size" name="size"
                            value="{{ old('size', $plan->size) }}" required readonly>
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
                        <input type="number" class="form-control" id="product_quantity" name="product_quantity"
                            value="{{ old('quantity', $plan->product_quantity) }}" required readonly>
                        @error('quantity')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="created_by">Plan Created By</label>
                        <input type="text" class="form-control" id="created_by" name="plan_created_by"
                            value="{{ old('created_by', $plan->plan_created_by) }}" required readonly>
                        @error('created_by')
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
                    @if (!empty($plan->rawMaterials))
                        @foreach ($plan->rawMaterials as $index => $rawItem)
                            <tr>
                                <td class="border border-zinc-300 p-2">{{ $index + 1 }}</td>
                                <td class="border border-zinc-300 p-2">
                                    <select class="form-control raw-item-select"
                                        name="raw_items[{{ $index }}][item_id]">
                                        <option value="{{ $rawItem->raw_item_id }}">
                                            {{ $rawItem->rawItem->name }}/{{ $rawItem->warehouse->name }}</option>
                                        @foreach ($rawItems as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->name }}/{{ $item->warehouses->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="border border-zinc-300 p-2">
                                    <select class="form-control warehouse-select"
                                        name="raw_items[{{ $index }}][warehouse]" required>
                                        <option value="{{ $rawItem->warehouse_id }}">{{ $rawItem->warehouse->name }}
                                        </option>
                                    </select>
                                </td>
                                <td class="border border-zinc-300 p-2">
                                    <input type="number" class="form-control raw-quantity"
                                        name="raw_items[{{ $index }}][quantity]" value="{{ $rawItem->quantity }}"
                                        min="1" required>
                                </td>
                                <td class="border border-zinc-300 p-2">
                                    <select class="form-control raw-unit" name="raw_items[{{ $index }}][unit]"
                                        required>
                                        <option value="{{ $rawItem->unit->unit_id }}">{{ $rawItem->unit->unit_name }}
                                        </option>
                                        <!-- Units will be dynamically populated -->
                                    </select>
                                </td>
                                <td class="border border-zinc-300 p-2">
                                    <button type="button" class="btn btn-danger remove-row">Remove row</button>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>

            </table>
            <button type="button" class="btn btn-primary" id="add-row">Add Item</button>
            <br>
            <br>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.raw-item-select').select2();
            let rowCount = {{ count($plan->rawMaterials) }};
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
                        </select>
                    </td>
                    <td class="border border-zinc-300 p-2">
                        <input type="number" class="form-control raw-quantity" name="raw_items[${rowCount}][quantity]" min="1" required>
                    </td>
                    <td class="border border-zinc-300 p-2">
                        <select class="form-control raw-unit" name="raw_items[${rowCount}][unit]" required>
                            <option value="">Select Unit</option>
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
            $(document).on('click', '.remove-row', function() {
                $(this).closest('tr').remove();
                rowCount--;
                updateRowIndices();
            });




            $(document).on('change', '.raw-item-select', function() {
                const selectedItemId = $(this).val();
                const quantityInput = $(this).closest('tr').find('.raw-quantity');
                const warehouseSelect = $(this).closest('tr').find('.warehouse-select');
                const rawUnit = $(this).closest('tr').find('.raw-unit');



                if (rawItemsQuantities[selectedItemId]) {
                    quantityInput.val(rawItemsQuantities[selectedItemId]);
                } else {
                    quantityInput.val('');
                }

                if (selectedItemId) {
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

            $('#edit-product-form').submit(function(e) {
                e.preventDefault(); // Prevent default form submission
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

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    </script>
@endsection
