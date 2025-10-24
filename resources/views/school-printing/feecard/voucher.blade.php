<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        .container {
            display: flex;
            justify-content: space-between;
            width: 100%;
            min-height: 100vh; /* Ensure container takes full viewport height */
        }
        .slip {
            width: 33%;
            padding: 20px;
            box-sizing: border-box;
            border-right: 1px dashed #000;
            position: relative;
            display: flex; /* Use flex to control internal layout */
            flex-direction: column; /* Stack content vertically */
            justify-content: space-between; /* Spread content to use full height */
        }
        .slip:last-child {
            border-right: none;
        }
        .header {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .college-name {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .bank-name {
            font-size: 20px;
            margin-bottom: 30px;
        }
        .copy-type {
            font-size: 20px;
            margin-bottom: 20px;
        }
        .slip-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .account-info {
            text-align: center;
            margin-bottom: 5px;
            white-space: nowrap;
            font-weight: bold;
        }
        .field-row {
            display: flex;
            margin-bottom: 20px;
        }
        .field-label {
            min-width: 150px;
            font-weight: bold;
        }
        .field-value {
            flex-grow: 1;
            border-bottom: 1px solid #000;
            padding-left: 10px;
        }
        .logo-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .bank-logo {
            width: 100px;
            height: 80px;
            background-color: #00428c;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            font-weight: bold;
        }
        .college-logo {
            width: 100px;
            height: 100px;
            background-color: #fff;
           /* border: 2px solid #ffc107;*/
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 50%;
        }
        .slip-copy {
            text-align: center;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .signatures {
            margin-top: auto; /* Push signatures to the bottom */
        }
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 60px;
            margin-bottom: 10px;
        }
        .signature-label {
            font-size: 14px;
        }
        @media print {
            @page {
                margin: 0;
                size: A4 landscape; /* Retained landscape orientation */
            }
            html {
                zoom: 80%;
            }
        }
    </style>
</head>
<body>
    @php
        $nf = new NumberFormatter('eng', NumberFormatter::SPELLOUT);
    @endphp
    @foreach ($student as $std)
    <div class="container">
        <!-- Bank Copy -->
        <div class="slip">
            <div class="header">
                <div class="college-name">Bab-e- Khyber College of Nursing <br>&<br>Health Science Khwaza Khela Swat</div>
                <div class="bank-name">Bank of Khyber Khwaza Khela</div>
            </div>
            <div class="logo-container">
                <div class="bank-logo">
                    <img src="https://bok.rozee.pk/i/bok/fblogo.gif?2025032912">
                </div>
                <div class="account-info">{{ $std['account']->name ?? 'Current A/C' }} : {{ $std['account']->account_number ?? '3004698745' }}<br>
                    <span class="slip-copy">(Bank Copy)</span>
                </div>
                <div class="college-logo">
                    <img src="{{ url('/uploads/business_logos/' . session()->get('system_details.org_logo')) }}">
                </div>
            </div>
            <div class="slip-info">
                <div>Slip No: {{ ucwords($std['current_transaction']->voucher_no ?? $std['current_transaction']->student->roll_no) }}</div>
                <div>Due Date: {{ $std['current_transaction']->due_date ? @format_date($std['current_transaction']->due_date) : @format_date($std['current_transaction']->transaction_date) }}</div>
            </div>
            <div class="field-row">
                <div class="field-label">Name:</div>
                <div class="field-value">{{ ucwords($std['current_transaction']->student->first_name . ' ' . $std['current_transaction']->student->last_name) }}</div>
            </div>
            <div class="field-row">
                <div class="field-label">Father's Name:</div>
                <div class="field-value">{{ ucwords($std['current_transaction']->student->father_name) }}</div>
            </div>
            <div class="field-row">
                <div class="field-label">Class No:</div>
                <div class="field-value">{{ $std['current_transaction']->student_class->title }} {{$std['current_transaction']->student->batch->title}} {{$std['current_transaction']->student->semester->title}}</div>
            </div>
            <div class="field-row">
                <div class="field-label">Purpose of Deposit:</div>
                <div class="field-value">SEMESTER FEE (DUES)
                    <ul style="margin: 5px 0; padding-left: 15px;">
                        @if($std['admission_fee'] > 0)
                            <li>Admission Fee: Rs {{$std['admission_fee']}}</li>
                        @endif
                        @if($std['tuition_fee'] > 0)
                            <li>Tuition Fee: Rs {{$std['tuition_fee']}}</li>
                        @endif
                        @if($std['library_fee'] > 0)
                            <li>Library Fee: Rs {{$std['library_fee']}}</li>
                        @endif
                        @if($std['activity_fee'] > 0)
                            <li>Activity Fee: Rs {{$std['activity_fee']}}</li>
                        @endif
                        @if($std['exam_fee'] > 0)
                            <li>Exam Fee: Rs {{$std['exam_fee']}}</li>
                        @endif
                        @foreach ($std['miscellaneous_detail'] as $detail)
                          <li>{{ $detail }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="field-row">
                <div class="field-label">Semester/Year:</div>
                <div class="field-value">{{ $std['current_transaction']->session->title }}</div>
            </div>
            <div class="field-row">
                <div class="field-label">Contact No:</div>
                <div class="field-value">{{ ucwords($std['current_transaction']->student->mobile_no) }}</div>
            </div>
            <div class="field-row">
                <div class="field-label">Amount Payable Rs:</div>
                <div class="field-value">{{number_format($std['total_due'],2)}}/=</div>
            </div>
            <div class="field-row">
                <div class="field-label">In Words Rs:</div>
                <div class="field-value">{{ strtoupper(str_replace(' ', '-', $nf->format($std['total_due']))) }}</div>
            </div>
            <div class="signatures">
                <div>Paid Date</div>
                <div class="signature-line"></div>
                <div>Depositor Signature</div>
                <div class="signature-line"></div>
                <div>Bank Authorized Signature with Stamp</div>
                <div class="signature-line"></div>
            </div>
        </div>
        <!-- Institute Copy -->
        <div class="slip">
            <div class="header">
                <div class="college-name">Bab-e- Khyber College of Nursing & Health<br>Science Khwaza Khela Swat</div>
                <div class="bank-name">Bank of Khyber Khwaza Khela</div>
            </div>
            <div class="logo-container">
                <div class="bank-logo">
                    <img src="https://bok.rozee.pk/i/bok/fblogo.gif?2025032912">
                </div>
                <div class="account-info">{{ $std['account']->name ?? 'Current A/C' }} : {{ $std['account']->account_number ?? '3004698745' }}<br>
                    <span class="slip-copy">(Institute Copy)</span>
                </div>
                <div class="college-logo">
                    <img src="{{ url('/uploads/business_logos/' . session()->get('system_details.org_logo')) }}">
                </div>
            </div>
            <div class="slip-info">
                <div>Slip No: {{ ucwords($std['current_transaction']->voucher_no ?? $std['current_transaction']->student->roll_no) }}</div>
                <div>Due Date: {{ @format_date($std['current_transaction']->transaction_date) }}</div>
            </div>
            <div class="field-row">
                <div class="field-label">Name:</div>
                <div class="field-value">{{ ucwords($std['current_transaction']->student->first_name . ' ' . $std['current_transaction']->student->last_name) }}</div>
            </div>
            <div class="field-row">
                <div class="field-label">Father's Name:</div>
                <div class="field-value">{{ ucwords($std['current_transaction']->student->father_name) }}</div>
            </div>
            <div class="field-row">
                <div class="field-label">Class No:</div>
                <div class="field-value">{{ $std['current_transaction']->student_class->title }} {{$std['current_transaction']->student->batch->title}} {{$std['current_transaction']->student->semester->title}}</div>
            </div>
            <div class="field-row">
                <div class="field-label">Purpose of Deposit:</div>
                <div class="field-value">SEMESTER FEE (DUES)
                    <ul style="margin: 5px 0; padding-left: 15px;">
                        @if($std['admission_fee'] > 0)
                            <li>Admission Fee: Rs {{$std['admission_fee']}}</li>
                        @endif
                        @if($std['tuition_fee'] > 0)
                            <li>Tuition Fee: Rs {{$std['tuition_fee']}}</li>
                        @endif
                        @if($std['library_fee'] > 0)
                            <li>Library Fee: Rs {{$std['library_fee']}}</li>
                        @endif
                        @if($std['activity_fee'] > 0)
                            <li>Activity Fee: Rs {{$std['activity_fee']}}</li>
                        @endif
                        @if($std['exam_fee'] > 0)
                            <li>Exam Fee: Rs {{$std['exam_fee']}}</li>
                        @endif
                        @foreach ($std['miscellaneous_detail'] as $detail)
                          <li>{{ $detail }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="field-row">
                <div class="field-label">Semester/Year:</div>
                <div class="field-value">{{ $std['current_transaction']->session->title }}</div>
            </div>
            <div class="field-row">
                <div class="field-label">Contact No:</div>
                <div class="field-value">{{ ucwords($std['current_transaction']->student->mobile_no) }}</div>
            </div>
            <div class="field-row">
                <div class="field-label">Amount Payable Rs:</div>
                <div class="field-value">{{number_format($std['total_due'],2)}}/=</div>
            </div>
            <div class="field-row">
                <div class="field-label">In Words Rs:</div>
                <div class="field-value">{{ strtoupper(str_replace(' ', '-', $nf->format($std['total_due']))) }}</div>
            </div>
            <div class="signatures">
                <div>Paid Date</div>
                <div class="signature-line"></div>
                <div>Depositor Signature</div>
                <div class="signature-line"></div>
                <div>Bank Authorized Signature with Stamp</div>
                <div class="signature-line"></div>
            </div>
        </div>
        <!-- Student's Copy -->
        <div class="slip">
            <div class="header">
                <div class="college-name">Bab-e- Khyber College of Nursing & Health<br>Science Khwaza Khela Swat</div>
                <div class="bank-name">Bank of Khyber Khwaza Khela</div>
            </div>
            <div class="logo-container">
                <div class="bank-logo">
                    <img src="https://bok.rozee.pk/i/bok/fblogo.gif?2025032912">
                </div>
                <div class="account-info">{{ $std['account']->name ?? 'Current A/C' }} : {{ $std['account']->account_number ?? '3004698745' }}<br>
                    <span class="slip-copy">(Student's Copy)</span>
                </div>
                <div class="college-logo">
                    <img src="{{ url('/uploads/business_logos/' . session()->get('system_details.org_logo')) }}">
                </div>
            </div>
            <div class="slip-info">
                <div>Slip No: {{ ucwords($std['current_transaction']->voucher_no ?? $std['current_transaction']->student->roll_no) }}</div>
                <div>Due Date: {{ @format_date($std['current_transaction']->transaction_date) }}</div>
            </div>
            <div class="field-row">
                <div class="field-label">Name:</div>
                <div class="field-value">{{ ucwords($std['current_transaction']->student->first_name . ' ' . $std['current_transaction']->student->last_name) }}</div>
            </div>
            <div class="field-row">
                <div class="field-label">Father's Name:</div>
                <div class="field-value">{{ ucwords($std['current_transaction']->student->father_name) }}</div>
            </div>
            <div class="field-row">
                <div class="field-label">Class No:</div>
                <div class="field-value">{{ $std['current_transaction']->student_class->title }} {{$std['current_transaction']->student->batch->title}} {{$std['current_transaction']->student->semester->title}}</div>
            </div>
            <div class="field-row">
                <div class="field-label">Purpose of Deposit:</div>
                <div class="field-value">SEMESTER FEE (DUES)
                    <ul style="margin: 5px 0; padding-left: 15px;">
                        @if($std['admission_fee'] > 0)
                            <li>Admission Fee: Rs {{$std['admission_fee']}}</li>
                        @endif
                        @if($std['tuition_fee'] > 0)
                            <li>Tuition Fee: Rs {{$std['tuition_fee']}}</li>
                        @endif
                        @if($std['library_fee'] > 0)
                            <li>Library Fee: Rs {{$std['library_fee']}}</li>
                        @endif
                        @if($std['activity_fee'] > 0)
                            <li>Activity Fee: Rs {{$std['activity_fee']}}</li>
                        @endif
                        @if($std['exam_fee'] > 0)
                            <li>Exam Fee: Rs {{$std['exam_fee']}}</li>
                        @endif
                        @foreach ($std['miscellaneous_detail'] as $detail)
                          <li>{{ $detail }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="field-row">
                <div class="field-label">Semester/Year:</div>
                <div class="field-value">{{ $std['current_transaction']->session->title }}</div>
            </div>
            <div class="field-row">
                <div class="field-label">Contact No:</div>
                <div class="field-value">{{ ucwords($std['current_transaction']->student->mobile_no) }}</div>
            </div>
            <div class="field-row">
                <div class="field-label">Amount Payable Rs:</div>
                <div class="field-value">{{number_format($std['total_due'],2)}}/=</div>
            </div>
            <div class="field-row">
                <div class="field-label">In Words Rs:</div>
                <div class="field-value">{{ strtoupper(str_replace(' ', '-', $nf->format($std['total_due']))) }}</div>
            </div>
            <div class="signatures">
                <div>Paid Date</div>
                <div class="signature-line"></div>
                <div>Depositor Signature</div>
                <div class="signature-line"></div>
                <div>Bank Authorized Signature with Stamp</div>
                <div class="signature-line"></div>
            </div>
        </div>
    </div>
    @endforeach
</body>
</html>