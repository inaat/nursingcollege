@extends('frontend.layouts.school.master')
@section('title')
Event Details
@endsection
@section('content')
<div class="breadcrumb">
    <div class="container">
        <div class="contentWrapper">
            <span class="title"> Event Details</span>
            <span>
                <a href="{{ url('/') }}" class="home">Home</a>
                <span><i class="fa-solid fa-caret-right"></i></span>
                <span class="page">Event Details</span>
            </span>
        </div>
    </div>
</div>

<section class="eventDetails commonMT commonWaveSect">
    <div class="container card p-4 shadow-lg rounded">
        <div class="row">
            <div class="col-lg-8">
                <div class="event-main">
                    <img src="{{ url('uploads/front_image/'.$event->images) }}" alt="{{ $event->title }}" class="img-fluid rounded mb-4" style="object-fit: cover; width: 100%; height: 400px;">
                    <h2 class="event-title mb-3">{{ $event->title }}</h2>
                    <div class="event-description">
                        {!! $event->description !!}
                    </div>
                </div>
                <div class="event-details card p-3 shadow-sm mt-4">
                    <h4 class="mb-3">Event Details</h4>
                    <ul class="list-unstyled">
                        <li><strong>Start:</strong> {{ $event->from ?? 'To Be Announced' }}</li>
                        <li><strong>End:</strong> {{ $event->to ?? 'To Be Announced' }}</li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="event-sidebar card p-3 shadow-sm">
                    <h4 class="mb-3">Upcoming Events</h4>
                    <ul class="list-unstyled">
                        @foreach($upcomingEvents as $upcoming)
                        <li class="d-flex mb-3">
                            <img src="{{ url('uploads/front_image/'.$upcoming->images) }}" alt="{{ $upcoming->title }}" class="img-thumbnail me-3" style="width: 60px; height: 60px; object-fit: cover;">
                            <div>
                                <a href="{{ action('Frontend\FrontHomeController@event_show', [$upcoming->slug, $upcoming->id]) }}" class="text-decoration-none">{{ $upcoming->title }}</a>
                                <p class="small text-muted mb-0">{{ $upcoming->from ?? 'Date TBA' }}</p>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('/assets/js/custom/common.js') }}"></script>
<script src="{{ asset('/assets/js/custom/custom.js') }}"></script>
<script src="{{ asset('/assets/js/custom/function.js') }}"></script>
<script src="{{ asset('/assets/js/sweetalert2.all.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
// Custom scripts if needed
</script>
@endsection
