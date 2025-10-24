<?php

namespace App\Http\Controllers;
use App\Models\Batch;
use App\Models\Semester;
use App\Models\Report;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ClassSection;
use App\Models\Classes;
use App\Models\Campus;
use App\Models\Vehicle;
class CustomReportController extends Controller
{
 // Available columns for student reports
    private function getAvailableColumns()
    {
        return [
            'basic_info' => [
                'student_name' => 'Student Name',
                'father_name' => 'Father Name',
                'gender' => 'Gender',
                'birth_date' => 'Birth Date',
                'mobile_no' => 'Mobile No',
                'std_permanent_address' => 'Permanent Address',
                'admission_no' => 'Admission No',
                'admission_date' => 'Admission Date',
                'roll_no' => 'Roll No',
                'old_roll_no' => 'Old Roll No',
                'status' => 'Status',
                'cnic_no' => 'Cnic No',
                
                
            ],
             'qualification_info' => [
                'matric_passing_year' => 'Matric Passing Year',
                'matric_marks_obtained' => 'Matric Marks Obtained',
                'matric_total_marks' => 'Matric Total Marks',
                'matric_percentage' => 'Matric Percentage',
                'matric_board' => 'Matric Board',
                'fsc_passing_year' => 'F.S.c Passing Year',
                'fsc_marks_obtained' => 'F.S.c Marks Obtained',
                'fsc_total_marks' => 'F.S.c Total Marks',
                'fsc_percentage' => 'F.S.c Percentage',
                'fsc_biology_marks' => 'F.S.c Biology Marks',
                'fsc_board' => 'F.S.c Board',
            ],
            'academic_info' => [
                'campus_name' => 'Campus Name',
                'adm_class' => 'Admission Class',
                'current_class' => 'Current Class',
                'section_name' => 'Section Name',
                'adm_section_name' => 'Admission Section',
                'batch_name' =>'Batch Name',
                'semester_name' =>'SemesterName',
                'group_by_batch_name_semter_class_name'=>'Group By Class Batch Semester Name'
            ],
            'financial_info' => [
                'student_tuition_fee' => 'Tuition Fee',
                'class_tuition_fee' => 'Class Tuition Fee',
                'student_transport_fee' => 'Transport Fee',
                'discount_per' => 'Discount %',
                'discount_type' => 'Discount Type',
                'total_due' => 'Total Due',
                'total_due_transport_fee' => 'Transport Due',
                'total_admission_fee' => 'Admission Fee',
            ],
            'transport_info' => [
                'vehicle_name' => 'Vehicle Name',
            ]
        ];
    }

    public function index()
    {
        //dd(Student::first());
        $reports = Report::with('creator')->where('is_active', true)->get();
        return view('reports.index', compact('reports'));
    }

