<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pakistan Nursing Council - Enrollment Form</title>
      <style>
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
      }

      body {
        font-family: Arial, sans-serif;
        
        font-size: 12px;
        line-height: 1.4;
        color: #000;
        background: white;
      }

      .page {
        width: 210mm;
        min-height: 297mm;
        margin: 0 auto 20px auto;
        padding: 15mm;
        background: white;
        border: 2px solid #000;
        page-break-after: always;
        position: relative;
      }

      .page:last-child {
        page-break-after: avoid;
      }

      .header {
        display: flex;
        align-items: flex-start;
        margin-bottom: 15px;
        border-bottom: 1px dashed #000;
        padding-bottom: 10px;
      }

      .logo {
        width: 80px;
        height: 80px;
      }

      .header-text {
        flex: 1;
        text-align: center;
        margin-top: 5px;
      }

     
    


      .section {
        border: 2px solid #000;
        margin-bottom: 15px;
        padding: 10px;
      }

     
         .section-title {
            font-weight: bold;
            font-size: 13px;
            text-decoration: underline;
            padding: 8px 12px;
            margin: 0;
            background: white;
              padding: 1px;
        }

      .form-row {
        margin-bottom: 10px;
        display: flex;
        align-items: center;
         font-weight: bold;
      }

      .form-row label {
        font-weight: bold;
        margin-right: 10px;
        min-width: 140px;
       
        
      }

      .form-row input {
        flex: 1;
        border: none;
        border-bottom: 1px solid #000;
        padding: 2px 5px;
        font-size: 11px;
        background: transparent;
         font-weight: bold;
      }

      .inline-row {
        display: flex;
        gap: 30px;
        margin-bottom: 10px;
        align-items: center;
        font-weight: bold;
      }

      .inline-item {
        display: flex;
        align-items: center;
        gap: 5px;
         font-weight: bold;
      }

      .underline {
        border-bottom: 1px solid #000;
        display: inline-block;
        min-width: 120px;
        padding: 2px 5px;
        font-size: 11px;
         font-weight: bold;
      }

      .table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
      
        ; font-weight: bold;
      }

      .table th,
      .table td {
        border: 1px solid #000;
        padding: 6px;
        text-align: left;
        vertical-align: top;
        ; font-weight: bold;
       
      }

      .table th {
        background: #f0f0f0;
        font-weight: bold;
        font-size: 9px;
      }

      .table td {

        min-height: 25px;
; font-weight: bold;
      }

      .checkbox-group {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin: 10px 0;
      }

      .checkbox-item {
        display: flex;
        align-items: center;
        gap: 5px;
      }

      .checkbox-item input[type="checkbox"] {
        margin-right: 5px;
      }

      .text-field {
        border-bottom: 1px solid #000;
        min-height: 25px;
        margin: 10px 0;
        padding: 5px;
        position: relative;
      }

      .field-label {
        position: absolute;
        bottom: -15px;
        left: 0;
        font-size: 9px;
        font-style: italic;
        color: #666;
      }

      .signature-section {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-top: 30px;
      }

      .certify-section {
        width: 45%;
      }

      .signature-box {
        width: 45%;
        text-align: center;
      }

      .signature-area {
        border: 1px solid #000;
        height: 100px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 9px;
        text-align: center;
        padding: 10px;
        line-height: 1.2;
      }

      .date-line {
        border-bottom: 1px solid #000;
        min-width: 150px;
        height: 20px;
        margin: 10px 0;
      }

      .page-number {
        position: absolute;
        top: 10mm;
        right: 15mm;
        font-weight: bold;
        font-size: 12px;
      }

      @media print {
        body {
          margin: 0;
          padding: 0;
        }

        .page {
          width: 100%;
          min-height: 100vh;
          margin: 0;
          padding: 10mm;
          border: none;
          page-break-after: always;
        }

        .page:last-child {
          page-break-after: avoid;
        }

        @page {
          size: A4;
          margin: 0;
        }
      }
        /* Form layout */
        .form-layout {
            position: relative;
            margin: 15px 0;
        }
        
        /* Photo box */
        .photo-box {
            position: absolute;
            top: 0;
            right: 0;
            width: 110px;
            height: 130px;
            border: 1px solid black;
            text-align: center;
            font-size: 9px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: white;
            z-index: 1;
        }
        
        /* Form title */
        .form-title {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            margin: 15px 130px 20px 0;
            letter-spacing: 3px;
            line-height: 1.4;
        }
        
        /* Session info */
        .session-info {
            margin: 15px 130px 20px 0;
            font-size: 12px;
        }
        
        .session-line {
            margin: 6px 0;
        }
        
        .underline {
            border-bottom: 1px solid black;
            display: inline-block;
            min-height: 16px;
            margin: 0 3px;
            padding: 0 5px;
            vertical-align: baseline;
        }
        
        .short-line { width: 100px; }
        .medium-line { width: 200px; }
        .long-line { width: 400px; }
        
      
       .header-title {
    font-size: 16px;
    font-weight: bold;
    margin: 0 0 3px 0;
}
        
  
      
        /* Single red diagonal dashed line across sections 5, 6, 7 */
        .page:nth-of-type(2)::after {
            content: '';
            position: absolute;
            top: 430px;
            right: 100px;
         
            width: 550px;
            height: 1px;
            background: repeating-linear-gradient(
                90deg,
                red 0px,
                red 10px,
                transparent 10px,
                transparent 20px
            );
            transform: rotate(-45deg);
            transform-origin: right top;
            z-index: 10;
        }    
        
        
       
        
    </style>
