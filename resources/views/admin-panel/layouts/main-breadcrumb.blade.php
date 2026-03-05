<!--  BEGIN NAVBAR  -->
<div class="sub-header-container layout-px-spacing custom-breadcrumbs">
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
                                        @if(!request()->is(Request::segment(1).'/dashboard*') && request()->route()->getName() != 'adminPanel.dashboard')
                                        <li class="breadcrumb-item">
                                            <span>{{$key}}</span>
                                        </li>
                                        @endif
                                    @endif
                                @endforeach
                            </ol>
                        @endif
                        <!-- @if (request()->is(Request::segment(1).'/dashboard*'))
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active" aria-current="page">
                                    <span>{{ __('language.dashboard') }}</span></li>
                            </ol>
                        @endif -->
                    </nav>
                </div>
            </li>
        </ul>
    </header>
</div>
<!--  END NAVBAR  -->
