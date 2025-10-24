<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DB;
use Excel;
use Illuminate\Http\Request;
use App\Utils\StudentUtil;
use App\Utils\FeeTransactionUtil;
use App\Models\Classes;
use App\Models\Student;
use App\Models\Batch;
use App\Models\ClassSection;
use App\Models\Category;
use App\Models\Campus;
use App\Models\Session;
use App\Models\Guardian;
use App\Models\StudentGuardian;
use App\Models\HumanRM\HrmEmployee;

use File;
use App\Models\HumanRM\HrmDesignation;

class ImportStudentsController extends Controller
{
    protected $studentUtil;
    protected $feeTransactionUtil;

    /**
     * Constructor
     *
     * @param ModuleUtil $moduleUtil
     * @return void
     */
    public function __construct(StudentUtil $studentUtil, FeeTransactionUtil $feeTransactionUtil)
    {
        $this->studentUtil = $studentUtil;
        $this->feeTransactionUtil = $feeTransactionUtil;
        $this->student_status_colors = [
            'active' => 'bg-success',
            'inactive' => 'bg-info',
            'struct_up' => 'bg-navy',
            'pass_out' => 'bg-danger',
            //'cancelled' => 'bg-red',
        ];
    }

    /**
     * Display import product screen.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->can('product.create')) {
            abort(403, 'Unauthorized action.');
        }

        $zip_loaded = extension_loaded('zip') ? true : false;

        //Check if zip extension it loaded or not.
        if ($zip_loaded === false) {
            $output = ['success' => 0,
                            'msg' => 'Please install/enable PHP Zip archive for import'
                        ];

            return view('import_students.index')
                ->with('notification', $output);
        } else {
            return view('import_students.index');
        }
    }
    public function store(Request $request)
    {
        if (!auth()->user()->can('product.create')) {
            abort(403, 'Unauthorized action.');
        }
     try {
        //Set maximum php execution time
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', -1);
        if ($request->hasFile('products_csv')) {
            $file = $request->file('products_csv');

            $parsed_array = Excel::toArray([], $file);
            //dd($parsed_array);
            //Remove header row
            $imported_data = array_splice($parsed_array[0], 1);
            $user_id = $request->session()->get('user.id');
            $formatted_data = [];

            $is_valid = true;
            $error_msg = '';

            $total_rows = count($imported_data);
           // dd($imported_data);

            foreach ($imported_data as $key => $value) {
                $first_name = trim($value[3]);
                $FatherName = trim($value[4]);
                $DOB = trim($value[5]);
                $CNIC = trim($value[6]);
                $Cell = trim($value[7]);
                $Address = trim($value[9]);
                         $current_class_id = trim($value[10]);
                if (!empty($current_class_id)) {
                    $cur_class = Classes::firstOrCreate(
                        ['campus_id' =>  1, 'title' => $current_class_id],
                        [
                            'created_by' => $user_id,
                            'system_settings_id' => 1
                        ]
                    );
                    $current_class_id = $cur_class->id;
                }
                     $current_class_section_id = trim($value[11]);
                if (!empty($current_class_section_id)) {
                    $cur_class_section =ClassSection::firstOrCreate(
                        ['campus_id' =>  1, 'section_name' => $current_class_section_id,
                        'class_id'=>$current_class_id],
                        [
                            'created_by' => $user_id,
                            'system_settings_id' => 1
                        ]
                    );
                    $current_class_section_id = $cur_class_section->id;

                }
                     $batch_id = trim($value[12]);
                if (!empty($batch_id)) {
                    $batch =Batch::firstOrCreate(
                        ['title' => $batch_id,
                        'status'=>1],
                        
                    );
                    $batch_id = $batch->id;

                }
                     $session_name = trim($value[2]);

                if (!empty($session_name)) {
                    $session=Session::where('title',$session_name)->first();
                    if(!empty($session)){
                    $session_id=$session->id;
                    }
                }
                $student_array = [
                    'system_settings_id'=>1,
                    'created_by'=> $user_id,
                    'campus_id'=>1,
                    'adm_session_id'=> $session_id,
                    'cur_session_id'=> $session_id,
                    'first_name'=>$first_name,
                    'last_name'=>null,
                    'admission_date'=> Carbon::now()->format('Y-m-d'),
                    'adm_class_id'=>$current_class_id,
                    'adm_class_section_id'=> $current_class_section_id,
                    'current_class_id'=>$current_class_id,
                    'current_class_section_id'=> $current_class_section_id,
                    'batch_id'=>  $batch_id,
                    'semester_id'=>1,
                    'gender'=>'male',
                    'birth_date'=>$this->processDate($DOB),
                    'religion'=>'islam',
                    'mobile_no'=>$Cell,
                    'cnic_no'=>$CNIC,
                    'nationality'=>'Pakistani',
                    'mother_tongue'=>'Pashto',
                   
                    'father_name'=>$FatherName,
                    'father_phone'=>$Cell,
                    'father_occupation'=>null,
                  
                    'guardian_name'=>$FatherName,
                    'guardian_occupation'=>null,
                    'guardian_relation'=>'father',
                    'guardian_cnic'=>null,
                    'guardian_phone'=>$Cell,
                    'guardian_email'=> null,
                    'country_id'=>1,
                    'province_id'=>1,
                    'district_id'=>1,
                    'city_id'=>1,
                    'region_id'=>null,
                    'std_current_address'=>$Address,
                    'std_permanent_address'=>$Address,
                    'student_tuition_fee'=>0,
                    'student_transport_fee'=>0,
                    'is_transport'=>0,
                    'opening_balance'=>0,
                    'status'=>'active'
                ];
               // dd($student_array);
            //     //Check if any column is missing
            //     if (count($value) < 48  ||  count($value) >48 ) {
            //         $is_valid =  false;
            //         $error_msg = "Some of the columns are missing. Please, use latest CSV file template.";
            //         break;
            //     }
            //     $student_array = [];
            //     $row_no = $key + 1;
            //     $student_array['system_settings_id'] = 1;
            //     $student_array['created_by'] = $user_id;
            //     $campus_name = trim($value[0]);
            //     if (!empty($campus_name)) {
            //         $campus=Campus::where('campus_name',$campus_name)->first();
            //         if(!empty($campus)){
            //         $student_array['campus_id'] = $campus->id;
            //         }else{
            //             $campus=Campus::create([
            //                 'campus_name'=>$campus_name,
            //                 'system_settings_id'=>1,
            //                 'created_by'=>$user_id
            //             ]);
            //             $student_array['campus_id'] = $campus->id;

            //         }
            //     }else{
            //         $is_valid =  false;
            //         $error_msg = __("english.name_of_the_campus") . '('.__("english.required").')';
            //         break;
            //     }
            //     $session_name = trim($value[1]);

            //     if (!empty($session_name)) {
            //         $session=Session::where('title',$session_name)->first();
            //         if(!empty($session)){
            //         $student_array['adm_session_id'] =$session->id;
            //             $student_array['cur_session_id'] = $session->id;
            //         }else{
                        
            //             $is_valid =  false;
            //             $error_msg = __('english.session_instruction');
            //             break;
            //         }
            //     }else{
            //         $is_valid =  false;
            //         $error_msg = "Session name is Required";
            //         break;
            //     }
            //     $first_name = trim($value[2]);
            //     if (!empty($first_name)) {
            //         $student_array['first_name'] = $first_name;
            //     }else{
            //         $is_valid =  false;
            //         $error_msg = __("english.first_name") . '('.__("english.required").')';
            //         break; 
            //     }
            //     $last_name = trim($value[3]);
            //     if (!empty($last_name)) {
            //         $student_array['last_name'] = $last_name;
            //     }
            //     //admission_date
            //     if (!empty($value[4])) {
            //         if (!empty($value[4])) {
            //             $date_excel =\Carbon::createFromFormat('m-d-Y', trim($value[4]))->format('Y-m-d');
            //             $date=$date_excel;
            //             $student_array['admission_date'] = $date;
            //         }
            //     } else {
            //         $student_array['admission_date'] = \Carbon::now()->format('Y-m-d');;
            //     }
               

            //     //Add Classes
            //     //Check if Classes exists else create new
            //     $adm_class_id = trim($value[5]);
            //     if (!empty($adm_class_id)) {
            //         $adm_class = Classes::firstOrCreate(
            //             ['campus_id' =>  $student_array['campus_id'], 'title' => $adm_class_id],
            //             [
            //                 'created_by' => $user_id,
            //                 'system_settings_id' => 1
            //             ]
            //         );
            //         $student_array['adm_class_id'] = $adm_class->id;
            //     }else{
            //         $is_valid =  false;
            //         $error_msg = __("english.admission_class") . '('.__("english.required").')';
            //         break; 
            //     }
            //     $adm_class_section_id = trim($value[6]);
            //     if (!empty($adm_class_section_id)) {
            //         $adm_class_section =ClassSection::firstOrCreate(
            //             ['campus_id' =>  $student_array['campus_id'], 'section_name' => $adm_class_section_id,
            //             'class_id'=>$student_array['adm_class_id']],
            //             [
            //                 'created_by' => $user_id,
            //                 'system_settings_id' => 1
            //             ]
            //         );
            //         $student_array['adm_class_section_id'] = $adm_class_section->id;
            //     }else{
            //         $is_valid =  false;
            //         $error_msg = __("english.admission_class_section") . '('.__("english.required").')';
            //         break; 
            //     }
            //     //Add Classes
            //     //Check if Classes exists else create new
            //     $current_class_id = trim($value[7]);
            //     if (!empty($current_class_id)) {
            //         $cur_class = Classes::firstOrCreate(
            //             ['campus_id' =>  $student_array['campus_id'], 'title' => $current_class_id],
            //             [
            //                 'created_by' => $user_id,
            //                 'system_settings_id' => 1
            //             ]
            //         );
            //         $student_array['current_class_id'] = $cur_class->id;
            //     }else{
            //         $is_valid =  false;
            //         $error_msg = __("english.current_class") . '('.__("english.required").')';
            //         break; 
            //     }
            //     $current_class_section_id = trim($value[8]);
            //     if (!empty($current_class_section_id)) {
            //         $cur_class_section =ClassSection::firstOrCreate(
            //             ['campus_id' =>  $student_array['campus_id'], 'section_name' => $current_class_section_id,
            //             'class_id'=>$student_array['current_class_id']],
            //             [
            //                 'created_by' => $user_id,
            //                 'system_settings_id' => 1
            //             ]
            //         );
            //         $student_array['current_class_section_id'] = $cur_class_section->id;
            //     }else{
            //         $is_valid =  false;
            //         $error_msg = __("english.current_class_section") . '('.__("english.required").')';
            //         break; 
            //     }
            //     $gender= trim($value[9]);
            //     if (!empty($gender)) {
            //         $student_array['gender'] = strtolower($gender);
            //     }else{
            //         $is_valid =  false;
            //         $error_msg = __("english.gender") . '('.__("english.required").')';
            //         break; 
            //     }
            //     $date_of_birth= trim($value[10]);

            //     if (!empty($date_of_birth)) {
                    
            //             $date_excel =\Carbon::createFromFormat('m-d-Y', trim($date_of_birth))->format('Y-m-d');
            //             $date=$date_excel;
            //             $student_array['birth_date'] = $date;
            //         }
            //     else {
            //         $student_array['birth_date'] = \Carbon::now()->format('Y-m-d');;
            //     }
            //     $category_name=trim($value[11]);
            //     if (!empty($category_name)) {
            //         $Category =Category::firstOrCreate(
            //             ['cat_name' => $category_name],
            //             [
            //                 'created_by' => $user_id,
            //                 'system_settings_id' => 1
            //             ]
            //         );
            //         $student_array['category_id'] =  $Category->id;

            //     }else{
            //         $student_array['category_id'] =  null;

            //     }
            //     $religion= trim($value[12]);
            //     if (!empty($religion)) {
            //         $student_array['religion'] = ucwords(strtolower($religion));
            //     }
            //     $mobile_no= trim($value[13]);
            //     if (!empty($religion)) {
            //         $student_array['mobile_no'] = $mobile_no;
            //     }
            //     $cnic_no= trim($value[14]);
            //     if (!empty($religion)) {
            //         $student_array['cnic_no'] = $cnic_no;
            //     }
            //     $blood_group= trim($value[15]);
            //     if (!empty($religion)) {
            //         $student_array['blood_group'] = $blood_group;
            //     }
            //     $nationality= trim($value[16]);
            //     if (!empty($religion)) {
            //         $student_array['nationality'] = $nationality;
            //     }
            //     $mother_tongue= trim($value[17]);
            //     if (!empty($religion)) {
            //         $student_array['mother_tongue'] = $mother_tongue;
            //     }
            //     $medical_history= trim($value[18]);
            //     if (!empty($religion)) {
            //         $student_array['medical_history'] = $medical_history;
            //     }
            //     $father_name=trim($value[19]);
            //     if (!empty($father_name)) {
                    
            //         $student_array['father_name'] = $father_name;
            //     }else{
            //         $is_valid =  false;
            //         $error_msg = __("english.father_name") . '('.__("english.required").')';
            //         break; 
            //     }
            //     $father_phone=trim($value[20]);
            //     if (!empty($father_phone)) {
                    
            //         $student_array['father_phone'] = $father_phone;
            //     }else{
            //         $is_valid =  false;
            //         $error_msg = __("english.father_phone") . '('.__("english.required").')';
            //         break; 
            //     }
            //     $father_occupation=trim($value[21]);
            //     if (!empty($father_occupation)) {
                    
            //         $student_array['father_occupation'] = $father_occupation;
            //     }
            //     $father_cnic_no=trim($value[22]);
            //     if (!empty($father_cnic_no)) {
                    
            //         $student_array['father_cnic_no'] = $father_cnic_no;
            //     }
            //     $father_cnic_no=trim($value[22]);
            //     if (!empty($father_cnic_no)) {
                    
            //         $student_array['father_cnic_no'] = $father_cnic_no;
            //     }
            //     $mother_name=trim($value[23]);
            //     if (!empty($mother_name)) {
                    
            //         $student_array['mother_name'] = $mother_name;
            //     }
            //     $mother_phone=trim($value[24]);
            //     if (!empty($mother_phone)) {
                    
            //         $student_array['mother_phone'] = $mother_phone;
            //     }
            //     $mother_occupation=trim($value[25]);
            //     if (!empty($mother_occupation)) {
                    
            //         $student_array['mother_occupation'] = $mother_occupation;
            //     }
            //     $mother_cnic_no=trim($value[26]);
            //     if (!empty($mother_cnic_no)) {
                    
            //         $student_array['mother_cnic_no'] = $mother_cnic_no;
                    
            //     }

            //     $if_guardian_is= trim($value[27]); 
            //     if($if_guardian_is==1){
            //         $student_array['guardian_name'] = $student_array['father_name'];
            //         $student_array['guardian_occupation'] = $student_array['father_occupation'];
            //         $student_array['guardian_relation'] = 'Father';
            //         $student_array['guardian_cnic'] = $student_array['father_cnic_no'];
            //         $student_array['guardian_phone'] = $student_array['father_phone'];
            //         $student_array['guardian_email'] = null;




            //     }else{
            //         $guardian_name= trim($value[28]);
            //         if (!empty($guardian_name)) {
            //             $student_array['guardian_name'] = $guardian_name;
            //         } else {
            //             $is_valid =  false;
            //             $error_msg = __("english.guardian_name") . '('.__("english.required").')';
            //             break; 
            //         }
            //         $guardian_relation= trim($value[29]);
            //         if (!empty($guardian_relation)) {
            //             $student_array['guardian_relation'] = $guardian_relation;
            //         } else {
            //             $student_array['guardian_relation'] = 'Father';
            //         }

            //         $guardian_occupation= trim($value[30]);
            //         if (!empty($guardian_occupation)) {
            //             $student_array['guardian_occupation'] = $guardian_occupation;
    
            //         } else {
            //             $student_array['guardian_occupation'] = '';
            //         }
            //         $guardian_cnic= trim($value[31]);
            //         if (!empty($guardian_cnic)) {
            //             $student_array['guardian_cnic'] = $guardian_cnic;
            //         } else {
            //             $student_array['guardian_cnic'] = '';
            //         }
            //         $guardian_phone= trim($value[32]);
            //         if (!empty($guardian_phone)) {
            //             $student_array['guardian_phone'] = $guardian_phone;
            //         } else {
            //             $student_array['guardian_phone'] = '';
            //         }
            //         $guardian_phone= trim($value[33]);
            //         if (!empty($guardian_phone)) {
            //             $student_array['guardian_email'] = $guardian_phone;
            //         } else {
            //             $student_array['guardian_email'] = '';
            //         }
                  
            //     }
            //     $country_id= trim($value[34]);
            //     if (!empty($country_id)) {
            //        // $student_array['country_id'] = $country_id;
            //     } else{
            //         $student_array['country_id'] = null;

            //     }
            //     $province_id= trim($value[35]);
            //     if (!empty($province_id)) {
            //        // $student_array['province_id'] = $province_id;
            //     } else{
            //         $student_array['province_id'] = null;

            //     }
            //     $district_id= trim($value[36]);
            //     if (!empty($district_id)) {
            //        // $student_array['district_id'] = $district_id;
            //     } else{
            //         $student_array['district_id'] = null;

            //     }
            //     $city_id= trim($value[37]);
            //     if (!empty($city_id)) {
            //        // $student_array['city_id'] = $city_id;
            //     } else{
            //         $student_array['city_id'] = null;

            //     }
            //     $region_id= trim($value[38]);
            //     if (!empty($region_id)) {
            //        // $student_array['region_id'] = $region_id;
            //     } else{
            //         $student_array['region_id'] = null;

            //     }  
            //     $std_current_address= trim($value[39]);
            //     if (!empty($std_current_address)) {
            //         $student_array['std_current_address'] = ucwords($std_current_address);
            //     }
            //     $std_permanent_address= trim($value[40]);
            //     if (!empty($std_permanent_address)) {
            //         $student_array['std_permanent_address'] = $std_permanent_address;
            //     }


            //     $student_tuition_fee= trim($value[41]);
            //     if (!empty($student_tuition_fee)) {
            //         $student_array['student_tuition_fee'] = $student_tuition_fee;
            //     }else{
            //         $student_array['student_tuition_fee'] = 0;

            //     }
            //     $student_transport_fee= trim($value[42]);
            //     if (!empty($student_transport_fee)) {
            //         $student_array['student_transport_fee'] = $student_transport_fee;
            //         $student_array['is_transport'] = 1;
            //     }else{
            //         $student_array['student_transport_fee'] = 0;

            //     }
            //     $opening_balance= trim($value[43]);
            //     if (!empty($opening_balance)) {
            //         $student_array['opening_balance'] = $opening_balance;
            //     } else {
            //         $student_array['opening_balance'] = 0;
            //     }
            //     $previous_school_name= trim($value[44]);
            //     if (!empty($previous_school_name)) {
            //         $student_array['previous_school_name'] = $previous_school_name;
            //     }
            //     $last_grade= trim($value[45]);
            //     if (!empty($last_grade)) {
            //         $student_array['last_grade'] = $last_grade;
            //     }
            //     $remark= trim($value[46]);
            //     if (!empty($remark)) {
            //         $student_array['remark'] = $remark;
            //     }
            //        $status= trim($value[27]);
            //     if(!empty($status)){
            //     if ($status==0) {
            //                 $student_array['status'] = 'pass_out';
            //             } else {
            //                 $student_array['status'] = 'active';
            //             }
            //         }
            //             else{
            //                 $is_valid =  false;
            //                 $error_msg = __("english.student_status") . '('.__("english.required").')';
            //                 break; 
            //             }
            //     //dd($student_array);////ok

            
            //     //Assign to formatted array
            //     //dd($student_array);
                 $formatted_data[] = $student_array;
             }
        }
      // dd($formatted_data);
        // if (!$is_valid) {
        //     throw new \Exception($error_msg);
        // }
        DB::beginTransaction();

        if (!empty($formatted_data)) {
            foreach ($formatted_data as $index => $student_data) {
                $opening_balance= $student_data['opening_balance'];
                $ref_admission_no=$this->studentUtil->setAndGetReferenceCount('admission_no', true, false);
                $admission_no=$this->studentUtil->generateReferenceNumber('admission', $ref_admission_no);
                $student_data['admission_no'] = $admission_no;
                $student_data['roll_no'] = $this->studentUtil->getRollNo($student_data['adm_session_id']);
                $guardian_email=$student_data['roll_no'].'gu@gmail.com';
                if(!empty($student_data['guardian_email'])){
                    $guardian_email=$student_data['guardian_email'];
                }
                $guardian_data=[
                'guardian_name'=>$student_data['guardian_name'],
                'guardian_relation'=>$student_data['guardian_relation'],
                'guardian_occupation'=>$student_data['guardian_occupation'],
                'guardian_cnic'=>$student_data['guardian_cnic'],
                'guardian_phone'=>$student_data['guardian_phone'],
                'guardian_email'=>$guardian_email,
                ];
                if (isset($student_data['opening_balance'])) {
                    unset($student_data['opening_balance']);
                }
                if (isset($student_data['guardian_name'])) {
                    unset($student_data['guardian_name']);
                }
                if (isset($student_data['guardian_relation'])) {
                    unset($student_data['guardian_relation']);
                }
                if (isset($student_data['guardian_occupation'])) {
                    unset($student_data['guardian_occupation']);
                }
                if (isset($student_data['guardian_cnic'])) {
                    unset($student_data['guardian_cnic']);
                }
                if (isset($student_data['guardian_phone'])) {
                    unset($student_data['guardian_phone']);
                }
                if (isset($student_data['guardian_email'])) {
                    unset($student_data['guardian_email']);
                }
              
             //   dd($student_data);
                $student_data['email'] = $student_data['roll_no'].'@gmail.com';
                //dd($guardian_data);
                // //Create new product
                $student = Student::create($student_data);
                $guardian = Guardian::create($guardian_data);
                $studentGuardian = StudentGuardian::create([
                'student_id' => $student->id,
                'guardian_id' => $guardian->id,
                ]);
               // dd( $student);
                //Add opening balance
                if (!empty($opening_balance)) {
                    if ($opening_balance>0) {
                        $this->studentUtil->createOpeningBalanceTransaction($student->system_settings_id, $student, $opening_balance, false);
                    }
                }
                $user = $this->studentUtil->studentCreateUpdateLogin($student, 'student', $student->id);
                $student->user_id = $user->id;
                $student->save();
                $this->studentUtil->createWithdrawRegister($student);
                $guardian_login =  $this->studentUtil->guardianCreateUpdateLogin($guardian, 'guardian', $guardian->id);
                $this->studentUtil->setAndGetReferenceCount('admission_no', false, true);
                $this->studentUtil->setAndGetRollNoCount('roll_no', false, true, $student->adm_session_id);
            }
        }
        $output = ['success' => 1,
                        'msg' => __('english.file_imported_successfully')
        ];

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

            $output = ['success' => 0,
                            'msg' => $e->getMessage()
                        ];
            return redirect('import-students')->with('notification', $output);
        }

        return redirect('import-students')->with('status', $output);
    }


    public function StudentImage(Request $request)
    {
        if (!auth()->user()->can('product.create')) {
            abort(403, 'Unauthorized action.');
        }
        //Set maximum php execution time
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', -1);
        if ($request->hasFile('products_csv')) {
            $file = $request->file('products_csv');

            $parsed_array = Excel::toArray([], $file);
            //Remove header row
            $imported_data = array_splice($parsed_array[0], 1);
            foreach ($imported_data as $key => $value) {
                // dd('tenant/uploads/pdf/image/'.trim($value[0]).'.png');
                if (File::exists(public_path('uploads/pdf/image/'.trim($value[0]).'.png'))) {
                    $student_data=Student::where('old_roll_no', $value[0])->first();
                    if (!empty($student_data)) {
                        if ($student_data->old_roll_no==trim($value[0])) {
                            $student_data->student_image=$student_data->old_roll_no.'.png';
                            $student_data->save();
                        } else {
                            File::delete(public_path('uploads/pdf/image/'.trim($value[0]).'.png'));
                        }
                    } else {
                        File::delete(public_path('uploads/pdf/image/'.trim($value[0]).'.png'));
                    }
                }

                // dd($imported_data);
            }
            dd(5);
            // $user_id = $request->session()->get('user.id');
            // $formatted_data = [];

            // $is_valid = true;
            // $error_msg = '';

            // $total_rows = count($imported_data);
            // return view('import_students.img')->with(compact('imported_data'));
        }
    }

    public function employeeImport(Request $request)
    {
        // $emp = HrmEmployee::get();
        // foreach ($emp as $ep){
        //     $employee = HrmEmployee::findOrFail($ep->id);
        //     $employeeID = explode("-",$employee->employeeID);
        //     $employee->employeeID=$employeeID[0].'-'.$employeeID[2];
        //     $employee->save();
        // }
        // dd($emp);
        if (!auth()->user()->can('product.create')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            //Set maximum php execution time
            ini_set('max_execution_time', 0);
            ini_set('memory_limit', -1);
            if ($request->hasFile('products_csv')) {
                $file = $request->file('products_csv');

                $parsed_array = Excel::toArray([], $file);
                //Remove header row
                $imported_data = array_splice($parsed_array[0], 1);
                $user_id = $request->session()->get('user.id');
                $formatted_data = [];

                $is_valid = true;
                $error_msg = '';

                $total_rows = count($imported_data);
                DB::beginTransaction();
                ;
                foreach ($imported_data as $key => $value) {
                    //Check if any column is missing
                    if (count($value) < 12) {
                        $is_valid =  false;
                        $error_msg = "Some of the columns are missing. Please, use latest CSV file template.";
                        break;
                    }
                    $row_no = $key + 1;
                    $student_array = [];
                    $student_array['created_by'] = $user_id;
                    $student_array['campus_id'] = 1;

                    //joining_date
                    if (!empty($value[0])) {
                        $date_excel = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value[0])->format('Y-m-d');
                        $date=$date_excel;
                        $student_array['joining_date'] = $date;
                        $student_array['birth_date'] = $date;
                    }
                    // //birth_date
                    // if (!empty($value[1])) {
                    //     if($value[1] != '0' || $value[1] != 0){
                    //         $birth_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value[1])->format('Y/m/d');
                    //         $student_array['birth_date'] = $birth_date;
                    //     }

                    // }
                    //old_EmpID
                    $old_EmpID = trim($value[2]);
                    if (!empty($old_EmpID)) {
                        $student_array['old_EmpID'] = $old_EmpID;
                    }
                    $name = trim($value[3]);
                    if (!empty($name)) {
                        $student_array['first_name'] = $name;
                    }
                    $father_name = trim($value[4]);
                    if (!empty($father_name)) {
                        $student_array['father_name'] = $father_name;
                    }
                    $gender = trim($value[5]);
                    if (!empty($gender)) {
                        $student_array['gender'] = 'male';
                    }
                    $M_Status = trim($value[6]);
                    if (!empty($M_Status)) {
                        $student_array['M_Status'] = $M_Status;
                    }
                    $mobile_no= trim($value[7]);
                    if (!empty($mobile_no)) {
                        $student_array['mobile_no'] = $mobile_no;
                    }
                    $current_address= trim($value[8]);
                    if (!empty($current_address)) {
                        $student_array['current_address'] = ucwords($current_address);
                        $student_array['permanent_address'] = $current_address;
                    }
                    $cnic_no= trim($value[9]);
                    if (!empty($cnic_no)) {
                        $student_array['cnic_no'] = ucwords($cnic_no);
                    }
                    $basic_salary= trim($value[10]);
                    if (!empty($basic_salary)) {
                        $student_array['basic_salary'] = ucwords($basic_salary);
                    }
                    //Add hrm_designations
                    //Check if hrm_designations exists else create new
                    $hrm_designations = trim($value[11]);
                    if (!empty($hrm_designations)) {
                        $designation = HrmDesignation::firstOrCreate(
                            ['designation' => $hrm_designations],
                            ['created_by' => $user_id]
                        );
                        $student_array['designation_id'] = $designation->id;
                    }



                    //Assign to formatted array
                    $formatted_data[] = $student_array;
                }
            }
            if (!$is_valid) {
                throw new \Exception($error_msg);
            }

            if (!empty($formatted_data)) {
                foreach ($formatted_data as $index => $student_data) {
                    $ref_employeeID=$this->studentUtil->setAndGetReferenceCount('employee_no', true, false);
                    $employeeID=$this->studentUtil->generateReferenceNumber('employee', $ref_employeeID);
                    $student_data['employeeID'] = $employeeID;
                    $student_data['email'] = $student_data['employeeID'].'@gmail.com';
                    $student_array['gender'] = 'male';
                    // //Create new Employee
                    $student = HrmEmployee::create($student_data);
                    $this->studentUtil->setAndGetReferenceCount('employee_no', false, true);
                }
            }
            $output = ['success' => 1,
                            'msg' => __('english.file_imported_successfully')
        ];

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

            $output = ['success' => 0,
                            'msg' => $e->getMessage()
                        ];
            return redirect('import-students')->with('notification', $output);
        }

        return redirect('import-students')->with('status', $output);
    }

function processDate($rawDate)
{
    if (!empty($rawDate)) {
        $rawDate = trim($rawDate); // Trim any whitespace

        // Check if it's a numeric Excel serial date
        if (is_numeric($rawDate)) {
            try {
                return Carbon::createFromFormat('Y-m-d', '1899-12-30')
                    ->addDays($rawDate)
                    ->format('Y-m-d');
            } catch (\Exception $e) {
                Log::error("Failed to process Excel serial date: $rawDate. Error: " . $e->getMessage());
                return null;
            }
        } else {
            // Fix common month spelling mistakes
            $months = [
                'janury'  => 'january',
                'febuary' => 'february',
                'agust'   => 'august',
                'sept'    => 'september',
                'octobr'  => 'october',
                'dicember'=> 'december',
                'Decmber'=> 'december',
            ];

            // Replace incorrect month spellings
            foreach ($months as $wrong => $correct) {
                if (stripos($rawDate, $wrong) !== false) {
                    $rawDate = str_ireplace($wrong, $correct, $rawDate);
                    break;
                }
            }

            // Try different date formats
            $formats = ['m-d-Y', 'M-d-Y', 'Y-m-d', 'd-M-Y', 'd-F-Y'];

            foreach ($formats as $format) {
                try {
                    return Carbon::createFromFormat($format, $rawDate)->format('Y-m-d');
                } catch (\Exception $e) {
                    continue; // Try the next format if parsing fails
                }
            }
            dd($rawDate);
            // Log an error if none of the formats work
            //Log::error("Invalid date format for admission_date: $rawDate");
            return null;
        }
    }

    return \Carbon::now()->format('Y-m-d');
}

}
