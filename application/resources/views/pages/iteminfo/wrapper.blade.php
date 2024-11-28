@extends('layout.wrapper') @section('content')
    <!-- main content -->
    <div class="container-fluid">
        <div class="modal" tabindex="-1" role="dialog" id="exampleModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        @include('pages.iteminfo.create')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- page content -->
    <div class="row">
        <div class="col-12">
            <div class="card count-{{ @count($leads ?? []) }}" id="leads-view-wrapper">
                <div class="card-body">
                    <div class="my-2">
                        <button type="button" data-toggle="modal" data-target="#exampleModal" class="btn btn-primary">
                            Add Item Info
                        </button>
                    </div>

                    <div class="table-responsive list-table-wrapper">
                        <table id="leads-list-table" class="table m-t-0 m-b-0 table-hover no-wrap contact-list"
                            data-page-size="10">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Color</th>
                                    <th>Size</th>
                                    <th>Unit</th>
                                    <th>Image</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($item_data as $item)
                                    <tr>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->item_category->name }}</td>
                                        <td>{{ $item->color ?? 'null' }}</td>
                                        <td>{{ $item->size ?? 'null' }}</td>
                                        <td>{{ $item->units->unit_name ?? 'N/A' }}</td>
                                        <td>
                                            <img class="img img-fluid" style="height: 50px;"
                                                src="{{ url('storage/images/' . $item->img_url) }}"
                                                alt="{{ $item->name }}" />
                                        </td>
                                        <td>
                                            <a href="{{ route('item.edit', $item->id) }}"
                                                class="btn btn-info label">Edit</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@push('script')
    <script>
        $(function() {
            $('#myModal').on('shown.bs.modal', function() {
                $('#myInput').trigger('focus')
            })
        })
    </script>
@endpush
