<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\HumanRM\HrmAttendance;
use App\Models\HumanRM\HrmEmployeeShift;
use App\Models\HumanRM\HrmShift;
use App\Models\HumanRM\HrmEmployee;
use Carbon\Carbon;
use App\Utils\NotificationUtil;
use App\Models\Student;
use App\Models\Attendance;
use DB;

class EmployeeAttendanceController extends Controller
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





    public function getEmployees(Request $request)
    {
        // Get the 'type' parameter from the request
        $type = $request->input('type');
    
        if ($type === 'staff') {
            // Fetch active staff members
            $employees = HrmEmployee::select(
                'employeeID as userId',
                'status',
                DB::raw("TRIM(CONCAT(first_name, ' ', last_name)) AS name")
            )->get();
        } elseif ($type === 'student') {
            // Fetch active students
            $employees = Student::select(
                'roll_no  as userId', // Adjust the column name if different
                'status',
                DB::raw("TRIM(CONCAT(first_name, ' ', last_name)) AS name")
            )->get();
        } else {
            // Handle invalid or missing type
            return response()->json(['error' => 'Invalid or missing type parameter'], 400);
        }
    
        // Return the data as a JSON response
        return response()->json($employees);
    }
    
    public function store(Request $request)
   { // Validate incoming request data
    $data = $request->input('data');

    $successCount = 0;
    $failedCount = 0;

    // Loop through each attendance record in the data array
    foreach ($data as $record) {
        try {
            // Parse the date for the current record
            $date = Carbon::parse($record['date']);
            
            // Retrieve employee by employee_id
            $employee = HrmEmployee::where('employeeID', $record['employee_id'])->first();
            
            if (!$employee) {
                \Log::error("Employee not found", ['employee_id' => $record['employee_id']]);
                $failedCount++;
                continue; // Skip to the next record if employee is not found
            }

            // Check if the employee has already clocked in on this date
            $attendance = HrmAttendance::where('employee_id', $employee->id)
                ->whereDate('clock_in_time', $date->format('Y-m-d'))
                ->first();

            if (empty($attendance)) {
                // If not clocked in, perform clock-in
                $clockInData = [
                    'employee_id' => $employee->id,
                    'clock_in_time' => $date,
                    'ip_address' => request()->ip(), // Get the actual IP address
                    'session_id' => $this->notificationUtil->getActiveSession(),
                ];

                $output = $this->clockin($clockInData, [], $employee);
                \Log::info('Clock In Data:', ['data' => $clockInData, 'output' => $output]);

            } else {
                // If clocked in, perform clock-out
                $clockOutData = [
                    'employee_id' => $employee->id,
                    'clock_out_time' => $date,
                    'ip_address' => request()->ip(), // Get the actual IP address
                    'session_id' => $this->notificationUtil->getActiveSession(),
                ];

                $output = $this->clockout($clockOutData, [], $employee);
                \Log::info('Clock Out Data:', ['data' => $clockOutData, 'output' => $output]);
            }

            $successCount++; // Increment success count if no errors

        } catch (\Exception $e) {
            // Log the error and move on to the next record
            \Log::emergency("Error in processing attendance", [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'message' => $e->getMessage(),
            ]);

            $failedCount++; // Increment failed count for this record
        }
    }
}
    


     public function clockout($data, $essentials_settings, $employee)
     {
         $date = $data['clock_out_time'];

         //Get clock in
         $clock_in = HrmAttendance::where('employee_id', $data['employee_id'])
                                 ->whereNull('clock_out_time')
                                 ->whereDate('clock_in_time', $date->format('Y-m-d'))
                                 ->first();

         $clock_out_time =  $date;
         if (!empty($clock_in)) {
           //  dd($clock_in,$essentials_settings,$clock_out_time,$shift );
             $can_clockout = $this->canClockOut($clock_in, $essentials_settings, $clock_out_time);
            // dd($can_clockout);

             if (!$can_clockout) {
                 $response=$this->notificationUtil->autoSendNotification('shift_is_not_over', $employee, null,$clock_in);
                 $output = ['success' => false,
                         'msg' => __("english.shift_not_over", ['id' => $employee->employeeID]),
                         'type' => 'clock_out'
                     ];

                 return $output;
             }

             $clock_in->clock_out_time = $data['clock_out_time'];
             $clock_in->save();
             $clock_in = HrmAttendance::where('employee_id', $data['employee_id'])
             ->whereNull('clock_out_time')
             ->first();

             //dd($clock_in);
             $response=$this->notificationUtil->autoSendNotification('attendance_check_out', $employee, null,$clock_in);

             $output = ['success' => true,
                     'msg' => __("english.clock_out_success", ['id' => $employee->employeeID]),
                     'type' => 'clock_out'
                 ];
         } else {
             $output = ['success' => false,
                     'msg' => __('english.not_clocked_in', ['id' => $employee->employeeID]),
                     'type' => 'clock_out'
                 ];
         }

         return $output;
     }
        /**
      * Validates user clock out
      */
      public function canClockOut($clock_in, $settings, $clock_out_time = null)
{
    $shift = HrmShift::find($clock_in->shift_id);
    
    // If no shift end time is defined, always allow clock out
    if (empty($shift->end_time)) {
        return true;
    }
    
    // If flexible shift, always allow clock out
    if ($shift->type == 'flexible_shift') {
        return true;
    }
    
    // Get grace periods from settings
    $grace_before_checkout = !empty($settings['grace_before_checkout']) ? (int) $settings['grace_before_checkout'] : 0;
    $grace_after_checkout = !empty($settings['grace_after_checkout']) ? (int) $settings['grace_after_checkout'] : 0;
    
    // Get the date from clock-in time
    $clock_in_date = \Carbon::parse($clock_in->clock_in_time)->format('Y-m-d');
    
    // Combine the date from clock-in time with the shift end time
    $shift_end_time = \Carbon::parse($clock_in_date . ' ' . $shift->end_time);
    
    // Calculate the clock out window
    $clock_out_start = $shift_end_time->copy()->subMinutes($grace_before_checkout);
    
    // Get current time or provided clock out time
    $current_time = empty($clock_out_time) ? \Carbon::now() : \Carbon::parse($clock_out_time);
    
    // Only restrict if current time is less than the allowed clock out start time
    if ($current_time->lt($clock_out_start)) {
        return false;
    }
    
    // Allow clock out in all other cases
    return true;
}

