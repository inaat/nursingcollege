<?php

namespace App\Utils;

// use \Notification;
use App\Models\SystemSetting;
use App\Models\HumanRM\HrmNotificationTemplate;
use App\Models\Sms;
use Config;

class NotificationUtil extends Util
{

    /**
     * Automatically send notification to customer/supplier if enabled in the template setting
     *
     * @param  int  $business_id
     * @param  string  $notification_type
     * @param  obj  $transaction
     * @param  obj  $contact
     *
     * @return void
     */
    public function autoSendNotification($notification_type, $employee = null, $student = null, $attendance = null, $payment = null)
    {
        $notification_template = HrmNotificationTemplate::
            where('template_for', $notification_type)
            ->first();
       if($notification_template->auto_send_sms==0){
        return true;
       }
        $business = SystemSetting::findOrFail(1);

        $data['email_settings'] = $business->email_settings;
        $data['sms_settings'] = $business->sms_settings;

        $whatsapp_link = '';



        $data['mobile_number'] = $employee->mobile_no ?? $student->mobile_no;
        if (!empty($data['mobile_number']) && $data['mobile_number'] > 10) {
            $sms_body = $notification_template->sms_body;
            $tag_replaced_data = $this->replaceTags($sms_body, $employee, $student, $attendance, $payment);

            $data['sms_body'] = $tag_replaced_data;
            try {

                $whatsapp_link = $this->sendSms($data);


            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());
            }
        }



