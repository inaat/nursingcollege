<!DOCTYPE html>
<html>
<head>
    <title>Certificate of Completion</title>
    <!-- Include your CSS styles here -->
    <style>
        * {
            box-sizing: border-box;
        }

        @media print {
            * {
                margin: 2px;
                padding: 0px;
            }

            .no-print,
            .no-print * {
                display: none !important;
            }

            .print-m-0 {
                margin: 0 !important;
            }

            .cert-container {
                page-break-before: always;
            }
            
        }
                  .cert-container {
                page-break-before: always;
            }
            
        
                body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
}

      

   

        .cert-container {
            margin: 0;

            display: flex;
            justify-content: center;

        }

        .cert {
            width: 800px;
            height: 600px;
            padding: 15px 20px;
            text-align: center;
            position: relative;
            z-index: -1;
        }

        .cert-bg {
            position: absolute;
            left: 0px;
            top: 0;
            z-index: -1;
            width: 21cm;
            height: 29.1cm;
        }

        .cert-content {
            width: 100%;
            /*height: 500px;*/
            padding: 40px 40px 0px 40px;
            text-align: center;
            font-family: Arial, Helvetica, sans-serif;

        }

   

     
        .bottom-txt {
            padding: 12px 5px;
            display: flex;
            justify-content: space-between;
            font-size: 16px;
        }

        .bottom-txt * {
            white-space: nowrap !important;
        }

        .other-font {
            font-family: Cambria, Georgia, serif;
            font-style: italic;
        }
h5 {
  position: relative;
  margin: 0 auto 20px;
  padding: 10px 40px;
  text-align: center;
  color:#fff;
  
  background-color: #de1d30;
}

h5::before, h5::after {
  content: '';
  width: 80px;
  height: 100%;
  background-color: #de1d30;

  /* position ribbon ends behind and slightly lower */
  position: absolute;
  z-index: -1;
  top: 20px;
  
  /* clip ribbon end shape */
  clip-path: polygon(0 0, 100% 0, 100% 100%, 0 100%, 25% 50%);

  /* draw and position the folded ribbon bit */
  background-image: linear-gradient(45deg, transparent 50%, #de1d30 50%);
  background-size: 20px 20px;
  background-repeat: no-repeat;
  background-position: bottom right;
}

h5::before {
  left: -60px;
}

h5::after {
  right: -60px;
  transform: scaleX(-1); /* flip horizontally */
}

/* everything below is for demo appearances and not important to the concept */

.ribbon {
  display: grid;
  align-items: center;
 
}

.student-info {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
}
    .flex-container {
        margin-top:15px;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        zoom:80%;
    }
    
    .underline {
        text-decoration: underline;
    }
    
    .text-align-center {
        text-align: center;
    }
table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

table th, table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: center;
}

table th {
    background-color: #0070C0;
    
}
.grand_total th {
    background-color: red;
    color: white;
}

.remarks {
    margin-bottom: 20px;
}

.signatures {
    display: flex;
    justify-content: space-between;
    
}
.signatures p {
        border-top: 1px solid #000;

    
}
.summary, .final {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
}

.summary p, .final p {
    margin: 0;
    font-weight: bold;
    font-size:14px;
}

.summary span, .final span {
    font-weight: normal;
}
    </style>
</head>
<body>
    <!-- Paste your existing HTML template here -->
    @foreach($results as $key => $result)
    @if($result['grand_total']>0)
    <div class="cert-container print-m-0">
        <div id="content2" class="cert">
            <img src="https://abasynschools.com/public/uploads/business_logos/dmc.jpeg" class="cert-bg" alt="" />
            <div class="cert-content">
                <div class="logo-content">
                <div style="float: left; width: 75%; margin-right: 5px;">
                    @include('common.logo')
                </div>

                <div style="float: left; width: 20%; ">
                    @if (file_exists(public_path('uploads/student_image/' . $result['student_image'])))
                    <img width="100%" height="100" src="{{ url('uploads/student_image/' . $result['student_image']) }}" />
                    @else
                    <img width="100%" height="100" src="{{ url('uploads/student_image/default.png') }}" />
                    @endif </div>
                 
                <div style="clear: both;"></div>
                <div class="header">
        <h2 class="cursive bold" id="head">FIRST TERM EXAMINATION</h2>
        <h3 class="cursive bold" id="head">{{ $result['session'] }}</h3>
        <div class="ribbon">

        <h5>RESULT CARD</h5>
        </div>
        <br>
       
