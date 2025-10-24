<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Semester;
use Yajra\DataTables\Facades\DataTables;

class SemesterController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('semester.view')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            $semester = Semester::select(['title', 'status', 'id']);
            return Datatables::of($semester)
                ->addColumn(
                    'action',
                    '<div class="d-flex order-actions">
                     @can("semester.update")
                    <button data-href="{{action(\'SemesterController@edit\', [$id])}}" class="btn btn-sm btn-primary edit_semester_button"><i class="bx bxs-edit f-16 mr-15 text-white"></i> @lang("english.edit")</button>
                        &nbsp;
                        @endcan
                        @can("semester.delete")
                        <button data-href="{{action(\'SemesterController@destroy\', [$id])}}" class="btn btn-sm btn-danger delete_semester_button"><i class="bx bxs-trash f-16 text-white"></i> @lang("english.delete")</button>
                        @endcan
                    </div>'
                )

                ->removeColumn('id')
                ->rawColumns(['action','status','title'])
                ->make(true);
        }

        return view('admin.global_configuration.semesters.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('semester.create')) {
            abort(403, 'Unauthorized action.');
        }
        return view('admin.global_configuration.semesters.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('semester.create')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            $input = $request->only(['title']);
            $user_id = $request->session()->get('user.id');
          ///  $input['created_by'] = $user_id;
            $input['status'] = 1;
            $semester = Semester::create($input);
            $output = ['success' => true,
                            'data' => $semester,
                            'msg' => __("english.added_success")
                        ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

            $output = ['success' => false,
                            'msg' => __("english.something_went_wrong")
                        ];
        }

        return $output;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!auth()->user()->can('semester.update')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            $semester = Semester::find($id);
            return view('admin.global_configuration.semesters.edit')
                ->with(compact('semester'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('semester.update')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            try {
                $input = $request->only(['title']);

                $semester = Semester::findOrFail($id);
                $semester->title = $input['title'];
                $semester->save();

                $output = ['success' => true,
                            'msg' => __("english.updated_success")
                            ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

                $output = ['success' => false,
                            'msg' => __("english.something_went_wrong")
                        ];
            }

            return $output;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!auth()->user()->can('semester.delete')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            try {
                $semester = Semester::findOrFail($id);
                //$semester->delete();

                $output = ['success' => true,
                            'msg' => __("english.deleted_success")
                            ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

                $output = ['success' => false,
                            'msg' => __("english.something_went_wrong")
                        ];
            }

            return $output;
        }
    }

}
