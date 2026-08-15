<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title">Edit Transaction</h4>
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
            {!! Form::open(['class' => 'update-transaction-form', 'method' => 'post', 'url' => route('nutritionPanel.transactions.updateTransaction', ['id' => ev($transaction->id)]), 'enctype' => 'multipart/form-data', 'autocomplete' => 'off' ]) !!}
                <div class="row mb-4">
                    <div class="col-md-12 mt-3">
                        <label for="amount"> Total Amount </label>
                        {!! Form::number('amount', $transaction->total_amount, ['class' => 'form-control', 'id' => 'amount', 'placeholder' => 'Amount' ]) !!}
                    </div>

                    <div class="col-md-12 mt-3">
                        <label for="received_amount"> Received Amount </label>
                        {!! Form::number('received_amount', $transaction->received_amount, ['class' => 'form-control', 'id' => 'received_amount', 'placeholder' => 'Received Amount', ]) !!}
                    </div>

                    <div class="col-md-12 mt-3">
                        <label for="remark"> Remark </label>
                        {!! Form::textarea('remark', $transaction->remark, ['class' => 'form-control remark', 'id' => 'remark', 'placeholder' => 'Remark', ]) !!}
                    </div>
                </div>                                
                {{ Form::button( '<i class="fa fa-save"></i> &nbsp;'. __('language.update'), ['class' => 'btn btn-primary btn-submit', 'type' => 'submit', 'title' => __('language.update')] )}}
            {!! Form::close() !!}
        </div>
    </div>
</div>