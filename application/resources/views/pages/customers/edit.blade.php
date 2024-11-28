@extends('layout.wrapper') 

@section('content')
<!-- main content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="card-title">
                                <h3>Edit Customer</h3>
                            </div>
                        </div>
                    </div>
                    <!-- form -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card-body">
                                <form action="{{ route('customer.update', $customer->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="customer_name">Customer Name</label>
                                                <input type="text" class="form-control" id="customer_name"
                                                    name="customer_name" value="{{ $customer->customer_name }}"
                                                    required>
                                                <small class="form-text text-muted">Customer Name</small>
                                            </div>
                                            <div class="form-group">
                                                <label for="contact_name">Contact Name</label>
                                                <input type="text" class="form-control" id="contact_name"
                                                    name="contact_name" value="{{ $customer->contact_name }}" required>
                                                <small class="form-text text-muted">Contact Name</small>
                                            </div>
                                            <div class="form-group">
                                                <label for="contact_email">Contact Email</label>
                                                <input type="email" class="form-control" id="contact_email"
                                                    name="contact_email" value="{{ $customer->contact_email }}"
                                                    required>
                                                <small class="form-text text-muted">Contact Email</small>
                                            </div>
                                            <div class="form-group">
                                                <label for="contact_address">Contact Address</label>
                                                <input type="text" class="form-control" id="contact_address"
                                                    name="contact_address" value="{{ $customer->contact_address }}"
                                                    required>
                                                <small class="form-text text-muted">Contact Address</small>
                                            </div>
                                            <div class="form-group">
                                                <label for="contact_phone">Contact Phone</label>
                                                <input type="text" class="form-control" id="contact_phone"
                                                    name="contact_phone" value="{{ $customer->contact_phone }}"
                                                    required>
                                                <small class="form-text text-muted">Contact Phone</small>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection