<form action="{{route("purchase.create")}}" method="POST">
    @csrf

    <div class="d-flex flex-column form-group">
        <div>Price</div>
        <input class="form-control" type="number" name="price" id="price" />
    </div>

    <div class="form-group">
        <label for="vendor_id">Vendor</label>
        <select name="vendor_id" id="vendor_id" class="form-control" required>
            <option value="">Select Vendor</option>
            @foreach($vendors as $vendor)
                <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="item_info_id">Item Info</label>
        <select name="item_info_id" id="item_info_id" class="form-control" required>
            <option value="">Select Item</option>
            @foreach($iteminfos as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="warehouse_id">Warehouse</label>
        <select name="warehouse_id" id="warehouse_id" class="form-control" required>
            <option value="">Select Warehouse</option>
            @foreach($warehouses as $warehouse)
                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="d-flex flex-column form-group">
        <div>Item Quantity</div>
        <input class="form-control" type="number" name="item_quantity" id="item_quantity" />
    </div>

    <div class="d-flex flex-column form-group">
        <div>Item Unit</div>
        <input type="number" class="form-control" name="item_unit" id="item_unit" />
    </div>

    <div class="d-flex flex-column form-group">
        <div>Date</div>
        <input type="date" class="form-control" name="purchase_date" id="purchase_date" />
    </div>

    <div class="d-flex flex-column form-group">
        <div>Purchased By</div>
        <input type="text" class="form-control" name="purchased_by" id="purchased_by" />
    </div>

    <div class="d-flex flex-column form-group">
        <div>Total Price</div>
        <input type="number" class="form-control" name="total_price" id="total_price" />
    </div>

    <div class="d-flex flex-column form-group">
        <div>Discount Amount</div>
        <input type="number" class="form-control" name="discount_amount" id="discount_amount" />
    </div>

    <div class="d-flex flex-column form-group">
        <div>Net Paid Amount</div>
        <input type="number" class="form-control" name="net_paid_amount" id="net_paid_amount" />
    </div>

    <!-- <div class="modal-footer"> -->
    <button type="submit" class="btn btn-primary my-2">Save</button>
    <!-- </div> -->
</form>