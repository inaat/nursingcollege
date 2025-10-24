@extends("admin_layouts.app")
@section('title', __('english.faqs'))

@section("wrapper")
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('english.manage_your_faq')</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('english.faq')</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-primary">@lang('english.faq_list')</h5>
                @can('faq.create')
                <div class="d-lg-flex align-items-center mb-4 gap-3">
                     <div class="ms-auto"><a class="btn btn-primary radius-30 mt-2 mt-lg-0 btn-faq-modal" href="#" data-href="{{ action('Frontend\FrontFaqController@create') }}" data-container=".faq_modal">
                            <i class="bx bxs-plus-square"></i>@lang('english.add_new_faq')</a></div> 
                </div>
                @endcan

                <hr>
                @can('faq.view')
                <div class="table-responsive">
                    <table class="table mb-0" width="100%" id="faq_table">
                        <thead class="table-light" width="100%">
                            <tr>
                                <th>@lang( 'english.action' )</th>                        
                                <th>@lang( 'english.title' )</th>                           
                                <th>@lang( 'english.description' )</th>
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
<div class="modal fade faq_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>

@endsection
@section('javascript')
<script type="text/javascript">
    //faq table
    $(document).ready(function() {
        var faq_table = $('#faq_table').DataTable({
            processing: true
            , serverSide: true
            , ajax: '/front-faqs'
            , columns: [{
                    data: "action"
                    , name: "action"
                    , orderable: false
                    , searchable: false
                , }
              
                , {
                    data: "title"
                    , name: "title"
                , }
                , {
                    data: "description"
                    , name: "description"
                , }
                ,

            ]
        , });
        $(document).on('click', 'a.delete_faq_button', function() {
            swal({
                title: LANG.sure
                , text: LANG.confirm_delete
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
                                faq_table.ajax.reload();
                            } else {
                                toastr.error(result.msg);
                            }
                        }
                    });
                }
            });
        });

        $(document).on("click", ".btn-faq-modal", function(e) {
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
        $(document).on("click", "a.edit_faq_button", function() {
            $("div.faq_modal").load($(this).data("href"), function() {
                $(this).modal("show");

                $("form#faq_edit_form").submit(function(e) {
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
                                $("div.faq_modal").modal("hide");
                                toastr.success(result.msg);
                                faq_table.ajax.reload();
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

