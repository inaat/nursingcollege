<!DOCTYPE html>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@lang('english.fee_card')</title>
<style>
    @page {
        margin: 0px;
        padding: 3px;
        font-size: 12px;
        font-weight: 700;
    }

    @import url('https://fonts.googleapis.com/css?family=Roboto');

    @media print {
        .pace-progress {
            display: none;
        }

        * {
            margin: 0px;
            padding: 0px;
            color: #000;
            page-break-inside: avoid;
            font-family: 'Roboto', sans-serif;
        }

        td>strong {
            font-size: 18px;
            color: black;
        }

        @page {
            size: A4;
            -webkit-print-color-adjust: exact;
            page-break-inside: avoid;

            margin: 15px !important;
            padding: 10px !important;
            width: 100%;
            height: 100%;
        }

        .flex_container {
            display: flex;
            width: 100%;
            zoom: 98%;
            border-bottom: 1px solid #000;
            page-break-inside: avoid;
        }

        .flex_item_left {
            width: 100%;
            padding: 5px;
            overflow: hidden;
            border-right: 2px solid #000;
        }

        .flex_item_right {
            width: 100%;
            overflow: hidden;
            padding: 5px;
        }

        #head {
            width: 100%;
            text-align: center;
            padding: 3px;
            margin: 0px auto;
            border-radius: 5px;
            height: 25px;
            border-bottom: 1px solid #000;
        }

        #head>h6 {
            color: #000;
            -webkit-print-color-adjust: exact;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2px solid black;
            color: #000;
        }

        td,
        th {
            padding: 5px;
            border: 1px solid black;
            text-align: center;
            font-size: 12px;
            color: black;
        }
    }
</style>

<body style="color:#000;background-color:#ffff;">

    <div class="paided" style="border:1px solid black; margin-top:10px;">

        <div class="flex_container">
            <div class="flex_item_left">
                @include('common.logo')
            </div>
        </div>
        <div class="flex_container">
            <div class="flex_item_left">
                <div id="head">
                    <h6>Expense Slip</h6>
                </div>
                <div class="info" style="border: 1px solid #000; padding:1px; ">

                    <div class="info_left">
                        <strong>@lang('english.challan_no'):</strong>
                        <strong>{{ ucwords($transaction->ref_no ?? 'N/A') }}</strong>

                        <span style="float:right;margin-left:30px"><strong>@lang('english.date'):</strong>
                            <strong>{{ @format_date($transaction->transaction_date ?? now()) }}</strong>
                        </span><br>

                        <strong>@lang('english.category_name'):</strong>
                        <strong>{{ ucwords(optional($transaction->expenseCategory)->name ?? 'N/A') }}</strong>

                        <span style="float:right;margin-left:30px"><strong>@lang('english.employee_name'):</strong>
                            <strong>{{ ucwords(optional($transaction->employee)->first_name . ' ' . optional($transaction->employee)->last_name ?? 'N/A') }}</strong>
                        </span><br>

                        <strong>@lang('english.created_by'):</strong>
                        <strong>{{ ucwords(optional($transaction->create_person)->first_name . ' ' . optional($transaction->create_person)->last_name ?? 'N/A') }}</strong>

                        <span style="float:right;margin-left:30px"><strong>@lang('english.vehicle'):</strong>
                            <strong>{{ ucwords(optional($transaction->vehicle)->name ?? 'N/A') }}</strong>
                        </span><br>
                    </div>
                </div>
                  <table class="totaltable table table-striped table-responsive">
            <tbody>
                <tr>
                    <th width="20%">Total Amount</th>
                    <td class="text-right">@format_currency($transaction->final_total) </td>
                </tr>

             
             
                <tr>
                    <th width="20%">Total Paid</th>
                    <td class="text-right">@format_currency($total_paid)  </td>
                </tr>
                <tr>
                    <th width="20%">Total Due</th>
                    <td class="text-right">@format_currency($transaction->final_total-$total_paid)  </td>
                </tr>
            

            </tbody>
        </table>

                <p><br><strong>@lang('english.note')</strong>
                    <strong>{{ ucwords($transaction->additional_notes) }}</strong>
                </p>
                <p>
    <strong>@lang('Cash Received By')</strong>
    <strong>________________</strong>
    <span style="margin-left: 50px;"></span> <!-- Adds spacing between the two sections -->
    <strong>@lang('Finance Officer ')</strong>
    <strong>_______________</strong>
    <strong>@lang('Approved By')</strong>
    <strong>_______________</strong>
</p>

            </div>
        </div>
    </div>

</body>

</html>
