@extends("admin_layouts.app")
@section('title', __('english.account_book'))
@section('wrapper')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Three Column Cash Book Report</h4>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('cashbook.report') }}" class="mb-4">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="date_from">From Date</label>
                                            <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from', now()->startOfMonth()->format('Y-m-d')) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="date_to">To Date</label>
                                            <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to', now()->format('Y-m-d')) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label> </label>
                                            <button type="submit" class="btn btn-primary form-control">Generate Report</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="box box-solid print_area">
                                <div class="box-header print_section">
                                    @include('common.logo')
                                    <h2 class="box-title text-center">Three Column Cash Book Report</h2>
                                    <p>Period: {{ $dateFrom }} to {{ $dateTo }}</p>
                                </div>
                                @if($accounts->isNotEmpty())
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="cash-book-table">
                                            <thead>
                                                <tr>
                                                    <th rowspan="2" class="align-middle">Date</th>
                                                    <th rowspan="2" class="align-middle">Particulars</th>
                                                    <th rowspan="2" class="align-middle">V/N</th>
                                                    <th rowspan="2" class="align-middle">Discount</th>
                                                    @foreach($accounts as $account)
                                                        <th colspan="3" class="text-center">{{ $account->name }} ({{$account->account_type->name}})</th>
                                                    @endforeach
                                                </tr>
                                                <tr>
                                                    @foreach($accounts as $account)
                                                        <th>Dr</th>
                                                        <th>Cr</th>
                                                        <th>Balance</th>
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Initialize running balances for all accounts -->
                                                @php
                                                    $runningBalances = [];
                                                    foreach($accounts as $account) {
                                                        $runningBalances[$account->id] = $openingBalances[$account->id] ?? 0;
                                                    }
                                                @endphp

                                                <!-- Opening Balance Row -->
                                                <tr class="table-secondary">
                                                    <td>{{ $dateFrom }}</td>
                                                    <td><strong>Opening Balance</strong></td>
                                                    <td></td>
                                                    <td></td>
                                                    @foreach($accounts as $account)
                                                        @php 
                                                            $balance = $openingBalances[$account->id] ?? 0;
                                                            $debitIncreases = $account->account_type->debit_increases;
                                                        @endphp
                                                        @if($debitIncreases == 1)
                                                            <!-- If Debit Increases (1), debit increases the balance -->
                                                            @if($balance >= 0)
                                                                <td class="text-right">{{ number_format($balance, 2) }}</td>
                                                                <td></td>
                                                                <td class="text-right">{{ number_format($balance, 2) }}</td>
                                                            @else
                                                                <td></td>
                                                                <td class="text-right">{{ number_format(abs($balance), 2) }}</td>
                                                                <td class="text-right">{{ number_format($balance, 2) }}</td>
                                                            @endif
                                                        @else
                                                            <!-- If Debit Does Not Increase (0), credit increases the balance -->
                                                            @if($balance >= 0)
                                                                <td class="text-right">{{ number_format($balance, 2) }}</td>
                                                                <td></td>
                                                                <td class="text-right">{{ number_format($balance, 2) }}</td>
                                                            @else
                                                                <td></td>
                                                                <td class="text-right">{{ number_format(abs($balance), 2) }}</td>
                                                                <td class="text-right">{{ number_format($balance, 2) }}</td>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                </tr>

                                                <!-- Transaction Rows -->
                                                @foreach($transactions->sortBy('operation_date') as $transaction)
                                                    <tr>
                                                        <td>{{ date('Y-m-d', strtotime($transaction->operation_date)) }}</td>
                                                        <td>{!! __getPaymentDetails($transaction) !!}</td>
                                                        <td>
                                                            @if($transaction->transaction_payment_id && $transaction->feeTransactionPayment)
                                                                {{ $transaction->feeTransactionPayment->payment_ref_no }}
                                                            @elseif($transaction->expense_transaction_payment_id && $transaction->expenseTransactionPayment)
                                                                {{ $transaction->expenseTransactionPayment->payment_ref_no }}
                                                            @elseif($transaction->hrm_transaction_payment_id && $transaction->hrmTransactionPayment)
                                                                {{ $transaction->hrmTransactionPayment->payment_ref_no }}
                                                            @elseif(!empty($transaction->sub_type))
                                                                {{ $transaction->note }}
                                                            @endif
                                                        </td>
                                                        <td class="text-right">
                                                            @if($transaction->transaction_payment_id && $transaction->feeTransactionPayment && $transaction->feeTransactionPayment->discount_amount > 0)
                                                                {{ number_format($transaction->feeTransactionPayment->discount_amount, 2) }}
                                                            @else
                                                                0.00
                                                            @endif
                                                        </td>

                                                        @foreach($accounts as $account)
                                                            @if($transaction->account_id == $account->id)
                                                                <td class="text-right">
                                                                    @if($transaction->type == 'credit')
                                                                        {{ number_format($transaction->amount, 2) }}
                                                                        @php 
                                                                            // If debit increases, subtract from balance for credit transactions
                                                                            if($transaction->account->account_type->debit_increases) {
                                                                                $runningBalances[$account->id] -= $transaction->amount;
                                                                            } else {
                                                                                $runningBalances[$account->id] += $transaction->amount;
                                                                            }
                                                                        @endphp
                                                                    @endif
                                                                </td>
                                                                <td class="text-right">
                                                                    @if($transaction->type == 'debit')
                                                                        {{ number_format($transaction->amount, 2) }}
                                                                        @php 
                                                                            // If debit increases, add to balance for debit transactions
                                                                            if($transaction->account->account_type->debit_increases) {
                                                                                $runningBalances[$account->id] += $transaction->amount;
                                                                            } else {
                                                                                $runningBalances[$account->id] -= $transaction->amount;
                                                                            }
                                                                        @endphp
                                                                    @endif
                                                                </td>
                                                                <td class="text-right">
                                                                    {{ number_format($runningBalances[$account->id], 2) }}
                                                                </td>
                                                            @else
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                            @endif
                                                        @endforeach
                                                    </tr>
                                                @endforeach

                                                <!-- Closing Balance Row -->
                                                <tr class="table-secondary">
                                                    <td>{{ $dateTo }}</td>
                                                    <td><strong>Closing Balance</strong></td>
                                                    <td></td>
                                                    <td></td>
                                                    @foreach($accounts as $account)
                                                        <td></td>
                                                        <td></td>
                                                        <td class="text-right"><strong>{{ number_format($runningBalances[$account->id] ?? 0, 2) }}</strong></td>
                                                    @endforeach
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <button id="export_pdf" class="btn btn-danger">
                                        <i class="fas fa-file-pdf"></i> Export PDF
                                    </button>
                                    <button id="export_excel" class="btn btn-success">
                                        <i class="fas fa-file-excel"></i> Export Excel
                                    </button>
                                    <button id="print_invoice" class="btn btn-secondary">
                                        <i class="fas fa-print"></i> Print
                                    </button>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    No cash or bank accounts found. Please create accounts first.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
