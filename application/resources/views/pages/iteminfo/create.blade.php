<form action="{{ route('item.create') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="d-flex flex-column form-group">
        <div>Name</div>
        <input class="form-control" type="text" name="name" id="name" required />
    </div>
    <div class="d-flex flex-column form-group">
        <div>Category</div>
        <select class="form-control" name="item_category_id" id="item_icategory_id">
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="d-flex flex-column form-group">
        <div>Units</div>
        <select class="form-control" name="unit_id" id="unit_id">
            <option value="">Select Unit</option>
            @foreach ($units as $unit)
                <option value="{{ $unit->unit_id }}">{{ $unit->unit_name }}</option>
            @endforeach
        </select>
    </div>
    <div class="d-flex flex-column form-group">
        <div>color</div>
        <input class="form-control" type="text" name="color" id="color" />
    </div>
    <div class="d-flex flex-column form-group">
        <div>size</div>
        <input class="form-control" type="text" name="size" id="size" />
    </div>
    <div class="d-flex flex-column form-group">
        <div>image</div>
        <input type="file" name="img_url" id="img_url" />
    </div>
    <button type="submit" class="btn btn-primary my-2">Save</button>
</form>
