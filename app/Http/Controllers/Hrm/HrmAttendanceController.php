<?php

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\HumanRM\HrmAttendance;
use App\Models\HumanRM\HrmEmployee;
use App\Models\Campus;
use Carbon\Carbon;
use App\Utils\NotificationUtil;
use Yajra\DataTables\Facades\DataTables;
use DB;

class HrmAttendanceController extends Controller
{
    /**
     * Constructor
     *
     * @param NotificationUtil $notificationUtil
     * @return void
     */
    public function __construct(NotificationUtil $notificationUtil)
    {
        $this->notificationUtil = $notificationUtil;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->can('employee_attendance.view')) {
            abort(403, 'Unauthorized action.');
        }
        $status = 'present';
        if (request()->has('type')) {
            $status = request()->get('type');
        }
        //dd($HrmEmployees->get());
        if (request()->ajax()) {
            $attendance = HrmAttendance::leftJoin('hrm_employees', 'hrm_employees.id', '=', 'hrm_attendances.employee_id')
                ->leftJoin('campuses', 'hrm_employees.campus_id', '=', 'campuses.id')
                ->select(
                    'campuses.campus_name',
                    'hrm_employees.employeeID',
                    'hrm_attendances.id as id',
                    'hrm_attendances.clock_in_time',
                    'hrm_attendances.clock_out_time',
                    'hrm_attendances.clock_in_note',
                    'hrm_attendances.clock_out_note',


                    DB::raw("CONCAT(COALESCE(hrm_employees.first_name, ''),' ',COALESCE(hrm_employees.last_name,'')) as employee_name")
                );
            $permitted_campuses = auth()->user()->permitted_campuses();
            if ($permitted_campuses != 'all') {
                $attendance->whereIn('hrm_employees.campus_id', $permitted_campuses);
            }
            if (request()->has('campus_id')) {
                $campus_id = request()->get('campus_id');
                if (!empty($campus_id)) {
                    $attendance->where('hrm_employees.campus_id', $campus_id);
                }
            }
            if (request()->has('employeeID')) {
                $employeeID = request()->get('employeeID');
                if (!empty($employeeID)) {
                    $attendance->where('hrm_employees.employeeID', 'like', "%{$employeeID}%");
                }
            }
            if (request()->has('employee_name')) {
                $employee_name = request()->get('employee_name');
                if (!empty($employee_name)) {
                    $attendance->where('hrm_employees.first_name', 'like', "%{$employee_name}%");
                }
            }
            if (request()->has('status')) {
                $status = request()->get('status');
                if (!empty($status)) {
                    $attendance->where('hrm_attendances.type', $status);
                }
            }
            if (!empty(request()->start_date) && !empty(request()->end_date)) {
                $start = request()->start_date;
                $end = request()->end_date;
                $attendance->whereDate('clock_in_time', '>=', $start)
                    ->whereDate('clock_in_time', '<=', $end);
            }
            $datatable = Datatables::of($attendance)
                ->addColumn(
                    'action',
                    function ($row) {
                        $html = '<div class="dropdown">
                         <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">' . __("english.actions") . '</button>
                         <ul class="dropdown-menu" style="">';
                        $html .= '<li><a class="dropdown-item "href="' . action('Hrm\HrmEmployeeController@edit', [$row->id]) . '"><i class="bx bxs-edit "></i> ' . __("english.edit") . '</a></li>';

                        $html .= '</ul></div>';

                        return $html;
                    }
                )
                ->editColumn('work_duration', function ($row) {
                    $clock_in = \Carbon::parse($row->clock_in_time);
                    if (!empty($row->clock_out_time)) {
                        $clock_out = \Carbon::parse($row->clock_out_time);
                    } else {
                        $clock_out = \Carbon::now();
                    }

                    $html = $clock_in->diffForHumans($clock_out, true, true, 2);

                    return $html;
                })
                ->editColumn('clock_in', function ($row) {
                    $html = $this->notificationUtil->format_date($row->clock_in_time, true);


                    if (!empty($row->clock_in_note)) {
                        $html .= '<br>' . $row->clock_in_note . '<br>';
                    }

                    return $html;
                })
                ->editColumn('clock_out', function ($row) {
                    $html = $this->notificationUtil->format_date($row->clock_out_time, true);
                    if (!empty($row->clock_out_note)) {
                        $html .= '<br>' . $row->clock_out_note . '<br>';
                    }

                    return $html;
                })
                ->filterColumn('employeeID', function ($query, $keyword) {
                    $query->where(function ($q) use ($keyword) {
                        $q->where('hrm_employees.employeeID', 'like', "%{$keyword}%");
                    });
                })

                ->filterColumn('employee_name', function ($query, $keyword) {
                    $query->whereRaw("CONCAT(COALESCE(hrm_employees.first_name, ''), ' ', COALESCE(hrm_employees.last_name, '')) like ?", ["%{$keyword}%"]);
                });
            $rawColumns = ['action', 'campus_name', 'clock_in', 'work_duration', 'clock_out'];

            return $datatable->rawColumns($rawColumns)
                ->make(true);
        }
        $campuses = Campus::forDropdown();

