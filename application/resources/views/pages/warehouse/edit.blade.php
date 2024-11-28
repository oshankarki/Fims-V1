@extends('layout.wrapper') 

@section('content')
<!-- main content -->
<div class="container-fluid">
    <h2>Edit Warehouse</h2>
    <div class="row">
        <div class="col-12">
            <form action="{{ route('warehouse.update', $warehouse->id) }}" method="POST">
                @method('PUT')
                @csrf
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="name" value="{{ $warehouse->name }}" required>
                </div>
                <div class="form-group">
                    <label for="capacity">Capacity</label>
                    <input type="number" class="form-control" name="capacity" value="{{ $warehouse->capacity }}" required>
                </div>
                <div class="form-group">
                    <label for="used_capacity">Used Capacity</label>
                    <input type="number" class="form-control" name="used_capacity" value="{{ $warehouse->used_capacity }}" required>
                </div>
                
                <button class="btn btn-primary">Update</button>

            </form>
        </div>
    </div>
</div>
@endsection