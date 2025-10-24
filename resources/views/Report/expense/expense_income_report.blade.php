@extends("admin_layouts.app")
@section('title', __('english.expense_income_report'))
@section("style")
<link href="{{ asset('assets/plugins/vectormap/jquery-jvectormap-2.0.2.css')}}" rel="stylesheet" />
@endsection

@section('wrapper')
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        {!! Form::open(['url' => action('Report\ReportController@getExpenseAndIncomeReport'), 'method' => 'get' ]) !!}

        <div class="card">
            <div class="card-body">
                <h6 class="card-title text-primary">@lang('english.select_ground')</h6>
                <hr>
                <div class="row m-0">
                    <div class="col-md-4">
                        {!! Form::label('english.student', __('english.campuses') . ':*') !!}
                        {!! Form::select('campus_id', $campuses, null, ['class' => 'form-select select2 global-campuses', 'id' => 'students_list_filter_campus_id', 'style' => 'width:100%', 'placeholder' => __('english.all')]) !!}
                    </div>
                    <div class="col-md-4">
                        {!! Form::label('english.category', __('english.category') . ':*') !!}
                        {!! Form::select('category', $categories, null, ['placeholder' => __('english.all'), 'class' => 'form-control select2', 'style' => 'width:100%', 'id' => 'category_id']) !!}
                    </div>
                    <div class="col-md-4">
                        {!! Form::label('transaction_date_range', __('english.date_range') . ':') !!}
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text" id="addon-wrapping"><i class="fa fa-calendar"></i></span>
                            {!! Form::text('list_filter_date_range', null, ['class' => 'form-control', 'id'=>'list_filter_date_range','readonly', 'placeholder' => __('english.date_range')]) !!}
                        </div>
                    </div>
                </div>
                
                <div class="d-lg-flex align-items-center mt-4 gap-3">
                    <div class="ms-auto">
                        <button class="btn btn-primary radius-30 mt-2 mt-lg-0" type="submit">
                            <i class="fas fa-filter"></i>@lang('english.filter')
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Expense and Income Report Table -->
              <div class="box box-solid print_area">
                      <div class="box-header print_section">
                              @include('common.logo')

                          <h5 class="box-title text-center"> Expense and Income Report - <span id="hidden_date">{{@format_date('now')}}</span></h5>

                      </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title text-primary">@lang('english.expense_income_report')</h6>
                        <hr>
                        
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>@lang('english.month')</th>
                                        <th class="text-end">@lang('english.income')</th>
                                        <th class="text-end">@lang('english.expenses')</th>
                                        <th class="text-end">@lang('english.hrm')</th>
                                        <th class="text-end">@lang('english.total_cost')</th>
                                        <th class="text-end">@lang('english.net_profit')</th>
                                        <th class="text-center no-print">@lang('english.action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalIncome = 0;
                                        $totalExpenses = 0;
                                        $totalHrm = 0;
                                        $totalCost = 0;
                                        $totalProfit = 0;
                                    @endphp
                                    
                                    @foreach($monthlyData as $monthKey => $monthData)
                                        @php
                                            $totalIncome += $monthData['total_income'];
                                            $totalExpenses += $monthData['total_expenses'];
                                            $totalHrm += $monthData['hrm_total'];
                                            $totalCost += $monthData['total_cost'];
                                            $totalProfit += $monthData['net_profit'];
                                        @endphp
                                        <tr>
                                            <td>{{ $monthData['month_name'] }}</td>
                                            <td class="text-end">
                                                <span class="display_currency text-success" data-currency_symbol="true">{{ $monthData['total_income'] }}</span>
                                            </td>
                                            <td class="text-end">
                                                <span class="display_currency text-danger" data-currency_symbol="true">{{ $monthData['total_expenses'] }}</span>
                                            </td>
                                            <td class="text-end">
                                                <span class="display_currency text-warning" data-currency_symbol="true">{{ $monthData['hrm_total'] }}</span>
                                            </td>
                                            <td class="text-end">
                                                <span class="display_currency text-danger" data-currency_symbol="true">{{ $monthData['total_cost'] }}</span>
                                            </td>
                                            <td class="text-end">
                                                <span class="display_currency {{ $monthData['net_profit'] >= 0 ? 'text-success' : 'text-danger' }}" data-currency_symbol="true">{{ $monthData['net_profit'] }}</span>
                                            </td>
                                            <td class="text-center no-print">
                                                @if($monthData['total_income'] > 0 || $monthData['total_cost'] > 0)
                                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                                            onclick="showMonthDetails('{{ $monthKey }}', '{{ $monthData['month_name'] }}')">
                                                        <i class="fas fa-eye"></i> @lang('english.view_details')
                                                    </button>
                                                @else
                                                    <span class="text-muted">@lang('english.no_data')</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="table-dark">
                                        <th>@lang('english.total')</th>
                                        <th class="text-end">
                                            <span class="display_currency text-success" data-currency_symbol="true">{{ $totalIncome }}</span>
                                        </th>
                                        <th class="text-end">
                                            <span class="display_currency text-danger" data-currency_symbol="true">{{ $totalExpenses }}</span>
                                        </th>
                                        <th class="text-end">
                                            <span class="display_currency text-warning" data-currency_symbol="true">{{ $totalHrm }}</span>
                                        </th>
                                        <th class="text-end">
                                            <span class="display_currency text-danger" data-currency_symbol="true">{{ $totalCost }}</span>
                                        </th>
                                        <th class="text-end">
                                            <strong><span class="display_currency {{ $totalProfit >= 0 ? 'text-success' : 'text-danger' }}" data-currency_symbol="true">{{ $totalProfit }}</span></strong>
                                        </th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        @if(empty($monthlyData))
                            <div class="text-center py-5 no-print">
                                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">@lang('english.no_data_found')</h5>
                                <p class="text-muted">@lang('english.try_different_filters')</p>
                            </div>
                        @endif

                        <div class="d-lg-flex align-items-center mt-4 gap-3 no-print">
                            <div class="ms-auto">
                              <button type="button" class="btn btn-primary no-print pull-right" id="print_invoice">
                                  <i class="fa fa-print"></i> Print</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
   </div>
   </div>
        <!-- Month Details Modal -->
        <div class="modal fade" id="monthDetailsModal" tabindex="-1" aria-labelledby="monthDetailsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="monthDetailsModalLabel">@lang('english.month_details')</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="monthDetailsContent">
                            <!-- Details will be loaded here -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('english.close')</button>
                    </div>
                </div>
            </div>
        </div>

        {{ Form::close() }}
    </div>
</div>

@endsection

@section('javascript')
<script src="{{ asset('/js/apps.js?v=' . $asset_v) }}"></script>
    <script src="{{ asset('js/account.js?v=' . $asset_v) }}"></script>
<script type="text/javascript">
    // Month details data
    var monthlyData = @json($monthlyData);

    function showMonthDetails(monthKey, monthName) {
        var monthData = monthlyData[monthKey];
        
        if (!monthData) {
            return;
        }

        var detailsHtml = '<h6>' + monthName + ' - @lang("english.financial_breakdown")</h6>';
        
        // Income Section
        detailsHtml += '<div class="row mb-3">';
        detailsHtml += '<div class="col-md-6">';
        detailsHtml += '<div class="card border-success">';
        detailsHtml += '<div class="card-header bg-success text-white"><h6 class="mb-0">@lang("english.income")</h6></div>';
        detailsHtml += '<div class="card-body">';
        detailsHtml += '<table class="table table-sm">';
        detailsHtml += '<tr><td>@lang("english.fee_collection")</td><td class="text-end"><span class="display_currency" data-currency_symbol="true">' + monthData.total_income + '</span></td></tr>';
        detailsHtml += '<tr class="table-success"><th>@lang("english.total_income")</th><th class="text-end"><span class="display_currency" data-currency_symbol="true">' + monthData.total_income + '</span></th></tr>';
        detailsHtml += '</table>';
        detailsHtml += '</div></div></div>';

        // Expenses Section
        detailsHtml += '<div class="col-md-6">';
        detailsHtml += '<div class="card border-danger">';
        detailsHtml += '<div class="card-header bg-danger text-white"><h6 class="mb-0">@lang("english.expenses")</h6></div>';
        detailsHtml += '<div class="card-body">';
        detailsHtml += '<table class="table table-sm">';

        // Add expense categories
        for (var category in monthData.expenses) {
            if (monthData.expenses[category] > 0) {
                detailsHtml += '<tr>';
                detailsHtml += '<td>' + category + '</td>';
                detailsHtml += '<td class="text-end"><span class="display_currency" data-currency_symbol="true">' + monthData.expenses[category] + '</span></td>';
                detailsHtml += '</tr>';
            }
        }

        // Add HRM if exists
        if (monthData.hrm_total > 0) {
            detailsHtml += '<tr class="table-warning">';
            detailsHtml += '<td><strong>@lang("english.hrm")</strong></td>';
            detailsHtml += '<td class="text-end"><strong><span class="display_currency" data-currency_symbol="true">' + monthData.hrm_total + '</span></strong></td>';
            detailsHtml += '</tr>';
        }

        detailsHtml += '<tr class="table-danger"><th>@lang("english.total_cost")</th><th class="text-end"><span class="display_currency" data-currency_symbol="true">' + monthData.total_cost + '</span></th></tr>';
        detailsHtml += '</table>';
        detailsHtml += '</div></div></div></div>';

        // Summary Section
        detailsHtml += '<div class="card border-primary mt-3">';
        detailsHtml += '<div class="card-header bg-primary text-white"><h6 class="mb-0">@lang("english.summary")</h6></div>';
        detailsHtml += '<div class="card-body">';
        detailsHtml += '<table class="table table-sm">';
        detailsHtml += '<tr><td>@lang("english.total_income")</td><td class="text-end text-success"><span class="display_currency" data-currency_symbol="true">' + monthData.total_income + '</span></td></tr>';
        detailsHtml += '<tr><td>@lang("english.total_cost")</td><td class="text-end text-danger"><span class="display_currency" data-currency_symbol="true">' + monthData.total_cost + '</span></td></tr>';
        
        var profitClass = monthData.net_profit >= 0 ? 'text-success' : 'text-danger';
        var profitLabel = monthData.net_profit >= 0 ? '@lang("english.net_profit")' : '@lang("english.net_loss")';
        detailsHtml += '<tr class="table-light"><th>' + profitLabel + '</th><th class="text-end ' + profitClass + '"><span class="display_currency" data-currency_symbol="true">' + monthData.net_profit + '</span></th></tr>';
        detailsHtml += '</table>';
        detailsHtml += '</div></div>';

        $('#monthDetailsContent').html(detailsHtml);
        $('#monthDetailsModal').modal('show');
    }

    // Print functionality
 
</script>
@endsection