    public function create()
    {
        $availableColumns = $this->getAvailableColumns();
        return view('reports.create', compact('availableColumns'));
    }

public function store(Request $request)
    {
        try {
            // Convert JSON strings to arrays if needed
            $selectedColumns = $request->selected_columns;
            $columnOrder = $request->column_order;
            
            if (is_string($selectedColumns)) {
                $selectedColumns = json_decode($selectedColumns, true);
            }
            if (is_string($columnOrder)) {
                $columnOrder = json_decode($columnOrder, true);
            }
            
            // Replace the request data with decoded arrays
            $request->merge([
                'selected_columns' => $selectedColumns,
                'column_order' => $columnOrder
            ]);
            
            $request->validate([
                'report_name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'selected_columns' => 'required|array|min:1',
                'column_order' => 'required|array'
            ]);

            $report = Report::create([
                'report_name' => $request->report_name,
                'description' => $request->description,
                'selected_columns' => $selectedColumns,
                'column_order' => $columnOrder,
                'report_type' => 'student',
                'created_by' => auth()->id()
            ]);

            return redirect()->route('reports.index')->with('success', 'Report created successfully');
            
        } catch (\Exception $e) {
            \Log::error('Report creation failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'Failed to create report: ' . $e->getMessage()]);
        }
    }

    public function show(Report $report)
    {
        $campuses=Campus::forDropdown();
         $vehicles=Vehicle::forDropdown();
          $batches=Batch::forDropdown(1);
        $semesters=Semester::forDropdown(1);
        return view('reports.show', compact('campuses','vehicles','report','batches','semesters'));
    }
public function edit(Report $report)
{
    // Check if user has permission to edit this report
    if ($report->created_by !== auth()->id()) {
        abort(403, 'Unauthorized to edit this report.');
    }
    
    $availableColumns = $this->getAvailableColumns();
    
    return view('reports.edit', compact('report', 'availableColumns'));
}
public function update(Request $request, Report $report)
{
    try {
        // Check if user has permission to update this report
        if ($report->created_by !== auth()->id()) {
            abort(403, 'Unauthorized to update this report.');
        }
        
        // Convert JSON strings to arrays BEFORE validation
        $selectedColumns = $request->selected_columns;
        $columnOrder = $request->column_order;
        
        if (is_string($selectedColumns)) {
            $selectedColumns = json_decode($selectedColumns, true);
        }
        if (is_string($columnOrder)) {
            $columnOrder = json_decode($columnOrder, true);
        }
        
        // Merge the decoded arrays back into the request for validation
        $request->merge([
            'selected_columns' => $selectedColumns,
            'column_order' => $columnOrder
        ]);
        
        // Now validate with the decoded arrays
        $validated = $request->validate([
            'report_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'selected_columns' => 'required|array|min:1',
            'column_order' => 'required|array'
        ]);
        
        // Update the report
        $report->update([
            'report_name' => $validated['report_name'],
            'description' => $validated['description'],
            'selected_columns' => $validated['selected_columns'],
            'column_order' => $validated['column_order']
        ]);
        
        return redirect()->route('reports.index')->with('success', 'Report updated successfully');
        
    } catch (\Illuminate\Validation\ValidationException $e) {
        return redirect()->back()->withInput()->withErrors($e->validator->errors());
    } catch (\Exception $e) {
        \Log::error('Report update failed: ' . $e->getMessage());
        return redirect()->back()->withInput()->withErrors(['error' => 'Failed to update report: ' . $e->getMessage()]);
    }
}
public function destroy(Report $report)
{
    try {
        // Check if user has permission to delete this report
        if ($report->created_by !== auth()->id()) {
            return redirect()->back()->withErrors(['error' => 'Unauthorized to delete this report.']);
        }
        
        $reportName = $report->report_name;
        $report->delete();
        
        return redirect()->route('reports.index')->with('success', "Report '{$reportName}' deleted successfully");
        
    } catch (\Exception $e) {
        \Log::error('Report deletion failed: ' . $e->getMessage());
        return redirect()->back()->withErrors(['error' => 'Failed to delete report: ' . $e->getMessage()]);
    }
}
    public function generate(Report $report, Request $request)
    {
         //dd($request->input());
        $system_settings_id = session()->get('user.system_settings_id');
        
        // Base query similar to your existing code
        $query = Student::leftJoin('campuses', 'students.campus_id', '=', 'campuses.id')
            ->leftjoin('fee_transactions AS t', 'students.id', '=', 't.student_id')
            ->leftJoin('classes as c_class', 'students.current_class_id', '=', 'c_class.id')
            ->leftJoin('classes as adm_class', 'students.adm_class_id', '=', 'adm_class.id')
            ->leftJoin('class_sections', 'students.current_class_section_id', '=', 'class_sections.id')
            ->leftJoin('class_sections as adm_section', 'students.adm_class_section_id', '=', 'adm_section.id')
            ->leftJoin('vehicles', 'students.vehicle_id', '=', 'vehicles.id')
             ->leftJoin('semesters', 'students.semester_id', '=', 'semesters.id')
           ->leftJoin('batches', 'students.batch_id', '=', 'batches.id')
            ->where('students.system_settings_id', $system_settings_id);

        // Dynamic column selection based on report configuration
        $selectColumns = $this->buildSelectColumns($report->column_order);
        $query->select($selectColumns);

        // Add financial calculations if needed
        if (in_array('total_due', $report->column_order)) {
            $query->addSelect([
                DB::raw("COALESCE(SUM(IF(t.type = 'fee' AND t.status = 'final', final_total, 0)),0)-COALESCE(SUM(IF(t.type = 'fee' AND t.status = 'final', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM fee_transaction_payments WHERE fee_transaction_payments.fee_transaction_id=t.id), 0)),0)
                +COALESCE(SUM(IF(t.type = 'opening_balance', final_total, 0)),0) -COALESCE(SUM(IF(t.type = 'opening_balance', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM fee_transaction_payments WHERE fee_transaction_payments.fee_transaction_id=t.id), 0)),0)
                +COALESCE(SUM(IF(t.type = 'admission_fee', final_total, 0)),0) -COALESCE(SUM(IF(t.type = 'admission_fee', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM fee_transaction_payments WHERE fee_transaction_payments.fee_transaction_id=t.id), 0)),0)
                +COALESCE(SUM(IF(t.type = 'other_fee', final_total, 0)),0) -COALESCE(SUM(IF(t.type = 'other_fee', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM fee_transaction_payments WHERE fee_transaction_payments.fee_transaction_id=t.id), 0)),0) as total_due")
            ]);
        }

