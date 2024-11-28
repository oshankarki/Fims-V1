@extends('layout.wrapper')

@section('content')
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container-fluid {
            width: 100%;
            padding: 0 15px;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -15px;
        }

        .col-12 {
            width: 100%;
            padding: 0 15px;
        }

        .col-md-6,
        .col-md-4,
        .col-md-3 {
            padding: 0 15px;
        }

        .col-md-6 {
            flex: 0 0 50%;
            max-width: 50%;
        }

        .col-md-4 {
            flex: 0 0 33.3333%;
            max-width: 33.3333%;
        }

        .col-md-3 {
            flex: 0 0 25%;
            max-width: 25%;
        }

        .text-center {
            text-align: center;
        }

        .my-4 {
            margin-top: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .mb-4 {
            margin-bottom: 1.5rem;
        }

        .mb-3 {
            margin-bottom: 1rem;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
            padding: 0.75rem 1.25rem;
            margin-bottom: 1rem;
            border: 1px solid transparent;
            border-radius: 0.25rem;
        }

        .card {
            padding: 1.25rem;
            margin: 1.5rem 0;
            border: 1px solid #ddd;
            border-radius: 0.25rem;
            background-color: #fff;
        }

        .font-weight-bold {
            font-weight: bold;
        }

        .btn {
            display: inline-block;
            font-weight: 400;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            user-select: none;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: 0.25rem;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            cursor: pointer;
            margin-bottom: 0.5rem;
        }

        .btn-primary {
            color: #fff;
            background-color: #007bff;
            border: 1px solid #007bff;
        }

        .btn-secondary {
            color: #fff;
            background-color: #6c757d;
            border: 1px solid #6c757d;
        }

        .btn-dark {
            color: #fff;
            background-color: #343a40;
            border: 1px solid #343a40;
        }

        .btn-warning {
            color: #212529;
            background-color: #ffc107;
            border: 1px solid #ffc107;
        }

        .btn-danger {
            color: #fff;
            background-color: #dc3545;
            border: 1px solid #dc3545;
        }

        .w-100 {
            width: 100%;
        }

        .table-responsive {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }

        .table-bordered {
            border: 1px solid #dee2e6;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.05);
        }

        @media print {
            body * {
                visibility: hidden;
            }

            .print-card,
            .print-card * {
                visibility: visible;
            }

            .print-card {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 20px;
                box-sizing: border-box;
            }

            #print-button,
            #create-plan-button,
            #edit-button,
            #delete-button {
                display: none !important;
            }
        }
    </style>

    <div class="container-fluid" id="content">
        <div class="row">
            <div class="col-12">

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="card print-card">
                    <h1 class="text-center my-4">Cutting Plan {{ $cutting->id }}</h1>

                    <div class="row mb-4">
                        <!-- Plan ID and Plan Date -->
                        <div class="col-md-6 mb-3">
                            <h4 class="font-weight-bold">Plan ID: {{ $cutting->id }}</h4>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h4 class="font-weight-bold">Plan Date: {{ $cutting->start_date }}</h4>
                        </div>

                        <!-- Product Details -->
                        <div class="col-md-4 mb-3">
                            <h4 class="font-weight-bold">Product Name: {{ $cutting->product->name }}</h4>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h4 class="font-weight-bold">Color: {{ $cutting->plans->color }}</h4>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h4 class="font-weight-bold">Product Quantity: {{ $cutting->plans->product_quantity }}</h4>
                        </div>

                        <!-- Created By -->
                        <div class="col-md-4 mb-3">
                            <h4 class="font-weight-bold">Created by: {{ $cutting->created_by }}</h4>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h4 class="font-weight-bold">Status:
                                @if ($cutting->status == 0)
                                    Started
                                @elseif ($cutting->status == 1)
                                    Processing
                                @elseif($cutting->status == 2)
                                    Finished
                                @endif

                            </h4>
                        </div>
                    </div>

                    <!-- Plan Status Buttons -->
                    @if ($cutting->status == 2)
                        <h2 class="text-center mb-4">Output Details</h2>
                        <div class="row mb-4">
                            <div class="col-md-4 mb-3">
                                <h4 class="font-weight-bold">Output Name: {{ $cutting->output_name }}</h4>
                            </div>
                            <div class="col-md-4 mb-3">
                                <h4 class="font-weight-bold">Expected Quantity: {{ $cutting->output_quantity }}</h4>
                            </div>

                            <div class="col-md-4 mb-3">
                                <h4 class="font-weight-bold">Actual Output Quantity: {{ $cutting->output_actual_quantity }}
                                </h4>
                            </div>
                            <div class="col-md-4 mb-3">
                                <h4 class="font-weight-bold">Output Loss Quantity: {{ $cutting->output_loss_quantity }}
                                </h4>
                            </div>
                            <div class="col-md-4 mb-3">
                                <h4 class="font-weight-bold">Output Found Quantity: {{ $cutting->output_found_quantity }}
                                </h4>
                            </div>
                            <div class="col-md-4 mb-3">
                                <h4 class="font-weight-bold">Output Damage Quantity: {{ $cutting->output_damage_quantity }}
                                </h4>
                            </div>

                            <!-- Created By -->

                        </div>
                    @endif

                    <!-- Raw Materials Table -->
                    <h2 class="mb-4">Raw Materials</h2>
                    <div class="table-responsive mb-4">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>S.N.</th>
                                    <th>Items Name</th>
                                    <th>Warehouse</th>
                                    <th>QTY</th>
                                    <th>Unit</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($cutting->rawMaterials as $item)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ optional($item->rawItem)->name }}</td>
                                        <td>{{ $item->warehouse->name }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ $item->unit->unit_name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Action Buttons -->

                </div>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-end m-4">


        <form action="{{ route('admin.cutting.destroy', $cutting->id) }}" method="POST" id="delete-form"
            style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger mr-2" id="delete-button">Delete</button>
        </form>

        <button id="print-button" onclick="printPage()" class="btn btn-dark">Download</button>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <script>
        function printPage() {
            const element = document.getElementById('content'); // Ensure this matches your element ID
            const opt = {
                margin: 1,
                filename: 'cutting_plan.pdf',
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 2
                },
                jsPDF: {
                    unit: 'in',
                    format: 'letter',
                    orientation: 'portrait'
                }
            };
            html2pdf().set(opt).from(element).save();
        }
    </script>
@endsection
