@extends("admin_layouts.app")
@section('title', __('english.Web Page'))
@section('title', __('english.Web Page'))
@section('style')
<link rel="stylesheet" href="{{ asset('/assets/css/jquery.tagsinput.min.css') }}">
<style>
    .page-section1111 {
        border: 1px solid lightgray;
        padding: 1rem;
    }

    .file-upload-default {
        visibility: hidden;
        position: absolute;
    }

    .file-upload-info {
        background: white;
    }

   .btn-gradient-light {
        background: -webkit-gradient(linear, left top, right top, from(#da8cff), to(#9a55ff));
        background: linear-gradient(to right, #da8cff, #9a55ff);
        border: 0;
        color: #ffffff;
        padding: 6px 14px;
        font-size: .8125rem;
        font-family: inherit;
        line-height: 1;
    }

 

</style>
@endsection
@section("wrapper")
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3"> Manage Certificate  Template</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page"> Certificate  Template</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
       <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            {{ __('create_certificate') . ' ' . __('template') }}
                        </h4>
                        {!! Form::open(['url' => action('CertificateTemplateController@store'), 'method' => 'POST', 'id' => 'create-form', 'class' => 'pt-3 subject-create-form', 'enctype' => 'multipart/form-data', 'novalidate' => 'novalidate']) !!}


                            <div class="row">
                                <div class="form-group col-sm-12 col-md-4">
                                    <label>{{ __('name') }} <span class="text-danger">*</span></label>
                                    <input name="name" type="text" placeholder="{{ __('name') }}" class="form-control"/>
                                </div>
                                <div class=" col-sm-12 col-md-4">
                                    <label>Type<span class="text-danger">*</span></label>
                                    <div class=" d-flex ">
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label">
                                                <input type="radio" checked class="form-check-input certificate_type" name="type" value="Student" required="required">
                                                Student
                                            </label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input certificate_type" name="type" value="Staff" required="required">
                                                Staff
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-sm-12 col-md-4">
                                    <label>{{ __('page_layout') }} <span class="text-danger">*</span></label>
                                    {!! Form::select('page_layout', ['A4 Landscape' => 'A4 Landscape','A4 Portrait' => 'A4 Portrait','Custom' => 'Custom'], 'A4 Landscape', ['class' => 'form-control page_layout']) !!}
                                </div>

                                <div class="form-group col-sm-12 col-md-2">
                                    <label>{{ __('height') }} <span class="text-small text-info">({{ __('mm') }})</span> <span class="text-danger">*</span></label>
                                    <input name="height" min="50" type="number" required placeholder="{{ __('height') }}" class="form-control height"/>
                                </div>

                                <div class="form-group col-sm-12 col-md-2">
                                    <label>{{ __('width') }} <span class="text-small text-info">({{ __('mm') }})</span> <span class="text-danger">*</span></label>
                                    <input name="width" min="50" type="number" required placeholder="{{ __('width') }}" class="form-control width"/>
                                </div>

                                <div class="form-group col-sm-12 col-md-4">
                                    <label>{{ __('user_image_shape') }} <span class="text-danger">*</span></label>
                                    {!! Form::select('user_image_shape', ['Round' => 'Round','Square' => 'Square'], 'Round', ['class' => 'form-control']) !!}
                                </div>

                                <div class="form-group col-sm-12 col-md-4">
                                    <label>{{ __('image_size') }} <span class="text-small text-info">({{ __('px') }})</span><span class="text-danger">*</span></label>
                                    <input name="image_size" min="50" required type="number" placeholder="{{ __('image_size') }}" class="form-control"/>
                                </div>

                                <div class="form-group col-sm-12 col-md-4">
                                    <label>{{ __('background_image') }} </label>
                                    <input type="file" name="background_image" id="thumbnail" class="file-upload-default" accept="image/*"/>
                                    <div class="input-group col-xs-12">
                                        <input type="text" class="form-control file-upload-info" disabled=""
                                                placeholder="{{ __('thumbnail') }}" required aria-label=""/>
                                        <span class="input-group-append">
                                            <button class="file-upload-browse btn btn-theme"
                                                    type="button">{{ __('upload') }}</button>
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group col-sm-12 col-md-12">
                                    <label>{{ __('description') }} <span class="text-danger">*</span></label>
                                    <textarea id="tinymce_message" name="description" id="description" required placeholder="{{__('description')}}"></textarea>
                                </div>

                                <div class="form-group col-sm-12 col-md-12">
                                     @include('certificate.tags') 
                                </div>

                            </div>
                            {{-- <input class="btn btn-theme" id="create-btn" type="submit" value={{ __('submit') }}> --}}
                            <input class="btn btn-theme float-right ml-3" id="create-btn" type="submit" value={{ __('submit') }}>
                                <input class="btn btn-secondary float-right" type="reset" value={{ __('reset') }}>
                        </form>
                    </div>
                </div>
            </div>
        
        </div>
    
        <!--end row-->
    </div>
</div>

@endsection
@section('javascript')
<script src="{{ asset('/assets/js/jquery.tagsinput.min.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function() {
      $('.certificate_type').change(function (e) { 
    e.preventDefault();
    var type = $('input[name="type"]:checked').val();
    if (type == 'Student') {
        $('#staff_tags').hide(500);
        $('#student_tags').show(500);
    } else {
        $('#staff_tags').show(500);
        $('#student_tags').hide(500);
    }
});
     window.onload = setTimeout(() => {
            $('.page_layout').trigger('change');
            $('.certificate_type').trigger('change');
        }, 500);
        $('.btn_tag').click(function (e) { 
    e.preventDefault();
    var value = $(this).data('value');
    if (tinymce.activeEditor) { // Check if editor is active
        tinymce.activeEditor.insertContent(value);
    } else {
        alert('TinyMCE editor not active');
    }
});
if ($('#tinymce_message').length) {
        tinymce.init({
            height: "500",
            selector: '#tinymce_message',
            relative_urls: false,
            remove_script_host: false,
            menubar: 'file edit view formate tools',
            toolbar: [
                'styleselect fontselect fontsizeselect',
                'undo redo | cut copy paste | bold italic | alignleft aligncenter alignright alignjustify | table | image | fullscreen',
                'bullist numlist | outdent indent | blockquote autolink | lists | fontfamily | fontsize | code | preview'
            ],
             font_formats: 'Pinyon Script=Pinyon Script;' +
                  'Roboto=Roboto, sans-serif;' +
                  'Lobster=Lobster, cursive;' +
                  'Arial=arial,helvetica,sans-serif;' +
                  'Georgia=georgia,palatino,serif;'+"Arial Black=arial black;"+"avant garde;"+" Courier New=courier new"+"courier; Lato=lato;" ,

    // Load Google Fonts via content_style
    content_style: "@import url('https://fonts.googleapis.com/css2?family=Pinyon+Script&family=Roboto:wght@400;700&family=Lobster&display=swap');" +
                   " body { font-family: 'Lato', sans-serif; } h1,h2,h3,h4,h5,h6 { font-family: 'Pinyon%20Script', sans-serif; }",
            plugins: 'autolink link image lists code table fullscreen preview',
            font_size_formats: '8pt 10pt 12pt 14pt 16pt 18pt 24pt 28pt 36pt 48pt',
        });
    }

    });

</script>
@endsection