        if (in_array('total_due_transport_fee', $report->column_order)) {
            $query->addSelect([
                DB::raw("COALESCE(SUM(IF(t.type = 'transport_fee' AND t.status = 'final', final_total, 0)),0)-COALESCE(SUM(IF(t.type = 'transport_fee' AND t.status = 'final', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM fee_transaction_payments WHERE fee_transaction_payments.fee_transaction_id=t.id), 0)),0) as total_due_transport_fee")
            ]);
        }

        if (in_array('total_admission_fee', $report->column_order)) {
            $query->addSelect([
                DB::raw("COALESCE(SUM(IF(t.type = 'admission_fee', final_total, 0)),0) as total_admission_fee")
            ]);
        }

        // Apply filters from request (user selections)
        $this->applyRequestFilters($query, $request);

        // Check for permitted campuses
        $permitted_campuses = auth()->user()->permitted_campuses();
        if ($permitted_campuses != 'all') {
            $query->whereIn('students.campus_id', $permitted_campuses);
        }

        $query->groupBy('students.id');
        $query->orderBy('students.current_class_id', 'asc');
        $query->orderBy('students.batch_id', 'asc');
        $results = $query->get();
        
        ///dd($results);
        return view('reports.results', compact('report', 'results'));
    }

    private function buildSelectColumns($columnOrder)
    {
        $columnMap = [
            'student_name' => DB::raw("CONCAT(COALESCE(students.first_name, ''),' ',COALESCE(students.last_name,'')) as student_name"),
            'father_name' => 'students.father_name',
            'gender' => 'students.gender',
            'birth_date' => 'students.birth_date',
            'mobile_no' => 'students.mobile_no',
            'std_permanent_address' => 'students.std_permanent_address',
            'admission_no' => 'students.admission_no',
            'admission_date' => 'students.admission_date',
            'roll_no' => 'students.roll_no',
            'old_roll_no' => 'students.old_roll_no',
            'status' => 'students.status',
            'campus_name' => 'campuses.campus_name',
            'adm_class' => 'adm_class.title as adm_class',
            'current_class' => 'c_class.title as current_class',
            'section_name' => 'class_sections.section_name as section_name',
            'adm_section_name' => 'adm_section.section_name as adm_section_name',
            'student_tuition_fee' => 'students.student_tuition_fee',
            'class_tuition_fee' => 'c_class.tuition_fee as class_tuition_fee',
            'student_transport_fee' => 'students.student_transport_fee',
            'discount_per' => 'students.discount_per',
            'discount_type' => 'students.discount_type',
            'vehicle_name' => DB::raw("CONCAT(COALESCE(vehicles.name, ''),' ',COALESCE(vehicles.vehicle_model,'')) as vehicle_name"),
            'cnic_no'=> 'students.cnic_no',
            'board_roll_no'=> 'students.board_roll_no',
            'total_mark'=> 'students.total_mark',
            'obtain_mark'=> 'students.obtain_mark',
            'paper_remark'=> 'students.paper_remark',
            'struck_remark'=> 'students.struck_remark',
            'took_diploma_remark'=> 'students.took_diploma_remark',
            'batch_name' =>'batches.title as batch_name',
            'semester_name' =>'semesters.title as semester_name',
            'group_by_batch_name_semter_class_name' => DB::raw("CONCAT(
    COALESCE(c_class.title, ''), ' ',
    COALESCE(batches.title, ''), ' ',
    COALESCE(semesters.title, '')
) as group_by_batch_name_semter_class_name"),
 
// Matriculation columns - show data only if qualification is 'Matriculation'
'matric_passing_year' => DB::raw("(SELECT passing_year FROM qualifications WHERE qualifications.student_id = students.id AND qualifications.qualification = 'Matriculation' LIMIT 1) as matric_passing_year"),
'matric_marks_obtained' => DB::raw("(SELECT marks_obtained FROM qualifications WHERE qualifications.student_id = students.id AND qualifications.qualification = 'Matriculation' LIMIT 1) as matric_marks_obtained"),
'matric_total_marks' => DB::raw("(SELECT total_marks FROM qualifications WHERE qualifications.student_id = students.id AND qualifications.qualification = 'Matriculation' LIMIT 1) as matric_total_marks"),
'matric_percentage' => DB::raw("(SELECT percentage FROM qualifications WHERE qualifications.student_id = students.id AND qualifications.qualification = 'Matriculation' LIMIT 1) as matric_percentage"),
'matric_board' => DB::raw("(SELECT board FROM qualifications WHERE qualifications.student_id = students.id AND qualifications.qualification = 'Matriculation' LIMIT 1) as matric_board"),

// F.S.c columns - show data only if qualification is 'F.S.c'
'fsc_passing_year' => DB::raw("(SELECT passing_year FROM qualifications WHERE qualifications.student_id = students.id AND qualifications.qualification = 'F.S.c' LIMIT 1) as fsc_passing_year"),
'fsc_marks_obtained' => DB::raw("(SELECT marks_obtained FROM qualifications WHERE qualifications.student_id = students.id AND qualifications.qualification = 'F.S.c' LIMIT 1) as fsc_marks_obtained"),
'fsc_total_marks' => DB::raw("(SELECT total_marks FROM qualifications WHERE qualifications.student_id = students.id AND qualifications.qualification = 'F.S.c' LIMIT 1) as fsc_total_marks"),
'fsc_percentage' => DB::raw("(SELECT percentage FROM qualifications WHERE qualifications.student_id = students.id AND qualifications.qualification = 'F.S.c' LIMIT 1) as fsc_percentage"),
'fsc_biology_marks' => DB::raw("(SELECT biology_marks FROM qualifications WHERE qualifications.student_id = students.id AND qualifications.qualification = 'F.S.c' LIMIT 1) as fsc_biology_marks"),
'fsc_board' => DB::raw("(SELECT board FROM qualifications WHERE qualifications.student_id = students.id AND qualifications.qualification = 'F.S.c' LIMIT 1) as fsc_board")


            
        ];

        $selectColumns = ['students.id as id']; // Always include ID

        // Use column order from the report
        foreach ($columnOrder as $column) {
            if (isset($columnMap[$column])) {
                $selectColumns[] = $columnMap[$column];
            }
        }

        return $selectColumns;
    }

    private function applyRequestFilters($query, $request)
    {
        //dd($request);
        // Apply filters from user form submission
        if ($request->has('campus_id') && !empty($request->campus_id)) {
            $query->where('students.campus_id', $request->campus_id);
        }
        
        if ($request->has('class_id') && !empty($request->class_id)) {
            $query->where('students.current_class_id', $request->class_id);
        }
        
        if ($request->has('class_section_id') && !empty($request->class_section_id)) {
            $query->where('students.current_class_section_id', $request->class_section_id);
        }
         if ($request->has('batch_id') && !empty($request->batch_id)) {
            $query->where('students.batch_id', $request->batch_id);
        }
         if ($request->has('semester_id') && !empty($request->semester_id)) {
            $query->where('students.semester_id', $request->semester_id);
        }
        
        if ($request->has('status') && !empty($request->status)) {
            $query->where('students.status', $request->status);
        }
        
        if ($request->has('vehicle_id') && !empty($request->vehicle_id)) {
            $query->where('students.vehicle_id', $request->vehicle_id);
        }
        
        if ($request->has('gender') && !empty($request->gender)) {
            $query->where('students.gender', $request->gender);
        }
        
        if ($request->has('admission_no') && !empty($request->admission_no)) {
            $query->where('students.admission_no', 'like', "%{$request->admission_no}%");
        }
        
        if ($request->has('roll_no') && !empty($request->roll_no)) {
            $query->where('students.roll_no', 'like', "%{$request->roll_no}%");
        }
        
        if ($request->has('only_transport') && !empty($request->only_transport)) {
            $query->where('students.student_transport_fee', '>', 0);
        }
    }



    public function export(Report $report, $format = 'excel')
    {
        // Implementation for exporting reports to Excel/PDF
        // You can use Laravel Excel or similar packages
    }
}