        return view('hrm.attendance.index')->with(compact('campuses', 'status'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      
        try {
            $input = $request->input();
            $date = $this->notificationUtil->uf_date($input['date']);
            foreach ($input['attendance'] as $attend) {


                // dd($attend);
                $employee = HrmEmployee::where('id', $attend['employee_id'])->first();
                $attendance = HrmAttendance::where('employee_id', $employee->id)->whereDate('clock_in_time', $date)->first();
                if (empty($attendance)) {
                    if (!empty($attend['status'])) {
                        $std_attendance = HrmAttendance::create([
                            'employee_id' => $employee->id,
                            'clock_in_time' => $this->notificationUtil->uf_date($attend['check_in'], true),
                            'clock_out_time' => $this->notificationUtil->uf_date($attend['check_out'], true),
                            'type' => $attend['status'],
                            'session_id' => $this->notificationUtil->getActiveSession(),
                        ]);
                        if ($attend['status'] == 'present') {
                            $this->notificationUtil->autoSendNotification('attendance_check_in', $employee, null,$std_attendance);
                        } elseif ($attend['status'] == 'absent') {
                            $this->notificationUtil->autoSendNotification('attendance_absent_sms', $employee, null,$std_attendance);

                        }
                    }
                } else {
                    if (!empty($attend['status'])) {
                        $attendance->type = $attend['status'];
                        $attendance->clock_in_time = $this->notificationUtil->uf_date($attend['check_in'], true);
                        $attendance->clock_out_time = $this->notificationUtil->uf_date($attend['check_out'], true);
                        $attendance->save();
                        if ($attend['status'] == 'absent') {
                            $this->notificationUtil->autoSendNotification('attendance_absent_sms', $employee, null,$attendance);

                        }
                    }
                }
            }



            $output = [
                'success' => true,
                'msg' => __("english.updated_success")
            ];

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = [
                'success' => false,
                'msg' => __("english.something_went_wrong")
            ];
        }
        return redirect('hrm-attendance')->with('status', $output);


    }

    public function create()
    {
        if (!auth()->user()->can('employee_attendance.view')) {
            abort(403, 'Unauthorized action.');
        }
        $campuses = Campus::forDropdown();

        return view('hrm.attendance.create')->with(compact('campuses'));
    }

    public function attendanceAssignSearch(Request $request)
    {
        $input = $request->all();

        $campus_id = $input['campus_id'];
        $date = $this->notificationUtil->uf_date($input['date']);
        $employees = HrmEmployee::where('campus_id', $campus_id)->where('status', 'active')->get();
        $attendance_list = HrmAttendance::leftJoin('hrm_employees', 'hrm_employees.id', '=', 'hrm_attendances.employee_id')
            ->where('hrm_employees.campus_id', $campus_id)
            ->where('hrm_employees.status', 'active')
            ->whereDate('clock_in_time', '=', $date)
            ->select(
                'hrm_employees.id as employee_id',
                'hrm_attendances.id as id',
                'hrm_attendances.type',
                'hrm_attendances.clock_in_time',
                'hrm_attendances.clock_out_time',
            )->get();
        $campuses = Campus::forDropdown();

        return view('hrm.attendance.attendance_assign')->with(compact('employees', 'campuses', 'campus_id', 'date', 'attendance_list'));

    }
    public function updateAttendance(Request $request)
    {
        try {
            $input=$request->input();
            $date=$input['attendance_date'];
            if (!empty($input['check_all'])) {
                $employees= HrmEmployee::where('status', 'active')->get();
                $start_date = \Carbon::parse($date)->startOfMonth();
                $end_date = \Carbon::parse($date)->lastOfMonth();
                $days=$this->notificationUtil->generateDateRange($start_date, $end_date);
                foreach ($employees as $employee) {
                    $this->employee_attendance($employee->id, $days, $input['status']);
                }
            }
            
            if (!empty($input['check_single'])) {
             
                $start_date = \Carbon::parse($date)->startOfMonth();
                $end_date = \Carbon::parse($date)->lastOfMonth();
                $days=$this->notificationUtil->generateDateRange($start_date, $end_date);
                $this->employee_attendance($input['attendance_employee_id'], $days, $input['status']);
                
            }
            // if ($input['over_time_hour']>0) {
            //     $check_over= OverTime::where('employee_id', $input['attendance_employee_id'])->whereDate('overtime_date', $date)->first();
            //     if (empty($check_over)) {
            //         $over =['employee_id'=>$input['attendance_employee_id'],'description'=>$input['description'],'over_time_hour'=>$input['over_time_hour'],'overtime_date'=>$date];
            //         $overtime = OverTime::create($over);
            //     } else {
            //         $output = ['success' => false,
            //         'msg' => "Over Time already exist"
            //                ];
            //                return  $output;
            //     }
                
            // }
            $attendance = HrmAttendance::where('employee_id', $input['attendance_employee_id'])->whereDate('clock_in_time', $date)->first();
            if (empty($attendance)) {
                if (!empty($input['status'])) {
                    $std_attendance = HrmAttendance::create([
                    'employee_id' => $input['attendance_employee_id'],
                    'clock_in_time' =>$date,
                    'clock_out_time' =>$date,
                    'type' => $input['status'],
                    'session_id' =>$this->notificationUtil->getActiveSession(),
                                    ]);
                }
            } else {
                if (!empty($input['status'])) {
                    $attendance->type=$input['status'];

                    $attendance->save();
                }
            }


            $output = ['success' => true,
            'msg' => __("english.updated_success")
            ];

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

            $output = ['success' => false,
   'msg' => __("english.something_went_wrong")
];
        }
        return  $output;
     }

        public function employee_attendance($employee_id, $days, $status)
        {
            foreach ($days as $key => $day) {
                if (Carbon::parse($day)->format('l')=='Friday') {
                } else {

                    $attendance = HrmAttendance::where('employee_id', $employee_id)->whereDate('clock_in_time', $day)->first();
                   ///dd($attendance);
                    if (empty($attendance)) {
                        $std_attendance = HrmAttendance::create([
                                'employee_id' => $employee_id,
                                'clock_in_time' =>$day,
                                'clock_out_time' =>$day,
                                'type' => $status,
                                'session_id' =>1,
                            ]);
                    }else{
                       $attendance->type=$status;

                       $attendance->save();
                    }
                    
                }
            }
        }
}
