@extends('frontend.layouts.school.master')
@section('title')
Admission
@endsection
@section('content')
<div class="main">
    <div class="breadcrumb">
        <div class="container">
            <div class="contentWrapper">
                <span class="title">
                    Admission Form
                </span>
                <span>
                    <a href="/" class="home">Home</a>
                    <span><i class="fa-solid fa-caret-right"></i></span>
                    <span class="page">Admission Form</span>
                </span>
            </div>
        </div>
    </div>

    <section class="admissionPage ">
        <div class="commonMT commonWaveSect">
            <div class="container">
                {{-- <form class="pt-3 student-registration-form" id="create-form" data-success-function="formSuccessFunction" enctype="multipart/form-data" action="{{ route('online-admission.store') }}" method="POST" novalidate="novalidate"> --}}
                <form class="pt-3 student-registration-form online-admission-form" enctype="multipart/form-data" action="{{ route('online-admission.store') }}" method="POST" novalidate="novalidate">
                    @csrf
                    <div class="row formContainer">
                        <div class="col-12 mainDiv">
                            <div class="formHeading">
                                <span> Student Information </span>
                            </div>
                            <div class="row formWapper">
                                <div class="col-lg-8">
                                    <div class="row formDiv">

                                        <div class="col-lg-6 inputWrapper">
                                            <label for="firstName">First Name <span>*</span></label>
                                            <input type="text" placeholder="First Name" name="first_name" id="first_name" required>
                                        </div>

                                        <div class="col-lg-6 inputWrapper">
                                            <label for="lastName">Last Name <span>*</span></label>
                                            <input type="text" placeholder="Last Name" name="last_name" id="last_name" required>
                                        </div>

                                        <!-- ====================================================================================== -->

                                        <div class="col-lg-6 inputWrapper">
                                            <label for="dob">Date Of Birth <span>*</span></label>
                                            <input type="date" placeholder="DOB" class="invalid" name="dob" id="dob" required>
                                        </div>

                                        <div class="col-lg-6 inputWrapper">
                                            <label for="number">Mobile Number</label>
                                            <input type="number" placeholder="Mobile Number" name="mobile" id="mobile">
                                        </div>

                                        <!-- ====================================================================================== -->

                                        <div class="col-lg-6 inputWrapper">
                                            <label for="currentAddress">Current Address <span>*</span></label>
                                            <textarea placeholder="Current Address" name="current_address" id="current_address" required></textarea>
                                        </div>

                                        <div class="col-lg-6 inputWrapper">
                                            <label for="permanentAddress">Permanent Address <span>*</span></label>
                                            <textarea placeholder="Permanent Address" name="permanent_address" id="permanent_address" required></textarea>
                                        </div>

                                        <!-- ====================================================================================== -->

                                        <div class="col-lg-6 inputWrapper">
                                            <label for="=classMedium">Class and Medium <span>*</span></label>
                                            <div>
                                                <select name="class_id" id="class_id">
                                                    <option value="">Choose Class Medium</option>
                                                    @foreach ($classes as $class)
                                                    <option value="{{ $class->id }}">{{ $class->title??' '}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 inputWrapper">
                                            <label for="gender">Gender<span>*</span></label>
                                            <div class="row">
                                                <div class="col-lg-5">
                                                    <input type="radio" name="gender" checked id="male" value="male" required>
                                                    <span>Male</span>
                                                </div>
                                                <div class="col-lg-5">
                                                    <input type="radio" name="gender" id="female" value="female" required>
                                                    <span>Female</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="fileInputWrapper">
                                        <div>
                                            <img src="{{ asset('assets/school/images/Image Preview.png') }}" alt="imgPreview" class="upperImgPreview default-image">
                                        </div>
                                        <div class="file-upload upperFileUpload">
                                            <div class="file-select">
                                                <button type="button">Browse...</button>
                                                <span>No File Selected.</span>
                                                <input type="file" name="image" id="fileUpload" accept="image/*" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mainDiv">
                            <div class="formHeading">
                                <span> Parents Information </span>
                            </div>
                            <div class="row formWapper">
                                <div class="col-lg-8">
                                    <div class="row formDiv">

                                        <div class="col-lg-6 inputWrapper">
                                            <label for="firstName">First Name <span>*</span></label>
                                            <input type="text" placeholder="First Name" name="guardian_first_name" id="guardian_first_name" required>
                                        </div>

                                        <div class="col-lg-6 inputWrapper">
                                            <label for="lastName">Last Name <span>*</span></label>
                                            <input type="text" placeholder="Last Name" name="guardian_last_name" id="guardian_last_name" required>
                                        </div>

                                        <!-- ====================================================================================== -->

                                        <div class="col-lg-6 inputWrapper">
                                            <label for="number">Mobile Number <span>*</span></label>
                                            <input type="number" placeholder="Mobile Number" name="guardian_mobile" id="guardian_mobile" required>
                                        </div>

                                        <div class="col-lg-6 inputWrapper">
                                            <label for="email">Email<span>*</span></label>
                                            <input type="email" placeholder="Email" name="guardian_email" id="guardian_email" required>
                                        </div>

                                        <!-- ====================================================================================== -->

                                        <div class="col-lg-6 inputWrapper">
                                            <label for="gender">Gender<span>*</span></label>
                                            <div class="row">
                                                <div class="col-lg-5">
                                                    <input type="radio" name="guardian_gender" checked id="guardian_male" value="male" required>
                                                    <span>Male</span>
                                                </div>
                                                <div class="col-lg-5">
                                                    <input type="radio" name="guardian_gender" id="guardian_female" value="female" required>
                                                    <span>Female</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="fileInputWrapper">
                                        <div>
                                            <img src="{{ asset('assets/school/images/Image Preview.png') }}" alt="imgPreview" class="lowerImgPreview default-image">
                                        </div>
                                        <div class="file-upload lowerFileUpload">
                                            <div class="file-select">
                                                <button type="button">Browse...</button>
                                                <span>No File Selected.</span>
                                                <input type="file" name="guardian_image" id="fileUpload" accept="image/*" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- @if ($schoolSettings['SCHOOL_RECAPTCHA_SITE_KEY'] ?? '')
                                            <div class="col-lg-12">
                                                <div class="g-recaptcha mt-4" data-sitekey={{ $schoolSettings['SCHOOL_RECAPTCHA_SITE_KEY'] }}>
                            </div>
                        </div>
                        @endif --}}

                    </div>
            </div>
         @if(!empty($extraFields))
