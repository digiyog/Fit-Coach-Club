@extends('nutrition-panel.layouts.main-layout')

@section('page-title', ' Edit User | '.__('language.page_main_title').'')

@push('styles')
<link href="{{ asset('admin-assets/css/flatpickr.min.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/dropify/dropify.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/js/plugins/summernote/summernote-bs4.min.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">

<style>
    .dropify-wrapper
    {
        width:100% !important;
        margin-bottom: unset !important;
        height: 215px;
    }
</style>
@endpush

@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-12 layout-spacing">

                <!-- Validation error -->
                @component('nutrition-panel.validation.errors') @endcomponent
                <!-- / Validation error -->

                <div class="widget-content widget-content-area br-6">
                    <div class="container-fluid mt2">
                        <div class="row">
                            <div class="col-xl-8 col-lg-8 col-md-8 col-8">
                                <h4> Edit User </h4>
                            </div>
                        </div>

                        <div class="form pb-2">
                            {!! Form::open(['class' => 'user-form', 'method' => 'post', 'url' => route('nutritionPanel.users.update', ['id' => ev($user->id)]), 'enctype' => 'multipart/form-data', 'autocomplete' => 'off' ]) !!}
                                <div class="row mb-4">
                                    
                                    @php
                                        $imagePath = (get_image_url(config('constants.users.image_path'), $user->profile_image) ?? '');
                                    @endphp

                                    <div class="col-md-3">
                                        <div class="custom-dropify">
                                            <label class="form-control-label" for="image">@lang('language.image') <span class="text-danger">*</span></label>
                                            {!! Form::file('image', ['class' => 'image-preview', 'id' => 'image', 'autocomplete' => 'off', 'data-show-remove' => 'false', 'accept' => 'image/*', "data-default-file" => $imagePath, ]) !!}

                                            {!! Form::hidden('image_name', old('image_name', ($user->profile_image ?? null)) ,['class' => 'form-control','id' => 'image_name']) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="name"> Name </label>
                                                {!! Form::text('name', $user->name, ['class' => 'form-control', 'id' => 'name', 'placeholder' => 'Name', ]) !!}
                                            </div>
                                            <div class="col-md-6">
                                                <label for="email">Email </label>
                                                {!! Form::email('email', $user->email, ['class' => 'form-control', 'id' => 'email', 'placeholder' => 'Email', 'autocomplete' => 'off', 'data-url' => route('nutritionPanel.users.checkEmail', ['id' => $user->id]) ]) !!}
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label for="mobile_number">Mobile Number </label>
                                                {!! Form::text('mobile_number', $user->mobile_number, ['class' => 'form-control numeric', 'id' => 'mobile_number', 'placeholder' => 'Mobile Number', 'data-url' => route('nutritionPanel.users.checkMobile', ['id' => $user->id]) ]) !!}
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label for="coach_name">Coach Name</label>
                                                {!! Form::text('coach_name', $user->coach_name, ['class' => 'form-control', 'id' => 'coach_name', 'placeholder' => 'Coach Name', 'autocomplete' => 'off']) !!}
                                            </div>

                                            <div class="col-md-6 mt-3">
                                                <label for="meal_type_id">Meal Type</label>
                                                {!! Form::select('meal_type_id', create_select_options($mealTypes, 'name', 'id', 'Select Meal Type'), $user->meal_type_id, ['class' => 'form-control select-picker', 'id' => 'meal_type_id' ]) !!}
                                            </div>

                                            <div class="col-md-6 mt-3">
                                                <label for="product_type_id">Product Type</label>
                                                {!! Form::select('product_type_id', create_select_options($productTypes, 'name', 'id', 'Select Product Type'), $user->product_type_id, ['class' => 'form-control select-picker', 'id' => 'product_type_id' ]) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label for="new_pass">New Password </label>
                                        {!! Form::password('new_pass', ['class' => 'form-control', 'id' => 'new_pass', 'placeholder' => 'New Password', 'autocomplete' => 'new-password' ]) !!}
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label for="confirm_pass">Confirm Password </label>
                                        {!! Form::password('confirm_pass', ['class' => 'form-control', 'id' => 'confirm_pass', 'placeholder' => 'Confirm Password', ]) !!}
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label for="user_type">User Type</label>
                                        {!! Form::select('user_type', create_select_options(config('constants.user_type'), 'display', 'value', 'Select User Type'), $user->user_type, ['class' => 'form-control select-picker', 'id' => 'user_type', 'onchange' => "userType(this)" ]) !!}
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label for="user_state">User State</label>
                                        {!! Form::select('user_state', create_select_options(config('constants.user_state'), 'display', 'value', 'Select User State'), $user->user_state, ['class' => 'form-control select-picker', 'id' => 'user_state' ]) !!}
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label for="date_of_birth">Date of Birth</label>
                                        {!! Form::text('date_of_birth', ($user->date_of_birth ?? ''), ['class' => 'form-control date-picker', 'id' => 'date_of_birth', 'placeholder' => 'Select Date of Birth' ]) !!}
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label for="age"> Enter Age <span class="text-danger">*</span></label>
                                        {!! Form::number('age', $user->age, ['class' => 'form-control', 'id' => 'age', 'placeholder' => 'Enter Age', 'autocomplete' => 'off', 'min' => '5', 'max' => '120' ]) !!}
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label for="weight"> Enter Weight(In Kg) <span class="text-danger">*</span></label>
                                        {!! Form::number('weight', $user->current_weight, ['class' => 'form-control', 'id' => 'weight', 'placeholder' => 'Enter Weight(In Kg)', 'autocomplete' => 'off' ]) !!}
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label for="weight_goal"> Weight Goal(In Kg) <span class="text-danger">*</span></label>
                                        {!! Form::number('weight_goal', $user->weight_goal, ['class' => 'form-control', 'id' => 'weight_goal', 'placeholder' => 'Enter Weight Goal(In Kg)', 'autocomplete' => 'off', 'min' => '0', 'max' => '99' ]) !!}
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label for="height"> Enter Height(In cm) <span class="text-danger">*</span></label>
                                        {!! Form::number('height', $user->height, ['class' => 'form-control', 'id' => 'height', 'placeholder' => 'Enter Height(In cm)', 'autocomplete' => 'off' ]) !!}
                                    </div>
                                        
                                    <div class="col-md-6 mt-3">
                                        <label for="gender">Select Gender</label>
                                        {!! Form::select('gender', create_select_options(config('constants.users.gender'), 'caption', 'value', 'Select Gender'), $user->gender, ['class' => 'form-control select-picker', 'id' => 'gender' ]) !!}
                                    </div>

                                    <div class="col-md-6 mt-3" @if($user->user_type == 'Regular User') style="display: none;" @else style="display: none;" @endif id="show_days">
                                        <!-- <label for="days">Days</label>
                                        {!! Form::select('days', array_combine(range(1, 60), range(1, 60)), $user->days, [
                                            'class' => 'form-control select-picker',
                                            'id' => 'days',
                                            'title' => 'Select Days'
                                        ]) !!} -->
                                    </div>

                                    <input type="hidden" name="current_days" id="current_days" value="{{ $user->days }}">
                                </div>                                
                                {{ Form::button( '<i class="fa fa-save"></i> &nbsp;'. __('language.update'), ['class' => 'btn btn-primary btn-submit', 'type' => 'submit', 'title' => __('language.update')] )}}
                            {!! Form::close() !!}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    var maxImageSize = {{config('constants.max_image_size')}};
</script>
<script src="{{ asset('admin-assets/js/plugins/summernote/summernote-bs4.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/dropify/dropify.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/flatpickr.js') }}"></script>
<script src="{{ asset('admin-assets/js/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('admin-assets/js/components.js') }}"></script>
<script src="{{ asset('admin-assets/js/users/users.js') }}"></script>

<script type="text/javascript">
    function userType(type) {

        // var current_days = document.getElementById('current_days').value;

        // if(type.value == 'Regular User'){
        //     document.getElementById('days').value = current_days;
        //     document.getElementById('show_days').style.display = 'block';
        // } else if(type.value == 'Demo User'){
        //     document.getElementById('days').value = current_days;
        //     document.getElementById('show_days').style.display = 'none';
        // } else if(type.value == '3 Day Trail'){
        //     document.getElementById('days').value = current_days;
        //     document.getElementById('show_days').style.display = 'none';
        // } else {
        //     document.getElementById('days').value = current_days;
        //     document.getElementById('show_days').style.display = 'none';
        // }

        // const daysSelect = document.getElementById('days');

        // if ($(daysSelect).hasClass('select-picker')) {
        //     $(daysSelect).selectpicker('refresh');
        // }
    }

</script>

@endpush