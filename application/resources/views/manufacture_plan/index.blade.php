@extends('layout.wrapper')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h1>Manufacture Plan</h1>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <a href="{{ route('admin.manufacture_plan.create') }}" class="btn btn-primary mb-3">Add Plan</a>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Plan ID</th>
                                <th>Start Date</th>
                                <th>Product Name</th>
                                <th>Created by</th>
                                <th>Status</th>
                                <th>Remarks</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($manufacturePlans as $product)
                                <tr>
                                    <td>{{ $product->manufacture_plan_id }}</td>
                                    <td>{{ $product->start_date }}</td>
                                    <td>{{ $product->product->name }}</td>
                                    <td>{{ $product->plan_created_by }}</td>
                                    @if (empty($product->cutting))
                                        <td>Not Started</td>
                                    @elseif (!empty($product->cutting) && empty($product->printing))
                                        <td>Cutting</td>
                                    @elseif (!empty($product->printing) && empty($product->stiching))
                                        <td>Printing</td>
                                    @elseif (!empty($product->stiching) && empty($product->final))
                                        <td>Stitching</td>
                                    @elseif (!empty($product->final))
                                        <td>Final</td>
                                    @endif



                                    <td>
                                        <form action="{{ route('admin.manufacture_plan.saveRemarks', $product->id) }}"
                                            method="POST">
                                            @csrf
                                            <textarea id="remarks_{{ $product->id }}" name="remarks" placeholder="Enter remarks...">
                                                    {{ $product->remarks ?? '' }}
                                                </textarea>
                                            <br>
                                            <button type="submit" class="btn btn-success btn-sm">Save Remarks</button>
                                        </form>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.manufacture_plan.show', $product->id) }}"
                                            class="btn btn-info btn-sm">View</a>
                                        @if (empty($product->cutting))
                                            <a href="{{ route('admin.manufacture_plan.edit', $product->id) }}"
                                                class="btn btn-primary btn-sm">Edit</a>
                                            <form action="{{ route('admin.manufacture_plan.destroy', $product->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Are you sure you want to delete this plan?')">Delete</button>
                                            </form>

                                            <a href="{{ route('admin.cutting.edit', $product->id) }}"
                                                class="btn btn-warning btn-sm">Go to Cutting</a>
                                        @endif
                                        <a href="{{ route('admin.full_plan.show', $product->id) }}"
                                            class="btn btn-warning btn-sm">View Full Plan</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
