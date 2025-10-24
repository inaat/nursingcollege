<?php

namespace App\Http\Controllers;

use App\Models\AccountType;
use Illuminate\Http\Request;

class AccountTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }

        $system_settings_id = session()->get('user.system_settings_id');

        $account_types = AccountType::where('system_settings_id', $system_settings_id)
                                     ->whereNull('parent_account_type_id')
                                     ->get();

        return view('account_types.create')
                ->with(compact('account_types'));
    }

  /**
 * Store a newly created resource in storage.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\Response
 */
public function store(Request $request)
{
    if (!auth()->user()->can('account.access')) {
        abort(403, 'Unauthorized action.');
    }

    try {
        // Get the input from the form
        $input = $request->only(['name', 'parent_account_type_id', 'debit_increases', 'credit_increases']);
        
        // Add the system settings ID to the input
        $input['system_settings_id'] = $request->session()->get('user.system_settings_id');

        // Create the new AccountType record in the database
        AccountType::create($input);

        // Prepare the success message
        $output = ['success' => true,
                    'msg' => __("english.added_success")
                ];
    } catch (\Exception $e) {
        // Log the error if something goes wrong
        \Log::emergency("File:" . $e->getFile() . " Line:" . $e->getLine() . " Message:" . $e->getMessage());
        
        // Prepare the error message
        $output = ['success' => false,
                    'msg' => __("english.something_went_wrong")
                ];
    }

    // Return to the previous page with the success or error message
    return redirect()->back()->with('status', $output);
}


    /**
     * Display the specified resource.
     *
     * @param  \App\AccountType  $accountType
     * @return \Illuminate\Http\Response
     */
    public function show(AccountType $accountType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AccountType  $accountType
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }

        $system_settings_id = session()->get('user.system_settings_id');

        $account_type = AccountType::where('system_settings_id', $system_settings_id)
                                     ->findOrFail($id);

        $account_types = AccountType::where('system_settings_id', $system_settings_id)
                                     ->whereNull('parent_account_type_id')
                                     ->get();

        return view('account_types.edit')
                ->with(compact('account_types', 'account_type'));
    }

/**
 * Update the specified resource in storage.
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  \App\AccountType  $accountType
 * @return \Illuminate\Http\Response
 */
public function update(Request $request, $id)
{
    if (!auth()->user()->can('account.access')) {
        abort(403, 'Unauthorized action.');
    }

    // Validate incoming request data
    $request->validate([
        'name' => 'required|string|max:255',
        'parent_account_type_id' => 'nullable|exists:account_types,id',
        'debit_increases' => 'nullable|boolean', // Validate debit_increases as boolean
        'credit_increases' => 'nullable|boolean', // Validate credit_increases as boolean
    ]);

    try {
        // Get the system_settings_id
        $system_settings_id = $request->session()->get('user.system_settings_id');

        // Find the AccountType by system_settings_id and id
        $account_type = AccountType::where('system_settings_id', $system_settings_id)
                                   ->findOrFail($id);

        // Prepare the data for updating (including the new fields)
        $input = $request->only(['name', 'parent_account_type_id', 'debit_increases', 'credit_increases']);

        // If AccountType is changed to subtype, update all its subtypes' parent_account_type_id
        if (empty($account_type->parent_account_type_id) && !empty($input['parent_account_type_id'])) {
            AccountType::where('system_settings_id', $system_settings_id)
                ->where('parent_account_type_id', $account_type->id)
                ->update(['parent_account_type_id' => $input['parent_account_type_id']]);
        }

        // Update the account type
        $account_type->update($input);

        // Prepare success message
        $output = [
            'success' => true,
            'msg' => __("english.updated_success")
        ];
    } catch (\Exception $e) {
        // Log error details for debugging
        \Log::emergency("File:" . $e->getFile() . " Line:" . $e->getLine() . " Message:" . $e->getMessage());

        // Prepare failure message
        $output = [
            'success' => false,
            'msg' => __("english.something_went_wrong")
        ];
    }

    // Redirect back with status message
    return redirect()->back()->with('status', $output);
}


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AccountType  $accountType
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }

        $system_settings_id = session()->get('user.system_settings_id');

        AccountType::where('system_settings_id', $system_settings_id)
                                     ->where('id', $id)
                                     ->delete();

        //Upadete parent account if set
        AccountType::where('system_settings_id', $system_settings_id)
                 ->where('parent_account_type_id', $id)
                 ->update(['parent_account_type_id' => null]);

        $output = ['success' => true,
                            'msg' => __("english.deleted_success")
                        ];

        return redirect()->back()->with('status', $output);
    }
}
