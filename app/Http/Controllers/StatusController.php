<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Status;
use Validator;
use Response;
use View;
use Auth;

class StatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected $rules =
    [
        'description' => 'required|max:100',

    ];

    public function index()
    {
        if(access_level_user('view','status')=='allow')
        {
            activity_log(get_module_id('status'), 'View', '', '');
            return view('master/status/index');
        }
        else
        {
            activity_log(get_module_id('status'), 'View', '', 'Error 403 : Forbidden');
            abort(403);
        }
    }

    public function list_data(Request $request)
    {
            $page = $request->page;
            $per_page = 10;
          	if ($page != 1) $start = ($page-1) * $per_page;
          	else $start=0;

            $totalData = Status::with('user')
                          ->when($request->description, function($query) use ($request)
                          {
                                    return $query->where('deskripsi','like','%'.$request->description.'%');
                          })
                          ->whereNull('deleted_at')
                          ->count();

                          $data = Status::with('user')
                                          ->when($request->description, function($query) use ($request)
                                          {
                                              return $query->where('deskripsi','like','%'.$request->description.'%');
                                          })
                                           ->whereNull('deleted_at')
                                           ->offset($start)
                                           ->limit($per_page)
                                           ->orderBy('created_at','ASC')
                                           ->get();

            $numPage = ceil($totalData / $per_page);
            $page       = $page;
            $perpage    = $per_page;
            $count      = $totalData;

            $array =
            [
              'page'    => $page,
              'perpage' => $perpage,
              'count'   => $count
            ];
            $returnHTML   = view('master.status.status_list',['data' => $data,'array' => $array])->render();
            return response()->json(array('success' => true, 'html'=>$returnHTML,'numPage'=>$numPage,'numitem' => $count) );
    }

    public function store(Request $request)
    {
        $validator = Validator::make(Input::all(),$this->rules);
        if($validator->fails())
        {
            $json['status'] = 'error';
            $json['errors'] = $validator->getMessageBag()->toArray();
            return response()->json($json);
        }
        else
        {
            $count = Status::where('deskripsi',$request->description)->count();
            if($count==0)
            {
                $kode_status = autonumber('status','kode_status','S');
                $insert      = Status::create([
                                                'kode_status' => $kode_status,
                                                'deskripsi'   => $request->description,
                                                'created_by'  => auth::user()->id
                                              ]);
                if($insert)
                {
                    $json['status'] = 'success';
                    $json['msg']    = 'Data telah berhasil disimpan';
                    activity_log(get_module_id('status'), 'Create', $request->description, 'Berhasil menyimpan data !');
                }
                else
                {
                    $json['status'] = 'failed';
                    $json['msg']    = 'Data gagal disimpan, terjadi kesalahan pada database !';
                    activity_log(get_module_id('status'), 'Create', $request->description, 'Data gagal disimpan, terjadi kesalahan pada database ! ');

                }
            }
            else
            {
                $json['status'] = 'failed';
                $json['msg']    = 'Data gagal disimpan , data '.$request->description.' telah tersedia pada database !';
                activity_log(get_module_id('status'), 'Create', $request->description, 'Data gagal disimpan, data telah tersedia pada database ! ');

            }

        }

        return response()->json($json);
    }

    public function edit(Request $request)
    {
        $count = Status::where('kode_status',$request->id)->count();
        if($count==1)
        {
            $row        = Status::where('kode_status',$request->id)->first();
            $returnHTML = view('master.status.edit',compact('row'))->render();
            $json['status'] = 'success';
            $json['html']   = $returnHTML;
        }
        else
        {
            $json['status'] = 'failed';
            $json['msg']    = 'Data gagal diubah, data tidak ditemukan !';
        }

        return response()->json($json);
    }

    public function update(Request $request)
    {
        $validator = Validator::make(Input::all(),$this->rules);
        if($validator->fails())
        {
            $json['status'] = 'error';
            $json['errors'] = $validator->getMessageBag()->toArray();
            return response()->json($json);
        }
        else
        {
            $count = Status::where('kode_status',$request->id)->count();
            if($count==1)
            {
                $update = Status::where('kode_status',$request->id)
                          ->update([
                            'deskripsi' => $request->description,
                            'updated_by'=> auth::user()->id
                          ]);
                if($update)
                {
                    $json['status'] = 'success';
                    $json['msg']    = 'Data telah berhasil diubah !';
                    activity_log(get_module_id('status'), 'Alter', $request->description, 'Berhasil merubah data !');
                }
                else
                {
                    $json['status'] = 'failed';
                    $json['msg']    = 'Data gagal diubah, terjadi kesalahan pada database !';
                    activity_log(get_module_id('status'), 'Alter', $request->description, 'Data gagal diubah, terjadi kesalahan pada database ! ');

                }
            }
            else
            {
                $json['status'] = 'failed';
                $json['msg']    = 'Data gagal diubah, data tidak ditemukan !';
                activity_log(get_module_id('status'), 'Alter', $request->description, 'Data gagal diubah, data tidak ditemukan !');

            }
        }

        return response()->json($json);
    }

    public function delete(Request $request)
    {
        $count = Status::where('kode_status',$request->id)->count();
        if($count==1)
        {
            $delete = Status::where('kode_status',$request->id)->delete();
            if($delete)
            {
                $json['status'] = 'success';
                $json['msg']    = 'Data telah berhasil dihapus !';
                activity_log(get_module_id('status'), 'Drop', $request->id, 'Data telah berhasil dihapus !');

            }
            else
            {
                $json['status'] = 'failed';
                $json['msg']    = 'Data gagal dihapus,terjadi kesalahan pada database !';
                activity_log(get_module_id('status'), 'Drop', $request->id, 'Data gagal dihapus, terjadi kesalahan pada database !');
            }
        }
        else
        {
            $json['status'] = 'failed';
            $json['msg']    = 'Data gagal dihapus, data tidak ditemukan !';
            activity_log(get_module_id('status'), 'Drop', $request->id, 'Data gagal dihapus, data tidak ditemukan !');
        }

        return response()->json($json);
    }
}