<div class="flex-container">
    <div class="text-align-center">
        @lang('english.roll_no'): <strong class="underline">{{ ucwords($result['student_roll_no'])  }}</strong>
    </div>
    <div class="text-align-center">
        @lang('english.name'): <strong class="underline">{{ ucwords($result['student_name'])  }}</strong>
    </div>
    <div class="text-align-center">
        @lang('english.s/d_of'): <strong class="underline">{{ ucwords($result['father_name'])  }}</strong>
    </div>
    <div class="text-align-center">
        @lang('english.class'): <strong class="underline">{{ ucwords($result['class'])  }}</strong>
    </div>
</div>
<br>
        <table>
            <thead>
                <tr >
                    @foreach($result['exam_title'] as $title)
                    <th>{{$title}}</th>
                    @endforeach
                </tr>
               
            </thead>
            <tbody>
               
                     @foreach($result['subjects'] as $key=>$subject)
                      <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $key }}</td>
                     @foreach($subject as $obtain_mark)
                      @if (($obtain_mark['subject_name']==='dump'))
                     <td></td>
                     @else
                     <td>{{$obtain_mark['total_obtain_mark']}}</td>
                     @endif
                    @endforeach
                     </tr>
           
                    @endforeach
                  
                  <tr style="background:#8a9b67;">
                      <td colspan="2">OBT Marks</td>
                     @foreach($result['total_marks'] as $key=>$total_mark)
                    <td>{{ @num_format($total_mark['obtain_mark']) }}</td>
                    @endforeach
                      </tr>
                      <tr style="background:#9c91af;">
                      <td colspan="2">Total</td>
                     @foreach($result['total_marks'] as $key=>$total_mark)
                    <td>{{ @num_format($total_mark['total_mark']) }}</td>
                    @endforeach
                      </tr>
                          <tr style="background:#8fcedf;">
                      <td colspan="2">Percentage</td>
                     @foreach($result['total_marks'] as $key=>$total_mark)
                    <td>{{ @num_format($total_mark['final_percentage']) }}</td>
                    @endforeach
                      </tr>
                      
                     
            </tbody>
        </table>
           <table class="grand_total">
            <thead>
                <tr>
                    <th>Grand Total</th>
                    <td>{{ @num_format($result['grand_total'])}}</th>
                    <th >OBT Marks</th>
                    <td>{{ @num_format($result['grand_obt_total'])}}</th>
                     <th >Grade</th>
                    <td>{{$result['grade_name']}}</th>
                    {{-- <th >Remark</th>
                    <td>{{$result['grade_remark']}}</th>--}}
                     <th >Percentage</th>
                    <td>{{ @num_format($result['grand_btain_percentage'])}}</th>
                   
                </tr>
                        </table>
                        
                          <br>
                          
                           <div class="summary">
            <p>Summer Task Total Marks: <span>___</span></p>
            <p>OBT Marks: <span>___</span></p>
            <p>Behaviour Total Marks: <span>___</span></p>
            <p>OBT Marks: <span>___</span></p>
        </div>
        <br>
          <br>
 <div class="remarks">
            <p>Remarks:_______________________________________________________________</p>
        </div>
          <br>
            <br>
              <br>
        <div class="signatures">
            <p>Class Teacher</p>
            <p>Controller of Examinations</p>
            <p>Principal</p>
        </div>
                </div>
            </div>

        </div>
          @endif
        @endforeach

</body>
</html>
