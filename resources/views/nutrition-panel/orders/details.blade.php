@extends('nutrition-panel.layouts.main-layout')

    @section('page-title', 'View Order Details | '.__('language.page_main_title').'')

@section('content')

    @if(isset($breadcrumbFilter))
    <!-- Include breadcrumb -->
    @include('nutrition-panel.layouts.breadcrumb-filter')
    <!--/ Include breadcrumb -->
    @endif
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-12 layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    <div class="container-fluid mt2">
                        <div class="row">
                            <div class="col-xl-8 col-lg-8 col-md-8 col-8 page-heading">
                                <div class="media">
                                    <div class="media-body profile-info ms-1">
                                        <h6 class="mb-2"> Order Number - {{$orderDetails->order_number}} </h6>

                                        <h6 class="mb-2"> Order Date - {{ Carbon\Carbon::parse($orderDetails->created_at)->format('d M, Y h:i A') }}</h6>
                                        @php
                                            if ($orderDetails->order_status == 1)  {
                                                $order_status = '<label class="badge badge-dark">Order Placed</label>';
                                            } else if ($orderDetails->order_status == 2) {
                                                $order_status = '<label class="badge badge-info">Ready to Ship</label>';
                                            } else if ($orderDetails->order_status == 3) {
                                                $order_status = '<label class="badge badge-warning">Return</label>';
                                            } else if ($orderDetails->order_status == 4) {
                                                $order_status = '<label class="badge badge-dark">Shipped</label>';
                                            } else if ($orderDetails->order_status == 5) {
                                                $order_status = '<label class="badge badge-primary">In Transit</label>';
                                            } else if ($orderDetails->order_status == 6) {
                                                $order_status = '<label class="badge badge-success">Delivered</label>';
                                            } else if ($orderDetails->order_status == 7) {
                                                $order_status = '<label class="badge badge-danger">Cancelled</label>';
                                            } else if ($orderDetails->order_status == 8) {
                                                $order_status = '<label class="badge badge-success">Refund</label>';
                                            }
                                        @endphp

                                        {!! $order_status !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-12 page-heading">
                                <hr class="mt-0" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-12 page-heading">
                                <div class="row">
                                    <div class="col-md-4 mb-2">
                                        <label>
                                            <b>User Name</b>
                                        </label>
                                        <p class="text-dark">
                                            {{ $orderDetails->user_name ?? 'N/A' }}
                                        </p>
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label>
                                            <b>Mobile Number</b>
                                        </label>
                                        <p class="text-dark">
                                            {{ $orderDetails->mobile_number ?? 'N/A' }}
                                        </p>
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label>
                                            <b>Total Quantity</b>
                                        </label>
                                        <p class="text-dark">
                                            {{ $orderDetails->product_quantity ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-2">
                                        <label>
                                            <b>Total Amount</b>
                                        </label>
                                        <p class="text-dark">
                                            {{ $orderDetails->total_amount }}
                                        </p>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label>
                                            <b>Discount</b>
                                        </label>
                                        <p class="text-dark">
                                            {{ $orderDetails->discount }}
                                        </p>
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label>
                                            <b>Net Amount</b>
                                        </label>
                                        <p class="text-dark">
                                            {{ $orderDetails->net_amount ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-2">
                                        <label>
                                            <b>Payment Status</b>
                                        </label>
                                        <p class="text-dark">
                                            @if($orderDetails->payment_status == 1)
                                                <label class="badge badge-primary">Pending</label>
                                            @elseif($orderDetails->payment_status == 2)
                                                <label class="badge badge-success">Success</label>
                                            @else
                                                <label class="badge badge-danger">Failed</label>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-12 col-lg-12 col-md-12 col-12 page-heading">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Product</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Net Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($orderDetails['orderDetails'] as $key => $item)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->quantity }}</td>
                                                <td>₹ {{ number_format($item->price, 2) }}</td>
                                                <td>₹ {{ number_format($item->net_amount, 2) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">No items found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('admin-assets/js/components.js') }}"></script>
@endpush