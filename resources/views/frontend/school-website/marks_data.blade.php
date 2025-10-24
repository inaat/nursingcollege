

<style>
    
    .grow-img {
        width: 150px;
        height: 150px;
        border: 1px solid {{ config('constants.head_bg_color') }}; 
    }

    
</style>
<div>
    @php
    // $f = new \NumberFormatter('eng', \NumberFormatter::SPELLOUT);
    $nf = new NumberFormatter('eng', NumberFormatter::ORDINAL);
    @endphp

    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <table class="table ">
                    <thead>
                        <tr>
                            <th>@lang('english.roll_no')</th>
                            <td><strong>{{ ucwords($details->student->roll_no) }}</strong></td>
                        </tr>
                        <tr>
                            <th>@lang('english.name')</th>
                            <td><strong>{{ ucwords($details->student->first_name . ' ' . $details->student->last_name) }}</strong></td>
                        </tr>
                        <tr>
                            <th>@lang('english.s/d_of')</th>
                            <td><strong>{{ ucwords($details->student->father_name) }}</strong></td>
                        </tr>
                        <tr>
                            <th>@lang('english.class')</th>
                            <td><strong>{{ ucwords($details->current_class->title) . ' ' . $details->current_class_section->section_name }}</strong></td>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="col-md-4">
                <table class="table ">
                    <thead>
                        <tr>
                            <th>@lang('english.examination')</th>
                            <td><strong>{{ ucwords($details->exam_create->term->name) }}</strong></td>
                        </tr>
                        <tr>
                            <th>@lang('english.session')</th>
                            <td><strong>{{ ucwords($details->session->title) }}</strong></td>
                        </tr>
                        <tr>
                            <th>@lang('english.position_in_section')</th>
                            <td><strong>{{ $nf->format($details->class_section_position) }}</strong></td>
                        </tr>
                        <tr>
                            <th>@lang('english.position_in_class')</th>
                            <td><strong>{{ $nf->format($details->class_position) }}</strong></td>
                        </tr>
                    </thead>
                </table>
            </div>
          <div class="col-md-4 text-center">
    <div class="img-container">
        @if (file_exists(public_path('uploads/student_image/' . $details->student->student_image)))
            <img class="img-fluid rounded grow-img" src="{{ url('uploads/student_image/' . $details->student->student_image) }}" alt="Student Image">
        @else
            <img class="img-fluid rounded grow-img" src="{{ url('uploads/student_image/default.png') }}" alt="Default Image">
        @endif
    </div>
