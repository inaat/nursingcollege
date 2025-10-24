@extends("admin_layouts.app")
@section('title', __('english.class_batch_semester_fee_report'))
@section("style")
<link href="{{ asset('assets/plugins/vectormap/jquery-jvectormap-2.0.2.css')}}" rel="stylesheet" />
@endsection
@section('wrapper')
<div class="page-wrapper">
    <div class="page-content">

        <!-- Class Batch Semester Fee Report Table -->
        <div class="box box-solid print_area">
            <div class="box-header print_section">
                @include('common.logo')
                <h5 class="box-title text-center">Class Batch Semester Wise Fee Collection Report - <span id="hidden_date">{{@format_date('now')}}</span></h5>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title text-primary">@lang('english.class_batch_semester_fee_report')</h6>
                            <hr>
                            
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>@lang('english.description')</th>
                                            <th class="text-end">@lang('english.total_amount')</th>
                                            <th class="text-end">@lang('english.received_amount')</th>
                                            <th class="text-end">@lang('english.balance')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($groupedData as $batch)
                                            <!-- Class and Batch Header Row with Totals -->
                                            <tr class="table-info">
                                                <td><strong>{{ $batch['class_title'] }} - {{ $batch['batch_name'] }}</strong></td>
                                                <td class="text-end">
                                                    <strong><span class="display_currency" data-currency_symbol="true">{{ $batch['batch_total_amount'] }}</span></strong>
                                                </td>
                                                <td class="text-end">
                                                    <strong><span class="display_currency text-success" data-currency_symbol="true">{{ $batch['batch_received_amount'] }}</span></strong>
                                                </td>
                                                <td class="text-end">
                                                    <strong><span class="display_currency text-danger" data-currency_symbol="true">{{ $batch['batch_remaining_balance'] }}</span></strong>
                                                </td>
                                            </tr>
                                            
                                            <!-- Semester Rows -->
                                            @foreach($batch['semesters'] as $semester)
                                                <tr>
                                                    <td class="ps-4">
                                                        {{ $batch['class_title'] }} {{ $batch['batch_name'] }} {{ $semester['semester_title'] }}
                                                    </td>
                                                    <td class="text-end">
                                                        <span class="display_currency" data-currency_symbol="true">{{ $semester['total_amount'] }}</span>
                                                    </td>
                                                    <td class="text-end">
                                                        <span class="display_currency text-success" data-currency_symbol="true">{{ $semester['received_amount'] }}</span>
                                                    </td>
                                                    <td class="text-end">
                                                        <span class="display_currency text-danger" data-currency_symbol="true">{{ $semester['remaining_balance'] }}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            
                                            <!-- Add spacing -->
                                            <tr>
                                                <td colspan="4" style="height: 5px; background-color: #f8f9fa;"></td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">
                                                    <div class="py-5">
                                                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                                        <h5 class="text-muted">@lang('english.no_data_found')</h5>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    
                                    @if(!empty($groupedData))
                                    <tfoot>
                                        <tr class="table-dark">
                                            <th>@lang('english.total')</th>
                                            <th class="text-end">
                                                <span class="display_currency" data-currency_symbol="true">{{ $overallTotals['total_amount'] }}</span>
                                            </th>
                                            <th class="text-end">
                                                <span class="display_currency text-success" data-currency_symbol="true">{{ $overallTotals['received_amount'] }}</span>
                                            </th>
                                            <th class="text-end">
                                                <span class="display_currency text-danger" data-currency_symbol="true">{{ $overallTotals['remaining_balance'] }}</span>
                                            </th>
                                        </tr>
                                    </tfoot>
                                    @endif
                                </table>
                            </div>

                            <div class="d-lg-flex align-items-center mt-4 gap-3 no-print">
                                <div class="ms-auto">
                                    <button type="button" class="btn btn-primary no-print pull-right" id="print_invoice">
                                        <i class="fa fa-print"></i> Print
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@section('javascript')
<script src="{{ asset('/js/apps.js?v=' . $asset_v) }}"></script>
<script src="{{ asset('js/account.js?v=' . $asset_v) }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        // Print functionality
        $('#print_invoice').click(function() {
            window.print();
        });
    });
</script>
@endsection