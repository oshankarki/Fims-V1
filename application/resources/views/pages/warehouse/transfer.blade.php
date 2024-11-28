@extends('layout.wrapper')

@section('content')
<div class="container">
    <div class="row my-4">
        <div class="col-12">
            <h2>Warehouse Transfer</h2>
        </div>
        <div class="col-12">
            @include('pages.warehouse.components.transferform')
        </div>
    </div>
</div>
@endsection