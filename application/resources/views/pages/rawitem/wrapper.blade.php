@extends('layout.wrapper') @section('content')
    <!-- main content -->
    <div class="container-fluid">
        <div class="modal" tabindex="-1" role="dialog" id="exampleModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        @include('pages.rawitem.components.form')
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
                            <div class="row">
                                <div class="col">
                                    <button type="button" data-toggle="modal" data-target="#exampleModal"
                                        class="btn btn-primary">
                                        Add Raw Item
                                    </button>
                                </div>
                                <div class="col">

                                    <form action="{{ route('rawitem.search') }}" method="GET">
                                        <label for="searchTerm">Search Raw Item:</label>
                                        <input type="text" id="searchTerm" name="searchTerm" required>
                                        <button class="btn btn-primary">
                                            Search
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div>
                                <form action="{{ route('rawitem.excelsheet') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="file" name="file" />
                                    <button class="btn btn-primary">
                                        Submit
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="table-responsive list-table-wrapper">
                            <table id="leads-list-table" class="table m-t-0 m-b-0 table-hover no-wrap contact-list"
                                data-page-size="10">
                                <thead>
                                    <tr>
                                        <th>S.N</th>
                                        <th>Name</th>
                                        <th>Actual Quantity</th>
                                        <th>Unit</th>
                                        <th>Color</th>
                                        <th>Size</th>
                                        <th>Warehouse</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($raw_items as $raw_item)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $raw_item->name ?? 'null' }}</td>
                                            <td>{{ $raw_item->actual_quantity }}</td>
                                            <td>
                                                {{ $raw_item->itemInfos->units->unit_name ?? '' }}
                                            </td>
                                            <td>{{ $raw_item->itemInfos->color }}</td>
                                            <td>{{ $raw_item->itemInfos->size }}</td>
                                            <td>{{ $raw_item->warehouses->name }}</td>
                                            <td>
                                                <form action="{{ route('rawitem.destroy', $raw_item->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger"
                                                        onclick="return confirm('Are you sure you want to delete this plan?')">Delete</button>
                                                </form>
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
        <!--page content -->
    </div>
    <!--main content -->
@endsection

@push('script')
    <script>
        $(function() {
            // alert("ping")
            $('#myModal').on('shown.bs.modal', function() {
                $('#myInput').trigger('focus')
            })
        })
    </script>
@endpush
