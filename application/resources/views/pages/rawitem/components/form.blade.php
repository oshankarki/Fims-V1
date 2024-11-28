<form action="{{route("rawitem.create")}}" method="POST">
    @csrf    
    <div class="form-group">
        <label for="actual_quantity">Actual Quantity</label>
        <input type="number" class="form-control" name="actual_quantity" id="actual_quantity" />
    </div>

    <div class="form-group">
        <label for="color">Color</label>
        <input type="text" name="color" id="color" class="form-control" />
    </div>
    
    <div class="form-group">
        <label for="size">size</label>
        <input type="text" class="form-control" name="size" id="size" />
    </div>

    <div class="form-group">
        <label for="item_info">Item Info</label>
        <select name="iteminfo_id" id="iteminfo_id" class="form-control" required>
            <option value="">Select Item Info</option>
            @foreach($itemInfos as $iteminfo)
            <option value="{{ $iteminfo->id }}">{{ $iteminfo->name }}</option>
            @endforeach
        </select>
    </div>
    
    <div class="form-group">
        <label for="unit_id">Unit</label>
        <select name="unit_id" id="unit_id" class="form-control" required>
            <option value="">Select Unit</option>
            @foreach($units as $unit)
            <option value="{{ $unit->unit_id }}">{{ $unit->unit_name }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="purchase_id">Purchase Item</label>
        <select name="purchase_id" id="purchase_id" class="form-control" required>
            <option value="">Select Purchase</option>
            @foreach($purchases as $purchase)
                @foreach($purchase->itemInfos as $itemInfo)
                <option value="{{ $purchase->id }}">{{ $itemInfo->name }}</option>
                @endforeach
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

    <!-- <div class="modal-footer"> -->
    <button type="submit" class="btn btn-primary my-2">Save</button>
    <!-- </div> -->
</form>