function __getPaymentDetails($transaction)
{
    $details = '';

    try {
        // Handle sub_type transactions
        if (!empty($transaction->sub_type)) {
            $details = __('english.' . $transaction->sub_type);
            
            if ($transaction->sub_type === 'fund_transfer' && $transaction->transfer_transaction) {
                $accountName = $transaction->transfer_transaction->account->name ?? 'Unknown Account';
                $details .= $transaction->type === 'credit' 
                    ? " (" . __('english.from') . ": {$accountName})"
                    : " (" . __('english.to') . ": {$accountName})";
            }
        }

        // Handle expense transactions
        if ($transaction->expense_transaction_payment_id && $transaction->expenseTransactionPayment) {
            $expense = $transaction->expenseTransactionPayment->expense_transaction;
            if ($expense) {
                $details .= '<b>' . __('english.expense_category') . ': ' . 
                           ($expense->expenseCategory->name ?? 'N/A') . '</b><br>';
                $details .= '<b>' . __('english.expense_note') . ': </b>' . 
                           ($expense->additional_notes ?? '') . '<br>';
                $details .= '<b>' . __('english.pay_reference_no') . ': </b>' . 
                           ($expense->ref_no ?? '') . '<br>';
            }
        }

        // Handle HRM transactions
        if ($transaction->hrm_transaction_payment_id && $transaction->hrmTransactionPayment) {
            if (!empty($details)) {
                $details .= '<br>';
            }
            $employeeName = $transaction->hrmTransactionPayment->employee->name ?? 'N/A';
            $details .= '<b>' . __('english.employee') . ': </b>' . 
                       ($transaction->payment_for_pay_roll ?? $employeeName);
        }

        // Handle fee transactions
        if ($transaction->transaction_payment_id && $transaction->feeTransactionPayment) {
            if (!empty($details)) {
                $details .= '<br>';
            }
            $studentName = ($transaction->feeTransactionPayment->student->first_name ?? 'N/A') . ' ' . 
                           ($transaction->feeTransactionPayment->student->last_name ?? '') . 
                           ' (' . ($transaction->feeTransactionPayment->student->roll_no ?? 'N/A') . ')';
            $details .= '<b>' . __('english.student') . ': </b>' . 
                       ($transaction->payment_for ?? $studentName);
        }

        // Add advance payment indicator
        if ($transaction->is_advance == 1) {
            $details .= '<br>(' . __('english.advance_payment') . ')';
        }

        // Return 'N/A' if no details were generated
        return $details ?: 'N/A';
    } catch (\Exception $e) {
        // Log the error if needed: \Log::error($e->getMessage());
        return 'Error retrieving payment details';
    }
}
?>
@endsection

