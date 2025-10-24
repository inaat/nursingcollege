<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use App\Models\Frontend\Faq;
use Illuminate\Http\Request;
use Validator;
use Yajra\DataTables\Facades\DataTables;
use App\Utils\Util;
use Illuminate\Support\Str;


use File;

class FrontFaqController extends Controller
{
    protected $commonUtil;

    /**
    * Constructor
    *
    */
    public function __construct(Util $commonUtil)
    {
        $this->commonUtil = $commonUtil;
    }
  /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
  
        if (!auth()->user()->can('grade.view')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $faqs = Faq::select(['title', 'id','description']);

            return DataTables::of($faqs)
                            ->addColumn(
                                'action',
                                function ($row) {
                                    $html= '<div class="dropdown">
                             <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">'. __("english.actions").'</button>
                             <ul class="dropdown-menu" style="">';
                                   $html .= '<li><a  href="#" data-href="' .action('Frontend\FrontFaqController@edit', [$row->id]).'" class=" edit_faq_button dropdown-item"><i class="bx bxs-edit f-16 mr-15"></i>' . __("english.edit") . '</a></li>';
                                   $html .= '<li><a  href="#" data-href="' .action('Frontend\FrontFaqController@destroy', [$row->id]).'" class=" delete_faq_button dropdown-item"><i class="bx bxs-trash f-16 mr-15"></i>' . __("english.delete") . '</a></li>';


                                    $html .= '</ul></div>';

                                    return $html;
                                }
                            )
        
                            ->removeColumn('id')
                            ->rawColumns(['action','title','description'])
                            ->make(true);
        }

        return view('frontend.backend.faq.index');
    }
    public function create()
    {
        return view('frontend.backend.faq.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            
            'title' => 'required',
            'description'  => 'required'
        
        ]);

        if ($validator->fails()) {
            $output = ['success' => false,
            'msg' => __("english.something_went_wrong")
            ];
            return redirect('front-faqs')->with('status', $output);
        }

        try {
            $faq = new Faq();
            $faq->title = $request->title;
            $faq->description = $request->description;
          
            $faq->save();

            $output = ['success' => true,
            'msg' => __("english.added_success")
        ];
        } catch (\Throwable $e) {
        
            $output = ['success' => false,
            'msg' => __("english.something_went_wrong")
            ];
        }
        return redirect('front-faqs')->with('status', $output);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $faq=Faq::find($id);
        return view('frontend.backend.faq.edit')->with(compact('faq'));
        
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
        $validator = Validator::make($request->all(), [
           'title' => 'required',
            'description'  => 'required'
        ]);

        if ($validator->fails()) {
            $output = ['success' => false,
            'msg' => __("english.something_went_wrong")
            ];
            return $output;
        }

        try {
            $faq = Faq::find($id);
           
            $faq->title = $request->title;
            $faq->description = $request->description;
          
            $faq->save();

            $output = ['success' => true,
            'msg' => __("english.updated_success")
        ];
        } catch (\Throwable $e) {
        
            $output = ['success' => false,
            'msg' => __("english.something_went_wrong")
            ];
        }
        return $output;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $faq = Faq::find($id);
          
            $faq->delete();
            $output = ['success' => true,
            'msg' => __("english.deleted_success")
];
        } catch (\Throwable $e) {
            $output = ['success' => false,
            'msg' => __("english.something_went_wrong")
        ];
}

return $output;
    }
}
