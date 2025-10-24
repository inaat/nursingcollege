<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>UMT Fee Card</title>
  <style>
    @media print {
      @page {
        margin: 0;
        size: A4;
      }

      body {
        font-family: Arial, sans-serif;
        margin: 5px;
        padding: 0;
        color: #000;
        background-color: #fff;
        font-size: 16px; /* Reduced font size */
      }

      .card-container {
        display: flex;
        flex-direction: column;
        width: 100%;
        margin: 0 auto;
        padding: 5px; /* Reduced padding */
      }

      .card {
        border: 1px solid #000;
        margin-bottom: 0px; /* Reduced margin */
        padding: 5px; /* Reduced padding */
        page-break-inside: avoid; /* Prevent splitting cards across pages */
      }

      .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 5px; /* Reduced margin */
      }

      .logo {
        width: 70px; /* Reduced size */
        height: 70px; /* Reduced size */
        justify-content: center;
        align-items: center;
        font-weight: bold;
      }
      .logo img {
        width: 100%;
        height: 100%;
        object-fit: contain; /* Or 'cover' if you want it to fill completely */
        border-radius: 50%; /* Optional: If you want the image itself to be circular */
      }
      .title {
        flex-grow: 1;
        text-align: center;
      }

      .title h2 {
        color: #0000aa;
        margin: 0;
        font-size: 14px; /* Reduced font size */
      }

      .title h3 {
        color: #0000aa;
        margin: 2px 0;
        font-size: 12px; /* Reduced font size */
      }
      .title h4 {
        color: #0000aa;
        margin: 2px 0;
        font-size: 8px; /* Reduced font size */
      }

      .card-details {
        display: flex;
        margin-bottom: 5px; /* Reduced margin */
        font-size: 10px; /* Reduced font size */
      }

      table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 5px; /* Reduced margin */
        font-size: 12px; /* Reduced font size */
      }

      td {
        padding: 2px; /* Reduced padding */
        border: 1px solid #000;
      }

      .total-row {
        font-weight: bold;
      }

      .amount-in-words {
        margin: 5px 0; /* Reduced margin */
        font-size: 8px; /* Reduced font size */
      }

      .card-footer {
        display: flex;
        justify-content: space-between;
        font-size: 8px; /* Reduced font size */
        margin-top: 5px; /* Reduced margin */
      }

      .note {
        font-size: 6px; /* Reduced font size */
        font-style: italic;
        margin-top: 2px; /* Reduced margin */
      }
      .divider {
        border-top: 1px dashed #000;
        margin: 5px 0;
      }

      /* Beautified Section Styles */
      .note-section {
        margin: 5px 0;
        padding: 5px;
        background-color: #f5f5f5;
        border-left: 4px solid #0000aa;
        border-radius: 3px;
      }

      .note-section p {
        margin: 0;
        font-size: 8px;
        color: #333;
      }

      .note-section strong {
        color: #0000aa;
        font-weight: bold;
      }

      .signature-section {
        display: flex;
        justify-content: space-between;
        margin: 5px 0;
        padding: 5px;
        border-top: 1px solid #ccc;
        border-bottom: 1px solid #ccc;
      }

      .depositor, .authorized {
        text-align: center;
        width: 45%;
      }

      .depositor p, .authorized p {
        margin: 2px 0;
        font-size: 8px;
        color: #333;
      }

      .depositor p:first-child, .authorized p:first-child {
        font-weight: bold;
        color: #0000aa;
        font-size: 9px;
      }

      .footer-note {
        margin-top: 5px;
        padding: 5px;
        background-color: #fff;
        border: 1px dashed #0000aa;
        border-radius: 3px;
      }

      .footer-note p {
        margin: 2px 0;
        font-size: 6px;
        color: #555;
      }

      .footer-note strong {
        color: #0000aa;
      }

      .footer-note p:last-child {
        text-align: right;
        font-style: italic;
        color: #0000aa;
      }
    }
  </style>
