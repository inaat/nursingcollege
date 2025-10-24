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
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">
                        {{ __('create') . ' ' . __('gallery') }}
                    </h4>
                    <form class="create-form pt-3" id="create-form" action="{{ route('galleries.store') }}" method="POST" novalidate="novalidate" enctype="multipart/form-data">
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
                                <label>{{ __('thumbnail') }} <span class="text-danger">*</span> <span class="text-small text-info">( jpg,svg,jpeg,png )</span></label>
                                <input type="file" required name="thumbnail" id="thumbnail" class="file-upload-default" accept="image/*" />
                                <div class="input-group col-xs-12">
                                    <input type="text" class="form-control file-upload-info" disabled="" placeholder="{{ __('thumbnail') }}" required aria-label="" />
                                    <span class="input-group-append">
                                        <button class="file-upload-browse btn btn-theme" type="button">{{ __('upload') }}</button>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group col-sm-6 col-md-6">
                                <label>{{ __('images') }} <span class="text-small text-info"> ({{ __('upload_multiple_images') }})</span></label>
                                <input type="file" multiple name="images[]" id="uploadInput" class="file-upload-default" accept="image/*" />
                                <div class="input-group col-xs-12">
                                    <input type="text" class="form-control file-upload-info" disabled="" placeholder="{{ __('images') }}" required aria-label="" />
                                    <span class="input-group-append">
                                        <button class="file-upload-browse btn btn-theme" type="button">{{ __('upload') }}</button>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group col-sm-12 col-md-6">
                                <label for="">{{ __('youtube_links') }} <span class="text-small text-info">({{ __('please_use_commas_or_press_enter_to_add_multiple_links') }})</span></label>
                                <input name="youtube_links" id="tags" class="form-control" value="" />
                            </div>

                            <div class="form-group col-sm-12 col-md-3">
                                {!! Form::label('english.sessions', __('english.sessions') . ':*') !!}
                                {!! Form::select('session_id',$sessions,null, ['class' => 'form-select select2 exam-session ','required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select'),'id'=>'session_id']) !!}


                            </div>
                        </div>
                        <input class="btn btn-theme float-right ml-3" id="create-btn" type="submit" value={{ __('submit') }}>
                        <input class="btn btn-secondary float-right" type="reset" value={{ __('reset') }}>
                    </form>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-primary">@lang('english.gallery_list')</h5>

                @can('gallery.create')
                <div class="d-lg-flex align-items-center mb-4 gap-3">
                    <div class="ms-auto"><a class="btn btn-primary radius-30 mt-2 mt-lg-0 btn-gallery-modal" href="#" data-href="{{ action('Frontend\FrontGalleryController@create') }}" data-container=".gallery_modal">
                            <i class="bx bxs-plus-square"></i>@lang('english.add_new_album')</a></div>
                </div>
                @endcan

                <hr>
                @can('gallery.view')
                <div class="table-responsive">
                    <table class="table mb-0" width="100%" id="gallery_table">
                        <thead class="table-light" width="100%">
                            <tr>
                                <th>@lang( 'english.action' )</th>
                                <th>@lang( 'english.thumb_image' )</th>
                                <th>@lang( 'english.gallery_title' )</th>
                                <th>@lang( 'english.description' )</th>
                                <th>@lang( 'english.show_website' )</th>
                            </tr>
                        </thead>

                    </table>
                </div>
                @endcan
            </div>
        </div>
        <!--end row-->
    </div>
</div>
<div class="modal fade gallery_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>

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
    uploadInput.addEventListener('change', function() {
        // Update file counter with the number of selected files
        $(this).parent().find('.form-control').val(this.files.length + (this.files.length === 1 ? ' file selected' : ' files selected'));
    });
    $(document).ready(function() {
        var gallery_table = $('#gallery_table').DataTable({
            processing: true
            , serverSide: true
            , ajax: '/galleries'
            , columns: [{
                    data: "action"
                    , name: "action"
                    , orderable: false
                    , searchable: false
                , }
                , {
                    data: "thumbnail"
                    , name: "thumbnail"
                , }
                , {
                    data: "title"
                    , name: "title"
                , }
                , {
                    data: "description"
                    , name: "description"
                    , orderable: false
                    , searchable: false
                , }


            ]
        , });
        $(document).on('click', 'a.delete_gallery_button', function() {
            swal({
                title: LANG.sure
                , text: LANG.confirm_delete_role
                , icon: "warning"
                , buttons: true
                , dangerMode: true
            , }).then((willDelete) => {
                if (willDelete) {
                    var href = $(this).data('href');
                    var data = $(this).serialize();

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

        $(document).on("click", ".btn-gallery-modal", function(e) {
            e.preventDefault();
            var container = $(this).data("container");

            $.ajax({
                url: $(this).data("href")
                , dataType: "html"
                , success: function(result) {
                    $(container).html(result).modal("show");

                }
            , });
        });
        $(document).on("click", "a.edit_gallery_button", function() {
            $("div.gallery_modal").load($(this).data("href"), function() {
                $(this).modal("show");

                $("form#gallery_edit_form").submit(function(e) {
                    e.preventDefault();
                    var form = $(this);
                    $.ajax({
                        method: "POST"
                        , url: $(this).attr("action")
                        , data: new FormData(this)
                        , dataType: "json"
                        , contentType: false
                        , cache: false
                        , processData: false
                        , beforeSend: function(xhr) {
                            __disable_submit_button(
                                form.find('button[type="submit"]')
                            );
                        }
                        , success: function(result) {
                            if (result.success == true) {
                                $("div.gallery_modal").modal("hide");
                                toastr.success(result.msg);
                                gallery_table.ajax.reload();
                            } else {
                                toastr.error(result.msg);
                            }
                        }
                    , });
                });
            });
        });
    });

</script>
@endsection
