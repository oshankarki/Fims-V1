@extends('layout.wrapper') 

<!-- main content -->
@section('content')
<div class="container-fluid">
    <h2>Unit Edit</h2>
    <form action="{{route('unit.update', $unit->unit_id)}}" method="POST">
        @method('PUT')
        @csrf
        <div class="form-group">
            <label for="unit_name">Unit Name</label>
            <input type="text" class="form-control" name="unit_name" id="unit_name" value="{{$unit->unit_name}}">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
</div>
@endsection