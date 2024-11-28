<form action="{{route("sales.create")}}" method="POST">
    @csrf
    <div class="form-group">
        <label for="customer_id">Item Info</label>
        <select name="customer_id" id="customer_id" class="form-control" required>
            <option value="">Select Customer</option>
            @foreach($customers as $customer)
                <option value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="sale_date">Sales Date</label>
        <input class="form-control" name="sales_date" type="date" />
    </div>

    <div class="form-group">
        <label for="contact_phone">Contact Phone</label>
        <input class="form-control" name="contact_phone" type="number" />
    </div>

    <button type="submit" class="btn btn-primary my-2">Save</button>
</form>