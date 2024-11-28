@extends('layout.wrapper') 

@section('content')
<div class="container-fluid">
    <h2>Edit Item</h2>
    <form action="{{ route('item.update', $item->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Name</label>
            <input class="form-control" value="{{ $item->name }}" type="text" name="name" id="name" required />
        </div>
        <div class="form-group">
            <label for="item_category_id">Category</label>
            <select class="form-control" name="item_category_id" id="item_category_id">
                <option value="">Select Category</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ $item->item_category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="unit_id">Units</label>
            <select class="form-control" name="unit_id" id="unit_id">
                <option value="">Select Unit</option>
                @foreach ($units as $unit)
                    <option value="{{ $unit->unit_id }}" {{ $item->unit_id == $unit->unit_id ? 'selected' : '' }}>
                        {{ $unit->unit_name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="color">Color</label>
            <input class="form-control" type="text" name="color" id="color" value="{{ $item->color }}" required />
        </div>
        <div class="form-group">
            <label for="size">Size</label>
            <input class="form-control" type="text" name="size" id="size" value="{{ $item->size }}" required />
        </div>
        <div class="form-group">
            <label for="img_url">Image</label>
            @if($item->img_url)
                <p>Current image: {{ $item->img_url }}</p>
            @endif
            <input type="file" name="img_url" id="img_url" class="form-control-file" />
        </div>
        <button type="submit" class="btn btn-primary my-2">Update</button>
    </form>
</div>
@endsection