@section('javascript')
<!-- Include required libraries -->
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<style>
    @media print {
        body {
            width: 100%;
            margin: 0;
            padding: 0;
            font-size: 12px;
        }
        
        .print_area {
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        
        #cash-book-table {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            page-break-inside: avoid;
            border-collapse: collapse;
        }
        
        #cash-book-table th,
        #cash-book-table td {
            padding: 3px !important;
            font-size: 10px !important;
            white-space: nowrap;
        }
        
        #cash-book-table thead {
            display: table-header-group;
        }
        
        .table-bordered th,
        .table-bordered td {
            border: 1px solid #000 !important;
        }
        
        @page {
            size: landscape;
            margin: 0.5cm;
        }
        
    }

    .pdf-export {
        font-family: Arial;
    }
    .pdf-header {
        text-align: center;
        margin-bottom: 15px;
    }
    .pdf-title {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 5px;
    }
    .pdf-period {
        font-size: 14px;
        margin-bottom: 10px;
    }
  
</style>

<script>
    $(document).ready(function() {
        // Print functionality remains the same
        $('#print_invoice').on('click', function() {
            var printContents = $('.print_area').html();
            var originalContents = $('body').html();
            
            var printWindow = window.open('', '', 'height=600,width=1000');
            printWindow.document.write('<html><head><title>Three Column Cash Book Report</title>');
            printWindow.document.write('<style>' + 
                'body { font-family: Arial; font-size: 12px; } ' +
                'table { width: 100%; border-collapse: collapse; } ' +
                'th, td { padding: 3px; border: 1px solid #000; font-size: 10px; } ' +
                '@page { size: landscape; margin: 0.5cm; } ' +
                '</style>');
            printWindow.document.write('</head><body>');
            printWindow.document.write(printContents);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            
            setTimeout(function() {
                printWindow.print();
                printWindow.close();
            }, 500);
        });

        // Excel export remains the same
        $('#export_excel').on('click', function() {
            var wb = XLSX.utils.book_new();
            var table = document.getElementById('cash-book-table');
            var ws = XLSX.utils.table_to_sheet(table);
            XLSX.utils.book_append_sheet(wb, ws, "Cash Book");
            XLSX.writeFile(wb, 'CashBook_{{ $dateFrom }}_to_{{ $dateTo }}.xlsx');
        });

        // Enhanced PDF export with header
        $('#export_pdf').on('click', function() {
            // Clone the elements we want to include
            var header = $('.print_section').clone();
            var table = $('#cash-book-table').clone();
            
            // Create a container for PDF export
            var pdfContainer = document.createElement('div');
            pdfContainer.className = 'pdf-export';
            
            // Create PDF-specific header
            var pdfHeader = document.createElement('div');
            pdfHeader.className = 'pdf-header';
            
            // Add logo if exists
            var logo = header.find('img').clone()[0];
            if (logo) {
                logo.className = 'pdf-logo';
                pdfHeader.appendChild(logo);
            }
            
            // Add title
            var title = document.createElement('div');
            title.className = 'pdf-title';
            title.innerHTML = 'Three Column Cash Book Report';
            pdfHeader.appendChild(title);
            
            // Add period
            var period = document.createElement('div');
            period.className = 'pdf-period';
            period.innerHTML = 'Period: {{ $dateFrom }} to {{ $dateTo }}';
            pdfHeader.appendChild(period);
            
            // Add header to container
            pdfContainer.appendChild(pdfHeader);
            
            // Add table to container
            pdfContainer.appendChild(table[0]);
            
            // PDF options
            var opt = {
                margin:       [10, 5, 10, 5],
                filename:     'CashBook_{{ $dateFrom }}_to_{{ $dateTo }}.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { 
                    scale: 2,
                    scrollY: 0,
                    useCORS: true,
                    ignoreElements: function(element) {
                        // Ignore elements that shouldn't be in PDF
                        return $(element).hasClass('no-export');
                    }
                },
                jsPDF:       { 
                    unit: 'mm', 
                    format: 'a3',
                    orientation: 'landscape' 
                }
            };
            
            // Generate PDF
            html2pdf().set(opt).from(pdfContainer).save();
        });
    });
</script>
@endsection