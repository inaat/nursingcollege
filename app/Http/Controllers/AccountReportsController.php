<?php

namespace App\Http\Controllers;


use App\Models\Account;
use App\Models\AccountTransaction;
use App\Utils\FeeTransactionUtil;
use App\Utils\HrmTransactionUtil;
use App\Utils\ExpenseTransactionUtil;
use DB;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Campus;
use PDF; // Make sure to install barryvdh/laravel-dompdf
use Maatwebsite\Excel\Facades\Excel; // Make sure to install maatwebsite/excel
use App\Exports\CashBookExport;
use App\Models\AccountType;
class AccountReportsController extends Controller
{
     /**
     * All Utils instance.
     *
     */
    protected $fee_transactionUtil;
    protected $hrmTransactionUtil;
    protected $expenseTransactionUtil;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(FeeTransactionUtil $fee_transactionUtil,HrmTransactionUtil $hrmTransactionUtil,ExpenseTransactionUtil $expenseTransactionUtil)
    {
        $this->fee_transactionUtil = $fee_transactionUtil;
        $this->hrmTransactionUtil = $hrmTransactionUtil;
        $this->expenseTransactionUtil = $expenseTransactionUtil;
    }
     public function balanceSheet()
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            $end_date = !empty(request()->input('end_date')) ? $this->fee_transactionUtil->uf_date(request()->input('end_date')) : \Carbon::now()->format('Y-m-d');
             $campus_id = !empty(request()->input('campus_id')) ? request()->input('campus_id') : null;

            $fee_details = $this->fee_transactionUtil->getFeeTotals(
                null,
                $end_date,
                $campus_id
            );
            $hrm_details = $this->hrmTransactionUtil->getPayRollTotals(
                null,
                $end_date,
                $campus_id
            );
            $expense_details = $this->expenseTransactionUtil->getExpenseTotals(
                null,
                $end_date,
                $campus_id
            );

            $account_details = $this->getAccountBalance($end_date, $campus_id);


            $output = [
                'expense_due' => $expense_details['total_expense_due'],
                'fee_due' => $fee_details['total_fee_due'],
                'payroll_due' => $hrm_details['total_hrm_due'],
                'account_balances' => $account_details,
                'capital_account_details' => null
            ];

