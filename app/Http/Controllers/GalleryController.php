<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Repositories\Files\FilesInterface;
use App\Repositories\Gallery\GalleryInterface;
use App\Repositories\SessionYear\SessionYearInterface;
use App\Services\BootstrapTableService;
use App\Services\CachingService;
use App\Services\ResponseService;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Storage;
use Throwable;
use App\Models\Session;
use Yajra\DataTables\DataTables;

class GalleryController extends Controller
{
    
    private  $cache;
    private  $gallery;
    private  $files;

    public function __construct( CachingService $cache, GalleryInterface $gallery, FilesInterface $files) {
      
        $this->cache = $cache;
        $this->gallery = $gallery;
        $this->files = $files;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        if (request()->ajax()) {
            $galleries = Gallery::select(['title', 'id','thumbnail','description']);

            return DataTables::of($galleries)
                            ->addColumn(
                          
                                'action',
                                function ($row) {
                                    $html= '<div class="dropdown">
                             <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">'. __("english.actions").'</button>
                             <ul class="dropdown-menu" style="">';
                                    $html .= '<li><a  href="' .action('GalleryController@edit', [$row->id]).'" class="  dropdown-item"><i class="bx bxs-edit f-16 mr-15"></i>' . __("english.edit") . '</a></li>';
                                    $html .= '<li><a  href="#" data-href="' .action('GalleryController@destroy', [$row->id]).'" class=" delete_gallery_button dropdown-item"><i class="bx bxs-trash f-16 mr-15"></i>' . __("english.delete") . '</a></li>';


                                    $html .= '</ul></div>';

                                    return $html;
                                }
                            )
                                ->editColumn('thumbnail', function ($row) {
                                    $image =  $row->thumbnail ;
                                    $img=' <img src="'.url( $image).'" class="img-border " width="50" height="50" alt="" ></div>';
                                    return $img;
                                })
                            // ->editColumn('registration_date', function ($row) {
                            //     return $this->accountTransactionUtil->format_date($row->registration_date);
                            // })
                            ->removeColumn('id')
                            ->rawColumns(['action','thumbnail','description'])
                            ->make(true);
        }
        $sessions=Session::forDropdown();
        return view('frontend.backend.gallery.index', compact('sessions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        ResponseService::noFeatureThenRedirect("School Gallery Management");
        ResponseService::noPermissionThenSendJson('gallery-create');
        
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'thumbnail' => 'required|mimes:jpg,svg,jpeg,png',
            'images.*' => 'mimes:jpg,svg,jpeg,png'
        ]);
        if ($validator->fails()) {
            ResponseService::errorResponse($validator->errors()->first());
        }

        try {
            DB::beginTransaction();
            if ($request->youtube_links) {
                $links = explode(",", $request->youtube_links);
                $status = 1;
                foreach ($links as $key => $link) {
                    if (preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/((?:watch)\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]{11})/", $link, $matches)) {
                        $status = 1;
                    } else {
                        $status = 0;
                        break;
                    }
                }

                if ($status == 0) {
                    ResponseService::errorResponse('Please Enter Valid Youtube Link');
                }

            }
            $data = [
                'title' => $request->title,
                'description' => $request->description,
                'thumbnail' => $request->thumbnail,
                'session_id' => $request->session_id
            ];
          
            $gallery = $this->gallery->create($data);

            // Initialize the Empty Array
            $galleryFileData = array();

            // Create A File Model Instance
            $galleryFile = $this->files->model();

            // Get the Association Values of File with gallery
            $galleryModelAssociate = $galleryFile->modal()->associate($gallery);

            if (!empty($request->images)) {
                
                foreach ($request->images as $key => $image) {
                    
                    $tempFileData = array(
                        'modal_type' => $galleryModelAssociate->modal_type,
                        'modal_id'   => $galleryModelAssociate->modal_id,
                        'file_name'  => basename($image->getClientOriginalName(), '.'.$image->getClientOriginalExtension()),
                    );

                    $tempFileData['type'] = 1;
                    $tempFileData['file_thumbnail'] = null;
                    $tempFileData['file_url'] = $image;

                    $galleryFileData[] = $tempFileData;
                }
            }
            // YouTube links
            if ($request->youtube_links) {
                
                
                foreach ($links as $key => $link) {
                    $tempFileData = array(
                        'modal_type' => $galleryModelAssociate->modal_type,
                        'modal_id'   => $galleryModelAssociate->modal_id,
                        'file_name'  => 'YouTube Link',
                    );

                    $tempFileData['type'] = 2;
                    $tempFileData['file_thumbnail'] = null;
                    $tempFileData['file_url'] = $link;

                    $galleryFileData[] = $tempFileData;
                }
            }
            if (!empty($request->images) || $request->youtube_links) {
                $this->files->createBulk($galleryFileData);
            }
            
            DB::commit();
            ResponseService::successResponse('Data Stored Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            ResponseService::logErrorResponse($th, "Gallery Controller -> Store Method");
            ResponseService::errorResponse();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        ResponseService::noFeatureThenRedirect("School Gallery Management");
        ResponseService::noPermissionThenSendJson('gallery-list');

        $offset = request('offset', 0);
        $limit = request('limit', 10);
        $sort = request('sort', 'id');
        $order = request('order', 'DESC');
        $search = request('search');
        $session_year_id = request('session_year_id');

        $sql = $this->gallery->builder()->with('file')
            ->where(function ($query) use ($search) {
                $query->when($search, function ($query) use ($search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('id', 'LIKE', "%$search%")
                        ->orwhere('title', 'LIKE', "%$search%")
                        ->orwhere('description', 'LIKE', "%$search%");
                    });
                });
            })
            ->where(function ($query) use ($session_year_id) {
                $query->when($session_year_id, function ($query) use ($session_year_id) {
                    $query->where(function ($query) use ($session_year_id) {
                        $query->where('session_year_id',$session_year_id);
                    });
                });
            });


