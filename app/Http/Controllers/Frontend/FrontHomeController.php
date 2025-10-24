<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\ExtraStudentData;
use App\Models\Frontend\FrontSlider;
use App\Models\Frontend\FrontNews;
use App\Models\Frontend\FrontAboutUs;
use App\Models\Frontend\FrontGalleryContent;
use App\Models\Frontend\FrontEvent;
use App\Models\Frontend\OnlineApplicant;
use App\Models\Gallery;
use App\Models\Slider;
use App\Models\Student;
use App\Models\SystemSetting;
use App\Repositories\ExtraFormField\ExtraFormFieldsInterface;
use App\Repositories\SchoolSetting\SchoolSettingInterface;
use App\Services\CachingService;
use App\Services\ResponseService;
use App\Services\UploadService;
use Illuminate\Http\Request;
use App\Models\Exam\ExamCreate;
use App\Models\Exam\ExamAllocation;
use App\Models\Frontend\FrontCustomPageNavbar;
use App\Models\Frontend\FrontCustomPage;

use App\Models\ClassSection;
use App\Models\Campus;
use App\Models\ClassLevel;
use App\Models\Users;
use App\Models\Classes;
use App\Models\Session;
use App\Models\District;
use App\Utils\Util;
use App\Models\HumanRM\HrmEmployee;
use App\Models\HumanRM\HrmEducation;
use DB;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Notifications\ContactFormMail;
use App\Notifications\SendMessageToEndUser;
use App\Mail\TestEmail;
use Illuminate\Http\JsonResponse;
use Throwable;
use Validator;

class FrontHomeController extends Controller
{
    protected $studentUtil;
    private  $cache;


    /**
     * Constructor
     *
     * @return void
     */
    public function __construct(CachingService $cache, Util $commonUtil, SchoolSettingInterface $schoolSettings, ExtraFormFieldsInterface $extraFormFields)
    {
        $this->commonUtil = $commonUtil;
        $this->schoolSettings = $schoolSettings;
        $this->cache = $cache;
        $this->extraFormFields = $extraFormFields;

        $this->student_status_colors = [
            'active' => 'bg-success',
            'inactive' => 'bg-info',
            'struct_up' => 'bg-warning',
            'pass_out' => 'bg-danger',
             'took_slc' => 'bg-secondary',
        ];
    }

 


    public function index()
    {

       

        $sliders = Slider::whereIn('type',[2,3])->get();
        
        if (!count($sliders)) {
            $sliders = [
                url('assets/school/images/heroImg1.jpg'),
                url('assets/school/images/heroImg2.jpg'),
            ];
        }
        $faqs = \App\Models\Frontend\Faq::get();

        $students = Student::whereHas('user', function($q) {
            $q->where('status','active');
        })->count();

        $classes = 15;
        $streams =20;

        $counters = [
            'students' => $students,
            'classes' => $classes,
            'streams' => $streams,
        ];

        $announcements =  Announcement::where('table_type','!=','App\Models\SubjectTeacher')->with('file','table')->orderBy('id','DESC')->take(10)->get();
     //  dd($announcements);
        // Announcement::whereHas('announcement_class',function($q) {
        //     $q->where('class_subject_id',null);
        // })->with('announcement_class.class_section.class','announcement_class.class_section','announcement_class.class_section')->orderBy('id','DESC')->take(10)->get();

        $class_groups =ClassLevel::get();
        $schoolSettings=$this->cache->getSchoolSettings();
        
        return view('frontend.school-website.index',compact('sliders','faqs','counters','announcements','class_groups','schoolSettings'));
    }










    
    public function photo()
    {
        return view('frontend.school-website.photo');
    }

    public function photo_file($id)
    {
        try {
            $photos = Gallery::with(['file' => function($q) {
                $q->where('type',1);
            }])->find($id);
            if ($photos) {
                return view('frontend.school-website.photo_file',compact('photos'));
            } else {
                return redirect('photos');
            }
        } catch (Throwable $th) {
            return redirect('photos');
        }
    }

    public function video()
    {
        return view('frontend.school-website.video');
    }

    public function video_file($id)
    {
        try {
            $videos = Gallery::with(['file' => function($q) {
                $q->where('type',2);
            }])->find($id);
            if ($videos) {
               //dd(  $videos);
                return view('frontend.school-website.video_file',compact('videos'));
            } else {
                return redirect('videos');
            }
        } catch (\Throwable $th) {
            return redirect('videos');
        }
    }

    public function terms_conditions()
    {
        return view('frontend.school-website.terms_conditions');
    }

    public function privacy_policy()
    {
        return view('frontend.school-website.privacy_policy');
    }

    public function refund_cancellation()
    {
        return view('frontend.school-website.refund_cancellation');
    }






    public function indexResult()
    {
        $sessions=Session::forDropdown();
        return view('frontend.school-website.result',compact('sessions'));
    }

    public function about_us()
    {
       
        return view('frontend.school-website.about_us');
    }
    
    public function contact_us()
    {
        return view('frontend.school-website.contact');
    }

    public function contact_form(Request $request) {


    
    }

    
    public function admission()
    {
               
        $classes = Classes::get();       
        return view('frontend.school-website.admission', compact('classes'));
    }
    
