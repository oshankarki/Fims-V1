@extends('layout.wrapper') @section('content')
    <!-- main content -->
    <div class="container-fluid">
        <div class="modal" tabindex="-1" role="dialog" id="exampleModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">

                        <form action="{{ route('admin.category_item.store') }}" method="POST">
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

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <span class="text-danger">{{ $error }}</span>
                @endforeach
            </div>
        @endif
        <!-- page content -->
        <div class="row">

            <div class="col-12">
                <div class="card count-{{ @count($leads ?? []) }}" id="leads-view-wrapper">
                    <div class="card-body">
                        <div class="my-2">
                            <button type="button" data-toggle="modal" data-target="#exampleModal" class="btn btn-primary">
                                Add Category
                            </button>
                            <div class="table-responsive list-table-wrapper">
                                <table id="leads-list-table" class="table m-t-0 m-b-0 table-hover no-wrap contact-list"
                                    data-page-size="10">
                                    <thead>
                                        <tr>
                                            <th>
                                                S.N
                                            </th>
                                            <th>
                                                Name
                                            </th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($categories as $category)
                                            <tr>
                                                <td>{{ $loop->index + 1 }}</td>
                                                <td>{{ $category->name }}</td>
                                                <td>
                                                    <form style="display: inline-block;"
                                                        action="{{ route('admin.category_item.destroy', $category->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                    </form>
                                                    <a href="{{ route('admin.category_item.edit', $category->id) }}"
                                                        class="btn btn-info">Edit</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="20">
                                                <!--load more button-->
                                                {{-- @include('misc.load-more-button') --}}
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
            $(function() {
                // alert("ping")
                $('#myModal').on('shown.bs.modal', function() {
                    $('#myInput').trigger('focus')
                })
            })
        </script>
    @endpush