</head>
<body>
@foreach ($student as $std)
  <div class="card-container">
    <!-- Participant Copy -->
    <div class="card">
      <div class="card-header">
        <div class="logo">
          <img src="{{ url('/uploads/business_logos/'.session()->get('system_details.org_logo')) }}" >
        </div>
        <div class="title">
          <h2>{{ session()->get("system_details.org_name") }}</h2>
          <h4>{{ session()->get("system_details.org_address") }}: {{ session()->get("system_details.org_contact_number") }}</h4>
          <h3>Fee Card (Participant Copy)</h3>
        </div>
      </div>
      <table>
        <tr>
          <td colspan="6" style="text-align: center; font-weight: bold;">
            PAYABLE AT ANY BANK OF KHYBER <br>
            Branch: Khwaza Khela Swat. Br. Code 0143 Collection A/C #0020053435860060
          </td>
        </tr>
        <tr>
          <td>@lang('english.student_name'):</td>
          <td>{{ ucwords($std['current_transaction']->student->first_name . ' ' . $std['current_transaction']->student->last_name) }}</td>
          <td>Semester</td>
          <td></td>
          <td>@lang('english.challan_no'):</td>
          <td>{{ ucwords($std['current_transaction']->voucher_no) }}</td>
        </tr>
        <tr>
          <td>Roll NO:</td>
          <td>{{ ucwords($std['current_transaction']->student->roll_no) }}</td>
          <td>Program</td>
          <td>{{ $std['current_transaction']->student_class->title }}</td>
          <td>Issue Date:</td>
          <td>{{ @format_date($std['current_transaction']->transaction_date) }}</td>
        </tr>
        <tr>
          <td colspan="2">Admission Fee</td>
          <td>Rs {{$std['admission_fee']}}</td>
          <td colspan="3">
            <label>
              <input type="checkbox" name="payment_method" value="cash"> Cash
            </label>
          </td>
        </tr>
        <tr>
          <td colspan="2">Admission Fee</td>
          <td>Rs {{$std['admission_fee']}}</td>
          <td colspan="2">Tuition Fee</td>
          <td>Rs {{$std['tuition_fee']}}</td>
          <td colspan="3" style="text-align: center">
            <strong>Due Date: {{ $std['current_transaction']->due_date ? @format_date($std['current_transaction']->due_date) : ' ' }}</strong>
          </td>
        </tr>
        <tr>
          <td colspan="2">Admission Fee</td>
          <td>Rs {{$std['admission_fee']}}</td>
          <td colspan="2">Tuition Fee</td>
          <td>Rs {{$std['tuition_fee']}}</td>
          <td colspan="2">Library Fee</td>
          <td>Rs {{$std['library_fee']}}</td>
          <td rowspan="4" colspan="3" style="padding: 5px;">
            <div style="font-size: 10px; line-height: 1.5; padding: 5px; border: 1px solid #000; border-radius: 5px;">
              <strong>Miscellaneous Charges Details:</strong>
              <ul style="margin: 5px 0; padding-left: 15px;">
                @foreach ($std['miscellaneous_detail'] as $detail)
                  <li>{{ $detail }}</li>
                @endforeach
              </ul>
            </div>
          </td>
        </tr>
        <tr>
          <td colspan="2">Admission Fee</td>
          <td>Rs {{$std['admission_fee']}}</td>
          <td colspan="2">Tuition Fee</td>
          <td>Rs {{$std['tuition_fee']}}</td>
          <td colspan="2">Library Fee</td>
          <td>Rs {{$std['library_fee']}}</td>
          <td colspan="2">Activity Fee</td>
          <td>Rs {{$std['activity_fee']}}</td>
        </tr>
        <tr>
          <td colspan="2">Exam Fee</td>
          <td>Rs {{$std['exam_fee']}}</td>
        </tr>
        <tr>
          <td colspan="2">Miscellaneous Charges Fee</td>
          <td>Rs {{$std['miscellaneous_charges']}}</td>
        </tr>
        <tr>
          <td colspan="2">Total (Till Due Date)</td>
          <td>Rs {{$std['total_due']}}</td>
          <td colspan="3">
            @php
              $nf = new NumberFormatter('eng', NumberFormatter::SPELLOUT);
            @endphp
            {{ strtoupper(str_replace(' ', '-', $nf->format($std['total_due']))) }}
          </td>
        </tr>
      </table>
      <div class="note-section">
        <p><strong>NOTE:</strong> Fine of Rs. 100/- per day will be charged after due date</p>
      </div>
      <div class="signature-section">
        <div class="depositor">
          <p>PREPARED BY</p>
        </div>
        <div class="authorized">
          <p>Depositor Name/Signature & Contact #</p>
        </div>
        <div class="authorized">
          <p>BOK AUTHORIZED SIGNATURE</p>
        </div>
      </div>
      <div class="footer-note">
        <p><strong>Note:</strong> Cash should always be deposited at respective counter and collect electronic computer generated receipt through flatbed printer on deposit slip/challan should be obtained. Please be assured to check the receipt and ensure that complete details and amount deposited are correctly printed, failing which the bank will not be responsible.</p>
        <p>POWERED BY: Explainer Technologies</p>
      </div>
    </div>
    <div class="divider"></div>

    <!-- Bank Copy -->
    <div class="card">
      <div class="card-header">
        <div class="logo">
          <img src="{{ url('/uploads/business_logos/'.session()->get('system_details.org_logo')) }}" >
        </div>
        <div class="title">
          <h2>{{ session()->get("system_details.org_name") }}</h2>
          <h4>{{ session()->get("system_details.org_address") }}: {{ session()->get("system_details.org_contact_number") }}</h4>
          <h3>Fee Card (Bank Copy)</h3>
        </div>
      </div>
      <table>
        <tr>
          <td colspan="6" style="text-align: center; font-weight: bold;">
            PAYABLE AT ANY BANK OF KHYBER <br>
            Branch: Khwaza Khela Swat. Br. Code 0143 Collection A/C #0020053435860060
          </td>
        </tr>
        <tr>
          <td>@lang('english.student_name'):</td>
          <td>{{ ucwords($std['current_transaction']->student->first_name . ' ' . $std['current_transaction']->student->last_name) }}</td>
          <td>Semester</td>
          <td></td>
          <td>@lang('english.challan_no'):</td>
          <td>{{ ucwords($std['current_transaction']->voucher_no) }}</td>
        </tr>
        <tr>
          <td>Roll NO:</td>
          <td>{{ ucwords($std['current_transaction']->student->roll_no) }}</td>
          <td>Program</td>
          <td>{{ $std['current_transaction']->student_class->title }}</td>
          <td>Issue Date:</td>
          <td>{{ @format_date($std['current_transaction']->transaction_date) }}</td>
        </tr>
        <tr>
          <td colspan="2">Admission Fee</td>
          <td>Rs {{$std['admission_fee']}}</td>
          <td colspan="3">
            <label>
              <input type="checkbox" name="payment_method" value="cash"> Cash
            </label>
          </td>
        </tr>
        <tr>
          <td colspan="2">Tuition Fee</td>
          <td>Rs {{$std['tuition_fee']}}</td>
          <td colspan="3" style="text-align: center">
            <strong>Due Date: {{ $std['current_transaction']->due_date ? @format_date($std['current_transaction']->due_date) : ' ' }}</strong>
          </td>
        </tr>
        <tr>
          <td colspan="2">Library Fee</td>
          <td>Rs {{$std['library_fee']}}</td>
          <td rowspan="4" colspan="3" style="padding: 5px;">
            <div style="font-size: 10px; line-height: 1.5; padding: 5px; border: 1px solid #000; border-radius: 5px;">
              <strong>Miscellaneous Charges Details:</strong>
              <ul style="margin: 5px 0; padding-left: 15px;">
                @foreach ($std['miscellaneous_detail'] as $detail)
                  <li>{{ $detail }}</li>
                @endforeach
              </ul>
            </div>
          </td>
        </tr>
        <tr>
          <td colspan="2">Activity Fee</td>
          <td>Rs {{$std['activity_fee']}}</td>
        </tr>
        <tr>
          <td colspan="2">Exam Fee</td>
          <td>Rs {{$std['exam_fee']}}</td>
        </tr>
        <tr>
          <td colspan="2">Miscellaneous Charges Fee</td>
          <td>Rs {{$std['miscellaneous_charges']}}</td>
        </tr>
        <tr>
          <td colspan="2">Total (Till Due Date)</td>
          <td>Rs {{$std['total_due']}}</td>
          <td colspan="3">
            @php
              $nf = new NumberFormatter('eng', NumberFormatter::SPELLOUT);
            @endphp
            {{ strtoupper(str_replace(' ', '-', $nf->format($std['total_due']))) }}
          </td>
        </tr>
      </table>
      <div class="note-section">
        <p><strong>NOTE:</strong> Fine of Rs. 100/- per day will be charged after due date</p>
      </div>
      <div class="signature-section">
        <div class="depositor">
          <p>PREPARED BY</p>
        </div>
        <div class="authorized">
          <p>Depositor Name/Signature & Contact #</p>
        </div>
        <div class="authorized">
          <p>BOK AUTHORIZED SIGNATURE</p>
        </div>
      </div>
      <div class="footer-note">
        <p><strong>Note:</strong> Cash should always be deposited at respective counter and collect electronic computer generated receipt through flatbed printer on deposit slip/challan should be obtained. Please be assured to check the receipt and ensure that complete details and amount deposited are correctly printed, failing which the bank will not be responsible.</p>
        <p>POWERED BY: Explainer Technologies</p>
      </div>
    </div>
    <div class="divider"></div>

    <!-- Account Office Copy -->
    <div class="card">
      <div class="card-header">
        <div class="logo">
          <img src="{{ url('/uploads/business_logos/'.session()->get('system_details.org_logo')) }}" >
        </div>
        <div class="title">
          <h2>{{ session()->get("system_details.org_name") }}</h2>
          <h4>{{ session()->get("system_details.org_address") }}: {{ session()->get("system_details.org_contact_number") }}</h4>
          <h3>Fee Card (Account Office Copy)</h3>
        </div>
      </div>
      <table>
        <tr>
          <td colspan="6" style="text-align: center; font-weight: bold;">
            PAYABLE AT ANY BANK OF KHYBER <br>
            Branch: Khwaza Khela Swat. Br. Code 0143 Collection A/C #0020053435860060
          </td>
        </tr>
        <tr>
          <td>@lang('english.student_name'):</td>
          <td>{{ ucwords($std['current_transaction']->student->first_name . ' ' . $std['current_transaction']->student->last_name) }}</td>
          <td>Semester</td>
          <td></td>
          <td>@lang('english.challan_no'):</td>
          <td>{{ ucwords($std['current_transaction']->voucher_no) }}</td>
        </tr>
        <tr>
          <td>Roll NO:</td>
          <td>{{ ucwords($std['current_transaction']->student->roll_no) }}</td>
          <td>Program</td>
          <td>{{ $std['current_transaction']->student_class->title }}</td>
          <td>Issue Date:</td>
          <td>{{ @format_date($std['current_transaction']->transaction_date) }}</td>
        </tr>
        <tr>
          <td colspan="2">Admission Fee</td>
          <td>Rs {{$std['admission_fee']}}</td>
          <td colspan="3">
            <label>
              <input type="checkbox" name="payment_method" value="cash"> Cash
            </label>
          </td>
        </tr>
        <tr>
          <td colspan="2">Tuition Fee</td>
          <td>Rs {{$std['tuition_fee']}}</td>
          <td colspan="3" style="text-align: center">
            <strong>Due Date: {{ $std['current_transaction']->due_date ? @format_date($std['current_transaction']->due_date) : ' ' }}</strong>
          </td>
        </tr>
        <tr>
          <td colspan="2">Library Fee</td>
          <td>Rs {{$std['library_fee']}}</td>
          <td rowspan="4" colspan="3" style="padding: 5px;">
            <div style="font-size: 10px; line-height: 1.5; padding: 5px; border: 1px solid #000; border-radius: 5px;">
              <strong>Miscellaneous Charges Details:</strong>
              <ul style="margin: 5px 0; padding-left: 15px;">
                @foreach ($std['miscellaneous_detail'] as $detail)
                  <li>{{ $detail }}</li>
                @endforeach
              </ul>
            </div>
          </td>
        </tr>
        <tr>
          <td colspan="2">Activity Fee</td>
          <td>Rs {{$std['activity_fee']}}</td>
        </tr>
        <tr>
          <td colspan="2">Exam Fee</td>
          <td>Rs {{$std['exam_fee']}}</td>
        </tr>
        <tr>
          <td colspan="2">Miscellaneous Charges Fee</td>
          <td>Rs {{$std['miscellaneous_charges']}}</td>
        </tr>
        <tr>
          <td colspan="2">Total (Till Due Date)</td>
          <td>Rs {{$std['total_due']}}</td>
          <td colspan="3">
            @php
              $nf = new NumberFormatter('eng', NumberFormatter::SPELLOUT);
            @endphp
            {{ strtoupper(str_replace(' ', '-', $nf->format($std['total_due']))) }}
          </td>
        </tr>
      </table>
      <div class="note-section">
        <p><strong>NOTE:</strong> Fine of Rs. 100/- per day will be charged after due date</p>
      </div>
      <div class="signature-section">
        <div class="depositor">
          <p>PREPARED BY</p>
        </div>
        <div class="authorized">
          <p>Depositor Name/Signature & Contact #</p>
        </div>
        <div class="authorized">
          <p>BOK AUTHORIZED SIGNATURE</p>
        </div>
      </div>
      <div class="footer-note">
        <p><strong>Note:</strong> Cash should always be deposited at respective counter and collect electronic computer generated receipt through flatbed printer on deposit slip/challan should be obtained. Please be assured to check the receipt and ensure that complete details and amount deposited are correctly printed, failing which the bank will not be responsible.</p>
        <p>POWERED BY: Explainer Technologies</p>
      </div>
    </div>
    <div class="divider"></div>
  </div>
@endforeach
</body>
</html>