<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KMU Examination Admission Form</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', serif;
            font-size: 12px;
            line-height: 1.2;
            color: #000;
            background-color: #f0f0f0;
            padding: 20px;
        }

        .page {
            width: 210mm;
            height: 297mm;
            background: white;
            margin: 0 auto 10px;
            padding: 5mm;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            page-break-after: always;
            position: relative;
            overflow: hidden;
            
        }

        .page:last-child {
            margin-bottom: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 0px;
            border-bottom: 2px solid #000;
            padding-bottom: 0px;
        }

        .logo-section {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0px;
        }

        .logo {
            width: 60px;
            height: 60px;
            margin-right: 20px;
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .university-info {
            text-align: center;
        }

        .university-name {
            font-size: 20px;
            font-weight: bold;
            color: #000;
            letter-spacing: 1px;
        }

        .form-title {
            font-size: 16px;
            font-weight: bold;
            margin: 5px 0;
            text-decoration: underline;
        }

        .semester-title {
            font-size: 14px;
            font-weight: bold;
            margin: 3px 0;
        }

        .session-info {
            font-size: 14px;
            margin-top: 8px;
            text-decoration: line-through;
        }

        .session-info .fall {
            text-decoration: line-through;
        }

        .photo-box {
            width: 35mm;
            height: 40mm;
            border: 2px solid #000;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 9px;
            text-align: center;
            background: white;
            line-height: 1.1;
            font-weight: normal;
            flex-shrink: 0;
        }

        .section-title {
            font-weight: bold;
            font-size: 16px;
            margin: 0px 0 10px 0;
            text-decoration: underline;
            text-align: center;
        }

        .program-section {
            margin: 15px 0;
            display: flex;
            gap: 20px;
            align-items: flex-start;
        }

        .program-content {
            flex: 1;
        }

        .program-list {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin-bottom: 15px;
        }

        .program-item {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
        }

        .checkbox {
            width: 15px;
            height: 15px;
            border: 2px solid #000;
            margin-right: 8px;
            display: inline-block;
            position: relative;
            background: white;
        }

        .checkbox.checked {
            background: #000;
        }

        .checkbox.checked::after {
            content: '✓';
            position: absolute;
            top: -3px;
            left: 1px;
            font-size: 14px;
            color: white;
            font-weight: bold;
        }

        .program-item label {
            font-size: 12px;
            cursor: pointer;
        }

        .form-row {
            margin: 10px 0;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }

        .form-row label {
            font-weight: bold;
            margin-right: 8px;
        }

        .underline {
            border-bottom: 1px solid #000;
            min-width: 200px;
            height: 22px;
            display: inline-block;
            margin: 0 5px;
            padding: 2px 5px;
        }

        .underline.short {
            min-width: 100px;
        }

        .underline.long {
            min-width: 350px;
        }

        .address-section {
            margin: 15px 0;
        }

        .address-line {
            margin: 8px 0;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }

        .subjects-section {
            margin: 15px 0;
        }

        .subjects-box {
            border: 2px solid #000;
            padding: 0px;
            margin: 0px 0;
            text-align: center;
            font-weight: bold;
            background: #f9f9f9;
        }

        .subjects-list {
            margin: 2px 0;
        }

        .subjects-list div {
            margin: 0px 0;
        }

        .declaration-box {
            border: 2px solid #000;
            padding: 0px;
            margin: 0px 0;
            background: #f9f9f9;
        }

        .declaration-title {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 10px;
            text-decoration: underline;
        }

        .declaration-text {
            text-align: justify;
            line-height: 1.3;
            font-size: 11px;
        }

        .signature-section {
            display: flex;
            justify-content: space-between;
            margin: 25px 0;
        }

        .office-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        .office-table td {
            border: 1px solid #000;
            padding: 10px;
            text-align: center;
            font-size: 11px;
        }

        .institute-info {
            display: flex;
            justify-content: space-between;
            margin: 15px 0;
            gap: 20px;
        }

        .institute-box {
            border: 2px solid #000;
            padding: 10px;
            text-align: center;
            background: #f9f9f9;
            font-size: 11px;
            line-height: 1.3;
        }

        .gender-section {
            margin-left: 30px;
            display: flex;
            align-items: center;
        }

        .gender-field {
            border: 2px solid #000;
            padding: 5px 10px;
            margin-left: 10px;
            background: white;
        }

        .certificate-list {
            counter-reset: item;
            padding-left: 0;
        }

        .certificate-list li {
            display: block;
            margin: 12px 0;
            position: relative;
            padding-left: 25px;
            text-align: justify;
            line-height: 1.3;
        }

        .certificate-list li::before {
            content: counter(item) ".";
            counter-increment: item;
            position: absolute;
            left: 0;
            font-weight: bold;
        }

        .instructions-list {
            counter-reset: instruction;
            padding-left: 0;
        }

        .instructions-list li {
            display: block;
            margin: 8px 0;
            position: relative;
            padding-left: 25px;
            text-align: justify;
            line-height: 1.3;
            font-size: 11px;
        }

        .instructions-list li::before {
            content: counter(instruction) ".";
            counter-increment: instruction;
            position: absolute;
            left: 0;
            font-weight: bold;
        }

        .footer-signature {
            text-align: center;
            margin-top: 20px;
        }

        input[type="text"] {
            border: none;
            border-bottom: 1px solid #000;
            background: transparent;
            font-family: inherit;
            font-size: inherit;
            padding: 2px 5px;
            outline: none;
        }

        @media print {
            body {
                background: white;
                padding: 0;
                margin: 0;
            }

            .page {
                box-shadow: none;
                margin: 0;
                padding: 8mm;
                page-break-after: always;
                height: 297mm;
                overflow: hidden;
            }
        }
    </style>
</head>

<body>
    <!-- PAGE 1 -->
    <div class="page">
        <!-- Header -->
        <div class="header">
            <div class="logo-section">
                <div class="logo">
                    <img src="https://achsonsautomotive.com/uploads/business_logos/kmulogo.png" alt="KMU Logo">
                </div>
                <div class="university-info">
                    <div class="university-name">KHYBER MEDICAL UNIVERSITY PESHAWAR</div>
                </div>
            </div>
            <div class="form-title">EXAMINATION ADMISSION FORM</div>
            <div class="semester-title">SEMESTER SYSTEM</div>
            <div class="session-info">Spring/ <span class="fall">Fall</span>{{$student->batch->from}}</div>
        </div>

        <!-- Program Section -->
        <div class="section-title">Program</div>
        <div class="program-section">
            <div class="program-content">
                <div class="program-list">
                    <div class="program-item">
                        <div class="checkbox"></div>
                        <label>1. Doctor of Physical Therapy (DPT)</label>
                    </div>
                    <div class="program-item">
                        <div class="checkbox"></div>
                        <label>2. Master of Physical Therapy (MSPT)</label>
                    </div>
                    <div class="program-item">
                        <div class="checkbox checked"></div>
                        <label>3. BS Nursing (Generic)</label>
                    </div>
                    <div class="program-item">
                        <div class="checkbox"></div>
                        <label>4. BS Nursing (Post RN)</label>
                    </div>
                    <div class="program-item">
                        <div class="checkbox"></div>
                        <label>5. M.Sc. (Nursing)</label>
                    </div>
                    <div class="program-item">
                        <div class="checkbox"></div>
                        <label>6. BHMS</label>
                    </div>
                    <div class="program-item">
                        <div class="checkbox"></div>
                        <label>7. M.Phil</label>
                    </div>
                    <div class="program-item">
                        <div class="checkbox"></div>
                        <label>8. MPH</label>
                    </div>
                    <div class="program-item">
                        <div class="checkbox"></div>
                        <label>9. BS (P&O) Sciences</label>
                    </div>
                    <div class="program-item">
                        <div class="checkbox"></div>
                        <label>10. BS Vision Sciences</label>
                    </div>
                    <div class="program-item">
                        <div class="checkbox"></div>
                        <label>11. BS Paramedics "Discipline</label>
                    </div>
                    <div class="program-item">
                        <div class="checkbox"></div>
                        <label>12. Any Other <span>___________________</span></label>                  

                    </div>
                </div>
               
            </div>
            <!-- Photo Box - positioned inline with program section -->
              @if (file_exists(public_path('uploads/student_image/' . $student->student_image)) && $student->student_image!="default.png")
            <img class="photo-box" src="{{ url('uploads/student_image/' . $student->student_image) }}" />
            @else
            <div class="photo-box">
                Paste photograph<br>
                attested on<br>
                face side
            </div>
            @endif
        </div>

        <!-- Registration and Institute -->
        <div class="form-row">
            <label>University Registration No.</label>
            <input type="text" style="width: 250px;">
        </div>

        <div class="institute-info">
            <label style="display: block; margin-bottom: 5px;"><strong>Institute Name</strong></label>

            <div style="flex: 1;">
                <div class="institute-box">
                    Bab-e- Khyber College of Nursing &<br>
                    Health Sciences Khwaza Khela Swat
                </div>
            </div>
            <label style="display: block; margin-bottom: 5px;"><strong>Examination Center</strong></label>

            <div style="flex: 0 0 200px;">
                <div class="institute-box" style="height: 60px;"></div>
            </div>
        </div>

        <!-- Student Information -->
        <div class="form-row">
            <label>1. Name (IN BLOCK LETTERS):</label>
            <input type="text" value="{{ strtoupper($student->first_name . ' '. $student->last_name) }}" style="width: 300px;">
            <div class="gender-section">
                <label>Gender:</label>
             
                @if($student->gender=='male')
                <div class="gender-field" style="width: 80px; height: 25px;"><div class='mg-left'>@lang('english.male')</div></div>
                @endif
                @if($student->gender=='female')
                <div class="gender-field" style="width: 80px; height: 25px; text-align: center;"><div class='mg-left'>@lang('english.male')</div></div>
               @endif
            </div>
        </div>

        <div class="form-row">
            <label>2. Father's Name (IN BLOCK LETTERS):</label>
            <input type="text" style="width: 400px;" value="{{ strtoupper($student->father_name) }}">
        </div>

        <div class="form-row">
            <label>3. N.I.C.No.</label>
            <input type="text" style="width: 400px;" value="{{ strtoupper($student->cnic_no) }}">
        </div>

        <div class="form-row">
            <label>4. Date of Birth:</label>
            <input type="text" style="width: 150px;" value="{{ @format_date($student->birth_date) }}">
            <label style="margin-left: 50px;">E-mail:</label>
            <input type="text" style="width: 250px;" value="{{ $student->email }}">
        </div>

        <!-- Address Section -->
        <div class="address-section">
            <div class="address-line">
                <label>5. Permanent address:</label>
            </div>
            <div class="address-line" style="margin-left: 30px;">
                <label>Moh:</label>
                <input type="text" style="width: 200px;" value="{{ strtoupper($student->region->name) }}">
                <label style="margin-left: 30px;">Village:</label>
                <input type="text" style="width: 180px;" value="{{ strtoupper($student->city->name) }}">
            </div>
            <div class="address-line" style="margin-left: 30px;">
                <label>Distt:</label>
                <input type="text" style="width: 400px;" value="{{ strtoupper($student->district->name) }}">
            </div>
            <div class="address-line">
                <label>Phone No:</label>
                <input type="text" style="width: 300px;" value="{{ $student->mobile_no }}">
            </div>
        </div>

        <div class="form-row">
            <label>6. Appeared in last time Sem, Examination under Roll No</label>
            <input type="text" style="width: 100px;">
            <label style="margin-left: 10px;">Session</label>
            <input type="text" style="width: 100px;">
            <label>(Spring/Fall).</label>
        </div>

        <!-- Subjects Section -->
        <div class="subjects-section">
            <div class="form-row">
                <label>7. Subjects in which to be examined:</label>
                <div class="subjects-box" style="width: 120px; margin-left: 20px;">{{ ucfirst($student->semester->title) }}</div>
            </div>

            <div class="subjects-list">
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                @foreach ($subjects as $subject )
                
                <div>{{$loop->iteration}}. {{$subject->class_subject->name}} </div>
           
                
                @endforeach
                </div>
              
            </div>
        </div>

        <!-- Declaration -->
        <div class="declaration-box">
            <div class="declaration-title">DECLARATION</div>
            <div class="declaration-text">
                I hereby solemnly declare that the particulars given above are correct. In case of any wrong information
                or concealment of facts I shall be responsible for the consequences. Further, I undertake to abide by
                the Rules and Regulations of Examination prescribed by the Khyber Medical University, Peshawar.
            </div>
        </div>

        <!-- Signatures -->
        <div class="signature-section">
            <div>
                <label>Dated</label>
                <input type="text" style="width: 120px;">
            </div>
            <div>
                <label>Signature of student</label>
                <input type="text" style="width: 180px;">
            </div>
        </div>

        <!-- Office Use Only -->
        <div style="margin-top: 0px;">
            <div class="section-title">FOR OFFICE USE ONLY</div>
            <table class="office-table">
                <tr>
                    <td>Entries and result checked<br>and found correct.</td>
                    <td>He/She is Eligible/Ineligible</td>
                    <td>Allowed/Disallowed</td>
                </tr>
                <tr>
                    <td>Dealing Assistant</td>
                    <td>A.C.E /S.I</td>
                    <td>D.C.E</td>
                </tr>
            </table>
            <div style="margin-top: 8px;">
                <label><strong>Remarks (if any)</strong></label>
            </div>
        </div>
    </div>

    <!-- PAGE 2 -->
    <div class="page">
        <div class="section-title ">CERTIFICATE</div>
        <ol class="certificate-list">
            <li>I certify that the candidate has fulfilled the conditions laid down in the rules, that he/she is of good
                moral character; that he/she has signed this application: and his/her particulars over-leaf are correct.
            </li>
            <li>I certify that he/she completed the course of lectures, practical, demonstrations, clinical work etc. as
                prescribed in the regulations and he/she fulfill the criteria to appear in the exam.</li>
            <li>He/She has remitted Rs<input type="text" style="width: 80px;">. (Rupees in words)<input
                    type="text" style="width: 280px;"><br>
                <input type="text" style="width: 450px; margin: 5px 0;"><br>
                Vide NBP Draft/University Receipt No<input type="text" style="width: 150px;">Dated<input
                    type="text" style="width: 120px;"> as Examination Admission Fee (attached).
            </li>
        </ol>

        <div style="margin: 20px 0;">
            <strong>Note:</strong> - All documents including Bank Draft/Bank receipt to be attached here.
        </div>

        <div style="margin: 40px 0;">
            <div style="display: flex; justify-content: space-between;">
                <div>
                    <div><strong>Principal: Azizullah Shah</strong></div>
                    <div style="margin: 50px 0;">
                        <label>Signature</label>
                        <input type="text" style="width: 180px;">
                    </div>
                </div>
                <div>
                    <div>
                        <label>Name of College</label>
                        <input type="text" style="width: 250px;">
                    </div>
                    <div style="margin: 30px 0;">
                        <label>Office Seal</label>
                        <input type="text" style="width: 180px;">
                    </div>
                </div>
            </div>
            <div style="margin: 25px 0;">
                <label>Remarks if any:</label>
                <input type="text" style="width: 400px;">
            </div>
        </div>

        <!-- Instructions -->
        <div style="margin-top: 50px;">
            <div class="section-title">INSTRUCTIONS: (TO BE READ CAREFULLY)</div>
            <ol class="instructions-list">
                <li>Examination Admission Form duly completed in all respects should reach the controller of
                    Examinations, Khyber Medical University Peshawar on or before the last date notified for the purpose
                    failing which late fee will be charged.</li>
                <li>Fee once deposited is neither refundable nor adjustable if the candidate is otherwise eligible.</li>
                <li>Two different Examinations are not allowed in one session of examination.</li>
                <li>Incomplete forms will not be entertained.</li>
                <li>All candidates are required to attach three copies of passport size photographs and one copy of
                    National Identity Card /Domicile Certificate duly attested by the principle concerned.</li>
                <li>Incomplete /unsigned forms will not be entertained and will be returned at the cost/risk of the
                    candidate.</li>
                <li>Admission fee remitted through money order/cheque will not be accepted.</li>
                <li>No student is eligible for a university examination without having attended 75% of the lectures,
                    demonstrations, tutorials, and practical or clinical work both inpatient and outpatient.</li>
                <li>Whatever may be the system of marking, for all examinations throughout the Semester System the
                    percentage of pass marks in each subject will not be less than 60%.</li>
                <li>No grace marks are allowed in any examination.</li>
            </ol>
        </div>

        <div class="footer-signature">
            <div style="margin-top: 40px;">
                <label>Student Signature</label>
                <input type="text" style="width: 180px;">
            </div>
            <div style="margin-top: 30px; font-weight: bold;">
                Bab-e- Khyber College of Nursing &<br>
                Health Sciences Swat
            </div>
        </div>
    </div>
</body>

</html>
