<?php

namespace App\Http\Controllers;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */public function index()
{
    if (!auth()->user()->can('mobile_slider.view')) {
        abort(403, 'Unauthorized action.');
    }

    if (request()->ajax()) {
        $sliders = Slider::select(['id', 'image', 'type', 'link']);

        return DataTables::of($sliders)
            ->addColumn(
                'action',
                function ($row) {
                    $html = '<div class="dropdown">
                                <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">' . __("english.actions") . '</button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="#" data-href="' . action('SliderController@edit', [$row->id]) . '" class="edit_slider_button dropdown-item">
                                            <i class="bx bxs-edit f-16 mr-15"></i>' . __("english.edit") . '
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" data-href="' . action('SliderController@destroy', [$row->id]) . '" class="delete_slider_button dropdown-item">
                                            <i class="bx bxs-trash f-16 mr-15"></i>' . __("english.delete") . '
                                        </a>
                                    </li>
                                </ul>
                            </div>';
                    return $html;
                }
            )
            ->editColumn('image', function ($row) {
                return '<img src="' . e($row->image) . '" class="img-border" width="50" height="50" alt="slider image">';
            })
            ->editColumn('type', function ($row) {
                switch ($row->type) {
                    case 1:
                        return '<div class="badge bg-primary badge-mark">' . __("english.app") . '</div>';
                    case 2:
                        return '<div class="badge bg-info badge-mark">' . __("english.web") . '</div>';
                    case 3:
                        return '<div class="badge bg-success badge-mark">' . __("english.both") . '</div>';
                    default:
                        return '';
                }
            })
            ->editColumn('link', function ($row) {
                if (!empty($row->link)) {
                    return '<a href="' . e($row->link) . '" target="_blank" class="text-primary">' . e($row->link) . '</a>';
                }
                return '<span class="text-muted">' . __("english.no_link_provided") . '</span>';
            })
            ->removeColumn('id')
            ->rawColumns(['action', 'image', 'type','link'])
            ->make(true);
    }

    return view('notification.mobile-slider.index');
}


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('notification.mobile-slider.create');

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
            'image' => 'required|mimes:jpeg,png,jpg|image|max:5000',
            'type' => 'required',
              'link'  => 'nullable|url'
        
        ]);

        if ($validator->fails()) {
            $output = ['success' => false,
            'msg' => __("english.something_went_wrong")
            ];
            return redirect('sliders')->with('status', $output);
        }

        try {
            $slider = new Slider();
            $slider->image = $request->file('image')->store('sliders', 'public');
            $slider->link = $request->link;
            $slider->type = $request->type;
          
            $slider->save();

            $output = ['success' => true,
            'msg' => __("english.added_success")
        ];
        } catch (\Throwable $e) {
        
            $output = ['success' => false,
            'msg' => __("english.something_went_wrong")
            ];
        }
        return redirect('sliders')->with('status', $output);
    }
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $slider= Slider::find($id);
        return view('notification.mobile-slider.edit')->with(compact('slider'));
        
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
            'image' => 'mimes:jpeg,png,jpg|image|max:5000',
             'type' => 'required',
              'link'  => 'nullable|url'
        ]);

        if ($validator->fails()) {
            $output = ['success' => false,
            'msg' => __("english.something_went_wrong")
            ];
            return $output;
        }

        try {
            $slider = Slider::find($id);
            if ($request->hasFile('image')) {
                if (Storage::disk('public')->exists($slider->image)) {
                    Storage::disk('public')->delete($slider->image);
                }
                $slider->image = $request->file('image')->store('sliders', 'public');
            }
            $slider->link = $request->link;
            $slider->type = $request->type;
            $slider->save();

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
            $slider = Slider::find($id);
            if (Storage::disk('public')->exists($slider->image)) {
                Storage::disk('public')->delete($slider->image);
            }
            $slider->delete();
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
