@extends('layout.wrapper')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card count-{{ @count($leads ?? []) }}" id="leads-view-wrapper">
                <div class="card-body">
                    <div class="my-2">
                        <a href="{{ route('warehouse.index') }}" class="btn btn-info">
                            Go Back
                        </a>
                        <a class="btn btn-primary" href="{{ route('warehouse.transfer.index') }}">
                            Transfer Warehouse
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">From</th>
                        <th scope="col">To</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transfers as $transfer)
                    <tr>
                        <th>{{ $loop->iteration }}</th>
                        <td>{{ $transfer->fromWarehouse->name }}</td>
                        <td>{{ $transfer->toWarehouse->name  }}</td>
                        <td>{{ $transfer->transferItems->sum('quantity') }}</td>
                        <td>
                            <a href="{{ route("warehouse.transfer.view", $transfer->id) }}" class="label label-info">
                                Show
                            </a>
                        </td>
                    </tr>
                    @endforeach
                    <!-- <tr>
                        <th>1</th>
                        <td>Mark</td>
                        <td>Otto</td>
                        <td>@mdo</td>
                    </tr> -->
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('css')
    <style>
        .table thead th,
        .table th,
        .table td {
            border: 1px solid #ccc !important;
        }
    </style>
@endsection