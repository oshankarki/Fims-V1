@extends('layout.wrapper') @section('content')
<!-- main content -->
<div class="container-fluid">
    <div class="modal" tabindex="-1" role="dialog" id="exampleModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <form action="{{route("warehouse.create")}}" method="POST">
                        @csrf
                        <div class="d-flex flex-column form-group">
                            <div>Name</div>
                            <input class="form-control" type="text" name="name" id="name" />
                        </div>
                        <button type="submit" class="btn btn-primary my-2">Save</button>
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
                            Add Warehouse
                        </button>
                        <a href="{{ route("warehouse.transfer.page") }}" class="btn btn-info">Transfer Warehouse Page</a>
                        <!-- <a class="btn btn-info" href="{{ route("warehouse.transfer.index") }}">Transfer Warehouse</a> -->
                        <div class="table-responsive list-table-wrapper">
                            <table id="leads-list-table" class="table m-t-0 m-b-0 table-hover no-wrap contact-list"
                                data-page-size="10">
                                <thead>
                                    <tr>
                                        <th>
                                            Name
                                        </th>
                                        <th>
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($warehouses as $warehouse)
                                        <tr>
                                            <td>{{$warehouse->name}}</td>
                                            <td>
                                                <a href="{{route('warehouse.view', $warehouse->id)}}"
                                                    class="btn btn-primary">View Details</a>
                                                <form style="display: inline-block;"
                                                    action="{{route("warehouse.destroy", $warehouse->id)}}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                                <a href="{{route('warehouse.edit', $warehouse->id)}}"
                                                    class="btn btn-info">Edit</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="20">
                                            <!--load more button-->
                                            {{--@include('misc.load-more-button')--}}
                                            <!--load more button-->
                                        </td>
                                    </tr>
                                </tfoot>
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