@extends('layout.wrapper')

@section('content')
<div class="container-fluid">
    <form action="{{route("sales.create")}}" method="POST">
        @csrf
        <div class="form-group">
            <label for="customer_id">Customer</label>
            <select name="customer_id" id="customer_id" class="form-control" required>
                <option value="">Select Customer</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="sale_date">Sales Date</label>
            <input class="form-control" name="sales_date" type="date" />
        </div>

        <!-- <div class="form-group">
            <label for="contact_phone">Contact Phone</label>
            <input class="form-control" name="contact_phone" type="number" />
        </div> -->

        <div class="productRow"></div>

        <button class="btn btn-info" type="button" id="addProductRow">Add Product</button>


        <!-- <div class="modal-footer"> -->
        <button type="submit" class="btn btn-primary my-2">Save</button>
        <!-- </div> -->
    </form>
</div>
@endsection


@section('scripts')
<script>
    $(function () {
        let products = @json($products);
        $('#addProductRow').click(function () {
            var row = `
                 <div class="form-group item-row">
                    <label for="item_info_id">Item Info</label>
                    <select class="form-control product-select" name="products[]" required>
                        <option value="">Select Item</option>
                        ${products.map(product => `<option id="selectedProduct" value="${product.id}">${product.name}</option>`).join('')}
                    </select>

                    <div class="stock-info mt-2" style="display: none;">
                        <p>Available Stock: <br />
                            <span class="available-stock-a"></span> <br />
                            <span class="available-stock-b"></span> <br />
                        </p>
                    </div>

                    <div class="d-none select-stock my-2">
                        <div class="form-group">
                            <label for="type-of-stock">Stock type</label>
                            <select class="form-control" name="stock_types[]" required>
                                <option value="stock_a">Stock A</option>
                                <option value="stock_b">Stock B</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="quantity">Quantity</label>
                            <input class="form-control" type="number" name="quantities[]" required />
                        </div>
                    </div>
                    
                    <button type="button" class="btn btn-danger mt-2 remove-item">Remove</button>
                </div>
            `;
            $('.productRow').append(row);
        })

        $(document).on('click', '.remove-item', function () {
            $(this).closest('.item-row').remove();
        });

        $(document).on('change', '.product-select', function() {
            var productId = $(this).val();
            var stockInfoDiv = $(this).siblings('.stock-info');
            var quantityInput = stockInfoDiv.find('.quantity-input');
            var selectStockDiv = $('.select-stock');
            
            if (productId) {
                $.ajax({
                    url: "{{ route('response.product', ':id') }}".replace(':id', productId),
                    type: 'GET',
                    success: function(response) {
                        stockInfoDiv.show();
                        selectStockDiv.addClass('d-block');
                        stockInfoDiv.find('.available-stock-a').text("Stock A: " + response.stock_a);
                        stockInfoDiv.find('.available-stock-b').text("Stock B: " + response.stock_b);
                        quantityInput.attr('max', response.stock);
                    },
                    error: function(xhr) {
                        console.log('Error:', xhr);
                    }
                });
            } else {
                stockInfoDiv.hide();
            }
        });
    })
</script>

@endsection