<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('english.result_card')</title>
    <style>
    
        body {
            margin: 0;
            padding: 0;

            width: 100%;
            background-color: #fff;

            font-family: 'Roboto Condensed', sans-serif;
 font-size: 18px;
 
        }

        @font-face {
            font-family: 'certificate';
            font-style: normal;
            font-weight: 400;
           src: url({{ public_path('/fonts/Certificate.ttf') }}) format('truetype');
        }

        .header {
            margin: 0 auto;
            width: 50%;
            text-align: center;
            font-family: 'certificate';
        }

        .container {
            width: 100%;
            overflow: hidden;
            margin: 0;
            color: #000;
        }

        .column {
            float: left;
            margin: 0px;
            line-height: 2.6;
        }

        .column {
            float: left;
            margin: 0px;
            line-height: 2.6;
        }

        .column-left {
            width: 36%;
        }

        .column-center {
            width: 36%;
        }

        .column-photo {
            width: 22%;
        }

        .underline:after {
            content: '';
            background: black;
            width: 100%;
            height: 2px;
            display: block;
        }

        .photo {
            border: 1px solid #000;
            text-align: center;
            padding: 3px;
            border-radius: 5px;
        }
   .qr {
          
            text-align: center;
            padding: 3px;
            border-radius: 5px;
            margin-top:20px;
        }
   .column-qr {
            width: 22%;
        }

        .timecard {
            margin: auto;
            width: 100%;
            border-collapse: collapse;
            border: 2px solid #000;
            /*for older IE*/
            border-style: hidden;

        }

        .timecard thead th {
            padding: 8px;
            background-color: #eee;
        }

        .timecard tfoot {
            font-weight: bold;

            background-color: #eee;
            color: #fff;
            border-top: 2px solid #000;
        }




        .timecard th,
        .timecard td {
            padding: 0px;
            border-width: 1px;
            border-style: solid;
            border-color: #eee #000;
            color: #000;
        }

        .timecard td {
            text-align: center;
        }

        .timecard tbody th {
            text-align: left;
            font-weight: normal;
        }


        .timecard tr.even {
            background-color: #eee;
        }

     .chart-single {
    border: 1px solid #000;
    padding: 20px;
    margin: 5 auto; /* Horizontally centers the element */
    width: fit-content; /* Adjusts the width to the content, you can set a specific width if needed */
}

        .numbers {
            margin: 0;
            padding: 0;
            display: inline-block;
            float: left;
        }

        .numbers li {
            text-align: right;
            padding-right: 8px;
            list-style: none;
            position: relative;
            left: 0px;
            height: 20px;
            top: -9px;
        }

        .bars {
            max-width: 257px;
            width: 80%;
            display: inline-block;
            padding: 0;
            margin: 0;
            background-image: linear-gradient(top, #19875 3%, transparent 2%);
            background-image: -webkit-linear-gradient(top, #19875 3%, transparent 2%);
            background-image: -moz-linear-gradient(top, #19875 3%, transparent 2%);
           background-image: -webkit-linear-gradient(top, #198754 3%, transparent 2%, transparent 2%, transparent 2%, transparent 2%, transparent 2%);

            background-size: 100% 33px;
            background-position: left top;
            font-size: 12px;

        }

      
        .bars {
              max-width: 100%;
    width: 90%;
    display: inline-block;
    padding: 0;
    margin: 0 auto;  
        }



     

        .bars li:last-of-type {
            margin: 0;
        }

        .bars li:first-of-type {
            margin-left: 1%;
        }

        .bars li .bar {
            display: block;
            width: 100%;
            background-image: linear-gradient(10deg, #198754 50%, #E25A25 58%, #E26724 67%, #E17C23 75%, #E19A21 84%, #E0BF1E 100%);
            background-image: -webkit-linear-gradient(10deg, #198754 50%, #E25A25 58%, #E26724 67%, #E17C23 75%, #E19A21 84%, #E0BF1E 100%);
            background-image: -moz-linear-gradient(10deg, #198754 50%, #E25A25 58%, #E26724 67%, #E17C23 75%, #E19A21 84%, #E0BF1E 100%);
            position: absolute;
            bottom: 0;
            /* numbers inside the bar */
        }

        .bars li .bar:before {
            position: absolute;
            content: attr(data-numbers);
            transform: rotate(-90deg);
            transform-origin: 100% 100%;
            width: 100%;
            bottom: 9%;
            right: 1px;
        }

        .bars li span {
            width: 100%;
            position: absolute;
            bottom: -2em;
            left: 0;
            text-align: center;
            font-weight: bold;
            font-size: 9px;
        }

        .bars li .percentage sup {
            font-size: 66%;
        }


     
       
       

    
    </style>
</head>
<body style="color:#000">

    <div>
        <img src="{{ public_path('/uploads/business_logos/'.$system_detail->page_header_logo) }}" width="100%" height="170" style="object-fit: contain;">

        <div class="header">
            <h2 class="cursive bold" id="head">@lang('english.detailed_marks_certificate')</h2>
        </div>

        @php
        $nf = new NumberFormatter('eng', NumberFormatter::ORDINAL);
        @endphp

        <div class="container">
            <div class="column column-left">
                <table width="100%">
                    <thead>
                        <tr>
                            <th>
                                <div class="underline text-align-center">@lang('english.roll_no')</div>
                            </th>
                            <td>
                                <div class="underline text-align-center"><strong>{{ ucwords($details->student->roll_no) }}</strong></div>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <div class="underline text-align-center">@lang('english.name')</div>
                            </th>
                            <td>
                                <div class="underline text-align-center"><strong>{{ ucwords($details->student->first_name . ' ' . $details->student->last_name) }}</strong></div>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <div class="underline text-align-center">@lang('english.s/d_of')</div>
                            </th>
                            <td>
                                <div class="underline text-align-center"><strong>{{ ucwords($details->student->father_name) }}</strong></div>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <div class="underline text-align-center">@lang('english.class')</div>
                            </th>
                            <td>
                                <div class="underline text-align-center"><strong>{{ ucwords($details->current_class->title) . ' ' . $details->current_class_section->section_name }}</strong></div>
                            </td>
                        </tr>
                    </thead>
                </table>
            </div>

            <div class="column column-center">
                <table width="100%">
                    <thead>
                        <tr>
                            <th>
                                <div class="underline text-align-center">@lang('english.examination')</div>
                            </th>
                            <td>
                                <div class="underline text-align-center"><strong>{{ ucwords($details->exam_create->term->name) }}</strong></div>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <div class="underline text-align-center">@lang('english.session')</div>
                            </th>
                            <td>
                                <div class="underline text-align-center"><strong>{{ ucwords($details->session->title) }}</strong></div>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <div class="underline text-align-center">@lang('english.position_in_section')</div>
                            </th>
                            <td>
                                <div class="underline text-align-center"><strong>{{ $nf->format($details->class_section_position) }}</strong></div>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <div class="underline text-align-center">@lang('english.position_in_class')</div>
                            </th>
                            <td>
                                <div class="underline text-align-center"><strong>{{ $nf->format($details->class_position) }}</strong></div>
                            </td>
                        </tr>
                    </thead>
                </table>
            </div>

            <div class="column column-photo photo">
                @if (file_exists(public_path('uploads/student_image/' . $details->student->student_image)))
                <img width="100%" height="150" src="{{ public_path('uploads/student_image/' . $details->student->student_image) }}" />
                @else
                <img width="100%" height="180" src="{{ public_path('uploads/student_image/default.png') }}" />
                @endif
            </div>
        </div>
    </div>
    <div style="margin:5px;">
        <table style="" id="subject_table" class="timecard" cellspacing="0" width="100%">
            <thead>
                <tr>
                    {{-- <th rowspan="2">Sr No</th> --}}
                    <th rowspan="2">@lang('english.name')</th>
                    <th colspan="3">@lang('english.total_marks')</th>
                    <th colspan="3">@lang('english.obtained_marks')</th>
                    {{-- <th rowspan="2">In Words </th> --}}
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
                // $f = new \NumberFormatter('eng', \NumberFormatter::SPELLOUT);
                //$nf = new NumberFormatter('eng', NumberFormatter::ORDINAL);
                $total_theory_marks = 0;
                $total_practical_marks = 0;
                $total_total_marks = 0;

                $obtain_theory_marks = 0;
                $obtain_practical_marks = 0;
                $obtain_total_marks = 0;
                $school_name =session()->get('system_details.org_name') ;
                $student_info=ucwords($details->student->first_name . ' ' . $details->student->last_name)."\r\n" . __('english.s/d_of') . ucwords($details->student->father_name) ."\r\n". 'Roll No: ' . $details->student->roll_no."\r\n" . ucwords($details->current_class->title) ." ".$details->current_class_section->section_name ."\r\n";
                $qr_code_details=[$school_name,$student_info];
                @endphp
                @foreach ($details->subject_result as $subject)
                <tr class="@if($loop->iteration%2==0) even @else odd @endif">
                    {{-- <td>{{ $loop->iteration }}</td> --}}
                    @php
                    $total_theory_marks += $subject->theory_mark;
                    $total_practical_marks += $subject->parc_mark;
                    $total_total_marks += $subject->total_mark;

                    $obtain_theory_marks += $subject->obtain_theory_mark;
                    $obtain_practical_marks += $subject->obtain_parc_mark;
                    $obtain_total_marks += $subject->total_obtain_mark;
                    $qr_code_details[]=$subject->subject_name->name .'='.$subject->total_obtain_mark.'/'.$subject->total_mark."\r\n";
                    @endphp
                    <td>{{ $subject->subject_name->name }}</td>
                    <td>{{ $subject->theory_mark}}</td>
                    <td>{{ $subject->parc_mark}}</td>
                    <td>{{ $subject->total_mark}}</td>

                    <td>{{ $subject->obtain_theory_mark}}</td>
                    <td>{{ $subject->obtain_parc_mark}}</td>
                    <td>{{ $subject->total_obtain_mark}}</td>
                    {{-- <td>@php
                                      echo $f->format($subject->total_obtain_mark);
                         @endphp</td> --}}
                    <td>{{ $nf->format($subject->position_in_subject)}}</td>
                    <td>{{ $subject->subject_grade->name ?? null}}</td>
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
                    <td>{{ $obtain_practical_marks}}</td>
                    <td>{{$obtain_total_marks }}</td>
                    {{-- <td colspan="4">@php
                                      echo $f->format($obtain_total_marks);
                         @endphp</td> --}}
                    <td></td>
                    <td></td>
                    <td></td>


                </tr>
            </tfoot>
        </table>
    </div>
  
    <div class="chart-single">

        <ul class="numbers">
            <li>100</li>
            <li></li>
            <li>80</li>
            <li></li>
            <li>60</li>
            <li></li>
            <li>40</li>
            <li></li>
            <li>20</li>
            <li></li>
            <li>0</li>

        </ul>
 <ul class="bars">
            @foreach ($details->subject_result as  $subject)

   <li style="display: block;
      width: 8%;
      height: 200px;
      margin: 0;
      text-align: center;
      position: relative;
      margin-right: {{100/$details->subject_result->count() }}px;
      float: left;"><div  style="height: {{ number_format($subject->obtain_percentage,0)}}%;" class="bar"></div><span>{{ $subject->subject_name->name }}</span><span class="percentage" style=" width: 100%;
        position: absolute;
        bottom: {{ number_format($subject->obtain_percentage,0)}}%;
        left: 0;
        text-align: center;
        font-weight: normal;
        font-size: 14px;">{{ number_format($subject->obtain_percentage,0)}}<sup>%</sup></span></li>
        @endforeach

    </ul>


    </div>
    <!--end single chart-->



    <div class="container">
            <div class="column column-left">
                <table width="100%">
                <thead>
                    <tr>
                        <th><div class="text-align-center">@lang('english.prepared_by'):</div></th>
                        <td><div class="underline text-align-center"></div></td>
                    </tr>
                    <tr>
                        <th><div class="text-align-center">@lang('english.note'):</div></th>
                        <td><div class="underline text-align-center"><strong style="zoom:90%">@lang('english.errors_omissions_accepted')</strong></div></td>
                    </tr>
                </thead>
            </table>
            <div style="text-align:center">
                <img src="{{ public_path('/uploads/invoice_logo/exam_controller.png') }}" style="height: 50px;width: 100%; margin-top:20px;">
                <strong>@lang('english.controller_of_examination')</strong>
            </div>
            </div>
  <div class="column column-qr qr">
                @php
                $qr_code_details[]='Total='.$obtain_total_marks.'/'.$total_total_marks."\r\n";
        $qr_code_text = implode(' ', $qr_code_details);
            @endphp
            <img class="center-block" style="margin-left: 20px;" src="data:image/png;base64,{{DNS2D::getBarcodePNG($qr_code_text, 'QRCODE')}}">
        </div>
           
            <div class="column column-center">
                <table width="100%">
                <thead>
                    <tr>
                        <th><div class="text-align-center">@lang('english.percentage'):</div></th>
                        <td><div class="underline text-align-center"><strong>{{ $details->final_percentage }}%</strong></div></td>
                    </tr>
                    <tr>
                        <th><div class="text-align-center">@lang('english.grade'):</div></th>
                        <td><div class="underline text-align-center"><strong>{{ $details->grade ? ucwords($details->grade->name) : '' }}</strong></div></td>
                    </tr>
                    <tr>
                        <th><div class="text-align-center">@lang('english.remarks'):</div></th>
                        <td><div class="underline text-align-center"><strong>{{ $details->grade ? ucwords($details->grade->remark) : '' }}</strong></div></td>
                    </tr>
                </thead>
            </table> 
            </div>

          
        </div>
</body>
</html>