public function clockin($data, $essentials_settings, $employee)
{
    // Check user can clockin
    $clock_in_time = $data['clock_in_time'];
    $shift = $this->checkUserShift($data['employee_id'], $essentials_settings, $clock_in_time);
    
    if (empty($shift)) {
        $available_shifts = $this->getAllAvailableShiftsForGivenUser($data['employee_id'], $clock_in_time);
        
        $output = [
            'success' => false,
            'msg' => __("english.shift_not_allocated", ['id' => $employee->employeeID]),
            'type' => 'clock_in',
            'shift_details' => $available_shifts
        ];
        return $output;
    }
    
    $data['shift_id'] = $shift;
    
    // Check if already clocked in
    $count = HrmAttendance::where('employee_id', $data['employee_id'])
                           ->whereNull('clock_out_time')
                           ->whereDate('clock_in_time', $clock_in_time)
                           ->count();
    
    if ($count == 0) {
        // Check if employee is late
        $shift_details = HrmShift::find($shift);
        $attendance_type = 'present';
        
        if (!empty($shift_details->start_time)) {
            // Get actual clock in time object
            $actual_clock_in_time = Carbon::parse($clock_in_time);
            
            // Get the date from clock-in time
            $clock_in_date = $actual_clock_in_time->format('Y-m-d');
            
            // Combine the date with shift start time to get the expected clock in time
            $expected_clock_in = Carbon::parse($clock_in_date . ' ' . $shift_details->start_time);
            
            // If employee is late, mark as late
            if ($actual_clock_in_time->gt($expected_clock_in)) {
                $attendance_type = 'late';
            }
        }
        
        // Set attendance type
        $data['type'] = $attendance_type;
        
        $hrm_attendance = HrmAttendance::create($data);
        $response = $this->notificationUtil->autoSendNotification('attendance_check_in', $employee, null, $hrm_attendance);
        
        $output = [
            'success' => true,
            'msg' => __("english.clock_in_success", ['id' => $employee->employeeID]),
            'type' => 'clock_in',
            'current_shift' => 'ok',
            'attendance_type' => $attendance_type
        ];
    } else {
        $output = [
            'success' => false,
            'msg' => __("english.already_clocked_in", ['id' => $employee->employeeID]),
            'type' => 'clock_in'
        ];
    }
    
    return $output;
}
      /**
      * Validates user clock in and returns available shift id
      */
     public function checkUserShift($employee_id, $settings, $clock_in_time)
     {
         $shift_id = null;
         $shift_date = $clock_in_time;
         $shift_datetime = $shift_date->format('Y-m-d');
         $day_string = strtolower($shift_date->format('l'));
         $grace_before_checkin = !empty($settings['grace_before_checkin']) ? (int) $settings['grace_before_checkin'] : 0;
         $grace_after_checkin = !empty($settings['grace_after_checkin']) ? (int) $settings['grace_after_checkin'] : 0;
         $clock_in_start = $clock_in_time->subMinutes($grace_before_checkin);
         $clock_in_end =$clock_in_time->addMinutes($grace_after_checkin);
         $user_shifts = HrmEmployeeShift::join('hrm_shifts as s', 's.id', '=', 'hrm_employee_shifts.hrm_shift_id')
                     ->where('employee_id', $employee_id)
                     ->where('start_date', '<=', $shift_datetime)
                     ->where(function ($q) use ($shift_datetime) {
                         $q->whereNull('end_date')
                         ->orWhere('end_date', '>=', $shift_datetime);
                     })
                     ->select('hrm_employee_shifts.*', 's.holidays', 's.start_time', 's.end_time', 's.type')
                     ->get();

         foreach ($user_shifts as $shift) {
             $holidays = json_decode($shift->holidays, true);
             //check if holiday
             if (is_array($holidays) && in_array($day_string, $holidays)) {
                 continue;
             }
             // dd($shift);
             //Check allocated shift time
             // if ((!empty($shift->start_time) && \Carbon::parse($shift->start_time)->between($clock_in_start, $clock_in_end)) || $shift->type == 'flexible_shift') {
             // if ((!empty($shift->start_time) && true) || $shift->type == 'flexible_shift') {
             return $shift->hrm_shift_id;
             // dd($shift);

             //}
         }

         return $shift_id;
     }

     public function getAllAvailableShiftsForGivenUser($employee_id,$date)
     {
         $available_user_shifts = HrmEmployeeShift::join(
             'hrm_shifts as s',
             's.id',
             '=',
             'hrm_employee_shifts.hrm_shift_id'
         )
                                     ->where('employee_id', $employee_id)
                                     ->whereDate('start_date', '<=', $date->format('Y-m-d'))
                                     ->whereDate('end_date', '>=', $date->format('Y-m-d'))
                                     ->select(
                                         'hrm_employee_shifts.start_date',
                                         'hrm_employee_shifts.end_date',
                                         's.name',
                                         's.type',
                                         's.start_time',
                                         's.end_time',
                                         's.holidays'
                                     )
                                     ->get();

         return $available_user_shifts;
     }
}