        return $whatsapp_link;
    }



    public function SendNotification($notification_type, $student, $attendance, $sms_body = null, $add_second = null)
    {
        $notification_template = HrmNotificationTemplate::
            where('template_for', $notification_type)
            ->first();
        $business = SystemSetting::findOrFail(1);

        $data['email_settings'] = $business->email_settings;
        $data['sms_settings'] = $business->sms_settings;
        $data['add_second'] = $add_second;

        $whatsapp_link = '';
        if (!empty($notification_template)) {
            if (!empty($notification_template->auto_send) || !empty($notification_template->auto_send_sms) || !empty($notification_template->auto_send_wa_notif)) {
                $orig_data = [
                    'email_body' => $notification_template->email_body,
                    'sms_body' => $notification_template->sms_body,
                    'subject' => $notification_template->subject,
                    'whatsapp_text' => $notification_template->whatsapp_text,
                ];

                $tag_replaced_data = $this->replaceTags($orig_data, null, null, null);
                //dd($tag_replaced_data);
                $data['email_body'] = $tag_replaced_data['email_body'];
                $data['sms_body'] = $tag_replaced_data['sms_body'];
                $data['whatsapp_text'] = $tag_replaced_data['whatsapp_text'];

                //Auto send sms
                if (!empty($notification_template->auto_send_sms)) {
                    $data['mobile_number'] = $student->mobile_no;
                    // $data['mobile_number'] = '03428927305';
                    if (!empty($student->mobile_no)) {
                        try {
                            $sms_send_through_app = config('constants.sms_send_through_app');
                            if ($sms_send_through_app) {
                                $sms_insert = [
                                    'sms_body' => $data['sms_body'],
                                    'mobile' => $data['mobile_number']
                                ];
                                Sms::create($sms_insert);
                            } else {
                                $whatsapp_link = $this->sendSms($data);
                            }
                            // $this->activityLog($transaction, 'sms_notification_sent', null, [], false, $business_id);

                        } catch (\Exception $e) {
                            \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());
                        }
                    }
                }
            }
        } else {

            $data['sms_body'] = str_replace('&nbsp;', ' ', strip_tags($sms_body));
            // dd($data);
            $data['mobile_number'] = $student->mobile_no;
            //$data['mobile_number'] = '03428927305';
            if (!empty($data['mobile_number'])) {
                try {
                    $sms_send_through_app = config('constants.sms_send_through_app');
                    if ($sms_send_through_app) {
                        $sms_insert = [
                            'sms_body' => $data['sms_body'],
                            'mobile' => $data['mobile_number']
                        ];
                        Sms::create($sms_insert);
                    } else {
                        $whatsapp_link = $this->sendSms($data);
                    }
                    // $this->activityLog($transaction, 'sms_notification_sent', null, [], false, $business_id);

                } catch (\Exception $e) {
                    \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());
                }
            }

        }

        return $whatsapp_link;
    }



    public function replaceTags($data, $employee = null, $student = null, $attendance = null, $payment = null)
    {
        $system_details = SystemSetting::findOrFail(1);
        if ($attendance) {
            //Replace clock_in_time
            if (strpos($data, '{clock_in_time}') !== false) {
                $clock_in_time = empty($attendance) ? $attendance->clock_in_time : $attendance->clock_in_time;

                $data = str_replace('{clock_in_time}', $this->format_date($clock_in_time, true, $system_details), $data);
            }
            //Replace clock_out_time
            if (strpos($data, '{clock_out_time}') !== false) {
                $clock_out_time = empty($attendance) ? $attendance->clock_out_time : $attendance->clock_out_time;

                $data = str_replace('{clock_out_time}', $this->format_date($clock_out_time, true, $system_details), $data);
            }
        }




        // Replace {employee_name}
        if (strpos($data, '{employee_name}') !== false && !empty($employee)) {
            $employee_name = $employee->first_name . ' ' . $employee->last_name;
            $data = str_replace('{employee_name}', ucwords($employee_name), $data);
        }
        // Replace {employee_first_name}
        if (strpos($data, '{employee_first_name}') !== false && !empty($employee)) {
            $employee_first_name = $employee->first_name;
            $data = str_replace('{employee_first_name}', ucwords($employee_first_name), $data);
        }
        // Replace {employee_last_name}
        if (strpos($data, '{employee_last_name}') !== false && !empty($employee)) {
            $employee_last_name = $employee->last_name;
            $data = str_replace('{employee_last_name}', ucwords($employee_last_name), $data);
        }
        // Replace {employee_email}
        if (strpos($data, '{employee_email}') !== false && !empty($employee)) {
            $employee_email = $employee->email;
            $data = str_replace('{employee_email}', ucwords($employee_email), $data);
        }
        // Replace {employee_gender}
        if (strpos($data, '{employee_gender}') !== false && !empty($employee)) {
            $employee_gender = $employee->gender;
            $data = str_replace('{employee_gender}', ucwords($employee_gender), $data);
        }
        // Replace {employee_basic_salary}
        if (strpos($data, '{employee_basic_salary}') !== false && !empty($employee)) {
            $employee_basic_salary = $employee->basic_salary;
            $data = str_replace('{employee_basic_salary}', ucwords($employee_basic_salary), $data);
        }
        // Replace {employee_contact_no}
        if (strpos($data, '{employee_contact_no}') !== false && !empty($employee)) {
            $employee_contact_no = $employee->mobile_no;
            $data = str_replace('{employee_contact_no}', ucwords($employee_contact_no), $data);
        }
        // Replace {employee_birth_date}
        if (strpos($data, '{employee_birth_date}') !== false && !empty($employee)) {
            $employee_birth_date = $employee->birth_date;
            $data = str_replace('{employee_birth_date}', ucwords($employee_birth_date), $data);
        }
        // Replace {employee_joining_date}
        if (strpos($data, '{employee_joining_date}') !== false && !empty($employee)) {
            $employee_joining_date = $employee->joining_date;
            $data = str_replace('{employee_joining_date}', ucwords($employee_joining_date), $data);
        }

        // Replace {employee_current_address}
        if (strpos($data, '{employee_current_address}') !== false && !empty($employee)) {
            $employee_current_address = $employee->current_address;
            $data = str_replace('{employee_current_address}', ucwords($employee_current_address), $data);
        }
        // Replace {employee_permanent_address}
        if (strpos($data, '{employee_permanent_address}') !== false && !empty($employee)) {
            $employee_permanent_address = $employee->permanent_address;
            $data = str_replace('{employee_permanent_address}', ucwords($employee_permanent_address), $data);
        }
        // Replace {employee_cnic_no}
        if (strpos($data, '{employee_cnic_no}') !== false && !empty($employee)) {
            $employee_cnic_no = $employee->cnic_no;
            $data = str_replace('{employee_cnic_no}', ucwords($employee_cnic_no), $data);
        }
        // Replace {employee_blood_group}
        if (strpos($data, '{employee_blood_group}') !== false && !empty($employee)) {
            $employee_blood_group = $employee->blood_group;
            $data = str_replace('{employee_blood_group}', ucwords($employee_blood_group), $data);
        }

        // Replace {employee_father_name}
        if (strpos($data, '{employee_father_name}') !== false && !empty($employee)) {
            $employee_father_name = $employee->father_name;
            $data = str_replace('{employee_father_name}', ucwords($employee_father_name), $data);
        }
        // Replace {employee_id}
        if (strpos($data, '{employee_id}') !== false && !empty($employee)) {
            $employee_id = $employee->employeeID;
            $data = str_replace('{employee_id}', ucwords($employee_id), $data);
        }
        // Replace {employee_department}
        if (strpos($data, '{employee_department}') !== false && !empty($employee)) {
            $employee_department = \App\Models\HumanRM\HrmDepartment::find($employee->department_id)->department ?? null;
            $data = str_replace('{employee_department}', ucwords($employee_department), $data);
        }
        // Replace {employee_designation}
        if (strpos($data, '{employee_designation}') !== false && !empty($employee)) {
            $employee_designation = \App\Models\HumanRM\HrmDesignation::find($employee->designation_id)->designation ?? null;
            $data = str_replace('{employee_designation}', ucwords($employee_designation), $data);
        }
        // Replace {employee_image}
        if (strpos($data, '{employee_image}') !== false && !empty($employee)) {
            $employee_image = file_exists(public_path('uploads/employee_image/' . $employee->employee_image)) ? url('uploads/employee_image/' . $employee->employee_image) : url('uploads/employee_image/default.jpg');

            $data = str_replace('{employee_image}', $employee_image, $data);
        }

        // Replace {school_name}
        if (strpos($data, '{school_name}') !== false) {
            $org_name = $system_details->org_name ?? '';
            $data = str_replace('{school_name}', $org_name, $data);
        }

        // Replace {school_address}
        if (strpos($data, '{school_address}') !== false) {
            $org_address = $system_details->org_address ?? '';
            $data = str_replace('{school_address}', $org_address, $data);
        }

        // Replace {school_contact_no}
        if (strpos($data, '{school_contact_no}') !== false) {
            $org_contact_number = $system_details->org_contact_number ?? '';
            $data = str_replace('{school_contact_no}', $org_contact_number, $data);
        }
        // Replace {school_tag_line}
        if (strpos($data, '{school_tag_line}') !== false) {
            $org_tag_line = $system_details->tag_line ?? '';
            $data = str_replace('{school_tag_line}', $org_tag_line, $data);
        }
        // Replace {school_short_name}
        if (strpos($data, '{school_short_name}') !== false) {
            $org_short_name = $system_details->org_short_name ?? '';
            $data = str_replace('{school_short_name}', $org_short_name, $data);
        }

        // Replace {school_page_header_logo}
        if (strpos($data, '{school_page_header_logo}') !== false) {
            $page_header_logo = file_exists(public_path('uploads/business_logos/' . $system_details->page_header_logo)) ? url('uploads/business_logos/' . $system_details->page_header_logo) : '';

            $data = str_replace('{school_page_header_logo}', $page_header_logo, $data);
        }

        // Replace {school_id_card_logo}
        if (strpos($data, '{school_id_card_logo}') !== false) {
            $id_card_logo = file_exists(public_path('uploads/business_logos/' . $system_details->id_card_logo)) ? url('uploads/business_logos/' . $system_details->id_card_logo) : '';

            $data = str_replace('{school_id_card_logo}', $id_card_logo, $data);
        }
        // Replace {school_logo}
        if (strpos($data, '{school_logo}') !== false) {
            $logo = file_exists(public_path('uploads/business_logos/' . $system_details->org_logo)) ? url('uploads/business_logos/' . $system_details->org_logo) : '';

            $data = str_replace('{school_logo}', $logo, $data);
        }
        // Replace {student_image}
        if (strpos($data, '{student_image}') !== false && !empty($student)) {
            $student_image = file_exists(public_path('uploads/student_image/' . $student->student_image)) ? url('uploads/student_image/' . $student->student_image) : url('uploads/student_image/default.jpg');

            $data = str_replace('{student_image}', $student_image, $data);
        }
        // Replace {student_name}
        if (strpos($data, '{student_name}') !== false && !empty($student)) {
            $student_name = $student->first_name . '   ' . $student->last_name;
            $data = str_replace('{student_name}', ucwords($student_name), $data);
        }
        // Replace {student_name}
        if (strpos($data, '{student_name}') !== false && !empty($student)) {
            $student_name = $student->first_name . '   ' . $student->last_name;
            $data = str_replace('{student_name}', ucwords($student_name), $data);
        }
        // Replace {student_first_name}
        if (strpos($data, '{student_first_name}') !== false && !empty($student)) {
            $student_first_name = $student->first_name;
            $data = str_replace('{student_first_name}', ucwords($student_first_name), $data);
        }
        // Replace {student_last_name}
        if (strpos($data, '{student_last_name}') !== false && !empty($student)) {
            $student_last_name = $student->last_name;
            $data = str_replace('{student_last_name}', ucwords($student_last_name), $data);
        }
        // Replace {student_roll_no}
        if (strpos($data, '{student_roll_no}') !== false && !empty($student)) {
            $student_roll_no = $student->roll_no;
            $data = str_replace('{student_roll_no}', ucwords($student_roll_no), $data);
        }
        // Replace {student_old_roll_no}
        if (strpos($data, '{student_old_roll_no}') !== false && !empty($student)) {
            $student_old_roll_no = $student->old_roll_no;
            $data = str_replace('{student_old_roll_no}', ucwords($student_old_roll_no), $data);
        }
        // Replace {student_admission_date}
        if (strpos($data, '{student_admission_date}') !== false && !empty($student)) {
            $student_admission_date = $student->admission_date;
            $data = str_replace('{student_admission_date}', ucwords($student_admission_date), $data);
        }
        // Replace {student_gender}
        if (strpos($data, '{student_gender}') !== false && !empty($student)) {
            $student_gender = $student->gender;
            $data = str_replace('{student_gender}', ucwords($student_gender), $data);
        }
        // Replace {student_birth_date}
        if (strpos($data, '{student_birth_date}') !== false && !empty($student)) {
            $student_birth_date = $student->birth_date;
            $data = str_replace('{student_birth_date}', ucwords($student_birth_date), $data);
        }
        // Replace {student_birth_place}
        if (strpos($data, '{student_birth_place}') !== false && !empty($student)) {
            $student_birth_place = $student->birth_place;
            $data = str_replace('{student_birth_place}', ucwords($student_birth_place), $data);
        }
        // Replace {student_caste}
        if (strpos($data, '{student_caste}') !== false && !empty($student)) {
            $student_caste = $student->caste;
            $data = str_replace('{student_caste}', ucwords($student_caste), $data);
        }
        // Replace {student_religion}
        if (strpos($data, '{student_religion}') !== false && !empty($student)) {
            $student_religion = $student->religion;
            $data = str_replace('{student_religion}', ucwords($student_religion), $data);
        }
        // Replace {student_mobile_no}
        if (strpos($data, '{student_mobile_no}') !== false && !empty($student)) {
            $student_mobile_no = $student->mobile_no;
            $data = str_replace('{student_mobile_no}', ucwords($student_mobile_no), $data);
        }
        // Replace {student_email}
        if (strpos($data, '{student_email}') !== false && !empty($student)) {
            $student_email = $student->email;
            $data = str_replace('{student_email}', ucwords($student_email), $data);
        }
        // Replace {student_cnic_no}
        if (strpos($data, '{student_cnic_no}') !== false && !empty($student)) {
            $student_cnic_no = $student->cnic_no;
            $data = str_replace('{student_cnic_no}', ucwords($student_cnic_no), $data);
        }
        // Replace {student_blood_group}
        if (strpos($data, '{student_blood_group}') !== false && !empty($student)) {
            $student_blood_group = $student->blood_group;
            $data = str_replace('{student_blood_group}', ucwords($student_blood_group), $data);
        }
        // Replace {student_nationality}
        if (strpos($data, '{student_nationality}') !== false && !empty($student)) {
            $student_nationality = $student->nationality;
            $data = str_replace('{student_nationality}', ucwords($student_nationality), $data);
        }
        // Replace {student_mother_tongue}
        if (strpos($data, '{student_mother_tongue}') !== false && !empty($student)) {
            $student_mother_tongue = $student->mother_tongue;
            $data = str_replace('{student_mother_tongue}', ucwords($student_mother_tongue), $data);
        }
        // Replace {student_father_name}
        if (strpos($data, '{student_father_name}') !== false && !empty($student)) {
            $student_father_name = $student->father_name;
            $data = str_replace('{student_father_name}', ucwords($student_father_name), $data);
        }
        // Replace {student_father_phone}
        if (strpos($data, '{student_father_phone}') !== false && !empty($student)) {
            $student_father_phone = $student->father_phone;
            $data = str_replace('{student_father_phone}', ucwords($student_father_phone), $data);
        }
        // Replace {student_father_occupation}
        if (strpos($data, '{student_father_occupation}') !== false && !empty($student)) {
            $student_father_occupation = $student->father_occupation;
            $data = str_replace('{student_father_occupation}', ucwords($student_father_occupation), $data);
        }
        // Replace {student_father_cnic_no}
        if (strpos($data, '{student_father_cnic_no}') !== false && !empty($student)) {
            $student_father_cnic_no = $student->father_cnic_no;
            $data = str_replace('{student_father_cnic_no}', ucwords($student_father_cnic_no), $data);
        }
        // Replace {student_father_cnic_no}
        if (strpos($data, '{student_father_cnic_no}') !== false && !empty($student)) {
            $student_father_cnic_no = $student->father_cnic_no;
            $data = str_replace('{student_father_cnic_no}', ucwords($student_father_cnic_no), $data);
        }
        // Replace {student_mother_name}
        if (strpos($data, '{student_mother_name}') !== false && !empty($student)) {
            $student_mother_name = $student->mother_name;
            $data = str_replace('{student_mother_name}', ucwords($student_mother_name), $data);
        }
        // Replace {student_mother_phone}
        if (strpos($data, '{student_mother_phone}') !== false && !empty($student)) {
            $student_mother_phone = $student->mother_phone;
            $data = str_replace('{student_mother_phone}', ucwords($student_mother_phone), $data);
        }
        // Replace {student_mother_occupation}
        if (strpos($data, '{student_mother_occupation}') !== false && !empty($student)) {
            $student_mother_occupation = $student->mother_occupation;
            $data = str_replace('{student_mother_occupation}', ucwords($student_mother_occupation), $data);
        }
        // Replace {student_mother_cnic_no}
        if (strpos($data, '{student_mother_cnic_no}') !== false && !empty($student)) {
            $student_mother_cnic_no = $student->mother_cnic_no;
            $data = str_replace('{student_mother_cnic_no}', ucwords($student_mother_cnic_no), $data);
        }
        // Replace {student_mother_cnic_no}
        if (strpos($data, '{student_mother_cnic_no}') !== false && !empty($student)) {
            $student_mother_cnic_no = $student->mother_cnic_no;
            $data = str_replace('{student_mother_cnic_no}', ucwords($student_mother_cnic_no), $data);
        }
        // Replace {student_current_address}
        if (strpos($data, '{student_current_address}') !== false && !empty($student)) {
            $student_current_address = $student->std_current_address;
            $data = str_replace('{student_current_address}', ucwords($student_current_address), $data);
        }
        // Replace {student_permanent_address}
        if (strpos($data, '{student_permanent_address}') !== false && !empty($student)) {
            $student_permanent_address = $student->std_permanent_address;
            $data = str_replace('{student_permanent_address}', ucwords($student_permanent_address), $data);
        }
        // Replace {student_remark}
        if (strpos($data, '{student_remark}') !== false && !empty($student)) {
            $student_remark = $student->remark;
            $data = str_replace('{student_remark}', ucwords($student_remark), $data);
        }
        // Replace {student_previous_school_name}
        if (strpos($data, '{student_previous_school_name}') !== false && !empty($student)) {
            $student_previous_school_name = $student->previous_school_name;
            $data = str_replace('{student_previous_school_name}', ucwords($student_previous_school_name), $data);
        }
        // Replace {student_last_grade}
        if (strpos($data, '{student_last_grade}') !== false && !empty($student)) {
            $student_last_grade = $student->last_grade;
            $data = str_replace('{student_last_grade}', ucwords($student_last_grade), $data);
        }
        // Replace {student_tuition_fee}
        if (strpos($data, '{student_tuition_fee}') !== false && !empty($student)) {
            $student_tuition_fee = $student->student_tuition_fee;
            $data = str_replace('{student_tuition_fee}', ucwords($student_tuition_fee), $data);
        }
        // Replace {student_transport_fee}
        if (strpos($data, '{student_transport_fee}') !== false && !empty($student)) {
            $student_transport_fee = $student->student_transport_fee;
            $data = str_replace('{student_transport_fee}', ucwords($student_transport_fee), $data);
        }
        // Replace {student_admission_no}
        if (strpos($data, '{student_admission_no}') !== false && !empty($student)) {
            $student_admission_no = $student->admission_no;
            $data = str_replace('{student_admission_no}', ucwords($student_admission_no), $data);
        }
        // Replace {student_campus}
        if (strpos($data, '{student_campus') !== false && !empty($student)) {
            $student_campus = \App\Models\Campus::find($student->campus_id)->campus_name ?? null;
            $data = str_replace('{student_campus}', ucwords($student_campus), $data);
        }
        // Replace {student_admission_session}
        if (strpos($data, '{student_admission_session') !== false && !empty($student)) {
            $student_admission_session = \App\Models\Session::find($student->adm_session_id)->title ?? null;
            $data = str_replace('{student_admission_session}', ucwords($student_admission_session), $data);
        }
        // Replace {student_current_session_id}
        if (strpos($data, '{student_current_session_id') !== false && !empty($student)) {
            $student_current_session_id = \App\Models\Session::find($student->cur_session_id)->title ?? null;
            $data = str_replace('{student_current_session_id}', ucwords($student_current_session_id), $data);
        }
        // Replace {student_admission_class}
        if (strpos($data, '{student_admission_class') !== false && !empty($student)) {
            $student_admission_class = \App\Models\Classes::find($student->adm_class_id)->title ?? null;
            $data = str_replace('{student_admission_class}', ucwords($student_admission_class), $data);
        }
        // Replace {student_current_class}
        if (strpos($data, '{student_current_class') !== false && !empty($student)) {

            $student_current_class = \App\Models\Classes::find($student->current_class_id)->title ?? $student->current_class;
            $data = str_replace('{student_current_class}', ucwords($student_current_class), $data);
        }
        // Replace {student_admission_class_section}
        if (strpos($data, '{student_admission_class_section') !== false && !empty($student)) {
            $student_admission_class_section = \App\Models\ClassSection::find($student->adm_class_section_id)->section_name ?? null;
            $data = str_replace('{student_admission_class_section}', ucwords($student_admission_class_section), $data);
        }
        // Replace {student_current_class_section}
        if (strpos($data, '{student_current_class_section') !== false && !empty($student)) {
            $student_current_class_section = \App\Models\ClassSection::find($student->current_class_section_id)->section_name ?? $student->current_class_section;
            $data = str_replace('{student_current_class_section}', ucwords($student_current_class_section), $data);
        }

        ///payment
        //Replace total_due
        if (strpos($data, '{student_total_due}') !== false && !empty($student)) {
            $total_due = $student->total_due ?? '';

            $data = str_replace('{student_total_due}', number_format($total_due, 0), $data);
        }
        //Replace paid_amount 
        if (strpos($data, '{paid_amount}') !== false && !empty($payment)) {
            $paid_amount = $payment->amount ?? ' ';

            $data = str_replace('{paid_amount}', $paid_amount, $data);
        }
        //Replace payment_ref_no 

        if (strpos($data, '{payment_ref_no}') !== false && !empty($payment)) {
            $payment_ref_no = $payment->payment_ref_no ?? ' ';

            $data = str_replace('{payment_ref_no}', $payment_ref_no, $data);
        }
        //Replace paid_on 
        if (strpos($data, '{paid_on}') !== false && !empty($payment)) {
            $paid_on = $this->format_date($payment->paid_on, true, $system_details) ?? ' ';

            $data = str_replace('{paid_on}', $paid_on, $data);
        }
        return $data;
    }

 

}
