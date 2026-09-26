<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title">Edit Transaction @if(!empty($transaction->user->name)) — <span style="color: #3b46f1;">{{ $transaction->user->name }}</span>@endif</h4>
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
                    @if(!empty($transaction->user))
                    <div class="col-md-12 mt-2">
                        <label class="text-muted mb-1 d-block"><small>User</small></label>
                        <strong>{{ $transaction->user->name ?? 'N/A' }}</strong>
                        @if(!empty($transaction->user->mobile_number))
                            <span class="text-muted">({{ $transaction->user->mobile_number }})</span>
                        @endif
                    </div>
                    @endif

                    <div class="col-md-12 mt-3">
                        <label for="amount"> Total Amount <span class="text-danger">*</span></label>
                        {!! Form::number('amount', $transaction->total_amount, ['class' => 'form-control', 'id' => 'amount', 'placeholder' => 'Amount', 'step' => 'any', 'min' => '0']) !!}
                    </div>

                    <div class="col-md-12 mt-3">
                        <label for="received_amount"> Received Amount <span class="text-danger">*</span></label>
                        {!! Form::number('received_amount', $transaction->received_amount, ['class' => 'form-control', 'id' => 'received_amount', 'placeholder' => 'Received Amount', 'step' => 'any', 'min' => '0']) !!}
                    </div>

                    <div class="col-md-12 mt-3">
                        <label for="type">Select Type</label>
                        <select class="form-control" name="type" id="type">
                            <option value="0" {{ (isset($transaction->type) && $transaction->type == 0) ? 'selected' : '' }}>Subscription</option>
                            <option value="1" {{ (isset($transaction->type) && $transaction->type == 1) ? 'selected' : '' }}>Product</option>
                        </select>
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