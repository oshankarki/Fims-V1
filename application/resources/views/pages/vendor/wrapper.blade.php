@extends('layout.wrapper') @section('content')
<!-- main content -->
<div class="container-fluid">
    <div class="modal" tabindex="-1" role="dialog" id="exampleModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <form action="{{route("vendor.create")}}" method="POST">
                        @csrf
                        <div class="d-flex flex-column form-group">
                            <div>Vendor Name</div>
                            <input class="form-control" type="text" name="name" id="name" />
                        </div>
                        <div class="d-flex flex-column form-group">
                            <div>Contact Name</div>
                            <input type="text" class="form-control" name="contact_name" id="contact_name" />
                        </div>
                        <div class="d-flex flex-column form-group">
                            <div>Contact Email</div>
                            <input type="email" class="form-control" name="contact_email" id="contact_email" />
                        </div>
                        <div class="d-flex flex-column form-group">
                            <div>Contact Phone</div>
                            <input type="number" class="form-control" name="contact_phone" id="contact_phone" />
                        </div>
                        <div class="d-flex flex-column form-group">
                            <div>Contact Address</div>
                            <input type="text" class="form-control" name="address" id="address" />
                        </div>
                        <!-- <div class="modal-footer"> -->
                        <button type="submit" class="btn btn-primary my-2">Save</button>
                        <!-- </div> -->
                    </form>
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
                            Add Vendor
                        </button>
                    </div>
                    <div class="table-responsive list-table-wrapper">
                        <table id="leads-list-table" class="table m-t-0 m-b-0 table-hover no-wrap contact-list"
                            data-page-size="10">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Contact Name</th>
                                    <th>Contact Email</th>
                                    <th>Contact Phone</th>
                                    <th>Address</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vendors as $vendor)
                                    <tr>
                                        <td>{{$vendor->name}}</td>
                                        <td>{{$vendor->contact_name}}</td>
                                        <td>{{$vendor->contact_email}}</td>
                                        <td>{{$vendor->contact_phone}}</td>
                                        <td>{{$vendor->address}}</td>
                                        <td class="d-flex" style="gap: 5px;">
                                            <form action="{{route("vendor.destroy", $vendor->id)}}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                            <a href="{{ route("vendor.edit", $vendor->id) }}" class="btn btn-info">Edit<a>
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