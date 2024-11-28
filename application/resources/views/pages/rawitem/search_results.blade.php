@extends('layout.wrapper') @section('content')
    <!-- main content -->
    <div class="container-fluid">


        <!-- page content -->
        <div class="row">
            <div class="col-12">
                <div class="card count-{{ @count($leads ?? []) }}" id="leads-view-wrapper">
                    <div class="card-body">
                        <div class="my-2">
                            <div class="row">
                                <div class="col">
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
                        </div>
                    </div>
                    <h1>Search Results</h1>
                    <p>You have searched for the keyword: <b>{{ $searchTerm }}</b> and found the following results.</p>
                    <p>Total number of results: <b>{{ $results->count() }}</b></p>
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
                                @if ($results->isEmpty())
                                    <h1>No results found.</h1>
                                @else
                                    @foreach ($results as $raw_item)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $raw_item->name ?? 'null' }}</td>
                                            <td>{{ $raw_item->actual_quantity }}</td>
                                            <td>
                                                {{ $raw_item->itemInfos->units->unit_name }}
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
                                @endif
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
