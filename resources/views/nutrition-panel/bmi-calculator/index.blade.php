@extends('nutrition-panel.layouts.main-layout')

@section('page-title', 'Bmi Calculator | '.__('language.page_main_title').'')

@push('styles')
<link href="{{ asset('admin-assets/css/forms/theme-checkbox-radio.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/datatables.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/dt-global_style.css') }}" rel="stylesheet">
<style type="text/css">
    .card-box{
        border: 1px solid #bfc9d4;
        border-radius: 15px;
    }
    .card-box p{
        padding: 5px 10px 0px 10px;
        font-size: 20px;
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
        background: lightgray;
        border-bottom: 1px solid gray;
        margin-bottom: 0px;
    }
    .card-box div{
        padding: 10px;
        font-size: 18px;
    }
</style>
@endpush

@section('content')
    @if(isset($breadcrumbFilter))
        <!-- Include breadcrumb -->
        @include('nutrition-panel.layouts.breadcrumb-filter')
        <!--/ Include breadcrumb -->
    @endif
    <div class="layout-px-spacing">

        <div class="row layout-top-spacing custom-datatable-filters hide">
            <div class="col-xl-12 col-lg-12 col-md-12 col-12 _layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    <div class="container-fluid mt2">
                        <div class="custom-datatable-filter _hide">
                            {!! Form::open(['class' => 'custom-datatable-filter-form']) !!}
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                        </div>
                                    </div>
                                </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row layout-top-spacing align-item-stregth">
            <!-- Content -->
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <div class="widget-content widget-content-area br-6">
                    <div class="animated-underline-content">
                        <!-- Tab Content start -->
                        <div class="tab-content" id="animateLineContent-4">
                            <div class="container-fluid mt2">
                                <div class="row">
                                    <div class="col-xl-8 col-lg-8 col-md-8 col-8">
                                        <h4>Bmi Calculator </h4>
                                    </div>
                                </div>
                            </div>
                            <!-- Tab Content Profile -->
                            <div class="tab-pane fade show active pt-0" id="animated-underline-profile" role="tabpanel" aria-labelledby="animated-underline-profile-tab">
                                <div class="form p-3">
                                    {!! Form::open(['class' => 'calculate-bmi-form', 'method' => 'post', 'enctype' => 'multipart/form-data' ]) !!}

                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="user">Select User</label>
                                                {!! Form::select('user', create_select_options($users, 'name', 'id', 'Select User'), '', ['class' => 'form-control select-picker', 'id' => 'user' ]) !!}
                                            </div>

                                            <div class="col-md-6 pl-0">
                                                <label for="name"> Enter Name <span class="text-danger">*</span></label>
                                                {!! Form::text('name', '', ['class' => 'form-control', 'id' => 'name', 'placeholder' => 'Enter Name', 'autocomplete' => 'off' ]) !!}
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label for="mobile_number"> Enter Mobile Number <span class="text-danger">*</span></label>
                                                {!! Form::text('mobile_number', '', ['class' => 'form-control', 'id' => 'mobile_number', 'placeholder' => 'Enter Mobile Number', 'autocomplete' => 'off' ]) !!}
                                            </div>

                                            <div class="col-md-6 mt-3 pl-0">
                                                <label for="age"> Enter Age <span class="text-danger">*</span></label>
                                                {!! Form::number('age', '', ['class' => 'form-control', 'id' => 'age', 'placeholder' => 'Enter Age', 'autocomplete' => 'off', 'min' => '5', 'max' => '120' ]) !!}
                                            </div>

                                            <div class="col-md-6 mt-3">
                                                <label for="weight"> Enter Weight(In Kg) <span class="text-danger">*</span></label>
                                                {!! Form::number('weight', '', ['class' => 'form-control', 'id' => 'weight', 'placeholder' => 'Enter Weight(In Kg)', 'autocomplete' => 'off' ]) !!}
                                            </div>

                                            <div class="col-md-6 mt-3 pl-0">
                                                <label for="height"> Enter Height(In cm) <span class="text-danger">*</span></label>
                                                {!! Form::number('height', '', ['class' => 'form-control', 'id' => 'height', 'placeholder' => 'Enter Height(In cm)', 'autocomplete' => 'off' ]) !!}
                                            </div>
                                                
                                            <div class="col-md-12 mt-3">
                                                <label for="gender">Select Gender</label>
                                                {!! Form::select('gender', create_select_options(config('constants.users.gender'), 'caption', 'value', 'Select Gender'), '', ['class' => 'form-control select-picker', 'id' => 'gender' ]) !!}
                                            </div>
                                        </div>

                                        {{ Form::button( '<i class="fa fa-save"></i> &nbsp;'. __('language.language_save'), ['class' => 'btn btn-primary btn-submit', 'type' => 'submit', 'title' => __('language.language_save') ] )}}
                                    {!! Form::close() !!}
                                </div>
                            </div>
                            <!-- Tab Content Profile -->
                        </div>
                        <!-- Tab Content End -->
                    </div>
                </div>
            </div>


            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <div class="user-profile" style="height: 100%;">
                    <div class="widget-content widget-content-area" style="height: 100%;  display: flex; justify-content: center; align-items: center;">
                        <div class="text-center user-info mt-0 p-5" id="responseHide">
                            <p><strong>🔥 You’re one step closer to your fitness goal!</strong></p>
                            <p><strong>Let’s see what your body needs to stay on track 💪</strong></p>
                        </div>
                        <div class="text-center user-info mt-0 p-2 d-none" id="responseShow">
                            <p><h3>🌿 Your health insights are ready.</h3></p>

                            <div class="row mt-5">
                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                    <div class="form-group card-box">
                                        <p><strong for="name">BMI</strong></p>
                                        <div class="text-dark" id="view_bmi"></div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                    <div class="form-group card-box">
                                        <p><strong for="name">Body Fat</strong></p>
                                        <div class="text-dark" id="view_body_fat"></div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                    <div class="form-group card-box">
                                        <p><strong for="name">Visceral Fat</strong></p>
                                        <div class="text-dark" id="view_visceral_fat"></div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                    <div class="form-group card-box">
                                        <p><strong for="name">Muscle Mass</strong></p>
                                        <div class="text-dark" id="view_muscle_mass"></div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                    <div class="form-group card-box">
                                        <p><strong for="name">Metabolic Rate</strong></p>
                                        <div class="text-dark" id="view_metabolic_rate"></div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                    <div class="form-group card-box">
                                        <p><strong for="name">Biologic Age</strong></p>
                                        <div class="text-dark" id="view_biologic_age"></div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                    <div class="form-group card-box">
                                        <p><strong for="name">Body Age</strong></p>
                                        <div class="text-dark" id="view_body_age"></div>
                                    </div>
                                </div>                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Content -->
        </div>

        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-12 layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    <div class="container-fluid mt2">
                        <div class="row">
                            <div class="col-xl-8 col-lg-8 col-md-8 col-8">
                                <h4>Bmi Calculator </h4>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive data-table-container mb-4 mt-2">
                        <div class="table-responsive _mb-4">
                            <table id="dataTable" class="table table-hover" data-url="{{ route('nutritionPanel.bmi-calculator.getBmiCalculator') }}" data-save-url="{{ route('nutritionPanel.bmi-calculator.store') }}" data-destroy-url="{{ route('nutritionPanel.bmi-calculator.destroy') }}">
                                <thead>
                                    <tr>
                                        <th class="checkbox-column"> # </th>
                                        <th>Name</th>
                                        <th>Mobile Number</th>
                                        <th>Age</th>
                                        <th>Weight</th>
                                        <th>Height</th>
                                        <th>Gender</th>
                                        <th>BMI</th>
                                        <th>Body Fat</th>
                                        <th>Visceral Fat</th>
                                        <th>Muscle Mass</th>
                                        <th>Metabolic Rate</th>
                                        <th>Biologic Age</th>
                                        <th>Body Age</th>
                                        <!-- <th class="text-right"> {{ __('language.table_action') }} </th> -->
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('admin-assets/js/plugins/table/datatable/datatables.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/table/datatable/button-ext/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/table/datatable/button-ext/jszip.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/table/datatable/button-ext/buttons.html5.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/components.js') }}"></script>
<script src="{{ asset('admin-assets/js/bmi-calculator/view.js') }}"></script>


<script>
    const usersData = @json($users);
</script>

<script type="text/javascript">
    document.getElementById('user').addEventListener('change', function() {
        const userId = this.value;
        const user = usersData.find(u => u.id == userId);

        if (user) {
            document.getElementById('name').value = user.name || '';
            document.getElementById('mobile_number').value = user.mobile_number || '';
            document.getElementById('age').value = user.age || '';
            document.getElementById('weight').value = user.current_weight || '';
            document.getElementById('height').value = user.height || '';
            document.getElementById('gender').value = user.gender || '';
        } else {
            document.getElementById('name').value = '';
            document.getElementById('mobile_number').value = '';
            document.getElementById('age').value = '';
            document.getElementById('weight').value = '';
            document.getElementById('height').value = '';
            document.getElementById('gender').value = '';
        }
    });
</script>
@endpush
