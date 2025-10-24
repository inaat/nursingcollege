@extends('frontend.layouts.school.master')
@section('title')
Home
@endsection
@section('content')
{{-- Hero section --}}
<section class="heroSection">
    <div class="owl-carousel owl-theme hero-carousel">
        @if (is_object($sliders))
        @foreach ($sliders as $slider)
        <div class="item">
            <img src="{{ $slider->image }}" alt="" class="swiperImage" />
        </div>
        @endforeach
        @else
        @foreach ($sliders as $slider)
        <div class="item">
            <img src="{{ $slider }}" alt="" class="swiperImage" />
        </div>
        @endforeach
        @endif
    </div>
</section>
<!-- heroSection ends here  -->

{{-- About us --}}
@include('frontend.school-website.about_us_section')

{{-- Education program --}}
@if (isset($schoolSettings['education_program_status']) && $schoolSettings['education_program_status'] == 1 && count($class_groups))
<section class="programs commonMT">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="flex_column_center">
                    <span class="commonTag"> {{ $schoolSettings['education_program_title'] ?? 'Educational Programs' }} </span>
                    <span class="commonTitle">
                        {{ $schoolSettings['education_program_heading'] ?? 'Educational Programs for every Stage' }}

                    </span>

                    <span class="commonDesc">
                        {{ $schoolSettings['education_program_description'] ?? '' }}
                    </span>
                </div>
            </div>

            <div class="col-12 programsCardWrapper">
                <div class="commonSlider">

                    <div class="slider-content owl-carousel">

                        @foreach ($class_groups as $group)
                        <div class="swiperDataWrapper">
                            <div class="card">
                                <div class="imgDiv">
                                    <img src="{{ $group->image ?? asset('assets/school/images/programImg1.png') }}" class="card-img-top" alt="..." />
                                </div>
                                <div class="cardDetails">
                                    <span class="cardTitle">{{ $group->title }}</span>
                                    <span class="cardDesc">{{ $group->description }}</span>
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
<!-- programs ends here  -->
@endif

