<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title">Add User Days</h4>
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
            {!! Form::open(['class' => 'add-user-days-form', 'method' => 'post', 'url' => route('nutritionPanel.users.updateUserDays', ['id' => ev($user->id)]), 'enctype' => 'multipart/form-data', 'autocomplete' => 'off' ]) !!}
                <div class="row mb-4">
                    <div class="col-md-12">
                        <label for="days">Days</label>
                        {!! Form::select('days', array_combine(range(1, 60), range(1, 60)), null, [
                            'class' => 'form-control select-picker',
                            'id' => 'days',
                            'title' => 'Select Days'
                        ]) !!}
                    </div>

                    <div class="col-md-12 mt-3">
                        <label for="amount"> Total Amount </label>
                        {!! Form::number('amount', '', ['class' => 'form-control', 'id' => 'amount', 'placeholder' => 'Amount', ]) !!}
                    </div>

                    <div class="col-md-12 mt-3">
                        <label for="received_amount"> Received Amount </label>
                        {!! Form::number('received_amount', '', ['class' => 'form-control', 'id' => 'received_amount', 'placeholder' => 'Received Amount', ]) !!}
                    </div>

                    <!-- <div class="col-md-12 mt-3">
                        <label for="payment_type">Payment Type</label>
                        {!! Form::select('payment_type', create_select_options(config('constants.payment_type'), 'display', 'value', 'Select Payment Type'), '', ['class' => 'form-control select-picker', 'id' => 'payment_type' ]) !!}
                    </div> -->

                    <div class="col-md-12 mt-3">
                        <label for="remark"> Remark </label>
                        {!! Form::textarea('remark', '', ['class' => 'form-control remark', 'id' => 'remark', 'placeholder' => 'Remark', ]) !!}
                    </div>
                </div>
                {{ Form::button('<i class="fa fa-save"></i> &nbsp; Add User Days', ['class' => 'btn btn-primary btn-submit', 'type' => 'submit', 'title' => 'Add User Days']) }}
            {!! Form::close() !!}
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
    </div>
</div>