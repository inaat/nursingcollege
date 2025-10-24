@extends("admin_layouts.app")
@section('title', __('english.Web Page'))
@section('title', __('english.Web Page'))

@section("wrapper")
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3"> Manage Certificate Template</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page"> Certificate Template</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">
       {!! Form::open(['url' => action('CertificateTemplateController@design_store', [$certificateTemplate->id]), 'method' => 'PUT', 'id' => 'add_expense_form', 'files' => true ]) !!}

            <div class="col-12">
                <h2 class="text-center mb-4">Certificate Generator</h2>
                {{-- school logo --}}
                <input type="hidden" style="width:100%" name="style[school_logo]" value="{{ $style['school_logo'] ?? '' }}" id="school_logo">
                <input type="hidden" style="width:100%" name="style[user_image]" value="{{ $style['user_image'] ?? '' }}" id="user_image">
                {{-- Title --}}
                <input type="hidden" style="width:100%" name="style[title]" value="{{ $style['title'] ?? '' }}" id="title">
                {{-- Description --}}
                <input type="hidden" style="width:100%" name="style[description]" value="{{ $style['description'] ?? '' }}" id="description">
                {{-- Issue date --}}
                <input type="hidden" style="width:100%" name="style[issue_date]" value="{{ $style['issue_date'] ?? '' }}" id="issue_date">
                {{-- Signature --}}
                <input type="hidden" style="width:100%" name="style[signature]" value="{{ $style['signature'] ?? '' }}" id="signature">
                {{-- School name --}}
                <input type="hidden" style="width:100%" name="style[school_name]" value="{{ $style['school_name'] ?? '' }}" id="school_name">
                {{-- School address --}}
                <input type="hidden" style="width:100%" name="style[school_address]" value="{{ $style['school_address'] ?? '' }}" id="school_address">
                {{-- School mobile --}}
                <input type="hidden" style="width:100%" name="style[school_mobile]" value="{{ $style['school_mobile'] ?? '' }}" id="school_mobile">
                {{-- School email --}}
                <input type="hidden" style="width:100%" name="style[school_email]" value="{{ $style['school_email'] ?? '' }}" id="school_email">
                <!-- Main Controls -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="form-group col-sm-12 col-md-12">

                            <div class="d-flex">
                                <div class="form-check w-fit-content ml-3">
                                    <label class="form-check-label ml-4">
                                        <input type="checkbox" class="form-check-input" {{ in_array('school_name', $certificateTemplate->fields) ? 'checked' : '' }} name="school_data[]" value="school_name">{{ __('School Name') }}
                                    </label>
                                </div>

                                <div class="form-check w-fit-content ml-3">
                                    <label class="form-check-label ml-4">
                                        <input type="checkbox" class="form-check-input" {{ in_array('school_logo', $certificateTemplate->fields) ? 'checked' : '' }} name="school_data[]" value="school_logo">{{ __('School  Logo') }}
                                    </label>
                                </div>

                                <div class="form-check w-fit-content ml-3">
                                    <label class="form-check-label ml-4">
                                        <input type="checkbox" class="form-check-input" {{ in_array('signature', $certificateTemplate->fields) ? 'checked' : '' }} name="school_data[]" value="signature">{{ __('Signature') }}
                                    </label>
                                </div>

                                <div class="form-check w-fit-content ml-3">
                                    <label class="form-check-label ml-4">
                                        <input type="checkbox" class="form-check-input" {{ in_array('issue_date', $certificateTemplate->fields) ? 'checked' : '' }} name="school_data[]" value="issue_date">{{ __('Issue Date') }}
                                    </label>
                                </div>

                                <div class="form-check w-fit-content ml-3">
                                    <label class="form-check-label ml-4">
                                        <input type="checkbox" class="form-check-input" {{ in_array('user_image', $certificateTemplate->fields) ? 'checked' : '' }} name="school_data[]" value="user_image">{{ __('User Image') }}
                                    </label>
                                </div>

                                {{-- --}}

                                <div class="form-check w-fit-content ml-3">
                                    <label class="form-check-label ml-4">
                                        <input type="checkbox" class="form-check-input" {{ in_array('school_address', $certificateTemplate->fields) ? 'checked' : '' }} name="school_data[]" value="school_address">{{ __('School Address') }}
                                    </label>
                                </div>
                                <div class="form-check w-fit-content ml-3">
                                    <label class="form-check-label ml-4">
                                        <input type="checkbox" class="form-check-input" {{ in_array('school_mobile', $certificateTemplate->fields) ? 'checked' : '' }} name="school_data[]" value="school_mobile">{{ __('School Mobile') }}
                                    </label>
                                </div>
                                <div class="form-check w-fit-content ml-3">
                                    <label class="form-check-label ml-4">
                                        <input type="checkbox" class="form-check-input" {{ in_array('school_email', $certificateTemplate->fields) ? 'checked' : '' }} name="school_data[]" value="school_email">{{ __('School Email') }}
                                    </label>
                                </div>

                                <div class="form-check w-fit-content ml-3">
                                    <label class="form-check-label ml-4">
                                        <input type="checkbox" class="form-check-input" {{ in_array('title', $certificateTemplate->fields) ? 'checked' : '' }} name="school_data[]" value="title">{{ __('Title') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-sm-12 col-md-12">
                            <span class="text-danger">{{ __('note_required_medium_or_large_screen_only') }}</span>
                        </div>


                          	<div class="col-sm-12 text-center">
		<button type="submit" class="btn btn-primary btn-big">@lang('english.save')</button>
	</div>
                        {{-- User image --}}



                    </div>
                </div>

                <!-- Certificate Preview -->
                <div id="certificate">
                   @if ($certificateTemplate->background_image)
                        {{-- Background image --}}
                    <img id="background-image" src="{{ $certificateTemplate->background_image }}" alt="Background">
                    @else
                        <div class="frame-border">

                        </div>
                    @endif

                    {{-- School Logo --}}
                    <div id="item_school_logo" class="draggable" {!! $style['school_logo'] ?? '' !!}>
                        <div class="element-controls">
                            <div class="d-flex align-items-center">
                                <label class="control-label">W:</label>
                                <input type="number" class="form-control size-control" data-dimension="width" value="150">
                                <label class="control-label">H:</label>
                                <input type="number" class="form-control size-control" data-dimension="height" value="150">
                            </div>
                        </div>
                        <img class="img" src="{{ url('/uploads/business_logos/'.session()->get("system_details.page_header_logo")) }}" alt="Logo">
                    </div>
                    {{-- User Image --}}
                    <div id="item_user_image" class="draggable" {!! $style['user_image'] ?? '' !!}>
                        <div class="element-controls">
                            <div class="d-flex align-items-center">
                                <label class="control-label">W:</label>
                                <input type="number" class="form-control size-control" data-dimension="width" value="{{ $certificateTemplate->image_size }}">
                                <label class="control-label">H:</label>
                                <input type="number" class="form-control size-control" data-dimension="height" value="{{ $certificateTemplate->image_size }}">
                            </div>
                        </div>
                        <img class="img item_user_image" src="{{ url('assets/dummy_logo.jpg') }}" alt="User Image">
                    </div>


                    {{-- Title --}}
                    <div id="item_title" class="certificate-text draggable" {!! $style['title'] ?? '' !!}>
                        <div class="element-controls">
                            <div class="d-flex align-items-center  " >
                                <label class="control-label">W:</label>
                                <input type="number" class="form-control size-control" data-dimension="width" value="600">
                                <label class="control-label">H:</label>
                                <input type="number" class="form-control size-control" data-dimension="height" value="100">
                            </div>
                        </div>
                        <div id="item_title-text">
                            <b>{{ $certificateTemplate->name }}</b>

                        </div>
                    </div>
                    {{-- Signature --}}
                    <div id="item_signature" class="item-signature draggable"  {!! $style['signature'] ?? '' !!}>
                        <div class="element-controls">
                            <div class="d-flex align-items-center">
                                <label class="control-label">W:</label>
                                <input type="number" class="form-control size-control" data-dimension="width" value="600">
                                <label class="control-label">H:</label>
                                <input type="number" class="form-control size-control" data-dimension="height" value="100">
                            </div>
                        </div>
                        <img class="img item_signature_img" src="{{ $settings['signature'] ?? url('assets/no_image_available.jpg') }}" alt="Signature">
                    </div>
                    {{-- Issue Date --}}
                    <div id="item_issue_date" class="certificate-text draggable" {!! $style['issue_date'] ?? '' !!}>
                        <div class="element-controls">
                            <div class="d-flex align-items-center">
                                <label class="control-label">W:</label>
                                <input type="number" class="form-control size-control" data-dimension="width" value="600">
                                <label class="control-label">H:</label>
                                <input type="number" class="form-control size-control" data-dimension="height" value="100">
                            </div>
                        </div>
                        <div id="item_issue_date-text">
                            <b>Issue Date</b>
                        </div>
                    </div>
                    {{-- School Name --}}
                    <div id="item_school_name" class="certificate-text draggable" {!! $style['school_name'] ?? '' !!}>
                        <div class="element-controls">
                            <div class="d-flex align-items-center">
                                <label class="control-label">W:</label>
                                <input type="number" class="form-control size-control" data-dimension="width" value="600">
                                <label class="control-label">H:</label>
                                <input type="number" class="form-control size-control" data-dimension="height" value="100">
                            </div>
                        </div>
                        <div id="item_school_name-text">
                            {{ $settings['school_name'] }} </div>
                    </div>
                    {{-- School Address --}}
                    <div id="item_school_address" class="certificate-text draggable" {!! $style['school_address'] ?? '' !!}>
                        <div class="element-controls">
                            <div class="d-flex align-items-center">
                                <label class="control-label">W:</label>
                                <input type="number" class="form-control size-control" data-dimension="width" value="600">
                                <label class="control-label">H:</label>
                                <input type="number" class="form-control size-control" data-dimension="height" value="100">
                            </div>
                        </div>
                        <div id="item_school_address-text">
                            {{ $settings['school_address'] }} </div>
                    </div>

                    {{-- School Mobile --}}

                    <div id="item_school_mobile" class="certificate-text draggable" {!! $style['school_mobile'] ?? '' !!}>
                        <div class="element-controls">
                            <div class="d-flex align-items-center">
                                <label class="control-label">W:</label>
                                <input type="number" class="form-control size-control" data-dimension="width" value="600">
                                <label class="control-label">H:</label>
                                <input type="number" class="form-control size-control" data-dimension="height" value="100">
                            </div>
                        </div>
                        <div id="item_school_mobile-text">
                            {{ $settings['school_phone'] }}
                        </div>
                    </div>
                    {{-- School Email --}}

                    <div id="item_school_email" class="certificate-text draggable" {!! $style['school_email'] ?? '' !!}>
                        <div class="element-controls">
                            <div class="d-flex align-items-center">
                                <label class="control-label">W:</label>
                                <input type="number" class="form-control size-control" data-dimension="width" value="600">
                                <label class="control-label">H:</label>
                                <input type="number" class="form-control size-control" data-dimension="height" value="100">
                            </div>
                        </div>
                        <div id="item_school_email-text">
                            {{ $settings['school_email'] }} </div>
                    </div>
                    {{-- Certificate Description --}}
                    <div id="item_description" class="certificate-text draggable" {!! $style['description'] ?? '' !!}>
                        <div class="element-controls">
                            <div class="d-flex align-items-center" >
                                <label class="control-label">W:</label>
                                <input type="number" class="form-control size-control" data-dimension="width" value="600">
                                <label class="control-label">H:</label>
                                <input type="number" class="form-control size-control" data-dimension="height" value="100">
                            </div>
                        </div>
                        <div id="description-text">
                            {!! $certificateTemplate->description !!}
                        </div>
                    </div>
                </div>

            </div>
              {!! Form::close() !!}

        </div>
    </div>
</div>
</div>

</div>

<!--end row-->
</div>
</div>

@endsection
@section('javascript')
<script>
    window.onload = setTimeout(() => {
        $('.page_layout').trigger('change');
        $('#certificate_type_id').trigger('change');
        $('.form-check-input').trigger('change');
    }, 500);
    // Size control handling
    document.querySelectorAll('.size-control').forEach(input => {
        input.addEventListener('input', function(e) {
            const element = e.target.closest('.draggable');
            const dimension = e.target.dataset.dimension;
            const value = e.target.value;

            // Update the dimension (width or height) directly on the element's style
            element.style[dimension] = `${value}px`;

            // Update the corresponding hidden input with the new dimension
            let type = element.id.replace('item_', ''); // Get the ID type
            let inputField = document.getElementById(type);
            if (inputField) {
                // Update the style attribute to include the updated width/height
                let style = `style="position: absolute; left: ${element.style.left}; top: ${element.style.top}; width: ${element.style.width}; height: ${element.style.height};"`;
                inputField.value = style; // Update the input with the new style
            }
        });
    });

    // Make elements draggable
    const draggableElements = document.querySelectorAll('.draggable');

    draggableElements.forEach(element => {
        let isDragging = false;
        let currentX;
        let currentY;
        let initialX;
        let initialY;
        let xOffset = 0;
        let yOffset = 0;
        let currentImage = null; // Declare currentImage here

        // When mouse is pressed down, start dragging
        element.addEventListener('mousedown', function(e) {
            if (!e.target.classList.contains('form-control')) {
                isDragging = true;
                initialX = e.clientX - xOffset;
                initialY = e.clientY - yOffset;
                currentImage = element; // Assign the element being dragged to currentImage
            }
        });

        // While mouse is moving, update element's position and input value
        document.addEventListener('mousemove', function(e) {
            if (isDragging) {
                e.preventDefault();
                currentX = e.clientX - initialX;
                currentY = e.clientY - initialY;
                xOffset = currentX;
                yOffset = currentY;

                // Update the element's position during dragging
                element.style.transform = `translate(${currentX}px, ${currentY}px)`;

                // Update the width and height along with position
                let width = element.style.width || 'auto'; // Use existing width or fallback to 'auto'
                let height = element.style.height || 'auto'; // Use existing height or fallback to 'auto'

                // Update the input value in real time for position, width, and height
                if (currentImage) {
                    let style = `style="transform:translate(${currentX}px, ${currentY}px); width: ${width}; height: ${height};"`;
                    let type = currentImage.id.replace('item_', ''); // Remove any prefix from the ID if needed

                    // Update the input field with the new style (live update during drag)
                    let inputField = document.getElementById(type);
                    if (inputField) {
                        inputField.value = style; // Update the input with the new position and size
                    }
                }
            }
        });

        // When mouse is released, stop dragging and save final position and size
        document.addEventListener('mouseup', function() {
            if (isDragging && currentImage) {
                isDragging = false;

                // Once the drag ends, save the final position and dimensions
                let width = currentImage.style.width || 'auto';
                let height = currentImage.style.height || 'auto';

                let style = `style="transform:translate(${currentX}px, ${currentY}px); width: ${width}; height: ${height};"`;
                let type = currentImage.id.replace('item_', ''); // Remove any prefix from the ID if needed

                // Update the input field with the final style (end of drag)
                let inputField = document.getElementById(type);
                if (inputField) {
                    inputField.value = style; // Final update of the input value with position and size
                }

                currentImage = null; // Reset currentImage after the drag ends
            }
        });
    });


    $('.form-check-input').change(function(e) {
        e.preventDefault();
        let field = '#item_' + $(this).val();
        let status = $(this).is(':checked');
        if (status) {
            $(field).show(500);
        } else {
            $(field).hide(500);
        }
    });

</script>

@endsection
@section('style')
@include('certificate.template.style')
@endsection
