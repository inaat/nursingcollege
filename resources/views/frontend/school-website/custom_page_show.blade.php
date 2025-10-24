@extends('frontend.layouts.school.master')
@section('title')
{{ $data->title }}
@endsection
<style>
.hover-scale:hover {
    transform: scale(1.05);
    transition: transform 0.3s ease-in-out;
}
.overlay {
    background: rgba(0, 0, 0, 0.5);
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
}
.image-photo:hover .overlay {
    opacity: 1;
}
.heading {
  color: #22577A;
  font-size: 28px;
  font-weight: 700;
}
</style>
@section('content')
<div class="breadcrumb py-3">
    <div class="container">
        <div class="contentWrapper d-flex justify-content-between align-items-center">
            <span class="title h4 mb-0 ">{{ $data->title }}</span>
            <span class="breadcrumb-links text-muted">
                <a href="{{ url('/') }}" class="home text-decoration-none text-muted">Home</a>
                <span><i class="fa-solid fa-caret-right"></i></span>
                <span class="page">{{ $data->title }}</span>
            </span>
        </div>
    </div>
</div>

<section class="contactUs commonMT commonWaveSect">
        <div class="container card shadow-lg rounded p-4"">
            <div class="row">

                <div class="col-lg-12">

                    <div class="headlines text-center">
                        <span></span>
                        <span>{{ $data->title }}</span>
                    </div>

                 
                </div>
           
              
            </div>
              <div class="row">
      <div class="col-12">

          <div class="card  card shadow-sm rounded p-4">

             
              <div class="card-body">
                  <p style="text-align: justify;" class="text-muted">{!! $data->description !!}</p>

                  @php
                  $elements = json_decode($data->elements);
                  $photos = [];
                  $videos = [];
                  $files = [];

                  // Categorize elements into photos, videos, and files
                  if (!empty($elements)) {
                  foreach ($elements as $pic) {
                  if ($pic->type == 1) {
                  $photos[] = $pic;
                  } elseif ($pic->type == 2) {
                  $videos[] = $pic;
                  } elseif ($pic->type == 3) {
                  $files[] = $pic;
                  }
                  }
                  }
                  @endphp

                  <!-- Photos Section -->
                  @if(count($photos) > 0)
                  <hr>
                  <div class="gallery-section mt-5">
                      <h4 class="mb-4 heading text-center">Photos</h4>
                      <div class="row g-4">
                          @foreach($photos as $pic)
                          <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                              <div class="image-photo card shadow-sm border-0 rounded-3 hover-scale position-relative">
                                  <img src="{{ url('uploads/front_image/' . $pic->image) }}" class="card-img-top img-fluid rounded-3" alt="Photo">
                                  <div class="overlay d-flex justify-content-center align-items-center position-absolute top-0 start-0 end-0 bottom-0 rounded-3">
                                      <a href="{{ url('uploads/front_image/' . $pic->image) }}" class="btn btn-light btn-lg">View Image</a>
                                  </div>
                              </div>
                          </div>
                          @endforeach
                      </div>
                  </div>
                  @endif

                  <!-- Videos Section -->
                  @if(count($videos) > 0)
                  <hr>
                  <div class="gallery-section mt-5">
                      <h4 class="mb-4 heading text-center">Videos</h4>
                      <div class="row g-4">
                          @foreach($videos as $pic)
                          <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                              <div class="card image-photo shadow-sm border-0 rounded-3 hover-scale position-relative">
                                  <img src="{{ url('uploads/front_image/' . $pic->image) }}" class="card-img-top img-fluid rounded-3" alt="Video">
                                  <div class="overlay d-flex justify-content-center align-items-center position-absolute top-0 start-0 end-0 bottom-0 rounded-3">
                                      <a href="{{ $pic->video_url }}" class="btn btn-danger btn-lg" target="_blank">Watch Video</a>
                                  </div>
                              </div>
                          </div>
                          @endforeach
                      </div>
                  </div>
                  @endif

                  <!-- Files Section -->
                  @if(count($files) > 0)
                  <hr>
                  <div class="gallery-section mt-5">
                      <h4 class="mb-4 heading text-center">Files</h4>
                      <div class="row g-4">
                          @foreach($files as $pic)
                          <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                              <div class="card image-photo shadow-sm border-0 rounded-3 hover-scale position-relative">
                                  <div class="card-body text-center">
                                      <a href="{{ url('uploads/front_image/' . $pic->image) }}" target="_blank" class="btn btn-success btn-lg w-100 mt-2">
                                          <i class="fa fa-file-pdf me-2"></i>{{ $pic->image }} <br>View PDF 
                                      </a>
                                  </div>
                              </div>
                          </div>
                          @endforeach
                      </div>
                  </div>
                  @endif

              </div>
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
<script src="{{ asset('/assets/js/jquery.validate.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
// Custom scripts if needed
</script>
@endsection
