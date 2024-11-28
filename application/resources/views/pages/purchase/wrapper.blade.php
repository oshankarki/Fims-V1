@extends('layout.wrapper') @section('content')
    <!-- main content -->
    <div class="container-fluid">

        <!--page heading-->
        <div class="row page-titles">

        </div>
        <!--page heading-->

        <div class="modal" tabindex="-1" role="dialog" id="exampleModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        @include('pages.purchase.components.form')
                    </div>
                </div>
            </div>
        </div>

        <!-- page content -->
        <div class="row">
            <div class="col-12">
                <h2>Purchase</h2>
                <div class="card count-{{ @count($leads ?? []) }}" id="leads-view-wrapper">
                    <div class="card-body">
                        <div class="my-2">
                            <a class="btn btn-primary" href="{{ route('purchase.create.get') }}">
                                Add Purchase
                            </a>
                            <div class="btn btn-info" onclick="printContentPDF('purchase_page', 'Purchases')">Print</div>
                        </div>

                        <div class="table-responsive list-table-wrapper" id="purchase_page">
                            <table id="leads-list-table" class="table m-t-0 m-b-0 table-hover no-wrap contact-list"
                                data-page-size="10">
                                <thead>
                                    <tr>
                                        <th>SN</th>
                                        <th>Vendor</th>
                                        <th>Item Name</th>
                                        <th>Purchase Date</th>
                                        <th>Puchased By</th>
                                        <th>Total Price</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($purchases as $purchase)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $purchase->vendor->name }}</td>
                                            <td>{{ $purchase->itemInfos->first()->name ?? 'No item info available' }}</td>
                                            <td>{{ $purchase->purchase_date }}</td>
                                            <td>{{ $purchase->purchased_by }}</td>
                                            <td>{{ $purchase->total_price }}</td>
                                            <td>
                                                <a href="{{ route('purchase.edit', $purchase->id) }}"
                                                    class="label label-info" style="color: white;">Edit</a>
                                                <a href="{{ route('purchase.show', $purchase->id) }}"
                                                    class="label label-primary" style="color: white;">View</a>
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
