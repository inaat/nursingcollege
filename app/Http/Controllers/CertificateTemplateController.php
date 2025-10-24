<?php

namespace App\Http\Controllers;

use App\Models\CertificateTemplate;
use App\Repositories\CertificateTemplate\CertificateTemplateInterface;
use App\Repositories\ClassSection\ClassSectionInterface;
use App\Repositories\Exam\ExamInterface;
use App\Repositories\FormField\FormFieldsInterface;
use App\Repositories\SessionYear\SessionYearInterface;
use App\Repositories\User\UserInterface;
use App\Services\BootstrapTableService;
use App\Services\CachingService;
use App\Services\ResponseService;
use App\Services\UploadService;
use App\Utils\Util;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Throwable;
use Yajra\DataTables\DataTables;

class CertificateTemplateController extends Controller
{


    protected $commonUtil;
    protected $certificateTemplate;
    protected $cache;

    /**
     * Constructor
     *
     * @param Util $commonUtil
     * @return void
     */
    public function __construct(Util $commonUtil, CertificateTemplateInterface $certificateTemplate, CachingService $cache)
    {
        $this->commonUtil = $commonUtil;
        $this->certificateTemplate = $certificateTemplate;
        $this->cache = $cache;

    }

    public function index()
    {
       
       
        if (request()->ajax()) {
            $templates = CertificateTemplate::select(['id','name','background_image','type','page_layout']);

            return DataTables::of($templates)
                            ->addColumn(
                                'action',
                                function ($row) {
                                    $html= '<div class="dropdown">
                              <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">'. __("english.actions").'</button>
                              <ul class="dropdown-menu" style="">';
                                   if($row->type=='download_and_blink'|| $row->type=='page_with_download'){
                                    $html .= '<li><a  class="dropdown-item  " href="' . action('Frontend\FrontCustomPageController@element', [$row->id]).'"><i class="fas fa-image" aria-hidden="true"></i>' . __("english.upload") . '</a></li>';
                                   }
                                     $html .= '<li><a  href="' .action('Frontend\FrontCustomPageController@edit', [$row->id]).'" class=" dropdown-item"><i class="bx bxs-edit f-16 mr-15"></i>' . __("english.edit") . '</a></li>';
                                     $html .= '<li><a  href="#" data-href="' .action('Frontend\FrontCustomPageController@destroy', [$row->id]).'" class=" delete_event_button dropdown-item"><i class="bx bxs-trash f-16 mr-15"></i>' . __("english.delete") . '</a></li>';


                                     $html .= '</ul></div>';

                                    return $html;
                                }
                            )
                          
                            ->removeColumn('id')
                            ->rawColumns(['action'])
                            ->make(true);
        }


        return view('certificate.template.index');
    }

    public function create()
    {
        //
        $columns = DB::getSchemaBuilder()->getColumnListing('students');

        $allTags = $this->commonUtil->notificationsTags();
        $employee_tags = $allTags['tags']['employee'];
        $school_tags = $allTags['tags']['school'];
        $student_tags = $allTags['tags']['student'];


        return view('certificate.template.template', compact('employee_tags', 'school_tags', 'student_tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

        $request->validate([
            'name' => 'required',
            'page_layout' => 'required',
            'height' => 'required',
            'width' => 'required',
            'user_image_shape' => 'required',
            'image_size' => 'required',
            'description' => 'required',
            'type' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $page_layout = 'A4 Landscape';
            if ($request->height == 210 && $request->width == 297) {
                // A4 Landscape
                $page_layout = 'A4 Landscape';
            } else if ($request->height == 297 && $request->width == 210) {
                // A4 Portrait
                $page_layout = 'A4 Portrait';
            } else {
                // Custom
                $page_layout = 'Custom';
            }

            $data = [
                'name' => $request->name,
                'page_layout' => $page_layout,
                'height' => $request->height,
                'width' => $request->width,
                'user_image_shape' => $request->user_image_shape,
                'image_size' => $request->image_size,
                'description' => $request->description,
                'type' => $request->type,
            ];
            if ($request->hasFile('background_image')) {
                $data['background_image'] = $request->background_image;
            }
            $this->certificateTemplate->create($data);
            DB::commit();
            $output = [
                'success' => 1,
                'msg' => __('english.added_success')
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = [
                'success' => 0,
                'msg' => __('english.something_went_wrong')
            ];
        }



        return redirect('certificate-template')->with('status', $output);
    }


    public function design($id)
    {
        //
       
            $certificateTemplate = $this->certificateTemplate->findById($id);
            $settings = $this->cache->getSchoolSettings();

            $style = json_decode($certificateTemplate->style, true);

            if (!isset($style['description'])) {
                $style['description'] = 'style="transform:translate(91px, 62px); width: 645px; height: auto;"';
            }

            if (!isset($style['title'])) {
                $style['title'] = 'style="transform:translate(91px, 62px); width: 645px; height: auto;"';
            }

            if (!isset($style['issue_date'])) {
                $style['issue_date'] = 'style="transform:translate(91px, 62px); width: 645px; height: auto;"';
            }

            if (!isset($style['signature'])) {
                $style['signature'] = 'style="transform:translate(91px, 62px); width: 645px; height: auto;"';
            }

            if (!isset($style['school_name'])) {
                $style['school_name'] = 'style="transform:translate(91px, 62px); width: 645px; height: auto;"';
            }

            if (!isset($style['school_address'])) {
                $style['school_address'] = 'style="transform:translate(91px, 62px); width: 645px; height: auto;"';
            }

            if (!isset($style['school_mobile'])) {
                $style['school_mobile'] = 'style="transform:translate(91px, 62px); width: 645px; height: auto;"';
            }

            if (!isset($style['school_email'])) {
                $style['school_email'] = 'style="transform:translate(91px, 62px); width: 645px; height: auto;"';
            }

            if (!isset($style['school_logo'])) {
                $style['school_logo'] = 'style="transform:translate(91px, 62px); width: 645px;
            height: 150px;"';
            }

            if (!isset($style['user_image'])) {
                $style['user_image'] = 'style="transform:translate(91px, 62px); width: 645px; height: auto;"';
            }

            $height = $certificateTemplate->height * 3.7795275591;
            $width = $certificateTemplate->width * 3.7795275591;

            $layout = [
                'height' => $height,
                'width' => $width
            ];


            return view('certificate.template.design', compact('certificateTemplate', 'settings', 'style', 'layout'));
     
    }

    public function design_store(Request $request, $id)
    {
        //
        // dd($request->school_data,$request->style );
        try {

            $fields = '';
            if ($request->school_data) {
                $fields = implode(",", $request->school_data);
            }


            $style = array();
            foreach ($request->style as $key => $value) {
                $style[$key] = $value;
            }
            $value = [
                'style' => $style,
                'fields' => $fields
            ];
            $this->certificateTemplate->update($id, $value);
            $output = [
                'success' => 1,
                'msg' => __('english.updated_success')
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = [
                'success' => 0,
                'msg' => __('english.something_went_wrong')
            ];
        }



        return redirect('certificate-template')->with('status', $output);
    }

}
