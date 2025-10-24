<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Batch;
use Yajra\DataTables\Facades\DataTables;

class BatchController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('batch.view')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            $batch = Batch::select(['title','from','to', 'status', 'id']);
            return Datatables::of($batch)
                ->addColumn(
                    'action',
                    '<div class="d-flex order-actions">
                     @can("batch.update")
                    <button data-href="{{action(\'BatchController@edit\', [$id])}}" class="btn btn-sm btn-primary edit_batch_button"><i class="bx bxs-edit f-16 mr-15 text-white"></i> @lang("english.edit")</button>
                        &nbsp;
                        @endcan
                        @can("batch.delete")
                        <button data-href="{{action(\'BatchController@destroy\', [$id])}}" class="btn btn-sm btn-danger delete_batch_button"><i class="bx bxs-trash f-16 text-white"></i> @lang("english.delete")</button>
                        @endcan
                    </div>'
                )

                ->removeColumn('id')
                ->rawColumns(['action','status','from','to','title'])
                ->make(true);
        }

        return view('admin.global_configuration.batches.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('batch.create')) {
            abort(403, 'Unauthorized action.');
        }
        return view('admin.global_configuration.batches.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('batch.create')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            $input = $request->only(['title','to','from']);
            $user_id = $request->session()->get('user.id');
          ///  $input['created_by'] = $user_id;
            $input['status'] = 1;
            $batch = Batch::create($input);
            $output = ['success' => true,
                            'data' => $batch,
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
        if (!auth()->user()->can('batch.update')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            $batch = Batch::find($id);
            return view('admin.global_configuration.batches.edit')
                ->with(compact('batch'));
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
        if (!auth()->user()->can('batch.update')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            try {
                $input = $request->only(['title','from','to']);

                $batch = Batch::findOrFail($id);
                $batch->title = $input['title'];
                $batch->to = $input['to'];
                $batch->from = $input['from'];
                $batch->save();

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
        if (!auth()->user()->can('batch.delete')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            try {
                $batch = Batch::findOrFail($id);
                //$batch->delete();

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
