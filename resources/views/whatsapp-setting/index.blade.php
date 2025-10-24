@extends("admin_layouts.app")
@section('title', __('Whatsapp Status'))
@section("wrapper")
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('Whatsapp Status')</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('english.Whatsapp Status')</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
 
        <div class="card">
            <div class="card-body">
                            <h5 class="card-title text-primary">@lang('Whatsapp  Devices')</h5>

               <div class="d-lg-flex align-items-center mb-4 gap-3">
                   
                                
                                
                                                    <div class="ms-auto"><a title="Scan" href="javascript:void(0)" id="textChange" class="btn btn-primary radius-30 mt-2 mt-lg-0 qrQuote textChange8" value="8"><i class="fas fa-qrcode"></i>&nbsp; Scan</a></div>

                </div>



                <hr>

                <div class="table-responsive">
                    <table class="table mb-0" width="100%" id="fee_heead_table">
                        <thead class="table-light" width="100%">
                            <tr>
                                <th>Name</th>
                                <th>@lang('english.status')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{$wpu->name}}</td>
                                <td>{{$wpu->status}}</td>
                            </tr>

                        </tbody>

                    </table>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>

@endsection