            return $output;
        }

        $campuses=Campus::forDropdown();

        return view('account_reports.balance_sheet')->with(compact('campuses'));
    }
     /**
     * Display a listing of the resource.
     * @return Response
     */
    public function trialBalance()
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }
       // dd($this->fee_transactionUtil->getFeeTotals());
       // dd($this->hrmTransactionUtil->getPayRollTotals());
       // dd($this->expenseTransactionUtil->getExpenseTotals());

        if (request()->ajax()) {
            $end_date = !empty(request()->input('end_date')) ? $this->fee_transactionUtil->uf_date(request()->input('end_date')) : \Carbon::now()->format('Y-m-d');
             $campus_id = !empty(request()->input('campus_id')) ? request()->input('campus_id') : null;

            $fee_details = $this->fee_transactionUtil->getFeeTotals(
                null,
                $end_date,
                $campus_id
            );
            $hrm_details = $this->hrmTransactionUtil->getPayRollTotals(
                null,
                $end_date,
                $campus_id
            );
            $expense_details = $this->expenseTransactionUtil->getExpenseTotals(
                null,
                $end_date,
                $campus_id
            );

            $account_details = $this->getAccountBalance($end_date, $campus_id);


            $output = [
                'expense_due' => $expense_details['total_expense_due'],
                'fee_due' => $fee_details['total_fee_due'],
                'payroll_due' => $hrm_details['total_hrm_due'],
                'account_balances' => $account_details,
                'capital_account_details' => null
            ];

            return $output;
        }

        $campuses=Campus::forDropdown();

        return view('account_reports.trial_balance')->with(compact('campuses'));
    }
    private function getAccountBalance($end_date, $campus_id = null)
    {
        $query = Account::leftjoin(
            'account_transactions as AT',
            'AT.account_id',
            '=',
            'accounts.id'
        )
                                // ->NotClosed()
                                ->whereNull('AT.deleted_at')
                                ->whereDate('AT.operation_date', '<=', $end_date);

       
//Filter by the campus
$permitted_campuses = auth()->user()->permitted_campuses();
if ($permitted_campuses != 'all') {
 $query->whereIn('accounts.campus_id', $permitted_campuses);
}
if (!empty($campus_id)) {
    $query->where('accounts.campus_id', $campus_id);
  }
        $account_details = $query->select(['name',
                                        DB::raw("SUM( IF(AT.type='credit', amount, -1*amount) ) as balance")])
                                ->groupBy('accounts.id')
                                ->get()
                                ->pluck('balance', 'name');

        return $account_details;
    }


    public function threeColumnCashBookReport(Request $request)
    {
        // Fetch all accounts with their account types
        $is_closed = request()->input('account_status') == 'closed' ? 1 : 0;

        $accounts = Account::with('account_type')->where('is_closed', $is_closed)->get();
    
        // Date range from request or default to the current month
        $dateFrom = $request->date_from ?? now()->startOfMonth()->format('Y-m-d');
        $dateTo = $request->date_to ?? now()->format('Y-m-d');
    
        // Initialize opening balances array
        $openingBalances = [];
    
        // Loop through each account and calculate the opening balance
        foreach ($accounts as $account) {
            $openingBalances[$account->id] = $this->getOpeningBalance($account->id, $dateFrom, $account->account_type);
        }
    
        // Get all account ids for the transactions
        $allAccountIds = $accounts->pluck('id');
    
        // Fetch transactions within the date range for the accounts
        $transactions = AccountTransaction::with([
            'account',
            'account.account_type',
            'feeTransactionPayment',
            'hrmTransactionPayment',
            'createdBy',
            'feeTransactionPayment.student',
            'feeTransactionPayment.fee_transaction',
            'feeTransactionPayment.student.current_class',
            'hrmTransactionPayment.employee',
            'transfer_transaction', 
            'media', 
            'transfer_transaction.media',
            'expenseTransactionPayment',
            'expenseTransactionPayment.expense_transaction',
            'expenseTransactionPayment.expense_transaction.expenseCategory'
        ])
        ->whereIn('account_id', $allAccountIds)
        ->whereDate('operation_date', '>=', $dateFrom)
        ->whereDate('operation_date', '<=', $dateTo)
        ->orderBy('operation_date', 'asc')
        ->orderBy('id', 'asc')
        ->get();
    
        return view('account_reports.cashbook', compact(
            'accounts',
            'transactions',
            'dateFrom',
            'dateTo',
            'openingBalances'
        ));
    }
    
    private function getOpeningBalance($accountId, $date, $accountType)
    {
        $account = Account::findOrFail($accountId);
        
        // Start with the initial balance from when the account was created
        $openingBalance = $account->opening_balance ?? 0;
    
        // Get the previous transactions for this account before the given date
        $previousTransactions = AccountTransaction::where('account_id', $accountId)
            ->whereDate('operation_date', '<', $date)
            ->get();
    
        // Loop through the previous transactions to calculate the opening balance
        foreach ($previousTransactions as $transaction) {
            // Determine if the debit or credit should increase or decrease based on the account type
            if ($accountType->debit_increases) {
                // For accounts where debit increases (e.g., Assets)
                if ($transaction->type == 'debit') {
                    $openingBalance += $transaction->amount;
                } elseif ($transaction->type == 'credit') {
                    $openingBalance -= $transaction->amount;
                }
            } else {
                // For accounts where credit increases (e.g., Liabilities/Equity)
                if ($transaction->type == 'debit') {
                    $openingBalance -= $transaction->amount;
                } elseif ($transaction->type == 'credit') {
                    $openingBalance += $transaction->amount;
                }
            }
        }
    
        return $openingBalance;
    }
    
        public function exportCashBookPDF(Request $request)
        {
            $data = $this->getReportData($request);
            
            $pdf = PDF::loadView('account_reports.cashbook_pdf', $data);
            return $pdf->download('cashbook_report_' . date('Ymd_His') . '.pdf');
        }
        public function exportCashBookExcel(Request $request)
        {
            // Get the filtered data based on request parameters
            $reportData = $this->getReportData($request);
            
            // Generate filename with current date and time
            $fileName = 'cashbook_report_' . date('Ymd_His') . '.xlsx';
            
            // Return the Excel download
            return Excel::download(
                new CashBookExport($reportData), 
                $fileName
            );
        }
    
        private function getReportData(Request $request)
        {
            $cashAccountType = AccountType::where('id', 1)->first();
            $bankAccountType = AccountType::where('id', 2)->first();
    
            $cashAccounts = Account::where('account_type_id', $cashAccountType->id)->get();
            $bankAccounts = Account::where('account_type_id', $bankAccountType->id)->get();
    
            $dateFrom = $request->date_from ?? now()->startOfMonth()->format('Y-m-d');
            $dateTo = $request->date_to ?? now()->format('Y-m-d');
    
            $openingCashBalances = [];
            $openingBankBalances = [];
    
            foreach ($cashAccounts as $cashAccount) {
                $openingCashBalances[$cashAccount->id] = $this->getOpeningBalance($cashAccount->id, $dateFrom);
            }
    
            foreach ($bankAccounts as $bankAccount) {
                $openingBankBalances[$bankAccount->id] = $this->getOpeningBalance($bankAccount->id, $dateFrom);
            }
    
            $allAccountIds = $cashAccounts->pluck('id')->merge($bankAccounts->pluck('id'));
    
            $transactions = AccountTransaction::with([
                'feeTransactionPayment',
                'hrmTransactionPayment',
                'createdBy',
                'feeTransactionPayment.student',
                'feeTransactionPayment.fee_transaction',
                'feeTransactionPayment.student.current_class',
                'hrmTransactionPayment.employee',
                'transfer_transaction', 
                'media', 
                'transfer_transaction.media',
                'expenseTransactionPayment',
                'expenseTransactionPayment.expense_transaction',
                'expenseTransactionPayment.expense_transaction.expenseCategory'
            ])
            ->whereIn('account_id', $allAccountIds)
            ->whereDate('operation_date', '>=', $dateFrom)
            ->whereDate('operation_date', '<=', $dateTo)
            ->orderBy('operation_date', 'asc')
            ->orderBy('id', 'asc')
            ->get();
    
            return compact(
                'cashAccounts',
                'bankAccounts',
                'transactions',
                'dateFrom',
                'dateTo',
                'openingCashBalances',
                'openingBankBalances'
            );
        }
    }