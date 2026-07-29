@extends('admin-panel.layouts.main-layout')

@section('page-title', ' '.__('language.cms_page_title').' | '.__('language.page_main_title').'')

@push('styles')
<link href="{{ asset('admin-assets/css/forms/theme-checkbox-radio.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/datatables.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/dt-global_style.css') }}" rel="stylesheet">
<style>
    .dropdown-menu.show
    {
        transform:unset !important;
    }
</style>
@endpush

@section('content')
    @if(isset($breadcrumbFilter))
        <!-- Include breadcrumb -->
        @include('admin-panel.layouts.breadcrumb-filter')
        <!--/ Include breadcrumb -->
    @endif
    <div class="layout-px-spacing">

        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-12 layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    <div class="container-fluid mt2">
                        <div class="row">
                            <div class="col-xl-8 col-lg-8 col-md-8 col-8">
                                <h4> {{ __('language.cms_page_title') }} </h4>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive data-table-container mb-4 mt-2">
                        <div class="table-responsive _mb-4">
                            <table id="dataTable" class="table table-hover" data-url="{{ route('adminPanel.cms-pages.getCms') }}">
                                <thead>
                                    <tr>
                                        <!-- <th class="checkbox-column"> # </th> -->
                                        <th> {{ __('language.title') }} </th>
                                        <th width="80"> {{ __('language.table_action') }} </th>
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
<script src="{{ asset('admin-assets/js/cms/view.js') }}"></script>
@endpush
