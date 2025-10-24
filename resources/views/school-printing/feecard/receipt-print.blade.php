<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Receipt</title>
    <style>
        @page {
            margin: 0;
            padding: 0;
            font-size: 10px;
            
        }

        @media print {
            body, html {
                margin: 0;
                padding: 0;
                font-size: 10px;
                font-family: 'Arial', sans-serif;
                color:black;
            }

            .ticket {
                width: 80mm;
                max-width: 80mm;
                margin: auto;
                padding: 10px;
                background: #f9f9f9;
                border: 1px solid #ddd;
                border-radius: 5px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .headings {
                font-size: 14px;
                font-weight: bold;
                text-transform: uppercase;
                text-align: center;
                margin-bottom: 10px;
                color: #333;
            }

            .sub-headings {
                font-size: 12px;
                font-weight: bold;
                color: black;
            }

            .border-top {
                border-top: 1px solid #000;
                margin-top: 10px;
                padding-top: 5px;
            }

            .table-info {
                width: 100%;
                margin-top: 10px;
                border-spacing: 0;
                font-size: 12px;
            }

            .table-info th {
                text-align: left;
                padding: 5px 0;
                color: black;
            }

            .table-info td {
                text-align: right;
                padding: 5px 0;
                color: #000;
            }

            .centered {
                text-align: center;
            }

            .footer {
                text-align: center;
                font-size: 10px;
                margin-top: 15px;
                color: black;
            }

            .highlight {
                color: black;
                font-weight: bold;
            }
        }
    </style>
</head>
<body>
    <div class="ticket">
        <!-- Logo -->
        <div class="centered">
            @include('common.logo_with_height')
        </div>

        <!-- Receipt Header -->
        <p class="headings">Payment Receipt</p>

        <!-- Payment Info -->
        <table class="table-info border-top">
            <tr>
                <th>Payment Ref</th>
                <td><span class="highlight">{{$payment->payment_ref_no}}</span></td>
            </tr>
            <tr>
                <th>Date</th>
                <td><span class="highlight">{{ @format_datetime($payment->paid_on) }}</span></td>
            </tr>
            <tr>
                <th>Student Name</th>
                <td><span class="highlight">{{ $payment->student->first_name }} {{ $payment->student->last_name }} ({{ $payment->student->roll_no }})</span></td>
            </tr>
        </table>

        <!-- Payment Details -->
        <table class="table-info border-top">
            <tr>
                <th class="sub-headings">Paid Amount</th>
                <td class="sub-headings">
                    <span class="highlight display_currency" data-currency_symbol="true">{{$payment->amount}}</span>
                </td>
            </tr>
            <tr>
                <th class="sub-headings">Remaining Amount</th>
                <td class="sub-headings">
                    <span class="highlight display_currency" data-currency_symbol="true">{{$total_due}}</span>
                </td>
            </tr>
        </table>

        <!-- Footer -->
        <p class="footer">Received By: <span class="highlight">{{ Auth::User()->first_name }} {{ Auth::User()->last_name }}</span></p>
    </div>
</body>
</html>