</div>
        </div>

        <div class="table-responsive my-3">
            <table class="table table-bordered" id="subject_table">
                <thead class="table-light">
                    <tr>
                        <th rowspan="2">@lang('english.name')</th>
                        <th colspan="3">@lang('english.total_marks')</th>
                        <th colspan="3">@lang('english.obtained_marks')</th>
                        <th rowspan="2">@lang('english.sub')<br>@lang('english.rank')</th>
                        <th rowspan="2">@lang('english.grade')</th>
                        <th rowspan="2">@lang('english.remarks')</th>
                    </tr>
                    <tr>
                        <th>@lang('english.marks_theory')</th>
                        <th>@lang('english.practical')/@lang('english.viva')</th>
                        <th>@lang('english.total')</th>
                        <th>@lang('english.marks_theory')</th>
                        <th>@lang('english.practical')</th>
                        <th>@lang('english.total')</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $total_theory_marks = 0;
                    $total_practical_marks = 0;
                    $total_total_marks = 0;

                    $obtain_theory_marks = 0;
                    $obtain_practical_marks = 0;
                    $obtain_total_marks = 0;

                    $school_name = session()->get('system_details.org_name');
                    $student_info = ucwords($details->student->first_name . ' ' . $details->student->last_name) . "\r\n" . __('english.s/d_of') . ucwords($details->student->father_name) . "\r\n" . 'Roll No: ' . $details->student->roll_no . "\r\n" . ucwords($details->current_class->title) . " " . $details->current_class_section->section_name . "\r\n";
                    $qr_code_details = [$school_name, $student_info];
                    @endphp
                    @foreach ($details->subject_result as $subject)
                    <tr>
                        @php
                        $total_theory_marks += $subject->theory_mark;
                        $total_practical_marks += $subject->parc_mark;
                        $total_total_marks += $subject->total_mark;

                        $obtain_theory_marks += $subject->obtain_theory_mark;
                        $obtain_practical_marks += $subject->obtain_parc_mark;
                        $obtain_total_marks += $subject->total_obtain_mark;
                        $qr_code_details[] = $subject->subject_name->name . '=' . $subject->total_obtain_mark . '/' . $subject->total_mark . "\r\n";
                        @endphp
                        <td>{{ $subject->subject_name->name }}</td>
                        <td>{{ $subject->theory_mark }}</td>
                        <td>{{ $subject->parc_mark }}</td>
                        <td>{{ $subject->total_mark }}</td>

                        <td>{{ $subject->obtain_theory_mark }}</td>
                        <td>{{ $subject->obtain_parc_mark }}</td>
                        <td>{{ $subject->total_obtain_mark }}</td>

                        <td>{{ $nf->format($subject->position_in_subject) }}</td>
                        <td>{{ $subject->subject_grade->name ?? null }}</td>
                        <td>{{ $subject->subject_grade->remark ?? null }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td>@lang('english.summary')</td>
                        <td>{{ $total_theory_marks }}</td>
                        <td>{{ $total_practical_marks }}</td>
                        <td>{{ $total_total_marks }}</td>
                        <td>{{ $obtain_theory_marks }}</td>
                        <td>{{ $obtain_practical_marks }}</td>
                        <td>{{ $obtain_total_marks }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="row border-top pt-3">
            <div class="col-md-4">
                <table class="table table-borderless">
                    <thead>
                        <tr>
                            <th>   </th>                  <td> <img style="width:100px; height:100px" src="{{ url('/uploads/invoice_logo/exam_controller.png') }}" alt="Exam Controller" class=""><br>

                            <strong>@lang('english.controller_of_examination')</strong></td>
                        </tr>
                        <tr class="text-center">
                            <th>@lang('english.note'):</th>
                            <td><strong>@lang('english.errors_omissions_accepted')</strong></td>
                        </tr>
                    </thead>
                </table>
               
            </div>
            <div class="col-md-4 text-center">
                @php
                $qr_code_details[] = 'Total=' . $obtain_total_marks . '/' . $total_total_marks . "\r\n";
                $qr_code_text = implode(' ', $qr_code_details);
                @endphp
                <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG($qr_code_text, 'QRCODE') }}" alt="QR Code" class="img-fluid">
            </div>
            <div class="col-md-4">
                <table class="table table-borderless">
                    <thead>
                        <tr>
                            <th>@lang('english.percentage'):</th>
                            <td><strong>{{ @num_format($details->final_percentage) }}%</strong></td>
                        </tr>
                        <tr>
                            <th>@lang('english.grade'):</th>
                            <td><strong>{{ $details->grade ? ucwords($details->grade->name) : '' }}</strong></td>
                        </tr>
                        <tr>
                            <th>@lang('english.remarks'):</th>
                            <td><strong>{{ $details->grade ? ucwords($details->grade->remark) : '' }}</strong></td>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    
                              <!-- Submit Button -->
                            <div class="col-lg-12 text-center mt-4">
                                  <a 
href="{{ url('/get-result?' . http_build_query([
    'session_id' => $details->session_id,
    'exam_create_id' => $details->exam_create_id,
    'rollno' => $details->student->roll_no
])) }}"        target="_blank" 
        class="commonBtn get-result-pdf px-5 py-2 fw-bold shadow"
    >
        PDF Download
    </a>
                            </div>
</div>
