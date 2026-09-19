@if(!empty($lastAuctionPlayer))
    <div class="user-profile layout-spacing">
        <div class="widget-content widget-content-area position-relative">

            @if($lastAuctionPlayer['status'] == 1)
                <div class="position-absolute" style="top: 55px; right: 10px; transform: rotate(-35deg);">
                    <img src="{{ asset('admin-assets/images/goldensold.jpg') }}" width="150px">
                </div>
            @elseif($lastAuctionPlayer['status'] == 2)
                <div class="position-absolute" style="top: 55px; right: 10px; transform: rotate(-35deg);">
                    <img src="{{ asset('admin-assets/images/redunsold.jpg') }}" width="150px">
                </div>
            @else
            @endif

            @php
                $imagePath = asset('admin-assets/images/player.png');
                if(!empty($lastAuctionPlayer['player']->image) && \Storage::disk(config('filesystems.default'))->exists(config('constants.players.image_path').$lastAuctionPlayer['player']->image))
                {
                    $imagePath = (get_image_url(config('constants.players.image_path'), $lastAuctionPlayer['player']->image) ?? '');
                }
            @endphp

            <div class="text-center user-info mt-3">
                <img src="{{ $imagePath }}" width="100px" alt="avatar">
                <p class="">{{ ucwords($lastAuctionPlayer['player']->name) }}</p>
            </div>

            @if($lastAuctionPlayer['status'] == 1)
                <div class="text-center mt-0">
                    <p><b>Team : </b>{{ $lastAuctionPlayer['team']['name'] }}  - {{ $lastAuctionPlayer['points'] }} Points</p>
                </div>
            @endif

            <div class="user-info-list">
                <ul class="contacts-block list-unstyled m-0 text-center" style="max-width:100%">
                    @if($lastAuctionPlayer['player']->age)
                        <li class="contacts-block__item">
                            <b>Age : </b> {{ $lastAuctionPlayer['player']->age }}
                        </li>
                    @endif

                    @if($lastAuctionPlayer['player']->category_id)
                        <li class="contacts-block__item">
                            <b>Category : </b> {{ ucwords($lastAuctionPlayer['player']['categories']->name) }}
                        </li>
                    @endif

                    @if($lastAuctionPlayer['player']->tag)
                        <li class="contacts-block__item">
                            <b>Tag : </b> 
                            @if($lastAuctionPlayer['player']->tag == 1)
                                Owner
                            @elseif($lastAuctionPlayer['player']->tag == 2)
                                Co-Owner
                            @elseif($lastAuctionPlayer['player']->tag == 3)
                                Captain
                            @elseif($lastAuctionPlayer['player']->tag == 4)
                                Vice Captain
                            @elseif($lastAuctionPlayer['player']->tag == 5)
                                Icon
                            @elseif($lastAuctionPlayer['player']->tag == 6)
                                Retain
                            @endif
                        </li>
                    @endif

                    @if($lastAuctionPlayer['player']->playing_style)
                        <li class="contacts-block__item">
                            <b>Playing Style : </b> {{ $lastAuctionPlayer['player']->playing_style }}
                        </li>
                    @endif

                    @if($lastAuctionPlayer['player']->specification_1)
                        <li class="contacts-block__item">
                            <b>Specification 1 : </b> {{ $lastAuctionPlayer['player']->specification_1 }}
                        </li>
                    @endif

                    @if($lastAuctionPlayer['player']->specification_2)
                        <li class="contacts-block__item">
                            <b>Specification 2 : </b> {{ $lastAuctionPlayer['player']->specification_2 }}
                        </li>
                    @endif

                    @if($lastAuctionPlayer['player']->specification_3)
                        <li class="contacts-block__item">
                            <b>Specification 3 : </b> {{ $lastAuctionPlayer['player']->specification_3 }}
                        </li>
                    @endif
                    
                </ul>                             
            </div>
        </div>
    </div>
@endif