<div class="row other-details">
    @foreach ($extraFields as $key => $data)
    @php $fieldName = str_replace(' ', '_', $data->name) @endphp
    {{-- Hidden Fields --}}
    <input type="hidden" name="extra_fields[{{ $key }}][id]" value="">
    <input type="hidden" name="extra_fields[{{ $key }}][form_field_id]" value="{{ $data->id }}">
    <input type="hidden" name="extra_fields[{{ $key }}][input_type]" value="{{ $data->type }}">

    {{-- Form Field Design Based on Type --}}
    <div class="col-md-4">
        @if($data->type != 'radio' && $data->type != 'checkbox')
        <label>{{ $data->name }} @if($data->is_required) <span class="text-danger">*</span> @endif</label>
        @endif

        {{-- Text Input --}}
        @if($data->type == 'text')
        <input type="text" class="form-control" name="extra_fields[{{ $key }}][data]" id="{{ $fieldName }}" placeholder="{{ $data->name }}" @if($data->is_required) required @endif>

        {{-- Number Input --}}
        @elseif($data->type == 'number')
        <input type="number" class="form-control" name="extra_fields[{{ $key }}][data]" id="{{ $fieldName }}" placeholder="{{ $data->name }}" min="0" @if($data->is_required) required @endif>

        {{-- Dropdown --}}
        @elseif($data->type == 'dropdown')
        <select class="form-control" name="extra_fields[{{ $key }}][data]" id="{{ $fieldName }}" @if($data->is_required) required @endif>
            <option value="" disabled selected>Select {{ $data->name }}</option>
            @foreach($data->default_values as $value)
            <option value="{{ $value }}">{{ $value }}</option>
            @endforeach
        </select>

        {{-- Radio Buttons --}}
        @elseif($data->type == 'radio')
        <label class="d-block">{{ $data->name }} @if($data->is_required) <span class="text-danger">*</span> @endif</label>
        <div class="d-flex">
            @foreach($data->default_values as $keyRadio => $value)
            <div class="form-check me-3">
                <input type="radio" class="form-check-input" name="extra_fields[{{ $key }}][data]" id="{{ $fieldName }}_{{ $keyRadio }}" value="{{ $value }}" @if($data->is_required) required @endif>
                <label class="form-check-label" for="{{ $fieldName }}_{{ $keyRadio }}">{{ $value }}</label>
            </div>
            @endforeach
        </div>

        {{-- Checkboxes --}}
        @elseif($data->type == 'checkbox')
        <label class="d-block">{{ $data->name }} @if($data->is_required) <span class="text-danger">*</span> @endif</label>
        <div class="d-flex flex-wrap">
            @foreach($data->default_values as $chkKey => $value)
            <div class="form-check me-3">
                <input type="checkbox" class="form-check-input" name="extra_fields[{{ $key }}][data][]" id="{{ $fieldName }}_{{ $chkKey }}" value="{{ $value }}" @if($data->is_required) required @endif>
                <label class="form-check-label" for="{{ $fieldName }}_{{ $chkKey }}">{{ $value }}</label>
            </div>
            @endforeach
        </div>

        {{-- Textarea --}}
        @elseif($data->type == 'textarea')
        <textarea class="form-control" name="extra_fields[{{ $key }}][data]" id="{{ $fieldName }}" placeholder="{{ $data->name }}" rows="3" @if($data->is_required) required @endif></textarea>

        {{-- File Upload --}}
        @elseif($data->type == 'file')
                <input type="file" class="form-control" name="extra_fields[{{ $key }}][data]" id="{{ $fieldName }}" @if($data->is_required) required @endif>
        @endif
    </div>
    @endforeach
</div>
@endif

            <div class="col-12 formBtnsWrapper">
                <button class="commonBtn">Reset</button>
                {{-- <button class="commonBtn">Submit</button> --}}
                <input type="submit" class="commonBtn" value="Submit">
            </div>

        </div>
        </form>
</div>
</div>
</section>
</div>
@endsection
@section('js')
<script async src="https://www.google.com/recaptcha/api.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('/assets/js/custom/common.js') }}"></script>
<script src="{{ asset('/assets/js/custom/custom.js') }}"></script>
<script src="{{ asset('/assets/js/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('/assets/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('/assets/jquery-toast-plugin/jquery.toast.min.js') }}"></script>

{{-- --}}
{{-- --}}


<!-- bootstrap  -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<!-- fontawesome icons   -->
<script src="https://kit.fontawesome.com/1d2a297b20.js" crossorigin="anonymous"></script>



<script type='text/javascript'>
    const today = new Date().toISOString().split('T')[0];

    // Set the max attribute to today's date to prevent future dates
    window.onlod = document.getElementById('dob').setAttribute('max', today);

</script>
@endsection
