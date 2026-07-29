<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title">Edit User Quick</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="form pb-2">
            {!! Form::open(['class' => 'user-form', 'method' => 'post', 'url' => route('nutritionPanel.users.updateUserQuick', ['id' => ev($user->id)]), 'enctype' => 'multipart/form-data', 'autocomplete' => 'off' ]) !!}
                <div class="row mb-4">
                    <div class="col-md-12">
                        <label for="meal_type_id">Meal Type</label>
                        {!! Form::select('meal_type_id', create_select_options($mealTypes, 'name', 'id', 'Select Meal Type'), $user->meal_type_id, ['class' => 'form-control select-picker', 'id' => 'meal_type_id' ]) !!}
                    </div>

                    <div class="col-md-12 mt-3">
                        <label for="user_type">User Type</label>
                        {!! Form::select('user_type', create_select_options(config('constants.user_type'), 'display', 'value', 'Select User Type'), $user->user_type, ['class' => 'form-control select-picker', 'id' => 'user_type', 'onchange' => "userType(this)" ]) !!}
                    </div>

                    <div class="col-md-12 mt-3">
                        <label for="user_state">User State</label>
                        {!! Form::select('user_state', create_select_options(config('constants.user_state'), 'display', 'value', 'Select User State'), $user->user_state, ['class' => 'form-control select-picker', 'id' => 'user_state' ]) !!}
                    </div>
                </div>                                
                {{ Form::button( '<i class="fa fa-save"></i> &nbsp;'. __('language.update'), ['class' => 'btn btn-primary btn-submit', 'type' => 'submit', 'title' => __('language.update')] )}}
            {!! Form::close() !!}
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
</div>