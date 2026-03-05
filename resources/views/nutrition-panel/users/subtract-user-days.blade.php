<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title">Subtract User Days</h4>
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
            {!! Form::open(['class' => 'subtract-user-days-form', 'method' => 'post', 'url' => route('nutritionPanel.users.updateSubtractUserDays', ['id' => ev($user->id)]), 'enctype' => 'multipart/form-data', 'autocomplete' => 'off' ]) !!}
                <div class="row mb-4">
                    <div class="col-md-12">
                        <label for="days">Days</label>
                        {!! Form::select('days', array_combine(range(1, $user->days), range(1, $user->days)), null, [
                            'class' => 'form-control select-picker',
                            'id' => 'days',
                            'title' => 'Select Days'
                        ]) !!}
                    </div>

                    <div class="col-md-12 mt-3">
                        <label for="remark"> Remark </label>
                        {!! Form::textarea('remark', '', ['class' => 'form-control remark', 'id' => 'remark', 'placeholder' => 'Remark', ]) !!}
                    </div>
                </div>
                {{ Form::button('<i class="fa fa-save"></i> &nbsp; Subtract User Days', ['class' => 'btn btn-primary btn-submit', 'type' => 'submit', 'title' => 'Subtract User Days']) }}
            {!! Form::close() !!}
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
</div>