

<!-- swiper -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

<link rel="stylesheet" href="{{ asset('/assets/school/css/style.css') }}">
<link rel="stylesheet" href="{{ asset('/assets/school/css/responsive.css') }}">

<link rel="stylesheet" href="{{ asset('/assets/school/css/custom.css') }}">

<link rel="stylesheet" href="{{ asset('/assets/jquery-toast-plugin/jquery.toast.min.css') }}">

<!-- bootstrap  -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet"
 crossorigin="anonymous" />

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

{{-- <link rel="stylesheet" href="{{ asset('/assets/css/ekko-lightbox.css') }}"> --}}

{{-- <link rel="stylesheet" href="{{ asset('/assets/css/style.min.css') }}">
<script src="{{ asset('/assets/js/vendor.bundle.base.js') }}"></script> --}}

<link rel="shortcut icon" class="school-favicon" href=""/>

<style>
    :root {
    --primary-color: {{ $schoolSettings['primary_color'] ?? '#22577a' }};
    --primary-hover-color: {{ $schoolSettings['primary_hover_color'] ?? '#143449' }};
    --secondary-color1: {{ $schoolSettings['primary_color'] ?? '#22577a' }};
    --secondary-color2: {{ $schoolSettings['secondary_color'] ?? '#57cc99' }};
    
    --secondary-color3: #80ed99;
    --text--primary-color: #38a3a51f;
    
    --text--secondary-color: {{ $schoolSettings['text_secondary_color'] ?? '#2d2c2fb5' }};
    --text-white-color: #fff;
    --primary-background-color: {{ $schoolSettings['primary_background_color'] ?? '#f2f5f7' }};
}

    /*color changer*/

.select2-selection__choice{
  background-color: var(--bs-gray-200);
  border: none !important;
  font-size: 12px;
  font-size: 0.85rem !important;
}

.commonWaveSect::before {
  content: '';
  position: absolute;
  top: -76px;
  left: 0px;
  width: 100%;
  height: 85px;
  z-index: 1;
  background: url({{ asset('/assets/school/images/waveUp.svg')}});
  background-repeat: no-repeat;
  background-size: cover;
}

.commonWaveSect::after {
  content: '';
  position: absolute;
  bottom: -108px;
  left: 0px;
  width: 100%;
  height: 120px;
  z-index: 1;
  background: url({{ asset('/assets/school/images/waveDown.svg')}});
  background-repeat: no-repeat;
  background-size: cover;
}

.ctcaSection .cardBg {
  background: url({{ asset('/assets/school/images/ctcaImg.png')}});
  background-repeat: no-repeat;
  background-size: 100% 100%;
  padding: 20px;
  /* padding-top: 30px; */
  /* padding-bottom: 20px; */
  padding-top: 8px;
}

.ctcaSection .cardBg {
  background: url({{ asset('/assets/school/images/ctcaImg.png')}});
  background-repeat: no-repeat;
  background-size: 100% 100%;
  padding: 20px;
  /* padding-top: 30px; */
  /* padding-bottom: 20px; */
  padding-top: 8px;
}

.ctcaSection .diffColorBg {
  background: url({{ asset('/assets/school/images/ctcaImg.png')}});
  background-repeat: no-repeat;
  background-size: 100% 100%;
  padding: 20px;
  /* padding-top: 30px; */
  /* padding-bottom: 20px; */
  padding-top: 8px;
}



.ctcaSection .imgBg {
  mask: url({{ asset('/assets/school/images/ctcaImg.svg')}});
  mask-repeat: no-repeat;
  mask-size: 100% 100%;
}
.faqs .accordion-button::after {
  background-image:url({{ asset("/assets/school/images/bx-plus-circle.png")}}) !important;
}

.faqs .accordion-button:not(.collapsed)::after {
  background-image:url({{ asset("/assets/school/images/bx-minus-circle.png")}}) !important;
}
    
  /*color changer*/
    
    .colorChange {
        -webkit-animation: colorchange .5s infinite alternate;
        -moz-animation: colorchange .5s infinite alternate;
        -o-animation: colorchange .5s infinite alternate;
        -ms-animation: colorchange .5s infinite alternate;
        animation: colorchange .5s infinite alternate
    }
    
    @-webkit-keyframes colorchange {
        50% {
            color: #fec24d
        }
        100% {
            color: yellow
        }
    }
    
    @-moz-keyframes colorchange {
        50% {
            color: #fec24d
        }
        100% {
            color: red
        }
    }
    
    @-o-keyframes colorchange {
        50% {
            color: #fec24d
        }
        100% {
            color: red
        }
    }
    
    @-ms-keyframes colorchange {
        50% {
            color: #fec24d
        }
        100% {
            color: yellow
        }
    }
    
    @keyframes colorchange {
        50% {
            color: #fec24d
        }
        100% {
            color: black
        }
    }
</style>