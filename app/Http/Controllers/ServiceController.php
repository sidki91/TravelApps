<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Service;
use Validator;
use Response;
use View;
use Auth;

class ServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected $rules =
    [
        'tipe_service' => 'required|max:100',
        'keterangan'   => 'required|max:100'

    ];

    public function index()
    {
        if(access_level_user('view','service')=='allow')
        {
            activity_log(get_module_id('service'), 'View', '', '');
            return view('master/service/index');
        }
        else
        {
            activity_log(get_module_id('service'), 'View', '', 'Error 403 : Forbidden');
            abort(403);
        }

    }

    public function list_data(Request $request)
    {
            $page = $request->page;
            $per_page = 10;
          	if ($page != 1) $start = ($page-1) * $per_page;
          	else $start=0;

            $totalData = Service::with('user')
                          ->when($request->description, function($query) use ($request)
                          {
                                    return $query->where('tipe_service','like','%'.$request->description.'%');
                          })
                          ->whereNull('deleted_at')
                          ->count();

            $data = Service::with('user')
                            ->when($request->description, function($query) use ($request)
                            {
                                return $query->where('tipe_service','like','%'.$request->description.'%');
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
            $returnHTML   = view('master.service.service_list',['data' => $data,'array' => $array])->render();
            return response()->json(array('success' => true, 'html'=>$returnHTML,'numPage'=>$numPage,'numitem' => $count) );
    }

    public function store(Request $request)
    {
        $validator = Validator::make(Input::all(),$this->rules);
        if($validator->fails())
        {
            $json['status']    = 'error';
            $json['errors']    = $validator->getMessageBag()->toArray();
            return response()->json($json);
        }
        else
        {
            $count = Service::where('tipe_service',$request->tipe_service)->count();
            if($count==0)
            {
                $kode_service = autonumber('service','kode_service','SR');
                $insert = Service::create([
                                              'kode_service'    => $kode_service,
                                              'tipe_service'    => $request->tipe_service,
                                              'keterangan'      => $request->keterangan,
                                              'created_by'      => Auth::user()->id
                                            ]);
                 if($insert)
                 {
                    $json['status']  = 'success';
                    $json['msg']     = 'Data telah berhasil disimpan !';
                    activity_log(get_module_id('service'), 'Create', $request->tipe_service, 'Berhasil menyimpan data !');
                }
                else
                {
                    $json['status'] = 'failed';
                    $json['msg']    = 'Data gagal disimpan ,terjadi kesalahan pada database !';
                    activity_log(get_module_id('service'), 'Create', $request->tipe_service, 'Data gagal disimpan, terjadi kesalahan pada database ! ');
                }
            }
            else
            {
                $json['status'] = 'failed';
                $json['msg']    = 'Data gagal disimpan , data '.$request->tipe_service.' telah tersedia pada database !';
                activity_log(get_module_id('service'), 'Create', $request->tipe_service, 'Data gagal disimpan, data telah tersedia pada database ! ');

            }
        }

        return response()->json($json);
    }

    function edit(Request $request)
    {
        $count = Service::where('kode_service',$request->id)->count();
        if($count==1)
        {

            $row        = Service::where('kode_service',$request->id)->first();
            $returnHTML = view('master.service.edit',compact('row'))->render();
            $json['status']     = 'success';
            $json['html']       = $returnHTML;
        }
        else
        {
            $json['status']     = 'failed';
            $json['msg']        = 'Data gagal diedit, data tidak ditemukan !';
        }

        return response()->json($json);
    }

    function update(Request $request)
    {
        $validator = Validator::make(Input::all(),$this->rules);
        if($validator->fails())
        {
           $json['status']    = 'error';
           $json['errors']    = $validator->getMessageBag()->toArray();
           return response()->json($json);
        }
        else
        {
            $count = Service::where('kode_service',$request->id)->count();
            if($count==1)
            {
                $update = Service::where('kode_service',$request->id)
                          ->update([
                                      'tipe_service'  => $request->tipe_service,
                                      'keterangan'     => $request->keterangan,
                                      'updated_by'    => Auth::user()->id
                                  ]);
                if($update)
                {
                    $json['status'] = 'success';
                    $json['msg']    = 'Data telah berhasil diubah !';
                    activity_log(get_module_id('service'), 'Alter', $request->tipe_service, 'Berhasil merubah data !');

                }
                else
                {
                    $json['status'] = 'failed';
                    $json['msg']    = 'Data gagal diubah, terjadi kesalahan pada database !';
                    activity_log(get_module_id('service'), 'Alter', $request->tipe_service, 'Data gagal diubah, terjadi kesalahan pada database ! ');
                }
            }
            else
            {
                $json['status'] = 'failed';
                $json['msg']    = 'Data gagal diubah, data tidak ditemukan !';
                activity_log(get_module_id('service'), 'Alter', $request->tipe_service, 'Data gagal diubah, data tidak ditemukan !');

            }
        }
        return response()->json($json);
    }

    public function delete(Request $request)
    {
        $count =  Service::where('kode_service',$request->id)->count();
        if($count==1)
        {
            $delete = Service::where('kode_service',$request->id)->delete();
            if($delete)
            {
              $json['status'] = 'success';
              $json['msg']    = 'Data telah berhasil dihapus !';
              activity_log(get_module_id('service'), 'Drop', $request->id, 'Data telah berhasil dihapus !');

            }
            else
            {
                $json['status'] = 'failed';
                $json['msg']    = 'Data gagal dihapus,terjadi kesalahan pada database !';
                activity_log(get_module_id('service'), 'Drop', $request->id, 'Data gagal dihapus,terjadi kesalahan pada database !');
            }
        }
        else
        {
            $json['status'] = 'failed';
            $json['msg']    = 'Data gagal dihapus, data tidak ditemukan !';
            activity_log(get_module_id('service'), 'Drop', $request->id, 'Data gagal dihapus, data tidak ditemukan !');
        }

        return response()->json($json);
    }
}
