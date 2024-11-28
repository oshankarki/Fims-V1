@extends('layout.wrapper')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h1>Stiching Plan</h1>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                <div id="success-message" class="alert alert-success d-none"></div>
                <!-- Filter Form -->
                <form id="filterForm" method="GET" class="mb-4 d-flex justify-content-end">
                    <div class="form-row">
                        <div class="col-md-12">
                            <select id="statusFilter" class="form-control">
                                <option value="">Filter By</option>
                                <option value="in_warehouse">Products stored in Warehouse</option>
                                <option value="in_final">Products gone to Final</option>
                            </select>
                        </div>
                    </div>
                </form>


                <!-- Table -->


                <div class="table-responsive">
                    <table id="stichingTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Created by</th>
                                <th>Status</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Action</th>
                                <th>Stored in Warehouse?</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stichings as $stiching)
                                <tr>
                                    <td>{{ $stiching->product->name }}</td>
                                    <td>{{ $stiching->created_by }}</td>
                                    <td>
                                        @if ($stiching->status == 0)
                                            <button type="button" class="btn btn-success start-btn"
                                                data-id="{{ $stiching->id }}" data-status="{{ $stiching->status }}">
                                                Start
                                            </button>
                                        @elseif ($stiching->status == 1)
                                            <button type="button" class="btn btn-dark start-btn"
                                                data-id="{{ $stiching->id }}" data-status="{{ $stiching->status }}">
                                                Processing
                                            </button>
                                        @elseif ($stiching->status == 2)
                                            <button type="button" class="btn btn-danger start-btn" disabled>
                                                Finished
                                            </button>
                                            @if ($stiching->output_name == null)
                                                <button type="button" class="btn btn-success add-output-btn"
                                                    data-id="{{ $stiching->id }}"
                                                    data-output="{{ json_encode([
                                                        'output_name' => $stiching->product->name,
                                                        'output_quantity' => $stiching->output_quantity,
                                                        'output_actual_quantity' => $stiching->output_actual_quantity,
                                                        'output_loss_quantity' => $stiching->output_loss_quantity,
                                                        'output_found_quantity' => $stiching->output_found_quantity,
                                                        'output_damaged_quantity' => $stiching->output_damaged_quantity,
                                                    ]) }}">
                                                    Add Output
                                                </button>
                                            @endif
                                        @endif

                                    </td>
                                    <td id="start_date_{{ $stiching->id }}">{{ $stiching->start_date ?? 'N/A' }}</td>
                                    <td id="end_date_{{ $stiching->id }}">{{ $stiching->end_date ?? 'N/A' }}</td>
                                    <td style="width: 200px;">
                                        <a href="{{ route('admin.stiching.show', $stiching->id) }}"
                                            class="btn btn-info btn-sm">View</a>
                                        @if (empty($stiching->output_name))
                                            <a href="{{ route('admin.stiching.edit', $stiching->plan_id) }}"
                                                class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('admin.stiching.destroy', $stiching->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Are you sure you want to delete this plan?')">Delete</button>
                                            </form>
                                        @endif
                                        @if (!empty($stiching->output_name) && empty($stiching->final))
                                            <select id="storeSelect" class="form-control form-control-sm"
                                                style="width: 100px; display: inline-block;">

                                                <option value="">Select an option</option>
                                                <option value="goToFinal">Go to Final</option>
                                                <option value="storeToWarehouse">Store to Warehouse</option>
                                            </select>
                                            <a href="{{ route('admin.final.edit', $stiching->plan_id) }}"
                                                class="btn btn-warning btn-sm" id="goToFinalButton" style="display:none;">Go
                                                to Final</a>
                                            <button type="button" class="btn btn-success add-store-btn"
                                                id="storeToWarehouseButton" style="display:none;"
                                                data-id="{{ $stiching->id }}"
                                                {{ $stiching->warehouse_id > 0 ? 'disabled' : '' }}>
                                                Store
                                            </button>
                                        @endif

                                    </td>

                                    <td>
                                        @if ($stiching->warehouse_id != null)
                                            Yes
                                        @else
                                            Gone to final
                                        @endif

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Add Output -->
    <div class="modal fade" id="addOutputModal" tabindex="-1" role="dialog" aria-labelledby="addOutputModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="addOutputForm" method="POST">
                    @csrf
                    <input type="hidden" id="stichingId" name="id">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addOutputModalLabel">Add Output Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="output_name">Output Name</label>
                            <input type="text" id="output_name" name="output_name" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label for="output_quantity">Expected Output Quantity</label>
                            <input type="number" id="output_quantity" name="output_quantity" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="output_loss_quantity">Loss Quantity</label>
                            <input type="number" id="output_loss_quantity" name="output_loss_quantity"
                                class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="output_found_quantity">Found Quantity</label>
                            <input type="number" id="output_found_quantity" name="output_found_quantity"
                                class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="output_damaged_quantity">Damaged Quantity</label>
                            <input type="number" id="output_damaged_quantity" name="output_damaged_quantity"
                                class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="output_actual_quantity">Actual Output Quantity</label>
                            <input type="number" id="output_actual_quantity" name="output_actual_quantity"
                                class="form-control" readonly>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addWarehouseModel" tabindex="-1" role="dialog" aria-labelledby="addWarehouseModel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="addWarehouseForm" method="POST">
                    @csrf
                    <input type="hidden" id="stichingId" name="id">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addWarehouseModel">Store in the Warehouse</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="warehouse_id">Select Warehouse</label>
                            <select id="warehouseSelect" class="form-control" name="warehouse_id">
                                <option value="">Select Warehouse to store</option>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Store</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Hidden success message div -->
    <div id="success-message" class="alert alert-success d-none"></div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('storeSelect').addEventListener('change', function() {
            var selectedValue = this.value;
            var goToFinalButton = document.getElementById('goToFinalButton');
            var storeToWarehouseButton = document.getElementById('storeToWarehouseButton');

            if (selectedValue === 'goToFinal') {
                goToFinalButton.style.display = 'inline-block';
                storeToWarehouseButton.style.display = 'none';
            } else if (selectedValue === 'storeToWarehouse') {
                goToFinalButton.style.display = 'none';
                storeToWarehouseButton.style.display = 'inline-block';
            } else {
                goToFinalButton.style.display = 'none';
                storeToWarehouseButton.style.display = 'none';
            }
        });
    </script>
    <script>
        // Get references to input fields
        const outputQuantityInput = document.getElementById('output_quantity');
        const outputLossInput = document.getElementById('output_loss_quantity');
        const outputFoundInput = document.getElementById('output_found_quantity');
        const outputDamagedInput = document.getElementById('output_damaged_quantity');
        const actualOutputInput = document.getElementById('output_actual_quantity');

        // Function to calculate actual output quantity
        function calculateActualQuantity() {
            const outputQuantity = parseFloat(outputQuantityInput.value) || 0;
            const outputLoss = parseFloat(outputLossInput.value) || 0;
            const outputFound = parseFloat(outputFoundInput.value) || 0;
            const outputDamaged = parseFloat(outputDamagedInput.value) || 0;

            const actualQuantity = outputQuantity - outputLoss + outputFound - outputDamaged;

            // Update the value of the actual output quantity input field
            actualOutputInput.value = actualQuantity;
        }

        // Event listeners to trigger calculation on input change
        outputQuantityInput.addEventListener('input', calculateActualQuantity);
        outputLossInput.addEventListener('input', calculateActualQuantity);
        outputFoundInput.addEventListener('input', calculateActualQuantity);
        outputDamagedInput.addEventListener('input', calculateActualQuantity);

        // Initial calculation on page load (optional)
        calculateActualQuantity();
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const statusFilter = document.getElementById('statusFilter');
            const tableRows = document.querySelectorAll('#stichingTable tbody tr');

            statusFilter.addEventListener('change', () => {
                const selectedStatus = statusFilter.value;

                tableRows.forEach(row => {
                    const warehouseId = row.getAttribute('data-warehouse-id');

                    if (selectedStatus === 'in_warehouse') {
                        if (warehouseId) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    } else if (selectedStatus === 'in_final') {
                        if (!warehouseId) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    } else {
                        // Show all rows if no filter is selected
                        row.style.display = '';
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Function to handle clicking the start button
            $(document).on('click', '.start-btn', function() {
                var button = $(this);
                var stichingId = button.data('id');
                var currentStatus = button.data('status');
                if (currentStatus == 0) { // Only ask for confirmation if the status is "Start"
                    if (!confirm('Are you sure you want to start the plan?')) {
                        return; // If the user cancels, do nothing
                    }
                }
                if (currentStatus == 1) { // Only ask for confirmation if the status is "Start"
                    if (!confirm('Are you sure you want to finish the plan?')) {
                        return; // If the user cancels, do nothing
                    }
                }

                $.ajax({
                    url: '{{ route('admin.stiching.start') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: stichingId
                    },
                    success: function(response) {
                        $('#success-message').removeClass('d-none').text(response.message);

                        // Update button text and classes based on the response status
                        if (response.status === 1) {
                            button.text('Processing').removeClass('btn-success').addClass(
                                'btn-dark');
                            window.location.reload();
                            // Show the "Add Output" button
                            $('.add-output-btn' + stichingId).show();
                        } else if (response.status === 2) {
                            button.text('Finished').removeClass('btn-dark').addClass(
                                'btn-danger').prop('disabled', true);
                            window.location.reload();
                            // Show the "Add Output" button
                        }

                        // Update start_date and end_date in the table
                        $('#start_date_' + stichingId).text(response.start_date || 'N/A');
                        $('#end_date_' + stichingId).text(response.end_date || 'N/A');


                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });
            $('#filterForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'GET',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#stichingTable').html(response);
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });

            // Show add output modal
            $(document).on('click', '.add-output-btn', function() {
                var stichingId = $(this).data('id');
                var outputData = $(this).data('output');

                console.log("Output data:", outputData); // Debugging statement

                $('#stichingId').val(stichingId);

                if (outputData) {
                    $('#output_name').val(outputData.output_name || '');
                    $('#output_quantity').val(outputData.output_quantity || '');
                    $('#output_actual_quantity').val(outputData.output_actual_quantity || '');
                    $('#output_loss_quantity').val(outputData.output_loss_quantity || '');
                    $('#output_found_quantity').val(outputData.output_found_quantity || '');
                    $('#output_damaged_quantity').val(outputData.output_damaged_quantity || '');
                } else {
                    $('#output_name').val('');
                    $('#output_quantity').val('');
                    $('#output_actual_quantity').val('');
                    $('#output_loss_quantity').val('');
                    $('#output_found_quantity').val('');
                    $('#output_damaged_quantity').val('');
                }

                $('#addOutputModal').modal('show');
            });

            $(document).on('click', '.add-store-btn', function() {
                var stichingId = $(this).data('id');
                var outputData = $(this).data('output');

                console.log("Output data:", outputData); // Debugging statement

                $('#stichingId').val(stichingId);

                if (outputData) {
                    $('#warehouse_id').val(outputData.warehouse || '');
                } else {
                    $('#warehouse_id').val('');
                }

                $('#addWarehouseModel').modal('show');
            });
            // Submit form handler for adding output
            $('#addOutputForm').on('submit', function(e) {
                e.preventDefault();
                var formData = {
                    _token: '{{ csrf_token() }}',
                    id: $('#stichingId').val(),
                    output_name: $('#output_name').val(),
                    output_quantity: $('#output_quantity').val(),
                    output_actual_quantity: $('#output_actual_quantity').val(),
                    output_loss_quantity: $('#output_loss_quantity').val(),
                    output_found_quantity: $('#output_found_quantity').val(),
                    output_damaged_quantity: $('#output_damaged_quantity').val()
                };

                $.ajax({
                    url: '{{ route('admin.stiching.output_update') }}',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#success-message').removeClass('d-none').text(response
                            .message);
                        $('#addOutputModal').modal('hide');
                        window.location.reload();
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        // Handle error here
                    }
                });
            });

            $('#addWarehouseForm').on('submit', function(e) {
                e.preventDefault();

                var formData = {
                    _token: '{{ csrf_token() }}',
                    id: $('#stichingId').val(),
                    warehouse_id: $('#warehouseSelect').val(),
                };
                console.log(formData);

                $.ajax({
                    url: '{{ route('admin.stiching.warehouse_update') }}',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#success-message').removeClass('d-none').text(response
                            .message);
                        $('#addOutputModal').modal('hide');
                        window.location.reload();

                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        // Handle error here
                    }
                });
            });


        });
    </script>
@endsection
