@extends('layout.wrapper')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h1>Cutting Plan</h1>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                <div id="success-message" class="alert alert-success d-none"></div>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Created by</th>
                                <th>Status</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cuttings as $cutting)
                                <tr>
                                    <td>{{ $cutting->product->name }}</td>
                                    <td>{{ $cutting->created_by }}</td>
                                    <td>
                                        @if ($cutting->status == 0)
                                            <button type="button" class="btn btn-success start-btn"
                                                data-id="{{ $cutting->id }}" data-status="{{ $cutting->status }}">
                                                Start
                                            </button>
                                        @elseif ($cutting->status == 1)
                                            <button type="button" class="btn btn-dark start-btn"
                                                data-id="{{ $cutting->id }}" data-status="{{ $cutting->status }}">
                                                Processing
                                            </button>
                                        @elseif ($cutting->status == 2)
                                            <button type="button" class="btn btn-danger start-btn" disabled>
                                                Finished
                                            </button>
                                            @if ($cutting->output_name == null)
                                                <button type="button" class="btn btn-success add-output-btn"
                                                    data-id="{{ $cutting->id }}"
                                                    data-output="{{ json_encode([
                                                        'output_name' => $cutting->product->name,
                                                        'output_quantity' => $cutting->output_quantity,
                                                        'output_actual_quantity' => $cutting->output_actual_quantity,
                                                        'output_loss_quantity' => $cutting->output_loss_quantity,
                                                        'output_found_quantity' => $cutting->output_found_quantity,
                                                        'output_damaged_quantity' => $cutting->output_damaged_quantity,
                                                    ]) }}">
                                                    Add Output
                                                </button>
                                            @endif
                                        @endif


                                    </td>
                                    <td id="start_date_{{ $cutting->id }}">{{ $cutting->start_date ?? 'N/A' }}</td>
                                    <td id="end_date_{{ $cutting->id }}">{{ $cutting->end_date ?? 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('admin.cutting.show', $cutting->id) }}"
                                            class="btn btn-info btn-sm">View</a>
                                        @if ($cutting->output_name === null)
                                            <a href="{{ route('admin.cutting.edit', $cutting->plan_id) }}"
                                                class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('admin.cutting.destroy', $cutting->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Are you sure you want to delete this plan?')">Delete</button>
                                            </form>
                                        @endif

                                        @if (!empty($cutting->output_name) && empty($cutting->printing))
                                            <a href="{{ route('admin.printing.edit', $cutting->plan_id) }}"
                                                class="btn btn-warning btn-sm">Go to Printing</a>
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
                    <input type="hidden" id="cuttingId" name="id">
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
                            <input type="number" id="output_loss_quantity" name="output_loss_quantity" class="form-control"
                                required>
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

    <!-- Hidden success message div -->
    <div id="success-message" class="alert alert-success d-none"></div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
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
        $(document).ready(function() {
            // Function to handle clicking the start button
            $(document).on('click', '.start-btn', function() {
                var button = $(this);
                var cuttingId = button.data('id');
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
                    url: '{{ route('admin.cutting.start') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: cuttingId
                    },
                    success: function(response) {
                        $('#success-message').removeClass('d-none').text(response.message);

                        // Update button text and classes based on the response status
                        if (response.status === 1) {
                            button.text('Processing').removeClass('btn-success').addClass(
                                'btn-dark');
                            window.location.reload();

                            // Show the "Add Output" button
                            $('.add-output-btn' + cuttingId).show();
                        } else if (response.status === 2) {
                            button.text('Finished').removeClass('btn-dark').addClass(
                                'btn-danger').prop('disabled', true);
                            window.location.reload();


                            // Show the "Add Output" button
                        }

                        // Update start_date and end_date in the table

                        $('#start_date_' + cuttingId).text(response.start_date || 'N/A');
                        $('#end_date_' + cuttingId).text(response.end_date || 'N/A');


                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });

            // Show add output modal
            $(document).on('click', '.add-output-btn', function() {
                var cuttingId = $(this).data('id');
                var outputData = $(this).data('output');

                console.log("Output data:", outputData); // Debugging statement

                $('#cuttingId').val(cuttingId);

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
            // Submit form handler for adding output
            $('#addOutputForm').on('submit', function(e) {
                e.preventDefault();
                var formData = {
                    _token: '{{ csrf_token() }}',
                    id: $('#cuttingId').val(),
                    output_name: $('#output_name').val(),
                    output_quantity: $('#output_quantity').val(),
                    output_actual_quantity: $('#output_actual_quantity').val(),
                    output_loss_quantity: $('#output_loss_quantity').val(),
                    output_found_quantity: $('#output_found_quantity').val(),
                    output_damaged_quantity: $('#output_damaged_quantity').val()
                };

                $.ajax({
                    url: '{{ route('admin.cutting.output_update') }}',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#success-message').removeClass('d-none').text(response.message);
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
