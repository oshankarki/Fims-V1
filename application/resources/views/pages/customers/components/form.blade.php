<form action="{{route("customer.create")}}" method="POST">
    @csrf

    <div class="form-group">
        <label for="customer_name">Customer Name</label>
        <input type="text" class="form-control" name="customer_name" id="customer_name" />
    </div>

    <div class="form-group">
        <label for="contact_name">Contact Name</label>
        <input type="text" class="form-control" name="contact_name" id="contact_name" />
    </div>

    <div class="form-group">
        <label for="contact_email">Contact Email</label>
        <input type="email" class="form-control" name="contact_email" id="contact_email" />
    </div>

    <div class="form-group">
        <label for="contact_address">Contact Address</label>
        <input type="text" class="form-control" name="contact_address" id="contact_address" />
    </div>

    <div class="form-group">
        <label for="contact_phone">Contact Phone</label>
        <input type="text" class="form-control" name="contact_phone" id="contact_phone" />
    </div>

    <button type="submit" class="btn btn-primary my-2">Save</button>
</form>