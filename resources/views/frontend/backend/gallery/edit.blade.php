@extends("admin_layouts.app")
@section('title', __('english.gallery'))
@section('title', __('english.gallery'))
@section('style')
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
.img-lg {
    width: 92px;
    height: 92px;
}

.img-sm {
    width: 43px;
    height: 43px;
}

.img-xs {
    width: 37px;
    height: 37px;
}

.img-ss,
.image-grouped .text-avatar,
.image-grouped img,
.settings-panel .color-tiles .tiles {
    width: 35px;
    height: 35px;
}

.img-es {
    width: 25px;
    height: 25px;
}


.lightGallery {
    width: 100%;
    margin: 0;
}

.lightGallery .image-tile {
    position: relative;
    margin-bottom: 30px;
}

.lightGallery .image-tile .demo-gallery-poster {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
}

.lightGallery .image-tile .demo-gallery-poster img {
    display: block;
    margin: auto;
    width: 40%;
    max-width: 60px;
    min-width: 20px;
}

.lightGallery .image-tile img {
    max-width: 100%;
    width: 100%;
}

.listify-list input {
    border: 1px solid #f2f7f8;
    color: #aab2bd;
    background: #fff;
}

.listify-list ul.list {
    list-style: none;
    padding-left: 0;
}

.listify-list ul.list li {
    display: block;
    border-bottom: 1px solid #ebedf2;
    padding: 15px 10px;
}

.listify-list ul.list li h5 {
    color: #b66dff;
}

.listify-list ul.list li p {
    color: #aab2bd;
    margin: 0;
}

