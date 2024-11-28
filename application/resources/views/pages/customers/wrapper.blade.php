@extends('layout.wrapper') @section('content')
<!-- main content -->
<div class="container-fluid">
    <div class="modal" tabindex="-1" role="dialog" id="exampleModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    @include('pages.customers.components.form')
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
                        Add Customer
                    </button>
                </div>
                <div class="table-responsive list-table-wrapper">
                    <table id="leads-list-table" class="table m-t-0 m-b-0 table-hover no-wrap contact-list"
                        data-page-size="10">
                        <thead>
                            <tr>
                                <th>Customer Name</th>
                                <th>Contact Name</th>
                                <th>Contact Email</th>
                                <th>Contact Address</th>
                                <th>Contact Phone</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!--  -->
                            @foreach($customers as $customer)
                                <tr>
                                    <td>{{ $customer->customer_name }}</td>
                                    <td>{{ $customer->contact_name }}</td>
                                    <td>{{ $customer->contact_email }}</td>
                                    <td>{{ $customer->contact_address }}</td>
                                    <td>{{ $customer->contact_phone }}</td>
                                    <td>
                                        <form style="display: inline-block;" action="{{ route('customer.delete', $customer->id) }}" method="POST">
                                            @csrf
                                            @method("DELETE")
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                        <a href="{{ route('customer.edit', $customer->id) }}" class="btn btn-info btn-sm">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="20">
                                    {{-- Here comes Load More Info --}}
                                    {{--@include('misc.load-more-button')--}}
                                </td>
                            </tr>
                        </tfoot>
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
        $(function () {
            $('#myModal').on('shown.bs.modal', function () {
                $('#myInput').trigger('focus')
            })
        })
    </script>
@endpush