<form method="POST" action="{{ route('warehouse.transfer') }}">
    @csrf
    <div class="row">
        <div>
            <div class="form-group">
                <label for="old_warehouse">From</label>
                <select name="old_warehouse_id" id="oldWarehouse" class="form-control">
                    <option value="">Select Warehouse</option>
                    @foreach($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="">
            <div class="form-group">
                <label for="new_warehouse">To</label>
                <select name="new_warehouse_id" class="form-control" required aria-required="true">
                    <option value="">Select Warehouse</option>
                    @foreach($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="my-4" id="rawItemsSection">
                <h2>Raw Items</h2>
                <table class="table table-bordered" id="rawItemsTable">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Available Quantity</th>
                            <th>Transfer Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <p id="noRawItemsMessage" class="text-muted" style="display: none;">
                    No Raw Items Data in the warehouse
                </p>
            </div>
        </div>
    </div>

    <div class="my-4" id="productsSection" style="display: none;">
        <h2>Products</h2>
        <table class="table table-bordered" id="productsTable">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Available Quantity</th>
                    <th>Transfer Quantity</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <p id="noProductsMessage" class="text-muted" style="display: none;">
            No Products found in the warehouse
        </p>
    </div>

    <button class="btn btn-primary">Submit</button>
</form>

@push('script')
    <script>
        $(function () {
            $('#oldWarehouse').change(function () {
                var warehouseId = $(this).val();
                if (warehouseId) {
                    $.ajax({
                        url: "{{ route('warehouse.rawitems.json', ':id') }}".replace(':id', warehouseId),
                        type: "GET",
                        success: function (data) {
                            showWarehouseData(data);
                        },
                        error: function () {
                            showWarehouseData([]);
                        }
                    });

                    $.ajax({
                        url: "{{ route('warehouse.products.json', ':id') }}".replace(':id', warehouseId),
                        type: "GET",
                        success: function (data) {
                            showProducts(data);
                        },
                        error: function () {
                            showProducts([]);
                        }
                    });
                } else {
                    clearWarehouseData();
                }
            });

            function showWarehouseData(data) {
                var $section = $('#rawItemsSection');
                var $table = $('#rawItemsTable');
                var $noDataMessage = $('#noRawItemsMessage');

                if (data && data.length > 0) {
                    var tableRows = data.map((item) => {
                        return `
                            <tr>
                                <td>${item.name}</td>
                                <td>${item.actual_quantity}</td>
                                <td>
                                <input type="number" name="raw_item_transfer_quantity[${item.id}]" 
                                class="form-control" min="0" max="${item.actual_quantity}" 
                                placeholder="Enter quantity to transfer">
                                </td>
                            </tr>
                            `;
                    });
                    $table.find('tbody').html(tableRows.join(''));
                    $table.show();
                    $noDataMessage.hide();
                } else {
                    $table.find('tbody').empty();
                    $table.hide();
                    $noDataMessage.show();
                }
                $section.show();
            }

            function showProducts(data) {
                var $section = $('#productsSection');
                var $table = $('#productsTable');
                var $noDataMessage = $('#noProductsMessage');

                if (data && data.length > 0) {
                    var tableRows = data.map((product) => {
                        return `
                            <tr>
                                <td>${product.name}</td>
                                <td>${product.actual_quantity}</td>
                                <td>
                                    <input type="number" name="product_transfer_quantity[${product.id}]" 
                                        class="form-control" min="0" max="${product.actual_quantity}" 
                                        placeholder="Enter quantity to transfer">
                                </td>
                            </tr>
                        `;
                    });
                    $table.find('tbody').html(tableRows.join(''));
                    $table.show();
                    $noDataMessage.hide();
                } else {
                    $table.find('tbody').empty();
                    $table.hide();
                    $noDataMessage.show();
                }
                $section.show();
            }

            function clearWarehouseData() {
                $('#rawItemsTable tbody').empty();
                $('#productsTable tbody').empty();
                $('#rawItemsSection, #productsSection').hide();
                $('#noRawItemsMessage, #noProductsMessage').hide();
            }
        });
    </script>
@endpush