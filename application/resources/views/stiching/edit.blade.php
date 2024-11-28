@extends('layout.wrapper')

@section('content')
    <div class="container">
        <h1>Create Stiching Plan</h1>


        <form id="edit-product-form" method="POST" action="{{ route('admin.stiching.update', $plan->id) }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="plan_id" value="{{ $plan->id }}">

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
                        <label for="created_by">Responsible Person</label>
                        <input type="text" class="form-control" id="created_by" name="created_by"
                            value="{{ $stiching->created_by ?? '' }}" required>
                        @error('created_by')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-6">
                    <div class="form-group">
                        <label for="department">Department</label>
                        <select class="form-control" id="department" name="department_id" required>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}"
                                    {{ $stiching && $stiching->department_id == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('department')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <h3 class="text-center">Printing Output Details</h3>

            <div class="row">

                <div class="col-4">
                    <div class="form-group">
                        <label for="printing_output_name">Printing Output Name</label>
                        <input type="text" class="form-control" id="printing_output_name" name="printing_output_name"
                            value="{{ $printing->output_name }}" required readonly>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="printing_output_name">Quantity</label>
                        <input type="text" class="form-control" id="printing_output_name" name="printing_output_name"
                            value="{{ $printing->output_quantity }}" required readonly>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="printing_output_name">Actual Quantity</label>
                        <input type="text" class="form-control" id="printing_output_name" name="printing_output_name"
                            value="{{ $printing->output_actual_quantity }}" required readonly>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="printing_output_name">Loss Quantity</label>
                        <input type="text" class="form-control" id="printing_output_name" name="printing_output_name"
                            value="{{ $printing->output_loss_quantity }}" required readonly>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="printing_output_name">Found Quantity</label>
                        <input type="text" class="form-control" id="printing_output_name" name="printing_output_name"
                            value="{{ $printing->output_found_quantity }}" required readonly>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="printing_output_name">Damaged Quantity</label>
                        <input type="text" class="form-control" id="printing_output_name" name="printing_output_name"
                            value="{{ $printing->output_damaged_quantity }}" required readonly>
                    </div>
                </div>
                <div class="col-4" style="display: none;">
                    <div class="form-group">
                        <label for="printing_id">Printing ID</label>
                        <input type="text" class="form-control" id="printing_id" name="printing_id"
                            value="{{ $printing->id }}" required readonly>
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
                        @if (!empty($stiching_raw_materials))
                            @foreach ($stiching_raw_materials as $index => $rawItem)
                                <td class="border border-zinc-300 p-2">{{ $index + 1 }}</td>
                                <td class="border border-zinc-300 p-2">
                                    <select class="form-control raw-item-select"
                                        name="raw_items[{{ $index }}][item_id]" required>
                                        <option value="{{ $rawItem->rawItem->id }}" id="raw_item_{{ $index }}">
                                            {{ $rawItem->rawItem->name }}/{{ $rawItem->warehouse->name }}
                                        </option>
                                        @foreach ($plan->rawMaterials as $item)
                                            <option value="{{ $item->rawItem->id }}">
                                                {{ $item->rawItem->name }}/{{ $item->rawItem->warehouses->name }}
                                            </option>
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
                                    </select>
                                </td>
                                <td class="border border-zinc-300 p-2">
                                    <button type="button" class="btn btn-danger remove-row">Remove row</button>
                                </td>
                            @endforeach
                        @else
                            <td class="border border-zinc-300 p-2">1</td>
                            <td class="border border-zinc-300 p-2">
                                <select class="form-control raw-item-select" name="raw_items[0][item_id]" required>
                                    <option value="" id="raw_item_0">Select Item</option>
                                    @foreach ($plan->rawMaterials as $item)
                                        <option value="{{ $item->rawItem->id }}">
                                            {{ $item->rawItem->name }}/{{ $item->rawItem->warehouses->name }}</option>
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
                                <select class="form-control raw-unit" name="raw_items[0][unit]" required>
                                    <option value="">Select Unit</option>
                                    <!-- Units will be dynamically populated -->
                                </select>
                            </td>
                            <td class="border border-zinc-300 p-2">
                                <input type="text" class="form-control" name="raw_items[0][unit]" required>
                            </td>
                            <td class="border border-zinc-300 p-2">
                                <button type="button" class="btn btn-danger remove-row">Remove row</button>
                            </td>
                        @endif

                    </tr>

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
    <script>
        $(document).ready(function() {
            let rowCount = {{ count($plan->rawMaterials) }};
            const rawItemsQuantities = @json($rawItemsQuantities);

            $('#add-row').click(function() {
                const newRow = `
                <tr>
                    <td class="border border-zinc-300 p-2">${rowCount + 1}</td>
                    <td class="border border-zinc-300 p-2">
                        <select class="form-control raw-item-select" name="raw_items[${rowCount}][item_id]" required>
                            <option value="">Select Item</option>
                            @foreach ($plan->rawMaterials as $item)
                                    <option value="{{ $item->rawItem->id }}">{{ $item->rawItem->name }}/{{ $item->rawItem->warehouses->name }}</option>
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
            });

            function checkForDuplicates() {
                let selectedNames = [];
                $('.raw-item-select').each(function() {
                    let selectedName = $(this).find('option:selected').text();
                    if (selectedName) {
                        selectedNames.push(selectedName);
                    }
                });

                return selectedNames.length !== new Set(selectedNames).size;
            }

            $(document).on('change', '.raw-item-select', function() {
                const selectedItemId = $(this).val();
                const quantityInput = $(this).closest('tr').find('.raw-quantity');
                const warehouseSelect = $(this).closest('tr').find('.warehouse-select');
                const rawUnit = $(this).closest('tr').find('.raw-unit');
                if (checkForDuplicates()) {
                    alert('This item is already selected.');
                    $(this).val('');
                    return;
                }

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
                        url: '{{ route('admin.stiching.get_warehouse') }}',
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
                        url: '{{ route('admin.stiching.get_unit') }}',
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
            $(document).on('click', '.remove-row', function() {
                $(this).closest('tr').remove();
                rowCount--;
                updateRowIndices();
            });
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    </script>
@endsection
