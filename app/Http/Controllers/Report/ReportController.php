<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use DB;
use App\Models\ExpenseCategory;
use App\Models\ExpenseTransaction;
use App\Models\FeeTransactionPayment;
use App\Models\Campus;
use App\Utils\ExpenseTransactionUtil;
use App\Utils\HrmTransactionUtil;
use App\Models\HumanRM\HrmTransactionPayment;
class ReportController extends Controller
{
    /**
    * Constructor
    *
    * @param NotificationUtil $notificationUtil
    * @return void
    */
    public function __construct(ExpenseTransactionUtil $expenseTransactionUtil,  HrmTransactionUtil $hrmTransactionUtil)
    {
        $this->expenseTransactionUtil= $expenseTransactionUtil;
        $this->hrmTransactionUtil = $hrmTransactionUtil;

    }

/**
     * Shows expense report of a business
     *
     * @return \Illuminate\Http\Response
     */
    public function getExpenseReport(Request $request)
    {
        if (!auth()->user()->can('expense_report.view')) {
            abort(403, 'Unauthorized action.');
        }

        $filters = $request->only(['category', 'campus_id']);
       $campus_id = request()->campus_id;
        $date_range = $request->input('list_filter_date_range');
        if (!empty($date_range)) {
            $date_range_array = explode(' - ' , $date_range);
            //dd($date_range_array);

            $filters['start_date'] = $this->expenseTransactionUtil->uf_date(trim($date_range_array[0]));
            $filters['end_date'] = $this->expenseTransactionUtil->uf_date(trim($date_range_array[1]));
        } else {
            $filters['start_date'] = \Carbon::now()->startOfMonth()->format('Y-m-d');
            $filters['end_date'] = \Carbon::now()->endOfMonth()->format('Y-m-d');
        }
        ////aby///
        
        ///aby//
        $expenses = $this->expenseTransactionUtil->getExpenseReport( $filters);
         $hrm=$this->hrmTransactionUtil->getTotalHrmPaid( $filters['start_date'], $filters['end_date'],null, $campus_id);
        // dd($hrm);
        $values = [];
        $labels = [];
        foreach ($expenses as $expense) {
            $values[] = [
                'name'=>!empty($expense->category) ? $expense->category : __('english.others'),
                'y'=>(float) $expense->total_expense
            ] ;
            $labels[] = !empty($expense->category) ? $expense->category : __('english.others');
        }
         $values[] = [
                'name'=>'HRM',
                'y'=>(float) $hrm
            ] ;
            $labels[] = __('english.hrm');
        // $chart = new CommonChart;
        // $chart->labels($labels)
        //     ->title(__('report.expense_report'))
        //     ->dataset(__('report.total_expense'), 'column', $values);

        $categories = ExpenseCategory::pluck('name', 'id');
        
        $campuses = Campus::forDropdown();
        $labels= json_encode($labels) ;
        $values= json_encode($values) ;
        return view('Report.expense.expense_report')
                    ->with(compact('labels','values','categories', 'campuses', 'expenses','hrm'));
    }
    
    public function getMonthlyExpenseReport(Request $request)
{
    if (!auth()->user()->can('expense_report.view')) {
        abort(403, 'Unauthorized action.');
    }
    
    $filters = $request->only(['category', 'campus_id']);
    $campus_id = request()->campus_id;
    $date_range = $request->input('list_filter_date_range');
    
    if (!empty($date_range)) {
        $date_range_array = explode(' - ', $date_range);
        $filters['start_date'] = $this->expenseTransactionUtil->uf_date(trim($date_range_array[0]));
        $filters['end_date'] = $this->expenseTransactionUtil->uf_date(trim($date_range_array[1]));
    } else {
        $filters['start_date'] = \Carbon::now()->startOfYear()->format('Y-m-d');
        $filters['end_date'] = \Carbon::now()->endOfYear()->format('Y-m-d');
    }
    
    // Build expense query directly in controller
    $expenseQuery = ExpenseTransaction::leftjoin('expense_categories AS ec', 'expense_transactions.expense_category_id', '=', 'ec.id')
                            ->whereIn('type', ['expense', 'expense_refund']);
    
    // Apply campus permissions
    $permitted_campuses = auth()->user()->permitted_campuses();
    if ($permitted_campuses != 'all') {
        $expenseQuery->whereIn('expense_transactions.campus_id', $permitted_campuses);
    }
    
    // Apply filters
    if (!empty($filters['campus_id'])) {
        $expenseQuery->where('expense_transactions.campus_id', $filters['campus_id']);
    }
    if (!empty($filters['category'])) {
        $expenseQuery->where('ec.id', $filters['category']);
    }
    if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
        $expenseQuery->whereBetween(DB::raw('date(transaction_date)'), [$filters['start_date'], $filters['end_date']]);
    }
    
