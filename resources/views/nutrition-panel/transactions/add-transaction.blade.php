<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title">Add Transaction</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <style type="text/css">
        .remark{
            resize: none;
            height: 100px;
        }
    </style>
    
    <div class="modal-body">
        <div class="form pb-2">
            {!! Form::open(['class' => 'add-transaction-form', 'method' => 'post', 'url' => route('nutritionPanel.transactions.storeTransaction'), 'enctype' => 'multipart/form-data', 'autocomplete' => 'off' ]) !!}
                <div class="row mb-4">

                    <div class="col-md-12 mt-3">
                        <label for="user">Select User <span class="text-danger">*</span></label>
                        {!! Form::select('user', create_select_options($users, 'name', 'id', 'Select User'), '', ['class' => 'form-control select-picker', 'id' => 'user' ]) !!}
                    </div>

                    <div class="col-md-12 mt-4">
                        <label for="amount"> Total Amount <span class="text-danger">*</span></label>
                        {!! Form::number('amount', '', ['class' => 'form-control', 'id' => 'amount', 'placeholder' => 'Amount', 'step' => 'any', 'min' => '0']) !!}
                    </div>

                    <div class="col-md-12 mt-3">
                        <label for="received_amount"> Received Amount <span class="text-danger">*</span></label>
                        {!! Form::number('received_amount', '', ['class' => 'form-control', 'id' => 'received_amount', 'placeholder' => 'Received Amount', 'step' => 'any', 'min' => '0']) !!}
                    </div>

                    <div class="col-md-12 mt-3">
                        <label for="type">Select Type <span class="text-danger">*</span></label>
                        <select class="form-control" name="type" id="type">
                            <option value="">Select Type</option>
                            <option value="0">Subscription</option>
                            <option value="1">Product</option>
                        </select>
                    </div>

                    <div class="col-md-12 mt-3">
                        <label for="remark"> Remark </label>
                        {!! Form::textarea('remark', '', ['class' => 'form-control remark', 'id' => 'remark', 'placeholder' => 'Remark', ]) !!}
                    </div>
                </div>
                {{ Form::button('<i class="fa fa-save"></i> &nbsp; Add Transaction', ['class' => 'btn btn-primary btn-submit', 'type' => 'submit', 'title' => 'Add Transaction']) }}
            {!! Form::close() !!}
        </div>
    </div>
</div>