        $total = $sql->count();

        $sql->orderBy($sort, $order)->skip($offset)->take($limit);
        $res = $sql->get();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $no = 1;
        foreach ($res as $row) {
            $operate = '';
            $operate = BootstrapTableService::button('fa fa-eye', route('gallery.edit',$row->id), ['btn-gradient-info'], ['title' => trans("view")]);

            $operate .= BootstrapTableService::editButton(route('gallery.update', $row->id));
            $operate .= BootstrapTableService::deleteButton(route('gallery.destroy', $row->id));

            $tempRow = $row->toArray();
            $tempRow['no'] = $no++;
            $tempRow['files'] = $row->file;
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        return response()->json($bulkData);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
        ResponseService::noFeatureThenRedirect("School Gallery Management");
        ResponseService::noPermissionThenSendJson('gallery-edit');

        $gallery = Gallery::with('file')->where('id',$id)->first();
     
        $sessions=Session::forDropdown();
        return view('frontend.backend.gallery.edit',compact('gallery','sessions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        ResponseService::noFeatureThenRedirect("School Gallery Management");
        ResponseService::noPermissionThenSendJson('gallery-edit');
        
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'thumbnail' => 'mimes:jpg,svg,jpeg,png'
        ]);
        if ($validator->fails()) {
            ResponseService::errorResponse($validator->errors()->first());
        }

        try {
            DB::beginTransaction();

            if ($request->youtube_links) {
                $links = explode(",", $request->youtube_links);
                $status = 1;
                foreach ($links as $key => $link) {
                    if (preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/((?:watch)\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]{11})/", $link, $matches)) {
                        $status = 1;
                    } else {
                        $status = 0;
                        break;
                    }
                }

                if ($status == 0) {
                    ResponseService::errorResponse('Please Enter Valid Youtube Link');
                }

            }
            $data = [
                'title' => $request->title,
                'description' => $request->description,
                'session_id' => $request->session_id,
            ];

            if ($request->hasFile('thumbnail')) {
                $data['thumbnail'] = $request->thumbnail;
            }
    
            $gallery = $this->gallery->update($id,$data);

            // Initialize the Empty Array
            $galleryFileData = array();

            // Create A File Model Instance
            $galleryFile = $this->files->model();

            // Get the Association Values of File with gallery
            $galleryModelAssociate = $galleryFile->modal()->associate($gallery);
            if (!empty($request->images)) {
                foreach ($request->images as $key => $image) {
                    
                    $tempFileData = array(
                        'modal_type' => $galleryModelAssociate->modal_type,
                        'modal_id'   => $galleryModelAssociate->modal_id,
                        'file_name'  => basename($image->getClientOriginalName(), '.'.$image->getClientOriginalExtension()),
                    );

                    $tempFileData['type'] = 1;
                    $tempFileData['file_thumbnail'] = null;
                    $tempFileData['file_url'] = $image;

                    $galleryFileData[] = $tempFileData;
                }
            }

            // YouTube links
            if ($request->youtube_links) {
                
                $links = explode(",", $request->youtube_links);
                foreach ($links as $key => $link) {
                    $tempFileData = array(
                        'modal_type' => $galleryModelAssociate->modal_type,
                        'modal_id'   => $galleryModelAssociate->modal_id,
                        'file_name'  => 'YouTube Link',
                    );

                    $tempFileData['type'] = 2;
                    $tempFileData['file_thumbnail'] = null;
                    $tempFileData['file_url'] = $link;

                    $galleryFileData[] = $tempFileData;
                }
            }
            if (!empty($request->images) || $request->youtube_links) {
                $this->files->createBulk($galleryFileData);
            }

            DB::commit();
            ResponseService::successResponse('Data Updated Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            ResponseService::logErrorResponse($th, "Gallery Controller -> Update Method");
            ResponseService::errorResponse();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        ResponseService::noFeatureThenRedirect('School Gallery Management');
        ResponseService::noPermissionThenSendJson('gallery-delete');
        try {
            DB::beginTransaction();

            // Find the Data by FindByID
            $gallery = $this->gallery->findById($id);
            if (Storage::disk('public')->exists($gallery->getRawOriginal('thumbnail'))) {
                Storage::disk('public')->delete($gallery->getRawOriginal('thumbnail'));
            }

            foreach ($gallery->file as $key => $file) {
                if (Storage::disk('public')->exists($file->getRawOriginal('file_url'))) {
                    Storage::disk('public')->delete($file->getRawOriginal('file_url'));
                }
            }

            $gallery->file()->delete();
            $gallery->delete();

            DB::commit();
            ResponseService::successResponse('Data Deleted Successfully');
        } catch (Throwable $e) {
            DB::rollBack();
            ResponseService::logErrorResponse($e, "Gallery Controller -> destroy Method");
            ResponseService::errorResponse();
        }
    }

    public function deleteFile($id) {
        ResponseService::noFeatureThenRedirect('School Gallery Management');
        ResponseService::noPermissionThenSendJson('gallery-delete');
        try {
            DB::beginTransaction();

            // Find the Data by FindByID
            $file = $this->files->findById($id);
            dd(  $file );
            if (Storage::disk('public')->exists($file->getRawOriginal('file_url'))) {
                Storage::disk('public')->delete($file->getRawOriginal('file_url'));
            }

            // Delete the file data
            $file->delete();

            DB::commit();
            ResponseService::successResponse('Data Deleted Successfully');
        } catch (Throwable $e) {
            DB::rollBack();
            ResponseService::logErrorResponse($e, "Gallery Controller -> deleteFile Method");
            ResponseService::errorResponse();
        }
    }
}
