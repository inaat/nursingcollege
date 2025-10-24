<?php

namespace App\Http\Controllers;

use App\Models\Campus;
use App\Models\HumanRM\HrmDepartment;
use App\Models\HumanRM\HrmDesignation;
use App\Models\HumanRM\HrmEmployee;
use App\Models\HumanRM\HrmNotificationTemplate;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use App\Utils\Util;
use App\Utils\NotificationUtil;
use App\Utils\StudentUtil;
use Yajra\DataTables\Facades\DataTables;

class NotificationTemplateController extends Controller
{
    protected $util;
    protected $notificationUtil;
    protected $studentUtil;

    protected $templateForMapping = [
        'attendance_check_in' => 'HRM Attendance Check In',
        'attendance_check_out' => 'HRM Attendance Check Out',
        'shift_is_not_over' => 'HRM Shift is Not Over',
        'student_attendance_check_in' => 'Student Attendance Check In',
        'student_attendance_check_out' => 'Student Attendance Check Out',
        'payment_received' => 'Payment Received',
        'fee_due_sms' => 'Fee Due SMS',
        'student_attendance_absent_sms' => 'Student Attendance Absent SMS',
        'attendance_absent_sms' => 'HRM Attendance Absent SMS',

        'student_attendance_late_sms' => 'Student Attendance Late SMS',
        'student_attendance_half_day_sms' => 'Student Attendance Half Day SMS',
        'student_attendance_leave_sms' => 'Student Attendance Leave SMS',
    ];

    public function __construct(Util $util, NotificationUtil $notificationUtil, StudentUtil $studentUtil)
    {
        $this->util = $util;
        $this->notificationUtil = $notificationUtil;
        $this->studentUtil = $studentUtil;
    }

    public function index()
    {
        if (request()->ajax()) {
            $templates = HrmNotificationTemplate::select(['template_for', 'id', 'sms_body','auto_send_sms']);

            return DataTables::of($templates)
                ->addColumn('action', function ($row) {
                    return sprintf(
                        '<div class="dropdown">
                            <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">%s</button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="%s" class="dropdown-item">
                                        <i class="bx bxs-edit f-16 mr-15"></i>%s
                                    </a>
                                </li>
                            </ul>
                        </div>',
                        __("english.actions"),
                        action('NotificationTemplateController@edit', [$row->id]),
                        __("english.edit")
                    );
                })
                ->editColumn('template_for', function ($row) {
                    if($row->auto_send_sms){
                    return '<input class="form-check-input big-checkbox" checked type="checkbox" value="0">'.'  '.$this->getTemplateForLabel($row->template_for);
                    }
                    else{
                        return $this->getTemplateForLabel($row->template_for);

                    }
                })
                ->removeColumn('id'.'auto_send_sms')
                ->rawColumns(['action', 'template_for', 'sms_body'])
                ->make(true);
        }

        return view('notification.template.index');
    }

    public function edit($id)
    {
        $template = HrmNotificationTemplate::findOrFail($id); // Added fail-safe method
        $template_name = $this->getTemplateForLabel($template->template_for);
        $employee=HrmEmployee::first();
        $student=\App\Models\Student::first();
  //      $this->notificationUtil->replaceTags($template->sms_body,$employee,$student);

        $allTags = $this->notificationUtil->notificationsTags();
        $tags = [];

        // Improved conditional logic
        if (in_array($template->template_for, ['attendance_check_in', 'attendance_check_out', 'shift_is_not_over','attendance_absent_sms'])) {
            $tags[] = $allTags['tags']['attendance'];
            $tags[] = $allTags['tags']['school'];
            $tags[] = $allTags['tags']['employee'];
        } else {
            $tags[] = $allTags['tags']['attendance'];
            $tags[] = $allTags['tags']['school'];
            $tags[] = $allTags['tags']['student'];
            if($template->template_for=='payment_received'){
                $tags[] = $allTags['tags']['payment'];

            }
        }

        return view('notification.template.edit')->with(compact('template', 'template_name', 'tags'));
    }

    protected function getTemplateForLabel($key)
    {
        return $this->templateForMapping[$key] ?? $key;
    }

    public function update(Request $request, $id)
    {
     
        $request->validate([
            'sms_body' => 'required', // Added validation
        ]);
       
        try {
            $template = HrmNotificationTemplate::findOrFail($id); // Added fail-safe method
            $template->sms_body = $request->input('sms_body');
            if (request()->has('auto_send_sms')) { 
                $template->auto_send_sms=1;
            }
            else{
                $template->auto_send_sms=0;

            }
            $template->save();

            $output = [
                'success' => true,
                'msg' => __("english.updated_success"),
            ];
        } catch (\Exception $e) {
            report($e); // Log the error

            $output = [
                'success' => false,
                'msg' => __("english.something_went_wrong"),
            ];
        }
     
        return redirect('sms-email-templates')->with('status', $output);
    }
   
    
}