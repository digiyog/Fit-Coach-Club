<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title">Add Order</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>

    <style type="text/css">
        .remark{
            resize: none;
            height: 100px;
        }
    </style>
    
    <div class="modal-body">
        <div class="form pb-2">
            {!! Form::open(['class' => 'add-order-form', 'method' => 'post', 'url' => route('nutritionPanel.orders.storeOrder'), 'enctype' => 'multipart/form-data', 'autocomplete' => 'off' ]) !!}
                <div class="row mb-4">

                    <div class="col-md-12 mt-2">
                        <label for="user">Select User</label>
                        {!! Form::select('user', create_select_options($users, 'name', 'id', 'Select User'), '', ['class' => 'form-control select-picker', 'id' => 'user' ]) !!}
                    </div>

                    <div class="col-md-12 mt-3">
                        <label for="user">Select Product</label>
                        {!! Form::select('product_id', create_select_options($products, 'name', 'id', 'Select Product'), '', ['class' => 'form-control select-picker', 'id' => 'product_id' ]) !!}
                    </div>

                    <div class="col-md-12 mt-4">
                        <label for="amount"> Total Amount </label>
                        {!! Form::number('amount', '', ['class' => 'form-control', 'id' => 'amount', 'placeholder' => 'Amount', ]) !!}
                    </div>

                    <div class="col-md-12 mt-3">
                        <label for="received_amount"> Received Amount </label>
                        {!! Form::number('received_amount', '', ['class' => 'form-control', 'id' => 'received_amount', 'placeholder' => 'Received Amount', ]) !!}
                    </div>

                    <div class="col-md-12 mt-3">
                        <label for="remark"> Remark </label>
                        {!! Form::textarea('remark', $order->remark, ['class' => 'form-control remark', 'id' => 'remark', 'placeholder' => 'Remark', ]) !!}
                    </div>
                </div>
                {{ Form::button('<i class="fa fa-save"></i> &nbsp; Add Order', ['class' => 'btn btn-primary btn-submit', 'type' => 'submit', 'title' => 'Add Order']) }}
            {!! Form::close() !!}
        </div>
    </div>
</div>