@extends('admin-panel.layouts.main-layout')

@section('page-title', ' Create Franchise | '.__('language.page_main_title').'')

@push('styles')
<link href="{{ asset('admin-assets/css/plugins/dropify/dropify.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/js/plugins/summernote/summernote-bs4.min.css') }}" rel="stylesheet">
<style>
    .dropify-wrapper
    {
        width:100% !important;
        margin-bottom: unset !important;
        height: 215px !important;
    }
</style>
@endpush

@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-12 layout-spacing">

                <!-- Validation error -->
                @component('admin-panel.validation.errors') @endcomponent
                <!-- / Validation error -->

                <div class="widget-content widget-content-area br-6">
                    <div class="container-fluid mt2">
                        <div class="row">
                            <div class="col-xl-8 col-lg-8 col-md-8 col-8">
                                <h4> Create Franchise </h4>
                            </div>
                        </div>

                        <div class="form pb-2">
                            {!! Form::open(['class' => 'franchise-form', 'method' => 'post', 'url' => route('adminPanel.franchises.store'), 'enctype' => 'multipart/form-data', 'autocomplete' => 'off' ]) !!}
                                <div class="row mb-4">
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
                                                {!! Form::text('name', $user->name, ['class' => 'form-control', 'id' => 'name', 'placeholder' => 'Name', ]) !!}
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label for="email">Email </label>
                                                {!! Form::email('email', '', ['class' => 'form-control', 'id' => 'email', 'placeholder' => 'Email', 'autocomplete' => 'off', 'data-url' => route('adminPanel.franchises.checkEmail', ['id' => $user->id]) ]) !!}
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label for="mobile_number">Mobile Number </label>
                                                {!! Form::text('mobile_number', $user->mobile_number, ['class' => 'form-control numeric', 'id' => 'mobile_number', 'placeholder' => 'Mobile Number', 'data-url' => route('adminPanel.franchises.checkMobile', ['id' => $user->id]) ]) !!}
                                            </div>

                                            <div class="col-md-12 mt-3">
                                                <div class="form-group">
                                                    <label>Select Product</label>
                                                    <select name="products[]" id="products" class="form-control filter-field select-picker" multiple>
                                                        @foreach($products as $key => $value)
                                                            <option value="{{$value['id']}}">{{ $value['name'] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
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
<script src="{{ asset('admin-assets/js/components.js') }}"></script>
<script src="{{ asset('admin-assets/js/franchises/franchises.js') }}"></script>
@endpush
