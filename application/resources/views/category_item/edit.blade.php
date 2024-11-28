@extends('layout.wrapper')

@section('content')
    <!-- main content -->
    <div class="container-fluid">
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <span class="text-danger">{{ $error }}</span>
                @endforeach
            </div>
        @endif
        <h2>Edit Category</h2>
        <div class="row">
            <div class="col-12">
                <form action="{{ route('admin.category_item.update', $item->id) }}" method="POST">
                    @method('PUT')
                    @csrf
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" name="name" value="{{ $item->name }}" required>
                    </div>
                    <button class="btn btn-primary">Update</button>

                </form>
            </div>
        </div>
    </div>
@endsection
