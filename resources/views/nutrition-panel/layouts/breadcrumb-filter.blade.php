<!--  BEGIN NAVBAR  -->
<div class="sub-header-container layout-px-spacing custom-breadcrumbs">
    <header class="header navbar navbar-expand-sm">

        <ul class="navbar-nav flex-row">
            <li>
                <div class="page-header">
                    <nav class="breadcrumb-one" aria-label="breadcrumb">
                        @if(isset($breadcrumbFilter))
                            <ol class="breadcrumb">
                                @foreach($breadcrumbFilter as $key => $value)
                                    @if(!empty($key))
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
                                    @endif
                                @endforeach
                            </ol>
                        @endif
                    </nav>
                </div>
            </li>
        </ul>
    </header>
    <div class="_pull-right text-end" style="width: 100%;">
        @if(isset($breadcrumbButton))
            @foreach($breadcrumbButton as $key => $value)
                @php
                $attributes = '';
                if(isset($value['attributes']))
                {
                    foreach($value['attributes'] as $akey => $avalue)
                    {
                        $attributes .= "$akey=".$avalue." ";
                    }
                }
                @endphp
                <a href="{{ $value['btn_link'] }}" class="{{ $value['btn_class'] }}" style="display: inline-block" title="{{ $value['btn_text'] }}" {{$attributes}}>
                    <i data-feather="{{$value['btn_icon']}}"></i> &nbsp;
                </a>
            @endforeach
            @if(isset($breadcrumbButton['btn_filter']) && $breadcrumbButton['btn_filter'] == 'Y')
                <button type="button" class="btn btn-dark _mb-2 _mr-2 mt-2 rounded-circle filter-button" title="Filter">
                    <i data-feather="filter" class="feather-16"></i>
                </button>
            @endif
        @endif
    </div>
</div>
<!--  END NAVBAR  -->
