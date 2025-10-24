<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\HumanRM\HrmShift;
use App\Models\HumanRM\HrmEmployee;
use Carbon\Carbon;
use App\Utils\NotificationUtil;
use App\Models\Student;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    /**
    * Constructor
    *
    * @param NotificationUtil $notificationUtil
    * @return void
    */
    public function __construct(NotificationUtil $notificationUtil)
    {
        $this->notificationUtil= $notificationUtil;
    }

 public function store(Request $request)
    {
        $data = $request->input('data');
        $successCount = 0;
        $failedCount = 0;
        $results = [];
        \Log::emergency('Input data received', [
    'input_data' => $data 
]);
       
        // Loop through each attendance record in the data array
        foreach ($data as $record) {
            try {
                // Parse the date for the current record
                $date = Carbon::parse($record['date']);
                
                $shift = HrmShift::find(2);
                $student = Student::where('roll_no', $record['student_id'])->first();
    
                if (!$student) {
                    $failedCount++;
                     $output  = [
                        'success' => false,
                        'msg' => "Student with ID {$record['student_id']} not found",
                        'student_id' => $record['student_id']
                    ];
                    continue;
                }
    
                $attendance = Attendance::where('student_id', $student->id)
                    ->whereDate('clock_in_time', $date->format('Y-m-d'))
                    ->first();
            if (empty($attendance)) {
                    // Clock in
                    $attendance_type = 'present';
                    
                    if (!empty($shift->start_time)) {
                        // Get actual clock in time object
                        $actual_clock_in_time = Carbon::parse($date);
                        
                        // Get the date from clock-in time
                        $clock_in_date = $actual_clock_in_time->format('Y-m-d');
                        
                        // Combine the date with shift start time to get the expected clock in time
                        $expected_clock_in = Carbon::parse($clock_in_date . ' ' . $shift->start_time);
                        
                        // If student is late, mark as late
                        if ($actual_clock_in_time->gt($expected_clock_in)) {
                            $attendance_type = 'late';
                        }
                    }
    
                    $std_attendance = Attendance::create([
                        'student_id' => $student->id,
                        'clock_in_time' => $date,
                        'type' => $attendance_type,
                        'session_id' => $this->notificationUtil->getActiveSession(),
                    ]);
    
                    $this->notificationUtil->autoSendNotification('student_attendance_check_in', null, $student, $std_attendance);
                    
                    $successCount++;
                     $output  = [
                        'success' => true,
                        'msg' => 'Thank You ' . $student->roll_no,
                        'student_id' => $student->roll_no
                    ];
                } else {
                    // Clock out
                    $can_clockout = Carbon::parse($shift->end_time)->lessThanOrEqualTo($date);
                    
                    if ($can_clockout) {
                        $attendance->clock_out_time = $date;
                        $attendance->save();
                        
                        $this->notificationUtil->autoSendNotification('student_attendance_check_out', null, $student, $attendance);
                        
                        $successCount++;
                         $output  = [
                            'success' => true,
                            'msg' => __("english.clock_out_success", ['id' => $student->roll_no]),
                            'type' => 'clock_out',
                            'student_id' => $student->roll_no
                        ];
                    } else {
                        $failedCount++;
                         $output  = [
                            'success' => false,
                            'msg' => __("english.school_time_not_over", ['id' => $student->roll_no]),
                            'type' => 'clock_out',
                            'student_id' => $student->roll_no
                        ];
                    }
                }
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
                
                $failedCount++;
                 $output  = [
                    'success' => false,
                    'msg' => __("english.something_went_wrong"),
                    'error' => $e->getMessage(),
                    'student_id' => $record['student_id'] ?? 'unknown'
                ];
            }
        }
    
        return $output;
    }

}
