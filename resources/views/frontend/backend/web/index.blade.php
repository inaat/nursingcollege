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

    div.tagsinput span.tag {
        background: -webkit-gradient(linear, left top, right top, from(#da8cff), to(#9a55ff));
        background: linear-gradient(to right, #da8cff, #9a55ff);
        border: 0;
        color: #ffffff;
        padding: 6px 14px;
        font-size: .8125rem;
        font-family: inherit;
        line-height: 1;
    }

    div.tagsinput span.tag a {
        color: #ffffff;
    }

</style>
@endsection
@section("wrapper")
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('english.Manage Web Page')</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('english.Web Page')</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <!--end breadcrumb-->
      
        
          {!! Form::open(['url' => action('Frontend\SchoolWebsiteController@store'), 'class'=>'needs-validation','method' => 'post', 'novalidate','id' => 'bussiness_edit_form',
        'files' => true ]) !!}

 <div class="card">
         <div class="card-body">
             <h5 class="card-title text-primary">@lang('english.general_settings')
                 <small class="text-info font-13">@lang('english.setting_help_text')</small>
             </h5>
             <hr>

             <div class="row">
                 <div class="col-md-4 p-3">
                     {!! Form::label('school_name', __('english.school_name') . ':*', ['classs' => 'form-lable']) !!}
                     {!! Form::text('school_name',  $settings['school_name'] ?? '', ['class' => 'form-control','required','placeholder' => __('english.school_name')]) !!}
                 </div>
                 <div class="col-md-4 p-3">
                     {!! Form::label('address', __('english.address') . ':*', ['classs' => 'form-lable']) !!}
                     {!! Form::text('school_address', $settings['school_address'] ?? '', ['class' => 'form-control','required','placeholder' => __('english.address')]) !!}
                 </div>
                 <div class="col-md-4 p-3">
                     {!! Form::label('reg_no', __('english.reg_no') . ':*', ['classs' => 'form-lable']) !!}
                     {!! Form::text('school_reg_no', $settings['school_reg_no'] ?? '', ['class' => 'form-control','required','placeholder' => __('english.reg_no')]) !!}
                 </div>
                 <div class="col-md-4 p-3">
                     {!! Form::label('email', __('english.email') . ':*', ['classs' => 'form-lable']) !!}
                     {!! Form::text('school_email', $settings['school_email'] ?? '', ['class' => 'form-control','required','placeholder' => __('english.email')]) !!}
                 </div>
                 <div class="col-md-4 p-3">
                     {!! Form::label('phone_no', __('english.phone_no') . ':*', ['classs' => 'form-lable']) !!}
                     {!! Form::text('school_phone', $settings['school_phone']??'', ['class' => 'form-control','required','placeholder' => __('english.phone_no')]) !!}
                 </div>
             
                                  <div class="clearfix"></div>

                 <div class="col-md-6 p-3">
                     {!! Form::label('facebook_page', __('english.facebook_page') . ':*', ['classs' => 'form-lable']) !!}
                     {!! Form::textarea('facebook_embed',  $settings['facebook_embed']??'', ['class' => 'form-control','placeholder' => __('english.facebook_page')]) !!}
                 </div> 
                 <div class="col-md-6 p-3">
                     {!! Form::label('map_url', __('english.map_url') . ':*', ['classs' => 'form-lable']) !!}
                     {!! Form::textarea('google_map_link', $settings['google_map_link']??'', ['class' => 'form-control','placeholder' => __('english.map_url')]) !!}
                 </div>

                 <div class="clearfix"></div>

                 


                 <div class="row p-3">

                     <div class="col-sm-4">
                         <h5 class="card-title ">@lang('english.logo')</h5>
                         <img src="{{ $settings['school_logo_image'] ?? null }}" class="logo_image card-img-top" alt="...">
                         {!! Form::label('logo_image', __('english.logo') . ':') !!}
                         {!! Form::file('school_logo_image', ['accept' => 'image/*','class' => 'form-control upload_logo_image']) !!}
                         <p class="card-text fs-6 help-block"></p>
                     </div>




                 </div>



             </div>
         </div>


     </div>






        ///
        <div class="card">
            <div class="card-body">
                {{-- Theme color --}}
                <div class="page-section1111">
                    <h4 class="card-title">
                        {{ __('theme_color') }}
                    </h4>
                    <hr>

                    <div class="row">
                        <div class="col-md-4 p-3">
                            {!! Form::label('primary_color', __('english.primary_color') . ':*', ['classs' => 'form-lable']) !!}
                            {!! Form::text('primary_color', $settings['primary_color'] ?? '#22577a' , ['class' => 'form-control color-picker','required','id'=>'primary_color','placeholder' => __('english.primary_color')]) !!}
                        </div>


                        <div class="col-md-4 p-3">
                            {!! Form::label('secondary_color', __('secondary_color') . ':*', ['class' => 'form-label']) !!}
                            {!! Form::text('secondary_color', $settings['secondary_color'] ?? '#38a3a5', ['class' => 'form-control color-picker', 'required', 'id' => 'secondary_color', 'placeholder' => __('color')]) !!}
                        </div>

                        <div class="col-md-4 p-3">
                            {!! Form::label('primary_background_color', __('primary_background_color') . ':*', ['class' => 'form-label']) !!}
                            {!! Form::text('primary_background_color', $settings['primary_background_color'] ?? '#f2f5f7', ['class' => 'form-control color-picker', 'required', 'id' => 'primary_background_color', 'placeholder' => __('color')]) !!}
                        </div>

                        <div class="col-md-4 p-3">
                            {!! Form::label('text_secondary_color', __('text_secondary_color') . ':*', ['class' => 'form-label']) !!}
                            {!! Form::text('text_secondary_color', $settings['text_secondary_color'] ?? '#2d2c2fb5', ['class' => 'form-control color-picker', 'required', 'id' => 'text_secondary_color', 'placeholder' => __('color')]) !!}
                        </div>

                        <div class="col-md-4 p-3">
                            {!! Form::label('primary_hover_color', __('primary_hover_color') . ':*', ['class' => 'form-label']) !!}
                            {!! Form::text('primary_hover_color', $settings['primary_hover_color'] ?? '#143449', ['class' => 'form-control color-picker', 'required', 'id' => 'primary_hover_color', 'placeholder' => __('color')]) !!}
                        </div>


                    </div>
                </div>


                {{-- About Us --}}
                <div class="page-section1111 mt-3">
                    <h4 class="card-title">
                        {{ __('about_us') }}
                    </h4>
                    <hr>
                    <div class="row">
                        <div class=" col-sm-12 col-md-6">
                            <label class='form-lable'>{{ __('title') }} <span class="text-danger">*</span></label>
                            {!! Form::text('about_us_title', $settings['about_us_title'] ?? null, [ 'class' => 'form-control', 'placeholder' => __('title'), ' required', ]) !!}
                        </div>

                        <div class=" col-sm-12 col-md-6">
                            <label>{{ __('heading') }} <span class="text-danger">*</span></label>
                            {!! Form::text('about_us_heading', $settings['about_us_heading'] ?? null, ['class' => 'form-control', 'placeholder' => __('heading'), ' required']) !!}
                        </div>

                        <div class=" col-sm-12 col-md-6">
                            <label>{{ __('description') }} <span class="text-danger">*</span></label>
                            {!! Form::textarea('about_us_description', $settings['about_us_description'] ?? null, [ 'class' => 'form-control', 'placeholder' => __('description'), 'required', ]) !!}
                        </div>

                        <div class=" col-sm-12 col-md-6">
                            <label>{{ __('image') }} <span class="text-danger">*</span> <span class="text-info text-small">(645px*555px)</span></label>
                            <input type="file" name="about_us_image" class="file-upload-default form-control" />
                            <div class="input-group col-xs-12">
                                <input type="text" class="form-control file-upload-info" disabled="" placeholder="{{ __('image') }}" required />
                                <span class="input-group-append">
                                    <button class="file-upload-browse btn btn-theme btn btn-primary  mt-2 mt-lg-0" type="button">{{ __('upload') }}</button>
                                </span>
                            </div>
                            @if ($settings['about_us_image'] ?? null)
                            <img src="{{ $settings['about_us_image'] ?? null }}" class="img-fluid w-25" alt="">
                            @endif

                        </div>

                        <div class=" col-sm-6 col-md-4">
                            <label>{{ __('status') }} <span class="text-danger">*</span></label><br>
                            <div class="d-flex">
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label"> <input class="form-check-input" name="about_us_status" {{ isset($settings['about_us_status']) && $settings['about_us_status'] == 1 ? 'checked' : '' }} type="radio" value="1">{{ __('enable') }} <i class="input-helper"></i></label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label"> <input class="form-check-input" name="about_us_status" {{ isset($settings['about_us_status']) && $settings['about_us_status'] == 0 ? 'checked' : '' }} type="radio" value="0">{{ __('disable') }} <i class="input-helper"></i></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Education programs --}}
                <div class="page-section1111 mt-3">
                    <h4 class="card-title">
                        {{ __('education_program') }}
                    </h4>
                    <hr>
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-6">
                            <label>{{ __('title') }} <span class="text-danger">*</span></label>
                            {!! Form::text('education_program_title', $settings['education_program_title'] ?? null, [ 'class' => 'form-control', 'placeholder' => __('title'), ' required', ]) !!}
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            <label>{{ __('heading') }} <span class="text-danger">*</span></label>
                            {!! Form::text('education_program_heading', $settings['education_program_heading'] ?? null, [ 'class' => 'form-control', 'placeholder' => __('heading'), ' required', ]) !!}
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            <label>{{ __('description') }} <span class="text-danger">*</span></label>
                            {!! Form::textarea('education_program_description', $settings['education_program_description'] ?? null, [ 'class' => 'form-control', 'placeholder' => __('description'), 'required', ]) !!}
                        </div>

                        <div class="form-group col-sm-6 col-md-4">
                            <label>{{ __('status') }} <span class="text-danger">*</span></label><br>
                            <div class="d-flex">
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label"> <input class="form-check-input" name="education_program_status" {{ isset($settings['education_program_status']) && $settings['education_program_status'] == 1 ? 'checked' : '' }} type="radio" value="1">{{ __('enable') }} <i class="input-helper"></i></label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label"> <input class="form-check-input" name="education_program_status" type="radio" value="0" {{ isset($settings['education_program_status']) && $settings['education_program_status'] == 0 ? 'checked' : '' }}>{{ __('disable') }} <i class="input-helper"></i></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Announcement --}}
                <div class="page-section1111 mt-3">
                    <h4 class="card-title">
                        {{ __('announcement') }}
                    </h4>
                    <hr>
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-6">
                            <label>{{ __('title') }} <span class="text-danger">*</span></label>
                            {!! Form::text('announcement_title', $settings['announcement_title'] ?? null, [ 'class' => 'form-control', 'placeholder' => __('title'), ' required', ]) !!}
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            <label>{{ __('heading') }} <span class="text-danger">*</span></label>
                            {!! Form::text('announcement_heading', $settings['announcement_heading'] ?? null, ['class' => 'form-control', 'placeholder' => __('heading'), ' required']) !!}
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            <label>{{ __('description') }} <span class="text-danger">*</span></label>
                            {!! Form::textarea('announcement_description', $settings['announcement_description'] ?? null, [ 'class' => 'form-control', 'placeholder' => __('description'), 'required', ]) !!}
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            <label>{{ __('image') }} <span class="text-danger">*</span><span class="text-info text-small">(595px*496px)</span></label>
                            <input type="file" name="announcement_image" class="file-upload-default" />
                            <div class="input-group col-xs-12">
                                <input type="text" class="form-control file-upload-info" disabled="" placeholder="{{ __('image') }}" required />
                                <span class="input-group-append">
                                    <button class="file-upload-browse btn btn-theme" type="button">{{ __('upload') }}</button>
                                </span>
                            </div>
                            @if ($settings['announcement_image'] ?? null)
                            <img src="{{ $settings['announcement_image'] ?? null }}" class="img-fluid w-25" alt="">
                            @endif
                        </div>

                        <div class="form-group col-sm-6 col-md-4">
                            <label>{{ __('status') }} <span class="text-danger">*</span></label><br>
                            <div class="d-flex">
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label"> <input name="announcement_status" {{ isset($settings['announcement_status']) && $settings['announcement_status'] == 1 ? 'checked' : '' }} type="radio" value="1">{{ __('enable') }} <i class="input-helper"></i></label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label"> <input name="announcement_status" {{ isset($settings['announcement_status']) && $settings['announcement_status'] == 0 ? 'checked' : '' }} type="radio" value="0">{{ __('disable') }} <i class="input-helper"></i></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                {{-- Counters --}}
                <div class="page-section1111 mt-3">
                    <h4 class="card-title">
                        {{ __('counter') }}
                    </h4>
                    <hr>
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-6">
                            <label>{{ __('title') }} <span class="text-danger">*</span></label>
                            {!! Form::text('counter_title', $settings['counter_title'] ?? null, [ 'class' => 'form-control', 'placeholder' => __('title'), ' required', ]) !!}
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            <label>{{ __('heading') }} <span class="text-danger">*</span></label>
                            {!! Form::text('counter_heading', $settings['counter_heading'] ?? null, ['class' => 'form-control', 'placeholder' => __('heading'), ' required']) !!}
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            <div class="row">
                                <div class="form-group col-sm-12 col-md-12">
                                    <label>{{ __('description') }} <span class="text-danger">*</span></label>
                                    {!! Form::textarea('counter_description', $settings['counter_description'] ?? null, [ 'class' => 'form-control', 'placeholder' => __('description'), 'required', ]) !!}
                                </div>

                                <div class="form-group col-sm-12 col-md-12">
                                    <label>{{ __('status') }} <span class="text-danger">*</span></label><br>
                                    <div class="d-flex">
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label"> <input name="counter_status" type="radio" value="1" {{ isset($settings['counter_status']) && $settings['counter_status'] == 1 ? 'checked' : '' }}>{{ __('enable') }} <i class="input-helper"></i></label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label"> <input name="counter_status" type="radio" value="0" {{ isset($settings['counter_status']) && $settings['counter_status'] == 0 ? 'checked' : '' }}>{{ __('disable') }} <i class="input-helper"></i></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>


                        <div class="form-group col-sm-12 col-md-6">
                            <span class="text-info text-small"> {{ __('image_size') }} : (248px*210px)</span>
                            <div class="row mt-3">
                                <div class="form-group col-sm-12 col-md-6">
                                    <label>{{ __('teacher') }} <span class="text-danger">*</span></label>
                                    <input type="file" name="counter_teacher" class="file-upload-default" />
                                    <div class="input-group col-xs-12">
                                        <input type="text" class="form-control file-upload-info" disabled="" placeholder="{{ __('image') }}" required />
                                        <span class="input-group-append">
                                            <button class="file-upload-browse btn btn-theme" type="button">{{ __('upload') }}</button>
                                        </span>
                                    </div>
                                    @if ($settings['counter_teacher'] ?? null)
                                    <img src="{{ $settings['counter_teacher'] ?? null }}" class="img-fluid w-25" alt="">
                                    @endif
                                </div>
                                <div class="form-group col-sm-12 col-md-6">
                                    <label>{{ __('studenddt') }} <span class="text-danger">*</span></label>
                                    <input type="file" name="counter_student" class="file-upload-default" />
                                    <div class="input-group col-xs-12">
                                        <input type="text" class="form-control file-upload-info" disabled="" placeholder="{{ __('image') }}" required />
                                        <span class="input-group-append">
                                            <button class="file-upload-browse btn btn-theme" type="button">{{ __('upload') }}</button>
                                        </span>
                                    </div>
                                    @if ($settings['counter_student'] ?? null)
                                    <img src="{{ $settings['counter_student'] ?? null }}" class="img-fluid w-25" alt="">
                                    @endif
                                </div>

                                <div class="form-group col-sm-12 col-md-6">
                                    <label>{{ __('Clasdds') }} <span class="text-danger">*</span></label>
                                    <input type="file" name="counter_class" class="file-upload-default" />
                                    <div class="input-group col-xs-12">
                                        <input type="text" class="form-control file-upload-info" disabled="" placeholder="{{ __('image') }}" required />
                                        <span class="input-group-append">
                                            <button class="file-upload-browse btn btn-theme" type="button">{{ __('upload') }}</button>
                                        </span>
                                    </div>
                                    @if ($settings['counter_class'] ?? null)
                                    <img src="{{ $settings['counter_class'] ?? null }}" class="img-fluid w-25" alt="">
                                    @endif
                                </div>
                                <div class="form-group col-sm-12 col-md-6">
                                    <label>{{ __('Streaddm') }} <span class="text-danger">*</span></label>
                                    <input type="file" name="counter_stream" class="file-upload-default" />
                                    <div class="input-group col-xs-12">
                                        <input type="text" class="form-control file-upload-info" disabled="" placeholder="{{ __('image') }}" required />
                                        <span class="input-group-append">
                                            <button class="file-upload-browse btn btn-theme" type="button">{{ __('upload') }}</button>
                                        </span>
                                    </div>
                                    @if ($settings['counter_stream'] ?? null)
                                    <img src="{{ $settings['counter_stream'] ?? null }}" class="img-fluid w-25" alt="">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                {{-- Expert Teachers --}}
                <div class="page-section1111 mt-3">
                    <h4 class="card-title">
                        {{ __('expert_teachers') }}
                    </h4>
                    <hr>
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-6">
                            <label>{{ __('title') }} <span class="text-danger">*</span></label>
                            {!! Form::text('expert_teachers_title', $settings['expert_teachers_title'] ?? null, [ 'class' => 'form-control', 'placeholder' => __('title'), ' required', ]) !!}
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            <label>{{ __('heading') }} <span class="text-danger">*</span></label>
                            {!! Form::text('expert_teachers_heading', $settings['expert_teachers_heading'] ?? null, [ 'class' => 'form-control', 'placeholder' => __('heading'), ' required', ]) !!}
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            <label>{{ __('description') }} <span class="text-danger">*</span></label>
                            {!! Form::textarea('expert_teachers_description', $settings['expert_teachers_description'] ?? null, [ 'class' => 'form-control', 'placeholder' => __('description'), 'required', ]) !!}
                        </div>

                        <div class="form-group col-sm-6 col-md-4">
                            <label>{{ __('status') }} <span class="text-danger">*</span></label><br>
                            <div class="d-flex">
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label"> <input name="expert_teachers_status" {{ isset($settings['expert_teachers_status']) && $settings['expert_teachers_status'] == 1 ? 'checked' : '' }} type="radio" value="1">{{ __('enable') }} <i class="input-helper"></i></label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label"> <input name="expert_teachers_status" type="radio" value="0" {{ isset($settings['expert_teachers_status']) && $settings['expert_teachers_status'] == 0 ? 'checked' : '' }}>{{ __('disable') }} <i class="input-helper"></i></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Gallery --}}
                <div class="page-section1111 mt-3">
                    <h4 class="card-title">
                        {{ __('gallery') }}
                    </h4>
                    <hr>
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-6">
                            <label>{{ __('title') }} <span class="text-danger">*</span></label>
                            {!! Form::text('gallery_title', $settings['gallery_title'] ?? null, [ 'class' => 'form-control', 'placeholder' => __('title'), ' required', ]) !!}
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            <label>{{ __('heading') }} <span class="text-danger">*</span></label>
                            {!! Form::text('gallery_heading', $settings['gallery_heading'] ?? null, ['class' => 'form-control', 'placeholder' => __('heading'), ' required']) !!}
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            <label>{{ __('description') }} <span class="text-danger">*</span></label>
                            {!! Form::textarea('gallery_description', $settings['gallery_description'] ?? null, [ 'class' => 'form-control', 'placeholder' => __('description'), 'required', ]) !!}
                        </div>

                        <div class="form-group col-sm-6 col-md-4">
                            <label>{{ __('status') }} <span class="text-danger">*</span></label><br>
                            <div class="d-flex">
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label"> <input name="gallery_status" {{ isset($settings['gallery_status']) && $settings['gallery_status'] == 1 ? 'checked' : '' }} type="radio" value="1">{{ __('enable') }} <i class="input-helper"></i></label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label"> <input name="gallery_status" {{ isset($settings['gallery_status']) && $settings['gallery_status'] == 0 ? 'checked' : '' }} type="radio" value="0">{{ __('disable') }} <i class="input-helper"></i></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                {{-- Our Mission --}}
                <div class="page-section1111 mt-3">
                    <h4 class="card-title">
                        {{ __('our_mission') }}
                    </h4>
                    <hr>
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-6">
                            <label>{{ __('title') }} <span class="text-danger">*</span></label>
                            {!! Form::text('our_mission_title', $settings['our_mission_title'] ?? null, [ 'class' => 'form-control', 'placeholder' => __('title'), ' required', ]) !!}
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            <label>{{ __('heading') }} <span class="text-danger">*</span></label>
                            {!! Form::text('our_mission_heading', $settings['our_mission_heading'] ?? null, ['class' => 'form-control', 'placeholder' => __('heading'), ' required']) !!}
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            <label>{{ __('description') }} <span class="text-danger">*</span></label>
                            {!! Form::textarea('our_mission_description', $settings['our_mission_description'] ?? null, [ 'class' => 'form-control', 'placeholder' => __('description'), 'required', ]) !!}
                        </div>


                        <div class="form-group col-sm-12 col-md-6">
                            <label for="">{{ __('points') }}</label>
                            <span class="text-small text-info">({{ __('please_use_commas_or_press_enter_to_add_multiple_points') }})</span></label>
                            <input name="our_mission_points" id="tags" class="form-control" value="{{ $settings['our_mission_points'] ?? null }}" />
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            <label>{{ __('image') }} <span class="text-danger">*</span></label>
                            <input type="file" name="our_mission_image" class="file-upload-default" />
                            <div class="input-group col-xs-12">
                                <input type="text" class="form-control file-upload-info" disabled="" placeholder="{{ __('image') }}" required />
                                <span class="input-group-append">
                                    <button class="file-upload-browse btn btn-theme" type="button">{{ __('upload') }}</button>
                                </span>
                            </div>
                            @if ($settings['our_mission_image'] ?? null)
                            <img src="{{ $settings['our_mission_image'] ?? null }}" class="img-fluid w-25" alt="">
                            @endif
                        </div>

                        <div class="form-group col-sm-6 col-md-4">
                            <label>{{ __('status') }} <span class="text-danger">*</span></label><br>
                            <div class="d-flex">
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label"> <input name="our_mission_status" type="radio" value="1" {{ isset($settings['our_mission_status']) && $settings['our_mission_status'] == 1 ? 'checked' : '' }}>{{ __('enable') }} <i class="input-helper"></i></label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label"> <input name="our_mission_status" type="radio" value="0" {{ isset($settings['our_mission_status']) && $settings['our_mission_status'] == 0 ? 'checked' : '' }}>{{ __('disable') }} <i class="input-helper"></i></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                {{-- Contact Us --}}
                <div class="page-section1111 mt-3">
                    <h4 class="card-title">
                        {{ __('contact_us') }}
                    </h4>
                    <hr>
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-12">
                            <label>{{ __('heading') }} </label>
                            {!! Form::text('contact_us_heading', $settings['contact_us_heading'] ?? null, ['class' => 'form-control', 'placeholder' => __('heading')]) !!}
                        </div>

                        <div class="form-group col-sm-12 col-md-12">
                            <label>{{ __('description') }} </label>
                            {!! Form::textarea('contact_us_description', $settings['contact_us_description'] ?? null, [ 'class' => 'form-control', 'placeholder' => __('description') ]) !!}
                        </div>
                        <div class="form-group col-sm-6 col-md-4">
                            <label>{{ __('status') }} <span class="text-danger">*</span></label><br>
                            <div class="d-flex">
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label"> <input name="contact_us_status" type="radio" value="1" {{ isset($settings['contact_us_status']) && $settings['contact_us_status'] == 1 ? 'checked' : '' }}>{{ __('enable') }} <i class="input-helper"></i></label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label"> <input name="contact_us_status" type="radio" value="0" {{ isset($settings['contact_us_status']) && $settings['contact_us_status'] == 0 ? 'checked' : '' }}>{{ __('disable') }} <i class="input-helper"></i></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                {{-- FAQs --}}
                <div class="page-section1111 mt-3">
                    <h4 class="card-title">
                        {{ __('faqs') }}
                    </h4>
                    <hr>
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-6">
                            <label>{{ __('title') }} <span class="text-danger">*</span></label>
                            {!! Form::text('faqs_title', $settings['faqs_title'] ?? null, ['class' => 'form-control', 'placeholder' => __('title'), ' required']) !!}
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            <label>{{ __('heading') }} <span class="text-danger">*</span></label>
                            {!! Form::text('faqs_heading', $settings['faqs_heading'] ?? null, ['class' => 'form-control', 'placeholder' => __('heading'), ' required']) !!}
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            <label>{{ __('description') }} <span class="text-danger">*</span></label>
                            {!! Form::textarea('faqs_description', $settings['faqs_description'] ?? null, [ 'class' => 'form-control', 'placeholder' => __('description'), 'required', ]) !!}
                        </div>

                        <div class="form-group col-sm-6 col-md-4">
                            <label>{{ __('status') }} <span class="text-danger">*</span></label><br>
                            <div class="d-flex">
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label"> <input name="faqs_status" type="radio" value="1" {{ isset($settings['faqs_status']) && $settings['faqs_status'] == 1 ? 'checked' : '' }}>{{ __('enable') }} <i class="input-helper"></i></label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label"> <input name="faqs_status" type="radio" value="0" {{ isset($settings['faqs_status']) && $settings['faqs_status'] == 0 ? 'checked' : '' }}>{{ __('disable') }} <i class="input-helper"></i></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Admission Form --}}
                <div class="page-section1111 mt-3">
                    <h4 class="card-title">
                        {{ __('online_registration') }}
                    </h4>
                    <hr>
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-6">
                            <label>{{ __('title') }} <span class="text-danger">*</span></label>
                            {!! Form::text('online_registration_title', $settings['online_registration_title'] ?? null, [ 'class' => 'form-control', 'placeholder' => __('title'), ' required', ]) !!}
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            <label>{{ __('heading') }} <span class="text-danger">*</span></label>
                            {!! Form::text('online_registration_heading', $settings['online_registration_heading'] ?? null, ['class' => 'form-control', 'placeholder' => __('heading'), ' required']) !!}
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            <label>{{ __('description') }} <span class="text-danger">*</span></label>
                            {!! Form::textarea('online_registration_description', $settings['online_registration_description'] ?? null, [ 'class' => 'form-control', 'placeholder' => __('description'), 'required', ]) !!}
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            <label>{{ __('image') }} <span class="text-danger">*</span><span class="text-info text-small">(595px*496px)</span></label>
                            <input type="file" name="online_registration_image" class="file-upload-default" />
                            <div class="input-group col-xs-12">
                                <input type="text" class="form-control file-upload-info" disabled="" placeholder="{{ __('image') }}" required />
                                <span class="input-group-append">
                                    <button class="file-upload-browse btn btn-theme" type="button">{{ __('upload') }}</button>
                                </span>
                            </div>
                            {{-- @dd($settings) --}}
                            @if ($settings['online_registration_image'] ?? null)
                            <img src="{{ $settings['online_registration_image'] ?? null }}" class="img-fluid w-25" alt="">
                            @endif
                        </div>

                        <div class="form-group col-sm-6 col-md-4">
                            <label>{{ __('status') }} <span class="text-danger">*</span></label><br>
                            <div class="d-flex">
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label"> <input name="online_registration_status" {{ isset($settings['online_registration_status']) && $settings['online_registration_status'] == 1 ? 'checked' : '' }} type="radio" value="1">{{ __('enable') }} <i class="input-helper"></i></label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label"> <input name="online_registration_status" {{ isset($settings['online_registration_status']) && $settings['online_registration_status'] == 0 ? 'checked' : '' }} type="radio" value="0">{{ __('disable') }} <i class="input-helper"></i></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- online_registration_status --}}




                {{-- Footer --}}
                <div class="page-section1111 mt-3 mb-3">
                    <h4 class="card-title">
                        {{ __('footer') }}
                    </h4>
                    <hr>
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-6">
                            <div class="row">
                                <div class="form-group col-sm-12 col-md-12">
                                    <label>{{ __('short_description') }} </label>
                                    {!! Form::textarea('short_description', $settings['short_description'] ?? null, [ 'class' => 'form-control', 'placeholder' => __('short_description') ]) !!}
                                </div>

                                <div class="form-group col-sm-12 col-md-12">
                                    <label>{{ __('footer_logo') }} </label>
                                    <input type="file" name="footer_logo" class="file-upload-default" />
                                    <div class="input-group col-xs-12">
                                        <input type="text" class="form-control file-upload-info" disabled="" placeholder="{{ __('image') }}" required />
                                        <span class="input-group-append">
                                            <button class="file-upload-browse btn btn-theme" type="button">{{ __('upload') }}</button>
                                        </span>
                                    </div>
                                    @if ($settings['footer_logo'] ?? null)
                                    <img src="{{ $settings['footer_logo'] ?? null }}" class="img-fluid w-25" alt="">
                                    @endif
                                </div>

                                <div class="form-group col-sm-12 col-md-12">
                                    <label>{{ __('footer_text') }} </label>
                                    {!! Form::text('footer_text', $settings['footer_text'] ?? null, [ 'class' => 'form-control', 'placeholder' => __('footer_text') ]) !!}
                                </div>
                            </div>

                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            <div class="row">
                                <div class="form-group col-sm-12 col-md-12">
                                    <label>{{ __('facebook') }}</label>
                                    {!! Form::text('facebook', $settings['facebook'] ?? null, ['class' => 'form-control', 'placeholder' => __('facebook')]) !!}
                                </div>
                                <div class="form-group col-sm-12 col-md-12">
                                    <label>{{ __('instagram') }}</label>
                                    {!! Form::text('instagram', $settings['instagram'] ?? null, ['class' => 'form-control', 'placeholder' => __('instagram')]) !!}
                                </div>
                                <div class="form-group col-sm-12 col-md-12">
                                    <label>{{ __('linkedin') }}</label>
                                    {!! Form::text('linkedin', $settings['linkedin'] ?? null, ['class' => 'form-control', 'placeholder' => __('linkedin')]) !!}
                                </div>
                                <div class="form-group col-sm-12 col-md-12">
                                    <label>{{ __('youtube') }}</label>
                                    {!! Form::text('youtube', $settings['youtube'] ?? null, ['class' => 'form-control', 'placeholder' => __('youtube')]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- <input class="btn btn-theme mt-3" id="create-btn" type="submit" value={{ __('submit') }}> --}}
                <input class="btn btn-theme float-right ml-3" id="create-btn" type="submit" value={{ __('submit') }}>
                <input class="btn btn-secondary float-right" type="reset" value={{ __('reset') }}>
            </div>
             </div>
                {!! Form::close() !!}
       
    
        <!--end row-->
    </div>
</div>

@endsection
@section('javascript')
<script src="{{ asset('/assets/js/jquery.tagsinput.min.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('.color-picker').colorpicker();
        //File Upload Custom Component
        $('.file-upload-browse').on('click', function() {
            let file = $(this).parent().parent().parent().find('.file-upload-default');
            file.trigger('click');
        });
        $('.file-upload-default').on('change', function() {

            $(this).parent().find('.form-control').val($(this).val().replace(/C:\\fakepath\\/i, ''));
        });

        $('#tags').tagsInput({
            'width': '100%'
            , 'height': '75%'
            , 'interactive': true
            , 'defaultText': 'Add More'
            , 'removeWithBackspace': true
            , 'minChars': 0,
            // 'maxChars': 20, // if not provided there is no limit
            'placeholderColor': '#666666'
        });

    });

</script>
@endsection
