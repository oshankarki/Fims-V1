@extends('layout.wrapper')

@section('content')
    <div class="container">
        <h1>Purchase Details</h1>
        <div class="my-4">
            <form action="{{ route('purchase.create') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="vendor">Vendor</label>
                            <select name="vendor_id" id="vendor_id" class="form-control" required>
                                <option value="">Select Vendor</option>
                                @foreach ($vendors as $vendor)
                                    <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="warehouse_id">Warehouse</label>
                            <select name="warehouse_id" id="warehouse_id" class="form-control" required>
                                <option value="">Select Warehouse</option>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex flex-column form-group">
                            <label for="total_price">Total Price</label>
                            <input type="number" class="form-control" name="total_price" id="total_price" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex flex-column form-group">
                            <div>Date</div>
                            <input type="date" class="form-control" name="purchase_date" id="purchase_date" />
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex flex-column form-group">
                            <div>Purchased By</div>
                            <input type="text" class="form-control" name="purchased_by" id="purchased_by" />
                        </div>
                    </div>
                </div>
                <!-- Real Change Starts from here -->

                <!-- CHANGES -->
                <div class="row">
                    <h2>Item Details</h2>
                    <table class="table table-bordered" id="itemTable">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Item Name</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Unit</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div style="display: inline-block;">
                    <button id="addItemRow" type="button" class="btn btn-info">
                        Add New Items
                    </button>
                    <button type="submit" class="btn btn-primary my-2">Save Purchase Details</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $(function () {
        let item_infos_data = @json($iteminfos);
        let units_data = @json($units);
        let warehouses = @json($warehouses);
        let total_row_count = 0;
        $('#addItemRow').click(function () {
            total_row_count++;
            let defaultWarehouseId = $('#warehouse_id').val();
            var row = `
                <tr>
                    <th scope="row">${total_row_count}</th>
                    <td>
                        <div class="form-group item-row">
                            <label for="item_info_id">Item Name</label>
                            <select class="form-control" name="item_infos[]" required>
                                <option value="">Select Item</option>
                                ${item_infos_data.map(item => `<option value="${item.id}">${item.name}</option>`).join('')}
                            </select>
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <div>Item Quantity</div>
                            <input
                            class="form-control"
                            type="number"
                            name="item_quantities[]"
                            required
                            />
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <div>Warehouse</div>
                            <select class="form-control" name="warehouses[]" required>
                                <option value="">Select Warehouse</option>
                                ${warehouses.map(warehouse => `<option value="${warehouse.id}" ${warehouse.id == defaultWarehouseId ? 'selected' : ''}>${warehouse.name}</option>`).join('')}
                            </select>
                        </div>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger mt-2 remove-item">Remove</button>
                    </td>
                </tr>
            `;
            $('#itemTable tbody').append(row);
        })

        $('#warehouse_id').change(function() {
            let newDefaultWarehouseId = $(this).val();
            $('#itemTable tbody tr').each(function() {
                $(this).find('select[name="warehouses[]"]').val(newDefaultWarehouseId);
            });
        });

        $(document).on('click', '.remove-item', function () {
            $(this).closest('tr').remove();
            updateRowIndices();
        });

        function updateRowIndices() {
            $('#itemTable tbody tr').each(function (index) {
                $(this).find('td:first').text(index + 1);
            });
            total_row_count = $('#itemTable tbody tr').length;
        }
    })
</script>
@endsection