</head>
<body>
    
    <!-- PAGE 1 -->
    <div class="page">
      
<!-- Header -->
      <div class="header">
        <div class="logo">
          <img
            src="https://achsonsautomotive.com/uploads/business_logos/pnclogo.png"
            alt="PNC Logo"
            style="width: 100%; height: 100%"
          />
        </div>

        <!-- Header -->
        <div class="header-text">
          <div class="header-title">PAKISTAN NURSING & MIDWIFERY COUNCIL,</div>
          <div class="header-info">
            National Institute of Health, Park Road, Chakshehzad,
          </div>
          <div class="header-info">Islamabad, Pakistan</div>
          <div class="header-info">
            Phone No. 051-9255804 ext-105, Fax No. 051-9255813
          </div>
          <div class="header-info">
            Website: www.pnc.org.pk Email: pncenrollment@gmail.com
          </div>
        </div>
      </div>

      
   <!-- Form layout with photo -->
        <div class="form-layout">
            <!-- Photo box -->
            <div class="photo-box">
                Past here recent<br>passport size<br>photograph
            </div>
            
            <!-- Form title -->
            <div class="form-title">
                P R E R E G I S T R A T I O N<br>
                E N R O L L M E N T &nbsp; F O R M<br>
                FOR SCHOOL / COLLEGE OF NURSING
            </div>
            
            <!-- Session info -->
            <div class="session-info">
                <div class="session-line">
                    <strong>SESSION</strong> <span class="underline short-line">{{$student->batch->from}}</span> <strong>to</strong> <span class="underline short-line">{{$student->batch->to}}</span>
                </div>
                <div class="session-line">
                    <strong>INSTITUTION:</strong> <span class="underline long-line">Bab-e- Khyber College of Nursing & Health Sciences</span>
                </div>
            </div>
        </div>
        <!-- Section I -->
        <div class="section">
            <div class="section-title">SECTION-I</div>
            
            <div class="form-row">
                <label>Student's Full Name:</label>
                <input type="text"  value="{{ strtoupper($student->first_name . ' '. $student->last_name) }}"  placeholder="«Full_Name»">
            </div>

            <div class="form-row">
                <label>Daughter of / Wife of / Son of:</label>
                <input type="text" value="{{ strtoupper($student->father_name) }}" placeholder="«Father_Name»">
            </div>

            <div class="inline-row">
                <div class="inline-item">
                    <label>Nationality:</label>
                    <span class="underline">{{ strtoupper($student->nationality) }}</span>
                </div>
                <div class="inline-item">
                    <label>Religion:</label>
                    <span class="underline">{{ strtoupper($student->religion) }}</span>
                </div>
            </div>

            <div class="form-row">
                <label>Province of Domicile:</label>
                <input type="text"  value="{{ strtoupper(isset($student->domicile) ? $student->domicile->name : '') }}" placeholder="«Domicile»">
            </div>

            <div class="form-row">
                <label>Permanent Address:</label>
                <input type="text" value="{{ strtoupper($student->std_permanent_address) }}" placeholder="«Residential_Address»">
            </div>

            <div style="height: 40px; border-bottom: 1px solid #000; margin: 15px 0;"></div>

            <div class="inline-row">
                <div class="inline-item">
                    <label>Date of Birth:</label>
                    <span class="underline">{{ @format_date($student->birth_date) }}</span>
                </div>
                <div class="inline-item">
                    <label>Contact #:</label>
                    <span class="underline">{{ $student->mobile_no }}</span>
                </div>
            </div>

          
          
                 <table style="border-collapse: collapse; margin: 8px 0; width: 100%;">
                    <tr>
                        <td style="border: 1px solid black; padding: 6px; font-weight: bold; text-align: center; width: 80px;">CNIC #</td>
                        <td style="border: 1px solid black; padding: 6px; width: 180px;">{{ strtoupper($student->cnic_no) }}</td>
                        <td style="border: none; padding: 6px; font-weight: bold; width: 100px;">Passport No.</td>
                        <td style="border-bottom: 1px solid black; border-top: none; border-left: none; border-right: none; padding: 6px; width: 120px;"></td>
                    </tr>
                </table>
                <div style="text-align: right; font-size: 9px; font-style: italic; margin-top: 2px;">(for foreigners only)</div>
        </div>

        <!-- Section II -->
        <div class="section">
            <div class="section-title">SECTION-II</div>
            
            <table class="table">
                <thead>
                    <tr>
                        <th>Qualification</th>
                        <th>Passing Year</th>
                        <th>Marks Obtained/Total Marks</th>
                        <th>% age of Marks</th>
                        <th>Marks Obtained in Biology (if applicable)</th>
                        <th>Board</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($student->qualifications as $qualification)
                          <tr>
                        <td>{{$qualification->qualification}}</td>
                        <td>{{$qualification->passing_year}}</td>
                        <td>{{$qualification->marks_obtained}}/{{$qualification->total_marks}}</td>
                        <td>{{$qualification->percentage}}</td>
                        <td>{{$qualification->biology_marks}}</td>
                        <td>{{$qualification->board}}</td>
                       
                       
                    </tr>
                    @endforeach
                        
                   
                  
                </tbody>
            </table>
        </div>
    </div>

    <!-- PAGE 2 -->
    <div class="page">
        <div class="page-number">Page-2</div>

        <!-- Section III -->
        <div class="section">
            <div class="section-title">SECTION-III</div>
            <p style="margin-bottom: 10px; font-weight: bold;">Course in which enrollment is desired</p>
            
            <table class="table">
                <thead>
                    <tr>
                        <th rowspan="2">S.No.</th>
                        <th rowspan="2">Diploma / Degree</th>
                        <th colspan="2">Period</th>
                        <th rowspan="2">Educational Institute<br>(E-mail / contact number)</th>
                    </tr>
                    <tr>
                        <th>From</th>
                        <th>To</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        @if($student->current_class->id==1)
                        <td>Generic BSN ( 04<br>years Degree<br>Program)</td>
                        @else
                         <td>GLady health visitor LHV ( 02<br>years Diploma <br>Program)</td>
                        @endif
                        <td>{{$student->batch->from}}</td>
                        <td>{{$student->batch->to}}</td>
                        <td>Bab-e- Khyber College of Nursing &<br>Health Sciences<br>Contact No: 0946-744338/<br>03456635815<br>khybernursingcollege@gmail.com</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Section IV -->
        <div class="section">
            <div class="section-title">SECTION-IV</div>
            <p style="margin-bottom: 10px;">In which type of Institution are you enrolled (tick the box where applicable)</p>
            
            <div class="checkbox-group">
                <div class="checkbox-item">
                    <input type="checkbox" id="midwifery">
                    <label for="midwifery">School of Midwifery</label>
                </div>
                <div class="checkbox-item">
                    <input type="checkbox" id="public-health">
                    <label for="public-health">School of Public Health</label>
                </div>
                <div class="checkbox-item">
                    <input type="checkbox" id="nursing">
                    <label for="nursing">School of Nursing</label>
                </div>
                <div class="checkbox-item">
                    <input type="checkbox" id="college-nursing" checked>
                    <label for="college-nursing">College of Nursing (BSN)</label>
                </div>
            </div>
            
            <div style="margin-top: 15px;">
                <span>Other <em>(specify)</em></span>
                <div class="underline" style="min-width: 400px; margin-left: 10px;"></div>
            </div>
        </div>

        <!-- Section V -->
        <div class="section">
            <div class="section-title">SECTION-V</div>
            <div style="margin-bottom: 15px;">
                <span>Ever register with PNC:</span>
                <div class="checkbox-item" style="display: inline-flex; margin-left: 20px;">
                    <label>Yes</label>
                    <input type="checkbox" style="margin: 0 5px;">
                </div>
                <div class="checkbox-item" style="display: inline-flex; margin-left: 15px;">
                    <label>No</label>
                    <input type="checkbox" style="margin: 0 5px;">
                </div>
            </div>
            <div style="margin-bottom: 5px;">
                <span>If yes, specify your PNC Registration #:</span>
                <span class="underline" style="min-width: 200px; margin-left: 10px;"></span>
                <span style="margin-left: 30px;">Date:</span>
                <span class="underline" style="min-width: 120px; margin-left: 10px;"></span>
            </div>
            <p style="font-style: italic; font-size: 10px; text-align: right; margin-top: 5px;">
                Valid up to
            </p>
        </div>

        <!-- Section VI -->
        <div class="section">
            <div class="section-title">SECTION-VI</div>
            <p style="margin-bottom: 10px;">Present employment is with: (tick the box where applicable)</p>
            
            <div class="checkbox-group">
                <div class="checkbox-item">
                    <label>Government</label>
                    <input type="checkbox">
                </div>
                <div class="checkbox-item">
                    <label>Private</label>
                    <input type="checkbox">
                </div>
                <div class="checkbox-item">
                    <label>Semi Government</label>
                    <input type="checkbox">
                </div>
                <div class="checkbox-item">
                    <label>Armed Forces</label>
                    <input type="checkbox">
                </div>
                <div class="checkbox-item">
                    <label>NGO</label>
                    <input type="checkbox">
                </div>
            </div>
        </div>

        <!-- Section VII -->
        <div class="section">
            <div class="section-title">SECTION-VII</div>
            <p style="margin-bottom: 15px; font-weight: bold;">What is your present position / designation at workplace?</p>
            
            <div class="text-field">
                <div class="field-label">(specify your designation)</div>
            </div>
            
            <div class="text-field">
                <div class="field-label">(Address of workplace)</div>
            </div>
        </div>

        <!-- Section VIII -->
        <div class="section">
            <div class="section-title">SECTION-VIII</div>
            <p style="margin-bottom: 20px;">I hereby certify that the information contained in this application is true and correct?</p>
            
            <div class="signature-section">
                <div class="certify-section">
                    <p style="margin-bottom: 10px;">Certify by</p>
                    <p style="margin-bottom: 20px;">Principal – College/School of Nursing</p>
                    <div class="date-line">{{ date('d-m-Y') }}</div>
                    <p style="font-size: 10px;">Date</p>
                </div>
                
                <div class="signature-box">
                    <div class="signature-area">
                        <div>
                           
                        </div>
                    </div>
                    <p style="font-weight: bold; margin-top: 5px;">Applicant Signature</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>