<?php

namespace App\Http\Controllers;

use App\Repositories\FormField\FormFieldsInterface;
use App\Models\FormField;
use App\Rules\uniqueForSchool;
use App\Services\BootstrapTableService;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use Mpdf\Tag\Select;
use Throwable;
use Validator;
use Yajra\DataTables\DataTables;

class FormFieldsController extends Controller
{


    public function index()
    {
        if (request()->ajax()) {
            $formFields = FormField::orderBy('rank')
                ->select([
                    'id',
                    'name',
                    'type',
                    'is_required',
                    'default_values',
                    'other',
                    'rank',
                    'school_type'
                ]);

            return DataTables::of($formFields)

                ->addColumn(
                    'action',
                    '
                    <div class="d-flex order-actions action-class">
                    <button data-href="{{action(\'FormFieldsController@edit\', [$id])}}" class="btn btn-sm btn-primary edit_form_field_button"><i class="bx bxs-edit f-16 mr-15 text-white"></i> @lang("english.edit")</button>
                        &nbsp;
                        <button data-href="{{action(\'FormFieldsController@destroy\', [$id])}}" class="btn btn-sm btn-danger delete_fields_button"><i class="bx bxs-trash f-16 text-white"></i> @lang("english.delete")</button>
                    </div>
                    '

                )
                ->editColumn('is_required', function ($row) {
                    if ($row->is_required) {
                        return "<span class='badge badge-mark bg-success'> Yes </span>";
                    } else {
                        return "<span class='badge badge-mark bg-danger'>No</span>";
                    }
                })
                ->editColumn('default_values', function ($row) {
                    $value = FormField::gettDefaultValuesAttribute($row->default_values);
                    if (!empty($value)) {
                        $html = '<ul>';
                        foreach ($value as $item) {
                            $html .= "<li><i class='fa fa-arrow-right' aria-hidden='true'></i> " . $item . "</li>";
                        }
                        $html .= '</ul>';
                        return $html;
                    }
                    return '<div class="text-center"></div>';
                })
                ->setRowAttr([
                    'data-id' => function ($row) {
                        return $row->id;

                    },
                ])
                ->rawColumns(['action', 'default_values', 'is_required'])
                ->make(true);
        }
        return view('form-fields.index');
    }
    public function edit($id)
    {
        $data = FormField::find($id);
        return view('form-fields.edit')->with(compact('data'));

    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:form_fields',
            'type' => 'required',
        ]);
        try {
            // Check the Type and then populate the default data according to it
            if ($request->type == 'dropdown' || $request->type == 'radio' || $request->type == 'checkbox') {

                // Make Array for Values Only
                $defaultData = array();
                foreach ($request->default_data as $data) {
                    $defaultData[] = $data["option"];
                }
                $defaultData = json_encode($defaultData, JSON_THROW_ON_ERROR);
            } else {
                $defaultData = null;
            }

            $getRank = FormField::latest()->pluck('rank')->first();

            $data = array(
                'name' => $request->name,
                'type' => $request->type,
                'is_required' => $request->required == 'on' ? 1 : 0,
                'default_values' => $defaultData,
                'rank' => isset($getRank) ? ++$getRank : 1,
            );
            // Pass the Data Array to Repository to Add Data in Database
            FormField::create($data);

            ResponseService::successResponse('Data Stored Successfully');
        } catch (Throwable $e) {
            ResponseService::logErrorResponse($e, "Form Fields Controller -> Store method");
            ResponseService::errorResponse();
        }
    }

    public function destroy($id)
    {
        ResponseService::noPermissionThenSendJson('form-fields-delete');
        try {
            FormField::findOrFail($id)->delete();
            $output = [
                'success' => true,
                'msg' => __("english.updated_success")
            ];
        } catch (Throwable $e) {
            $output = [
                'success' => false,
                'msg' => __("english.something_went_wrong")
            ];
        }

        return $output;
    }
    public function updateRanks(Request $request)
    {
        $updates = $request->input('updates');
        // dd($updates);
        foreach ($updates as $update) {
            FormField::where('id', $update['id'])->update(['rank' => $update['new_rank']]);
        }

        return response()->json(['success' => true, 'message' => 'Ranks updated successfully!']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:form_fields,name,' . $id,
            'type' => 'required',

        ]);
       try {
            $defaultData = null;
            // Check the Type and then populate the default data according to it
            if ($request->type == 'dropdown' || $request->type == 'radio' || $request->type == 'checkbox') {

                // Make Array for Values Only
                $defaultData = array();
                foreach ($request->default_data as $data) {
                    $defaultData[] = $data["option"];
                }
                $defaultData = json_encode($defaultData, JSON_THROW_ON_ERROR);
            }

            $data = array(
                'name' => $request->name,
                'is_required' => $request->edit_required == 'on' ? 1 : 0,
                'default_values' => $defaultData
            );
            // Pass the Data Array to Repository to Update Data in Database
            FormField::where('id', $id)->update($data);



            $output = [
                'success' => true,
                'msg' => __("english.updated_success")
            ];
        } catch (Throwable $e) {
            $output = [
                'success' => false,
                'msg' => __("english.something_went_wrong")
            ];
        }
        return $output;
    }


}