    // Get monthly expenses
    $monthlyExpenses = $expenseQuery->select(
        DB::raw('DATE_FORMAT(transaction_date, "%Y-%m") as month_key'),
        DB::raw('transaction_date as expense_date'),
        'ec.name as category',
        DB::raw("SUM( IF(expense_transactions.type='expense_refund', -1 * final_total, final_total) ) as total_expense")
    )
                ->groupBy(DB::raw('DATE_FORMAT(transaction_date, "%Y-%m")'), 'expense_category_id')
                ->orderBy('transaction_date', 'asc')
                ->get();
    
    // Build HRM query directly in controller using HrmTransactionPayment table
    $hrmQuery = HrmTransactionPayment::where('hrm_transaction_id', '!=', null);
    
    // Apply date filters
    if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
        $hrmQuery->whereDate('paid_on', '>=', $filters['start_date'])
                ->whereDate('paid_on', '<=', $filters['end_date']);
    }
    
    // Apply campus permissions for HRM
    if ($permitted_campuses != 'all') {
        $hrmQuery->whereIn('campus_id', $permitted_campuses);
    }
    
    // Apply campus filter for HRM
    if (!empty($campus_id)) {
        $hrmQuery->where('campus_id', $campus_id);
    }
    
    // Get monthly HRM data
    $monthlyHrm = $hrmQuery->select(
        DB::raw('DATE_FORMAT(paid_on, "%Y-%m") as month_key'),
        DB::raw('paid_on as payment_date'),
        DB::raw('SUM(amount) as total_hrm')
    )
    ->groupBy(DB::raw('DATE_FORMAT(paid_on, "%Y-%m")'))
    ->orderBy('paid_on', 'asc')
    ->get();
    
    // Prepare monthly data with month names
    $monthlyData = [];
    $months = [];
    
    // Get all months in the date range
    $startDate = \Carbon::parse($filters['start_date']);
    $endDate = \Carbon::parse($filters['end_date']);
    
    while ($startDate->lte($endDate)) {
        $monthKey = $startDate->format('Y-m');
        $monthName = $startDate->format('F Y'); // e.g., "January 2024"
        
        $months[$monthKey] = $monthName;
        
        // Initialize monthly data
        $monthlyData[$monthKey] = [
            'month_name' => $monthName,
            'month_key' => $monthKey,
            'expenses' => [],
            'hrm_total' => 0,
            'total_expenses' => 0,
            'grand_total' => 0
        ];
        
        $startDate->addMonth();
    }
    
    // Group expenses by month
    foreach ($monthlyExpenses as $expense) {
        $monthKey = \Carbon::parse($expense->expense_date)->format('Y-m');
        
        if (isset($monthlyData[$monthKey])) {
            $categoryName = !empty($expense->category) ? $expense->category : __('english.others');
            
            if (!isset($monthlyData[$monthKey]['expenses'][$categoryName])) {
                $monthlyData[$monthKey]['expenses'][$categoryName] = 0;
            }
            
            $monthlyData[$monthKey]['expenses'][$categoryName] += (float) $expense->total_expense;
            $monthlyData[$monthKey]['total_expenses'] += (float) $expense->total_expense;
        }
    }
    
    // Add HRM data to monthly totals
    foreach ($monthlyHrm as $hrm) {
        $monthKey = \Carbon::parse($hrm->payment_date)->format('Y-m');
        
        if (isset($monthlyData[$monthKey])) {
            $monthlyData[$monthKey]['hrm_total'] = (float) $hrm->total_hrm;
        }
    }
    
    // Calculate grand totals for each month
    foreach ($monthlyData as $monthKey => &$data) {
        $data['grand_total'] = $data['total_expenses'] + $data['hrm_total'];
    }
    
    // Get overall totals
    $overallTotals = [
        'total_expenses' => array_sum(array_column($monthlyData, 'total_expenses')),
        'total_hrm' => array_sum(array_column($monthlyData, 'hrm_total')),
        'grand_total' => 0
    ];
    $overallTotals['grand_total'] = $overallTotals['total_expenses'] + $overallTotals['total_hrm'];
    
    $categories = ExpenseCategory::pluck('name', 'id');
    $campuses = Campus::forDropdown();
    
    return view('Report.expense.monthly_expense_report')
                ->with(compact(
                    'monthlyData', 
                    'categories', 
                    'campuses', 
                    'overallTotals',
                    'filters'
                ));
}
public function getExpenseAndIncomeReport(Request $request)
{
    if (!auth()->user()->can('expense_report.view')) {
        abort(403, 'Unauthorized action.');
    }
    
    $filters = $request->only(['category', 'campus_id']);
    $campus_id = request()->campus_id;
    $date_range = $request->input('list_filter_date_range');
    
    if (!empty($date_range)) {
        $date_range_array = explode(' - ', $date_range);
        $filters['start_date'] = $this->expenseTransactionUtil->uf_date(trim($date_range_array[0]));
        $filters['end_date'] = $this->expenseTransactionUtil->uf_date(trim($date_range_array[1]));
    } else {
        $filters['start_date'] = \Carbon::now()->startOfYear()->format('Y-m-d');
        $filters['end_date'] = \Carbon::now()->endOfYear()->format('Y-m-d');
    }
    
    // Build expense query directly in controller
    $expenseQuery = ExpenseTransaction::leftjoin('expense_categories AS ec', 'expense_transactions.expense_category_id', '=', 'ec.id')
                            ->whereIn('type', ['expense', 'expense_refund']);
    
    // Apply campus permissions
    $permitted_campuses = auth()->user()->permitted_campuses();
    if ($permitted_campuses != 'all') {
        $expenseQuery->whereIn('expense_transactions.campus_id', $permitted_campuses);
    }
    
    // Apply filters
    if (!empty($filters['campus_id'])) {
        $expenseQuery->where('expense_transactions.campus_id', $filters['campus_id']);
    }
    if (!empty($filters['category'])) {
        $expenseQuery->where('ec.id', $filters['category']);
    }
    if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
        $expenseQuery->whereBetween(DB::raw('date(transaction_date)'), [$filters['start_date'], $filters['end_date']]);
    }
    
    // Get monthly expenses
    $monthlyExpenses = $expenseQuery->select(
        DB::raw('DATE_FORMAT(transaction_date, "%Y-%m") as month_key'),
        DB::raw('transaction_date as expense_date'),
        'ec.name as category',
        DB::raw("SUM( IF(expense_transactions.type='expense_refund', -1 * final_total, final_total) ) as total_expense")
    )
                ->groupBy(DB::raw('DATE_FORMAT(transaction_date, "%Y-%m")'), 'expense_category_id')
                ->orderBy('transaction_date', 'asc')
                ->get();
    
    // Build HRM query directly in controller using HrmTransactionPayment table
    $hrmQuery = HrmTransactionPayment::where('hrm_transaction_id', '!=', null);
    
    // Apply date filters
    if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
        $hrmQuery->whereDate('paid_on', '>=', $filters['start_date'])
                ->whereDate('paid_on', '<=', $filters['end_date']);
    }
    
    // Apply campus permissions for HRM
    if ($permitted_campuses != 'all') {
        $hrmQuery->whereIn('campus_id', $permitted_campuses);
    }
    
    // Apply campus filter for HRM
    if (!empty($campus_id)) {
        $hrmQuery->where('campus_id', $campus_id);
    }
    
    // Get monthly HRM data
    $monthlyHrm = $hrmQuery->select(
        DB::raw('DATE_FORMAT(paid_on, "%Y-%m") as month_key'),
        DB::raw('paid_on as payment_date'),
        DB::raw('SUM(amount) as total_hrm')
    )
    ->groupBy(DB::raw('DATE_FORMAT(paid_on, "%Y-%m")'))
    ->orderBy('paid_on', 'asc')
    ->get();
    
    // Build Fee Collection (Income) query using FeeTransactionPayment
    $feeQuery = FeeTransactionPayment::leftJoin('fee_transactions', 'fee_transaction_payments.fee_transaction_id', '=', 'fee_transactions.id')
                        ->whereIn('fee_transactions.type', ['fee', 'other_fee', 'opening_balance', 'admission_fee']);
    
    // Apply date filters for fees
    if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
        $feeQuery->whereDate('fee_transaction_payments.paid_on', '>=', $filters['start_date'])
                ->whereDate('fee_transaction_payments.paid_on', '<=', $filters['end_date']);
    }
    
    // Apply campus permissions for fees
    if ($permitted_campuses != 'all') {
        $feeQuery->whereIn('fee_transaction_payments.campus_id', $permitted_campuses);
    }
    
    // Apply campus filter for fees
    if (!empty($campus_id)) {
        $feeQuery->where('fee_transaction_payments.campus_id', $campus_id);
    }
    
    // Get monthly income (fee collection) data
    $monthlyIncome = $feeQuery->select(
        DB::raw('DATE_FORMAT(fee_transaction_payments.paid_on, "%Y-%m") as month_key'),
        DB::raw('fee_transaction_payments.paid_on as payment_date'),
        DB::raw('COALESCE(SUM(IF(fee_transaction_payments.is_return = 0, fee_transaction_payments.amount, fee_transaction_payments.amount * -1)), 0) as total_income')
    )
    ->groupBy(DB::raw('DATE_FORMAT(fee_transaction_payments.paid_on, "%Y-%m")'))
    ->orderBy('fee_transaction_payments.paid_on', 'asc')
    ->get();
    
    // Prepare monthly data with month names
    $monthlyData = [];
    $months = [];
    
    // Get all months in the date range
    $startDate = \Carbon::parse($filters['start_date']);
    $endDate = \Carbon::parse($filters['end_date']);
    
    while ($startDate->lte($endDate)) {
        $monthKey = $startDate->format('Y-m');
        $monthName = $startDate->format('F Y'); // e.g., "January 2024"
        
        $months[$monthKey] = $monthName;
        
        // Initialize monthly data
        $monthlyData[$monthKey] = [
            'month_name' => $monthName,
            'month_key' => $monthKey,
            'expenses' => [],
            'hrm_total' => 0,
            'total_income' => 0,
            'total_expenses' => 0,
            'net_profit' => 0,
            'total_cost' => 0
        ];
        
        $startDate->addMonth();
    }
    
    // Group expenses by month
    foreach ($monthlyExpenses as $expense) {
        $monthKey = \Carbon::parse($expense->expense_date)->format('Y-m');
        
        if (isset($monthlyData[$monthKey])) {
            $categoryName = !empty($expense->category) ? $expense->category : __('english.others');
            
            if (!isset($monthlyData[$monthKey]['expenses'][$categoryName])) {
                $monthlyData[$monthKey]['expenses'][$categoryName] = 0;
            }
            
            $monthlyData[$monthKey]['expenses'][$categoryName] += (float) $expense->total_expense;
            $monthlyData[$monthKey]['total_expenses'] += (float) $expense->total_expense;
        }
    }
    
    // Add HRM data to monthly totals
    foreach ($monthlyHrm as $hrm) {
        $monthKey = \Carbon::parse($hrm->payment_date)->format('Y-m');
        
        if (isset($monthlyData[$monthKey])) {
            $monthlyData[$monthKey]['hrm_total'] = (float) $hrm->total_hrm;
        }
    }
    
    // Add Income data to monthly totals
    foreach ($monthlyIncome as $income) {
        $monthKey = \Carbon::parse($income->payment_date)->format('Y-m');
        
        if (isset($monthlyData[$monthKey])) {
            $monthlyData[$monthKey]['total_income'] = (float) $income->total_income;
        }
    }
    
    // Calculate totals for each month
    foreach ($monthlyData as $monthKey => &$data) {
        $data['total_cost'] = $data['total_expenses'] + $data['hrm_total']; // Total Expenses + HRM
        $data['net_profit'] = $data['total_income'] - $data['total_cost']; // Income - Total Costs
    }
    
    // Get overall totals
    $overallTotals = [
        'total_expenses' => array_sum(array_column($monthlyData, 'total_expenses')),
        'total_hrm' => array_sum(array_column($monthlyData, 'hrm_total')),
        'total_income' => array_sum(array_column($monthlyData, 'total_income')),
        'total_cost' => 0,
        'net_profit' => 0
    ];
    $overallTotals['total_cost'] = $overallTotals['total_expenses'] + $overallTotals['total_hrm'];
    $overallTotals['net_profit'] = $overallTotals['total_income'] - $overallTotals['total_cost'];
    
    $categories = ExpenseCategory::pluck('name', 'id');
    $campuses = Campus::forDropdown();
    
    return view('Report.expense.expense_income_report')
                ->with(compact(
                    'monthlyData', 
                    'categories', 
                    'campuses', 
                    'overallTotals',
                    'filters'
                ));
}
}