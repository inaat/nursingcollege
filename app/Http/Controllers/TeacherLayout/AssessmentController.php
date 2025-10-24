<?php

namespace App\Http\Controllers\TeacherLayout;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssessmentHeading;
use App\Models\AssessmentStudent;
use App\Models\AssessmentStudentHeading;
use App\Models\AssessmentSubHeading;
use App\Models\ClassSection;
use App\Models\Student;
use App\Models\HumanRM\HrmEmployee;
use Carbon\Carbon;

use Illuminate\Support\Facades\Validator;
use DB;
use File;

class AssessmentController extends Controller
{
    public function index()
    {
        $user = \Auth::user();
        if ($user->user_type == 'teacher') {
            $class_teacher = ClassSection::where('teacher_id', $user->hook_id)->first();
            if (!empty($class_teacher)) {
                $students = Student::where('current_class_id', $class_teacher->class_id)->where('current_class_section_id', $class_teacher->id)
                    ->where('status', 'active')->select('id', DB::raw("CONCAT(COALESCE(students.first_name, ''),' ',COALESCE(students.last_name,''),'(',COALESCE(students.roll_no,''),')') as student_name"))->pluck('student_name', 'id');
                return view('teacher_layouts.assessment.index')->with(compact('students'));

            }else{
               abort(403, 'Unauthorized action.'); 
            }
        } else {
            abort(403, 'Unauthorized action.');

        }

    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            $response = array(
                'error' => true,
                'message' => $validator->errors()->first(),
                'code' => 102,
            );
            return response()->json($response);
        }
        try {
            $student_id = $request->student_id;
            $date = Carbon::now()->format('Y-m-d');
            $MONTH = \Carbon::createFromFormat('Y-m-d', $date)->format('m');
            $YEAR = \Carbon::createFromFormat('Y-m-d', $date)->format('Y');
            $check_assessment = AssessmentStudent::with(['heading_lines'])->whereMonth("assessment_date", $MONTH)
                ->whereYear("assessment_date", $YEAR)->where('student_id', $request->student_id)->first();
            $ids = array();
            ;
            $data = [];
            if (!empty($check_assessment)) {
                foreach ($check_assessment->heading_lines as $h) {
                    array_push($ids, $h->sub_heading_id);
                    $heading = AssessmentSubHeading::with(['heading'])->orderBy('heading_id', 'ASC')->find($h->sub_heading_id);
                    $data[] = [
                        'id' => $heading->id,
                        "title" => $heading->heading->name,
                        "subTitle" => $heading->name,
                        "isAverage" => $h->isAverage == 1 ? true : false,
                        "isGood" => $h->isGood == 1 ? true : false,
                        "isPoor" => $h->isPoor == 1 ? true : false,
                    ];

                }

            }
            $headings = AssessmentSubHeading::with(['heading'])->whereNotIn('id', $ids)->orderBy('heading_id', 'ASC')->get();
            foreach ($headings as $key => $heading) {
                $data[] = [
                    'id' => $heading->id,
                    "title" => $heading->heading->name,
                    "subTitle" => $heading->name,
                    "isAverage" => false,
                    "isGood" => false,
                    "isPoor" => false,
                ];
            }

            $assessments = array();

            foreach ($data as $key => $item) {
                $assessments[$item['title']][$key] = $item;
            }
            // foreach ($assessments as $key => $item) {
//     dd($item[0]['title']);
//  }
            return view('teacher_layouts.assessment.create')->with(compact('assessments', 'student_id'));

        } catch (\Exception $e) {
            $response = array(
                'error' => true,
                'message' => trans('error_occurred'),
                'code' => 103,
            );
        }
        return response()->json($response);
    }

    public function postAssessment(Request $request)
    {
        // dd($request->input());
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|numeric',
        ]);
        if ($validator->fails()) {

            $output = [
                'success' => false,
                'msg' => __("english.something_went_wrong")
            ];

            return redirect('assessment')->with('status', $validator->errors()->first());
        }
        try {
            DB::beginTransaction();
            $date = Carbon::now()->format('Y-m-d');
            $student = Student::find($request->student_id);
            $MONTH = \Carbon::createFromFormat('Y-m-d', $date)->format('m');
            $YEAR = \Carbon::createFromFormat('Y-m-d', $date)->format('Y');
            $check_assessment = AssessmentStudent::whereMonth("assessment_date", $MONTH)
                ->whereYear("assessment_date", $YEAR)->where('student_id', $request->student_id)->first();
            if (!empty($check_assessment)) {
                $check_assessment->delete();

            }
            $assessment = new AssessmentStudent();
            $assessment->campus_id = $student->campus_id;
            $assessment->class_id = $student->current_class_id;
            $assessment->class_section_id = $student->current_class_section_id;
            $assessment->student_id = $student->id;
            $assessment->employee_id = 1;
            $assessment->assessment_date = $date;
            $assessment->save();

            foreach ($request->assessment as $heading) {
                //dd($heading['status']);
                $sub = new AssessmentStudentHeading();
                $headings = AssessmentSubHeading::find($heading['id']);
                $sub->assessment_students_id = $assessment->id;
                $sub->heading_id = $headings->heading_id;
                $sub->sub_heading_id = $heading['id'];
                if (!empty($heading['status'])) {
                    $sub->isAverage = $heading['status'] == 'isAverage' ? true : false;
                    $sub->isGood = $heading['status'] == "isGood" ? true : false;
                    $sub->isPoor = $heading['status'] == "isPoor" ? true : false;
                } else {
                    $sub->isAverage = false;
                    $sub->isGood = false;
                    $sub->isPoor = false;
                }
                $sub->save();
            }



            DB::commit();
            $output = [
                'success' => true,
                'msg' => __("english.added_success")
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            $output = [
                'success' => false,
                'msg' => __("english.something_went_wrong")
            ];
        }
        return redirect('assessment')->with('status', $output);
    }
}