    public function registerStudent(Request $request)
    {
       
        $request->validate([
            'first_name'          => 'required',
            'last_name'           => 'required',
            'mobile'              => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/',
            'image'               => 'nullable|mimes:jpeg,png,jpg,svg|image|max:2048',
            'dob'                 => 'required',
            'class_id'            => 'required|numeric',
            /*NOTE : Unique constraint is used because it's not school specific*/
            'guardian_email'      => 'required|email|unique:online_applicants,guardian_email', // Adjust the table and column as needed
            'guardian_first_name' => 'required|string',
            'guardian_last_name'  => 'required|string',
            'guardian_mobile'     => 'required|numeric',
            'guardian_gender'     => 'required|in:male,female',
            'guardian_image'      => 'nullable|mimes:jpg,jpeg,png|max:4096',
            
        ]);
       
        try {
           DB::beginTransaction();

            // Get the current application date and active session
            $application_date = Carbon::now()->format('Y-m-d');
            $session_id = $this->commonUtil->getActiveSession();
    
            // Upload guardian image if provided
            $guardian_image = null;
            if ($request->hasFile('guardian_image')) {
                $guardian_image = UploadService::upload($request->file('guardian_image'), 'guardian');
            }
    
            // Upload applicant image if provided
            $applicant_image = null;
            if ($request->hasFile('image')) {
                $applicant_image = UploadService::upload($request->file('image'), 'user');
            }
    
            // Create new applicant record
           $student= OnlineApplicant::create([
                'first_name'          => $request->input('first_name'),
                'last_name'           => $request->input('last_name'),
                'dob'                 => $request->input('dob'),
                'mobile'              => $request->input('mobile'),
                'current_address'     => $request->input('current_address'),
                'permanent_address'   => $request->input('permanent_address'),
                'class_id'            => $request->input('class_id'),
                'gender'              => $request->input('gender'),
                'guardian_first_name' => $request->input('guardian_first_name'),
                'guardian_last_name'  => $request->input('guardian_last_name'),
                'guardian_mobile'     => $request->input('guardian_mobile'),
                'guardian_email'      => $request->input('guardian_email'),
                'guardian_gender'     => $request->input('guardian_gender'),
                'image'               => $applicant_image,
                'guardian_image'      => $guardian_image,
                'session_id'      => $session_id,
                'application_date'      => $application_date,
                'campus_id'      => 1,
                'status'              => 'pending', // Default to 'pending'
            ]);
            $extraFields =$request->extra_fields ?? [];
            // Store Extra Details
            $extraDetails = array();
            foreach ($extraFields as $fields) {
                $data = null;
                if (isset($fields['data'])) {
                    $data = (is_array($fields['data']) ? json_encode($fields['data'], JSON_THROW_ON_ERROR) : $fields['data']);
                }
                $extraDetails[] = array(
                    'student_id'    => $student->id,
                    'form_field_id' => $fields['form_field_id'],
                    'data'          => $data,
                );
            }
            if (!empty($extraDetails)) {
                $this->extraFormFields->createBulk($extraDetails);
            }
          

            DB::commit();
            ResponseService::successResponse('Student Registered successfully');
        } catch (Throwable $e) {
                DB::rollBack();
                ResponseService::logErrorResponse($e, "Student Controller -> Store method");
                ResponseService::errorResponse();
        }
    }
 
    public function getExamTerm(Request $request)
    {
        if (!empty($request->input('session_id'))) {
            $session_id = $request->input('session_id');

            $terms=ExamCreate::forDropdownFront($session_id);

            $html = '<option value="">' . __('english.please_select') . '</option>';

            if (!empty($terms)) {
                foreach ($terms as $id => $title) {
                    $html .= '<option value="' . $id .'">' . $title. '</option>';
                }
            }
            return $html;
        }
    }
    public function getResult(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'session_id'  => 'required',
            'exam_create_id'  => 'required',
            'rollno' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
    
        // Retrieve Student
        $student = Student::where('roll_no', $request->rollno)->first();
        if (!$student) {
            $title="Student not found";
            $message="Sorry, the student you are looking for does not exist in the records. Please try again with a different Roll Number.";
            return view('frontend.school-website.not_found', compact('message','title'))->render();
        }
    
        // System details
        $system_detail = SystemSetting::first();
        try {
            $details = ExamAllocation::with([
                'student', 'campuses', 'session', 'current_class', 'current_class_section',
                'exam_create', 'exam_create.term', 'grade', 'subject_result',
                'subject_result.subject_grade', 'subject_result.subject_name'
            ])
            ->where('student_id', $student->id)
            ->where('session_id', $request->session_id)
            ->where('exam_create_id', $request->exam_create_id)
            ->firstOrFail();
        } catch (\Exception $e) {
            $title = "Result Not Found";
            $message = "Sorry, the result may not have been published yet. Please contact the school for further information.";
            return view('frontend.school-website.not_found', compact('message','title'))->render();
        }
        // Render View for AJAX Request
        if ($request->ajax()) {
            return view('frontend.school-website.marks_data', compact('details'))->render();
        }
    
        // Generate PDF
        $pdf = \WPDF::loadView('ApiView.result_card', compact('details', 'system_detail'));
        $pdf->setPaper('a4')->setOption('margin-top', 10)->setOption('margin-left', 5)->setOption('margin-right', 5);
    
        $pdfContent = $pdf->output();
        return response($pdfContent, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename=' . $request->rollno . '.pdf');
    }
    public function event_show($slug, $id)
    {
        $event = FrontEvent::where('id', $id)
        // ->orWhere('slug', $slug)
         ->firstOrFail();
         $upcomingEvents = FrontEvent::orderBy('id', 'DESC')
         ->where('status', 'publish')
         ->whereDate('from', '>', now()) // Events that started before or on today
         ->get();
        return view('frontend.school-website.event_show')->with(compact('event', 'upcomingEvents'));
    }
    public function show_page_index($slug, $id)
    {
        $data = FrontCustomPage::first();
        
        //dd();
        return view('frontend.school-website.custom_page_show')->with(compact('data'));
    }
    public function show_page($slug, $id)
    {
        $data = FrontCustomPage::where('id',$id)->first();
       // dd(json_decode($data->elements));
        return view('frontend.school-website.custom_page_show')->with(compact('data'));
    }


}
