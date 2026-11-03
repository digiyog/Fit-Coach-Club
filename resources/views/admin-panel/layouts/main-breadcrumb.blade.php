<!--  BEGIN NAVBAR  -->
{{-- Legacy sub-header breadcrumb is suppressed because all admin pages now display unified breadcrumbs in the top main-header --}}
@if(isset($breadcrumb) && !request()->routeIs('adminPanel.*') && !request()->is('admin-panel*'))
<div class="sub-header-container custom-breadcrumbs">
    <header class="header navbar navbar-expand-sm">

        <ul class="navbar-nav flex-row">
            <li>
                <div class="page-header">
                    <nav class="breadcrumb-one" aria-label="breadcrumb">
                        @if(isset($breadcrumb))
                            <ol class="breadcrumb">
                                @foreach($breadcrumb as $key => $value)
                                    @if(!empty($value))
                                        <li class="breadcrumb-item active" aria-current="page">
                                            <a href="{{$value}}">{{$key}}</a>
                                        </li>
                                    @else
                                        <li class="breadcrumb-item">
                                            <span>{{$key}}</span>
                                        </li>
                                    @endif
                                @endforeach
                            </ol>
                        @endif
                    </nav>
                </div>
            </li>
        </ul>
    </header>
</div>
@endif
<!--  END NAVBAR  -->