@extends('layout.wrapper') 

<!-- main content -->
@section('content')
<div class="container-fluid">
    <h2>Vendor Edit</h2>
    <form action="{{route('vendor.update', $vendor->id)}}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" name="name" id="name" value="{{$vendor->name}}">
        </div>
        <div class="form-group">
            <label for="contact_name">Contact Name</label>
            <input type="text" class="form-control" name="contact_name" id="contact_name" value="{{$vendor->contact_name}}">
        </div>
        <div class="form-group">
            <label for="contact_email">Contact Email</label>
            <input type="email" class="form-control" name="contact_email" id="contact_email" value="{{$vendor->contact_email}}">
        </div>
        <div class="form-group">
            <label for="contact_phone">Contact Phone</label>
            <input type="number" class="form-control" name="contact_phone" id="contact_phone" value="{{$vendor->contact_phone}}">
        </div>
        <div class="form-group">
            <label for="address">Address</label>
            <input type="text" class="form-control" name="address" id="address" value="{{$vendor->address}}">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Save</button> 
        </div>
    </form>
</div>
@endsection