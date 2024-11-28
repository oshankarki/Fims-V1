@extends('layout.wrapper') @section('content')
<!-- main content -->
<div class="container-fluid">
    <!-- page content -->
    <div class="row">
        <div class="col-12">
            <div class="card count-{{ @count($leads ?? []) }}" id="leads-view-wrapper">
                <div class="card-body">
                    <div class="my-2">
                        <a class="btn btn-primary" href="{{ route('sales.createPage') }}">Add Sales</a>
                    </div>
                    <div class="table-responsive list-table-wrapper">
                        <table id="leads-list-table" class="table m-t-0 m-b-0 table-hover no-wrap contact-list"
                            data-page-size="10">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Customer</th>
                                    <th>Sales Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sales as $sale)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{$sale->customers->customer_name}}</td>
                                        <td>{{$sale->sales_date}}</td>
                                        <td>
                                            <a href="{{ route("sales.view", $sale->id) }}" class="btn btn-info label">View</a>
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
        $(function () {
            // alert("ping")
            $('#myModal').on('shown.bs.modal', function () {
                $('#myInput').trigger('focus')
            })
        })
    </script>
@endpush