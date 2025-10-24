
<section class="programs commonMT">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="flex_column_center">
                    <span class="commonTag"> EVENTS </span>
                    <span class="commonTitle">
                      Events

                    </span>

                    {{-- <span class="commonDesc">
                    dece
                    </span> --}}
                </div>
            </div>

            <div class="col-12 programsCardWrapper">
                <div class="commonSlider">

                    <div class="slider-content owl-carousel">

                      @foreach ($events as $event)
                        <div class="swiperDataWrapper">
                            <div class="card">
                                <div class="imgDiv">
                                    <a href="{{ action('Frontend\FrontHomeController@event_show', [$event->slug,$event->id]) }}"> <img src="{{ url('uploads/front_image/'.$event->images) }}" class="card-img-top" alt="..." />
                                    </a>
                                </div>
                                <div class="cardDetails">
                                <a href="{{ action('Frontend\FrontHomeController@event_show', [$event->slug,$event->id]) }}">
                                    <span class="cardTitle">{{ $event->title }}</span>
                                    <span class="cardDesc">{!! $event->description !!}</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        <!-- Add more swiperDataWrapper elements here -->
                    </div>
                    <!-- Navigation buttons -->
                    <div class="navigationBtns">
                        <button class="prev commonBtn">
                            <i class="fa-solid fa-arrow-left"></i>
                        </button>
                        <button class="next commonBtn">
                            <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
                <div class="sideImgs">
                    <img src="{{ asset('assets/school/images/color.png') }}" class="colorImg" alt="colorImg" />
                    <img src="{{ asset('assets/school/images/bag.png') }}" class="bagImg" alt="bagImg" />
                </div>
            </div>
        </div>
    </div>
</section>