</style>
@endsection
@section("wrapper")
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('english.manage_your_gallery')</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('english.gallery')</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
<div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title float-left">
                            {{ __('edit') . ' ' . __('gallery') }}
                        </h4>
                      
                        <hr>
                            {!! Form::model($gallery, [
                                'route' => ['galleries.update', $gallery->id],
                                'method' => 'PUT',
                                'class' => 'edit-form',
                                'novalidate' => 'novalidate',
                                'enctype' => 'multipart/form-data',
                                'data-success-function' => 'formSuccessFunction'
                            ]) !!}
                            @csrf
                            <div class="row">
                                <div class="form-group col-sm-12 col-md-6">
                                    <label>{{ __('title') }} <span class="text-danger">*</span></label>
                                    {!! Form::text('title', null, ['required', 'placeholder' => __('title'), 'class' => 'form-control']) !!}
                                </div>
                                <div class="form-group col-sm-6 col-md-6">
                                    <label>{{ __('description') }}</label>
                                    {!! Form::textarea('description', null, [
                                        'rows' => '2',
                                        'placeholder' => __('description'),
                                        'class' => 'form-control',
                                    ]) !!}
                                </div>
                                <div class="form-group col-sm-6 col-md-6">
                                    <label>{{ __('thumbnail') }} </label>
                                    <input type="file" name="thumbnail" id="thumbnail"
                                        class="file-upload-default" accept="image/*"/>
                                    <div class="input-group col-xs-12">
                                        <input type="text" class="form-control file-upload-info" disabled=""
                                            placeholder="{{ __('thumbnail') }}" aria-label="" />
                                        <span class="input-group-append">
                                            <button class="file-upload-browse btn btn-theme"
                                                type="button">{{ __('upload') }}</button>
                                        </span>
                                    </div>
                                    <img src="{{ $gallery->thumbnail }}" class="img-lg" alt="">
                                </div>
                                <div class="form-group col-sm-6 col-md-6">
                                    <label>{{ __('images') }} <span class="text-small text-info">({{ __('upload_multiple_images') }})</span></label>
                                    <input type="file" multiple name="images[]" id="uploadInput"
                                        class="file-upload-default" accept="image/*" />
                                    <div class="input-group col-xs-12">
                                        <input type="text" class="form-control file-upload-info" disabled=""
                                            placeholder="{{ __('images') }}" required aria-label="" />
                                        <span class="input-group-append">
                                            <button class="file-upload-browse btn btn-theme"
                                                type="button">{{ __('upload') }}</button>
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group col-sm-12 col-md-6">
                                    <label for="">{{ __('youtube_links') }} <span class="text-small text-info">({{__('please_use_commas_or_press_enter_to_add_multiple_links')}})</span></label>
                                    <input name="youtube_links" id="tags" class="form-control" value="" />
                                </div>

                                <div class="form-group col-sm-12 col-md-3">
                                    <label for="session_year_id">{{ __('session_year') }}</label>
                                    {!! Form::select('session_id', $sessions, $gallery->session_id, ['class' => 'form-control']) !!}
                                </div>

                            </div>
                            {{-- <input class="btn btn-theme" type="submit" value={{ __('submit') }}> --}}
                            <input class="btn btn-theme float-right ml-3" id="create-btn" type="submit" value={{ __('submit') }}>
                            <input class="btn btn-secondary float-right" type="reset" value={{ __('reset') }}>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">
                            {{ __('view') }} {{ __('gallery') }}
                        </h3>

                        <div class="row">
                            <div class="col-sm-12 col-md-12 mb-3">
                                <hr>
                                <h4 class="card-title">{{ __('photo_gallery') }}</h4>
                            </div>
                            <div id="lightgallery" class="row lightGallery">
                                @foreach ($gallery->file as $file)
                                    @if ($file->type == 1)
                                        <div class="col-sm-12 col-md-2 mt-2">
                                            <button class="mt-1 btn btn-sm btn-danger ml-2 remove-gallery-image" data-id="{{ $file->id }}">X</button>
                                            <a href="{{ $file->file_url }}" data-toggle="lightbox" class="image-tile">
                                                <img src="{{ $file->file_url }}" alt="image small" class="zoom-img">
                                            </a>
                                            <hr>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <div id="lightgallery" class="row lightGallery">
                            <div class="col-sm-12 col-md-12 mb-3">
                                <hr>
                                <h4 class="card-title">{{ __('video_gallery') }}</h4>
                            </div>

                            @foreach ($gallery->file as $file)
                                @if ($file->type == 2)
                                    <div class="col-sm-12 col-md-2 mb-4">
                                        <button class="mb-1 btn btn-sm btn-danger ml-2 remove-gallery-image" data-id="{{ $file->id }}">X</button>
                                        <a href="{{ $file->file_url }}" data-toggle="lightbox" class="image-tile">
                                            <img src="{{ $file->youtube_url_action->img ?? '' }}" class="zoom-img" alt="image small">
                                        </a>
                                        <hr>
                                    </div>    
                                @endif
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>

        </div>

@endsection
@section('javascript')
<script type="text/javascript">
     //File Upload Custom Component
        $('.file-upload-browse').on('click', function() {
            let file = $(this).parent().parent().parent().find('.file-upload-default');
            file.trigger('click');
        });
        $('.file-upload-default').on('change', function() {

            $(this).parent().find('.form-control').val($(this).val().replace(/C:\\fakepath\\/i, ''));
        });

    //gallery table
        const uploadInput = document.getElementById('uploadInput');

        // Event listener to handle file selection
        uploadInput.addEventListener('change', function () {
            // Update file counter with the number of selected files
            $(this).parent().find('.form-control').val(this.files.length + (this.files.length === 1 ? ' file selected' : ' files selected'));
        });
   $(document).ready(function() {
  $(document).on('click', '.remove-gallery-image', function() {
            swal({
                title: LANG.sure
                , text: LANG.confirm_delete_role
                , icon: "warning"
                , buttons: true
                , dangerMode: true
            , }).then((willDelete) => {
                if (willDelete) {
                     var file_id = $(this).data('id');
                    var href =  base_path + '/galleries/file/delete/' + file_id;
                    var data = null;

                    $.ajax({
                        method: "DELETE"
                        , url: href
                        , dataType: "json"
                        , data: data
                        , success: function(result) {
                            if (result.success == true) {
                                toastr.success(result.msg);
                                gallery_table.ajax.reload();
                            } else {
                                toastr.error(result.msg);
                            }
                        }
                    });
                }
            });
        });
        });

</script>
@endsection

