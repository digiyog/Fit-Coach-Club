<!--  BEGIN NAVBAR  -->
<div class="sub-header-container layout-px-spacing custom-breadcrumbs">
    <div class="d-flex align-items-center justify-content-between w-100 flex-wrap gap-2">
        <div class="page-header">
            <nav class="breadcrumb-one" aria-label="breadcrumb">
                @if(isset($breadcrumbFilter))
                    <ol class="breadcrumb mb-0">
                        @foreach($breadcrumbFilter as $key => $value)
                            @if(!empty($key))
                                @if(!empty($value))
                                    <li class="breadcrumb-item">
                                        <a href="{{$value}}">{{$key}}</a>
                                    </li>
                                @else
                                    @if(!request()->is(Request::segment(1).'/dashboard*') && request()->route()->getName() != 'adminPanel.dashboard')
                                    <li class="breadcrumb-item active" aria-current="page">
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

        <div class="breadcrumb-action-buttons d-flex align-items-center gap-2">
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
                    <a href="{{ $value['btn_link'] }}" class="{{ $value['btn_class'] }}" title="{{ $value['btn_text'] ?? '' }}" {{$attributes}}>
                        <i data-feather="{{$value['btn_icon']}}"></i>
                    </a>
                @endforeach
                @if(isset($breadcrumbButton['btn_filter']) && $breadcrumbButton['btn_filter'] == 'Y')
                    <button type="button" class="btn btn-dark filter-button" title="Filter">
                        <i data-feather="filter" class="feather-16"></i>
                    </button>
                @endif
            @endif
        </div>
    </div>
</div>
<!--  END NAVBAR  -->
