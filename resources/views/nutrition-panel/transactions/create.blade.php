@extends('nutrition-panel.layouts.main-layout')

@section('page-title', ' Create User | '.__('language.page_main_title').'')

@push('styles')
<link href="{{ asset('admin-assets/css/flatpickr.min.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/dropify/dropify.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/js/plugins/summernote/summernote-bs4.min.css') }}" rel="stylesheet">

<style>
    .dropify-wrapper
    {
        width:100% !important;
        margin-bottom: unset !important;
        height: 215px;
    }
    .textarea-height{
        size: none;
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
                                <h4> Create User </h4>
                            </div>
                        </div>

                        <div class="form pb-2">
                            {!! Form::open(['class' => 'user-form', 'method' => 'post', 'url' => route('nutritionPanel.users.store'), 'enctype' => 'multipart/form-data', 'autocomplete' => 'off' ]) !!}

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="custom-dropify">
                                            <label class="form-control-label" for="image">@lang('language.image')</label>
                                            {!! Form::file('image', ['class' => 'image-preview', 'id' => 'image', 'autocomplete' => 'off', 'data-show-remove' => 'false', 'accept' => 'image/*', "data-default-file" => '', ]) !!}

                                            {!! Form::hidden('image_name', '' ,['class' => 'form-control','id' => 'image_name']) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="name"> Name </label>
                                                {!! Form::text('name', '', ['class' => 'form-control', 'id' => 'name', 'placeholder' => 'Name', ]) !!}
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label for="email">Email </label>
                                                {!! Form::email('email', '', ['class' => 'form-control', 'id' => 'email', 'placeholder' => 'Email', 'autocomplete' => 'off', 'data-url' => route('nutritionPanel.users.checkEmail') ]) !!}
                                            </div>

                                            <div class="col-md-6 mt-3">
                                                <label for="mobile_number">Mobile Number </label>
                                                {!! Form::text('mobile_number', '', ['class' => 'form-control numeric', 'id' => 'mobile_number', 'placeholder' => 'Mobile Number', 'data-url' => route('nutritionPanel.users.checkMobile') ]) !!}
                                            </div>

                                            <div class="col-md-6 mt-3">
                                                <label for="coach_name">Coach Name</label>
                                                {!! Form::text('coach_name', '', ['class' => 'form-control', 'id' => 'coach_name', 'placeholder' => 'Coach Name', 'autocomplete' => 'off']) !!}
                                            </div>

                                            <div class="col-md-6 mt-3">
                                                <label for="meal_type_id">Meal Type</label>
                                                {!! Form::select('meal_type_id', create_select_options($mealTypes, 'name', 'id', 'Select Meal Type'), '', ['class' => 'form-control select-picker', 'id' => 'meal_type_id' ]) !!}
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
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6 mt-3">
                                        <label for="user_type">User Type</label>
                                        {!! Form::select('user_type', create_select_options(config('constants.user_type'), 'display', 'value', 'Select User Type'), '', ['class' => 'form-control select-picker', 'id' => 'user_type', 'onchange' => "userType(this)" ]) !!}
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label for="user_state">User State</label>
                                        {!! Form::select('user_state', create_select_options(config('constants.user_state'), 'display', 'value', 'Select User State'), '', ['class' => 'form-control select-picker', 'id' => 'user_state' ]) !!}
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label for="age"> Enter Age <span class="text-danger">*</span></label>
                                        {!! Form::number('age', '', ['class' => 'form-control', 'id' => 'age', 'placeholder' => 'Enter Age', 'autocomplete' => 'off', 'min' => '5', 'max' => '120' ]) !!}
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label for="weight"> Enter Weight(In Kg) <span class="text-danger">*</span></label>
                                        {!! Form::number('weight', '', ['class' => 'form-control', 'id' => 'weight', 'placeholder' => 'Enter Weight(In Kg)', 'autocomplete' => 'off' ]) !!}
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label for="height"> Enter Height(In cm) <span class="text-danger">*</span></label>
                                        {!! Form::number('height', '', ['class' => 'form-control', 'id' => 'height', 'placeholder' => 'Enter Height(In cm)', 'autocomplete' => 'off' ]) !!}
                                    </div>
                                        
                                    <div class="col-md-6 mt-3">
                                        <label for="gender">Select Gender</label>
                                        {!! Form::select('gender', create_select_options(config('constants.users.gender'), 'caption', 'value', 'Select Gender'), '', ['class' => 'form-control select-picker', 'id' => 'gender' ]) !!}
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label for="discount"> Enter Discount(%) <span class="text-danger">*</span></label>
                                        {!! Form::number('discount', '', ['class' => 'form-control', 'id' => 'discount', 'placeholder' => 'Enter Discount(%)', 'autocomplete' => 'off', 'min' => '0', 'max' => '99' ]) !!}
                                    </div>

                                    <div class="col-md-6 mt-3" style="display: none;" id="show_days">
                                        <label for="days">Days</label>
                                        {!! Form::select('days', array_combine(range(1, 60), range(1, 60)), null, [
                                            'class' => 'form-control select-picker',
                                            'id' => 'days',
                                            'title' => 'Select Days'
                                        ]) !!}
                                    </div>

                                </div>

                                {{ Form::button( '<i class="fa fa-save"></i> &nbsp;'. __('language.save'), ['class' => 'btn btn-primary btn-submit', 'type' => 'submit', 'title' => __('language.save')] )}}
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
        // if(type.value == 'Regular User'){
        //     document.getElementById('days').value = '';
        //     document.getElementById('show_days').style.display = 'block';
        // } else if(type.value == 'Demo User'){
        //     document.getElementById('days').value = 1;
        //     document.getElementById('show_days').style.display = 'none';
        // } else if(type.value == '3 Day Trail'){
        //     document.getElementById('days').value = 3;
        //     document.getElementById('show_days').style.display = 'none';
        // } else {
        //     document.getElementById('days').value = '';
        //     document.getElementById('show_days').style.display = 'none';
        // }

        // const daysSelect = document.getElementById('days');

        // if ($(daysSelect).hasClass('select-picker')) {
        //     $(daysSelect).selectpicker('refresh');
        // }
    }

</script>

@endpush
