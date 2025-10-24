@extends("admin_layouts.app")
@section('title', __('Notification Template'))

@section("wrapper")
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('Manage Your Notification Template')</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('Notification Template')</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-primary">@lang('Notification Templates')</h5>

                <hr>
                @can('slider.view')
                <div class="table-responsive">
                    <table class="table mb-0" width="100%" id="slider_table">
                        <thead class="table-light" width="100%">
                            <tr>
                                <th>@lang( 'english.action' )</th>
                                <th>@lang( 'Template For' )</th>
                                <th>@lang( 'Sms Body' )</th>
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

@endsection
@section('javascript')
<script type="text/javascript">
    //slider table
    $(document).ready(function() {
        var slider_table = $('#slider_table').DataTable({
            processing: true
            , serverSide: true
            , ajax: '/sms-email-templates'
            , columns: [{
                    data: "action"
                    , name: "action"
                    , orderable: false
                    , searchable: false
                , }
                , {
                    data: "template_for"
                    , name: "template_for"
                , }
                , {
                    data: "sms_body"
                    , name: "sms_body"
                , }
                

            ]
        , });
     

      
     
    });

</script>
@endsection
