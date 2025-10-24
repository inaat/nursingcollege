<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Frontend\FrontSlider;
use App\Models\Frontend\FrontNews;
use App\Models\Frontend\FrontAboutUs;
use App\Models\Frontend\FrontGalleryContent;
use App\Models\Frontend\FrontEvent;
use App\Models\Frontend\OnlineApplicant;
use App\Models\Gallery;
use App\Models\Slider;
use App\Models\Student;
use App\Repositories\SchoolSetting\SchoolSettingInterface;
use App\Services\CachingService;
use App\Services\ResponseService;
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

class SchoolWebsiteController extends Controller
{
    protected $studentUtil;
    private  $cache;


    /**
     * Constructor
     *
     * @return void
     */
    public function __construct(CachingService $cache, Util $commonUtil, SchoolSettingInterface $schoolSettings)
    {
        $this->commonUtil = $commonUtil;
        $this->schoolSettings = $schoolSettings;
        $this->cache = $cache;
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

         // $systemSettings = $this->cache->getSystemSettings();
        // if (isset($systemSettings['school_website_feature']) && $systemSettings['school_website_feature'] == 0) {
        //     return redirect('/');
        // }
        ResponseService::noFeatureThenRedirect('Website Management');
        ResponseService::noPermissionThenSendJson('school-web-settings');

        try {
            $settings = $this->cache->getSchoolSettings();
           
            return view('frontend.backend.web.index',compact('settings'));
        } catch (Throwable $e) {
            DB::rollBack();
            ResponseService::logErrorResponse($e, 'WebSettings Controller -> School index  method');
            ResponseService::errorResponse();
        }
       // return view('frontend.backend.web.index');
    }
 
    public function store(Request $request)
    {
        
        ResponseService::noFeatureThenRedirect('Website Management');
        ResponseService::noPermissionThenSendJson('school-web-settings');
        $settings = [

             "school_name" => 'required',
            'school_address' => 'required',
            'school_reg_no' => 'required',
            'school_email' => 'required',
            'school_phone' => 'required',
            'facebook_embed' => 'nullable',
            'google_map_link'=> 'nullable',
            'school_logo_image'=> 'nullable',

            "primary_color" => 'required',
            "secondary_color" => 'required',
            "primary_background_color" => 'required',
            "text_secondary_color" => 'required',
            "primary_hover_color" => 'required',

            "about_us_title" => 'required_if:about_us_status,1',
            "about_us_heading" => 'required_if:about_us_status,1',
            "about_us_description" => 'required_if:about_us_status,1',
            "about_us_image" => 'nullable',
            "about_us_status" => 'required',

            "education_program_title" => 'required_if:education_program_status,1',
            "education_program_heading" => 'required_if:education_program_status,1',
            "education_program_description" => 'required_if:education_program_status,1',
            "education_program_status" => 'required',

            "announcement_title" => 'required_if:announcement_status,1',
            "announcement_heading" => 'required_if:announcement_status,1',
            "announcement_description" => 'required_if:announcement_status,1',
            "announcement_image" => 'nullable',
            "announcement_status" => 'required',

            "counter_title" => 'required_if:counter_status,1',
            "counter_heading" => 'required_if:counter_status,1',
            "counter_description" => 'required_if:counter_status,1',
            "counter_teacher" => 'nullable',
            "counter_student" => 'nullable',
            "counter_class" => 'nullable',
            "counter_stream" => 'nullable',
            "counter_status" => 'required',

            "expert_teachers_title" => 'required_if:expert_teachers_status,1',
            "expert_teachers_heading" => 'required_if:expert_teachers_status,1',
            "expert_teachers_description" => 'required_if:expert_teachers_status,1',
            "expert_teachers_status" => 'required',

            "gallery_title" => 'required_if:gallery_status,1',
            "gallery_heading" => 'required_if:gallery_status,1',
            "gallery_description" => 'required_if:gallery_status,1',
            "gallery_status" => 'required',

            "our_mission_title" => 'required_if:our_mission_status,1',
            "our_mission_heading" => 'required_if:our_mission_status,1',
            "our_mission_description" => 'required_if:our_mission_status,1',
            "our_mission_points" => 'required_if:our_mission_status,1',
            "our_mission_image" => 'nullable',
            "our_mission_status" => 'required',


            "contact_us_heading" => 'required_if:contact_us_status,1',
            "contact_us_description" => 'required_if:contact_us_status,1',
            "contact_us_status" => 'required',

            "faqs_title" => 'required_if:faqs_status,1',
            "faqs_heading" => 'required_if:faqs_status,1',
            "faqs_description" => 'required_if:faqs_status,1',
            "faqs_status" => 'required',

           

   
         
         

            "online_registration_title" => 'required_if:online_registration_status,1',
            "online_registration_heading" => 'required_if:online_registration_status,1',
            "online_registration_description" => 'required_if:aonline_registration_status,1',
            "online_registration_image" => 'nullable',
            "online_registration_status" => 'required',

            "short_description" => 'nullable',
            "footer_text" => 'nullable',
            "footer_logo" => 'nullable',
            

            "facebook" => 'nullable',
            "instagram" => 'nullable',
            "linkedin" => 'nullable',
            "youtube" => 'nullable',
        ];

        $validator = Validator::make($request->all(), $settings);
        
        if ($validator->fails()) {
            $output = ['success' => false,
            'msg' => __("english.something_went_wrong")
            ];     
            return redirect('web-settings')->with('status', $output);

           }
        try {
            DB::beginTransaction();
            $data = array();
            foreach ($settings as $key => $rule) {
                $images = ['school_logo_image','about_us_image', 'counter_teacher','counter_student', 'counter_class', 'counter_stream', 'announcement_image','our_mission_image','footer_logo', 'online_registration_image'];
                if (in_array($key, $images)) {
                    if ($request->hasFile($key)) {
                        // TODO : Remove the old files from server
                        $data[] = [
                            "name" => $key,
                            "data" => $request->file($key),
                            "type" => "file"
                        ];
                    }
                } else {
                    if ($request->$key) {
                        $data[] = [
                            "name" => $key,
                            "data" => $request->$key,
                            "type" => "string"
                        ];    
                    } else {
                        $data[] = [
                            "name" => $key,
                            "data" => 0,
                            "type" => "string"
                        ];
                    }
                    
                }
            }            
            $this->schoolSettings->upsert($data, ["name"], ["data"]);
            $this->cache->removeSchoolCache(config('constants.CACHE.SCHOOL.SETTINGS'));

            DB::commit();
            $output = ['success' => true,
                   'msg' => __("english.added_success")
               ];
       } catch (\Exception $e) {
           \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

           $output = ['success' => false,
           'msg' => __("english.something_went_wrong")
           ];
       }
       return redirect('web-settings')->with('status', $output);
  }

        public function privacyPolicy() {
            
            $name = 'privacy_policy';
            $data = htmlspecialchars_decode($this->cache->getSchoolSettings($name));
            return view('frontend.backend.web.settings.privacy-policy', compact('name', 'data'));

    }
        public function posPrivacyPolicy(Request $request) {
            
            $name = 'privacy_policy';
            $data = htmlspecialchars_decode($this->cache->getSystemSettings($name));
            return view('frontend.backend.web.settings.privacy-policy', compact('name', 'data'));

    }


    public function termsConditions() {
        ResponseService::noPermissionThenRedirect('terms-condition');
        $name = 'terms_condition';
        $data = htmlspecialchars_decode($this->cache->getSystemSettings($name));
        return view('settings.terms-condition', compact('name', 'data'));
    }









}
