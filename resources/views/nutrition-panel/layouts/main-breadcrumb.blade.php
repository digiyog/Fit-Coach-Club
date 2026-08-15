@if(!request()->is(Request::segment(1).'/dashboard*') && isset($breadcrumb) && count($breadcrumb) > 0)
<!--  BEGIN NAVBAR  -->
<div class="sub-header-container custom-breadcrumbs px-4 py-2">
    <div class="d-flex align-items-center justify-content-between w-100">
        <div class="page-header">
            <nav class="breadcrumb-one" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    @foreach($breadcrumb as $key => $value)
                        @if(!empty($value))
                            <li class="breadcrumb-item">
                                <a href="{{$value}}">{{$key}}</a>
                            </li>
                        @else
                            <li class="breadcrumb-item active" aria-current="page">
                                <span>{{$key}}</span>
                            </li>
                        @endif
                    @endforeach
                </ol>
            </nav>
        </div>
    </div>
</div>
<!--  END NAVBAR  -->
@endif