{{-- Admission Section --}}
@if (isset($schoolSettings['online_registration_status']) && $schoolSettings['online_registration_status'] == 1)
<section class="admissionOpenSect commonMT">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="flex_column_center">
                    <span class="adminssionTag"> {{ $schoolSettings['online_registration_title'] ?? 'Where Learning Takes Flight' }} </span>
                    <span class="commonTitle">{{ $schoolSettings['online_registration_heading'] ?? 'Cultivate Your Curiosity & Explore Endless Possibilities. Admissions Open Now!' }}
                    </span>

                    <span class="commonDesc">{{ $schoolSettings['online_registration_description'] ?? 'Our admissions are now open for the 2024-25 school year. We invite curious and enthusiastic students to
                            join our vibrant learning community.' }}

                    </span>
                    {{-- <button class="commonBtn"><a href="{{ route('online-admission.index') }}">Apply Now</a></button> --}}
                </div>
            </div>
            <div class="col-lg-6 imgDiv">
                <img src="{{ $schoolSettings['online_registration_image'] ?? asset('assets/school/images/admissionSectImg.png') }}" alt="">
            </div>
        </div>
    </div>
</section>
@endif
{{-- End Admission Section --}}

{{-- Annoucenment --}}
@if (isset($schoolSettings['announcement_status']) && $schoolSettings['announcement_status'] == 1)
<section class="events annaouncementSection commonMT commonWaveSect">
    <div class="container">
        <div class="row mainRow">

            <div class="col-md-12 col-lg-6 imgDiv">
                <img src="{{ $schoolSettings['announcement_image'] ?? asset('assets/school/images/announcementImg.png') }}" alt="announcementImg">
            </div>

            <div class="col-12 col-md-12 col-lg-6 ">
                <div class="upperDiv">

                    <div class="flex_column_center">
                        <span class="commonTag"> {{ $schoolSettings['announcement_section'] ?? 'Announcements' }} </span>
                        <span class="commonTitle">
                            {{ $schoolSettings['announcement_title'] ?? 'Important Updates' }}
                        </span>

                        <span class="commonDesc">
                            {{ $schoolSettings['announcement_description'] ?? '' }}
                        </span>
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

                <div class="eventsCardWrapper">
                    <div class="row">
                        <div class="col-12">

                            <div class="commonSlider annaouncementSlider">

                                <div class="slider-content owl-carousel announcementSwiper">
                                    <!-- Example slide -->
                                    @foreach ($announcements->chunk(2) as $announcement)
                                    <div class="announcementRow">
                                        @foreach ($announcement as $item)
                                        <div class="announcementCardWrapper">
                                            <div class="card open-modal">
                                                <!-- Right Arrow -->
                                                <span class="rightArr">
                                                    <i class="fa-solid fa-chevron-right"></i>
                                                </span>

                                                <!-- Event Date -->
                                                <div class="eventDateWrapper">
                                                    <span class="date">{{ date('d', strtotime($item->created_at)) }}</span>
                                                    <span class="month">{{ date('F', strtotime($item->created_at)) }}</span>
                                                    <span class="d-none eventDate">{{ date('d F, Y', strtotime($item->created_at)) }}</span>
                                                </div>

                                                <!-- Event Description -->
                                                <div class="eventDescWrapper">
                                                    <span class="eventTitle">{{ $item->title }}</span>
                                                    <span class="eventDesc">
                                                        {{ $item->description }}
                                                    </span>

                                                    <!-- Class Information -->
                                                    <div class="classWrapper">
                                                        @php
                                                        $class_name = "";

                                                        if ($item->table_type == "App\Models\Classes" && $item->table) {
                                                        $class_name = 'Class ' . $item->table->title;
                                                        }

                                                        if ($item->table_type == "App\Models\ClassSection" && $item->table) {
                                                        $class = \App\Models\Classes::find($item->table->id);
                                                        if ($class) {
                                                        $class_name = 'Class ' . $class->title . ' Section ' . $item->table->section_name;
                                                        }
                                                        }
                                                        @endphp
                                                        <span class="eventDesc eventClasses">{{ $class_name }}</span>
                                                    </div>

                                                    <!-- File Display -->
                                                    @if ($item->file)
                                                    <div class="fileWrapper">
                                                        @foreach ($item->file as $key => $value)
                                                        @php
                                                        // Check if file URL ends with image extensions
                                                        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                                                        $isImage = false;

                                                        foreach ($imageExtensions as $extension) {
                                                        if (Str::endsWith($value->file_url, '.' . $extension)) {
                                                        $isImage = true;
                                                        break;
                                                        }
                                                        }
                                                        @endphp

                                                        @if ($isImage)
                                                        <!-- Display as Image -->
                                                        <div class="fileImage">
                                                            <a href="{{ $value->file_url }}" target="_blank"> <img src="{{ $value->file_url }}" alt="Image" style="max-width: 100px; height: auto;"></a>
                                                        </div>
                                                        @else
                                                        <!-- Display as Link -->
                                                        <div class="fileLink">
                                                            <a href="{{ $value->file_url }}" target="_blank">Download File</a>
                                                        </div>
                                                        @endif
                                                        @endforeach
                                                    </div>
                                                    @endif

                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    @endforeach

                                    <!-- Add more swiperDataWrapper elements here -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Structure -->
                    <div id="announcementModal" class="modal">
                        <div class="modal-content">
                            <div class="modal-header">
                                <span class="title" id="title"></span>
                                <span class="closeBtn close">&times;</span>
                            </div>
                            <div class="modal-body">
                                <div class="modalUpperDiv">
                                    <div class="dateStandardWrapper">
                                        <span class="date" id="date"></span>
                                        <span class="standard" id="classes"></span>
                                    </div>
                                    <div class="examImgDescWrapper">
                                        {{-- <img src="./Images/modalImg.png" alt=""> --}}
                                        <span class="commonDesc" id="description"></span>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
<!-- events ends here  -->


@endif

{{-- Counter --}}
@if (isset($schoolSettings['counter_status']) && $schoolSettings['counter_status'] == 1)
<section class="programs ctcaSection commonMT">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="flex_column_center">
                    <span class="commonTag"> {{ $schoolSettings['counter_section'] ?? 'CTCA - Contes' }} </span>
                    <span class="commonTitle">
                        {{ $schoolSettings['counter_title'] ?? 'Educational Programs for every Stage' }}
                    </span>

                    <span class="commonDesc">
                        {{ $schoolSettings['counter_description'] ?? '' }}
                    </span>
                </div>
            </div>

            <div class="col-12 mt-5">
                <div class="row ctcaCardsRow">
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                        <div class="cardBg">
                            <div class="card">
                                <div class="imgBg">
                                    <img src="{{ $schoolSettings['counter_teacher'] ?? asset('assets/school/images/teachers.png') }}" class="" alt="..." />
                                </div>
                                <div class="cardDetails">
                                    <span class="cardTitle">Total Teacher</span>
                                    <span class="cardDesc">{{ count($teachers) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                        <div class="cardBg">
                            <div class="card">
                                <div class="imgBg">
                                    <img src="{{ $schoolSettings['counter_student'] ?? asset('assets/school/images/students.png') }}" class="" alt="..." />
                                </div>
                                <div class="cardDetails">
                                    <span class="cardTitle">Total Student</span>
                                    <span class="cardDesc">{{ $counters['students'] ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                        <div class="cardBg">
                            <div class="card">
                                <div class="imgBg">
                                    <img src="{{ $schoolSettings['counter_class'] ?? asset('assets/school/images/classes.png') }}" class="" alt="..." />
                                </div>
                                <div class="cardDetails">
                                    <span class="cardTitle">Total Class</span>
                                    <span class="cardDesc">{{ $counters['classes'] ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>




                </div>
                <div class="sideImgs">
                    <img src="{{ asset('assets/school/images/pen.png') }}" class="colorImg" alt="colorImg" />
                    <img src="{{ asset('assets/school/images/triangle.png') }}" class="bagImg" alt="bagImg" />
                </div>
            </div>
        </div>
    </div>
</section>
@endif

{{-- Expert Teachers --}}
@include('frontend.school-website.our_teacher_section')

{{-- FAQs --}}
@if (isset($schoolSettings['faqs_status']) && $schoolSettings['faqs_status'] == 1 && count($faqs))
<section class="faqs commonMT" id="faqs">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="flex_column_center">
                    <span class="commonTag"> {{ $schoolSettings['faqs_section'] ?? 'Frequently Asked Questions' }}
                    </span>
                    <span class="commonTitle">
                        {{ $schoolSettings['faqs_title'] ?? 'Know More About eSchool' }}

                    </span>

                    <span class="commonDesc">
                        {{ $schoolSettings['faqs_description'] ?? '' }}
                    </span>
                </div>
            </div>

            <div class="col-12 lowerDiv">
                <div class="accordion" id="accordionExample">

                    @foreach ($faqs as $faq)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="{{ $faq->id }}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne-{{ $faq->id }}" aria-expanded="true" aria-controls="collapseOne-{{ $faq->id }}">
                                <span> {{ $loop->index + 1 }}. {{ $faq->title }} </span>
                            </button>
                        </h2>
                        <div id="collapseOne-{{ $faq->id }}" class="accordion-collapse collapse" aria-labelledby="{{ $faq->id }}" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <span>
                                    {!! nl2br(e($faq->description)) !!}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="sideImgs">
                    <img src="{{ asset('assets/school/images/ques2.png') }}" class="colorImg" alt="colorImg" />
                    <img src="{{ asset('assets/school/images/ques1.png') }}" class="bagImg" alt="bagImg" />
                </div>
            </div>
        </div>
    </div>
</section>
<!-- faqs ends here  -->
@endif
@if(!@empty($events))

@include('frontend.school-website.event_section')
@endif
{{-- Gallery --}}
@if (isset($schoolSettings['gallery_status']) && $schoolSettings['gallery_status'] == 1)
@include('frontend.school-website.gallery_section')
@endif

@endsection
@section('script')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Function to open the modal
        function openModal(title, date, classes, description) {
            setTimeout(() => {
                $('#title').html(title);
                $('#classes').html(classes);
                $('#description').html(description);
                $('#date').html(date);
            }, 200);

        }

        // Add click event to each card
        document.querySelectorAll('.open-modal').forEach(card => {
            card.addEventListener('click', () => {
                const title = card.querySelector('.eventTitle').textContent;
                const date = card.querySelector('.eventDate').textContent;
                const classes = card.querySelector('.eventClasses').textContent;
                const description = card.querySelector('.eventDesc').textContent;
                openModal(title, date, classes, description);

            });
        });

    });

</script>
@endsection
