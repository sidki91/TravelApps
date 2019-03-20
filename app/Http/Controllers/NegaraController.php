<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Negara;
use Validator;
use Response;
use View;
use Auth;

class NegaraController extends Controller
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
        if(access_level_user('view','negara')=='allow')
        {
            activity_log(get_module_id('negara'), 'View', '', '');
            return view('master/negara/index');
        }
        else
        {
            activity_log(get_module_id('negara'), 'View', '', 'Error 403 : Forbidden');
            abort(403);
        }

    }

    public function list_data(Request $request)
    {
            $page = $request->page;
            $per_page = 10;
          	if ($page != 1) $start = ($page-1) * $per_page;
          	else $start=0;

            $totalData = Negara::with('user')
                          ->when($request->description, function($query) use ($request)
                          {
                                    return $query->where('nama_negara','like','%'.$request->description.'%');
                          })
                          ->whereNull('deleted_at')
                          ->count();

            $data = Negara::with('user')
                            ->when($request->description, function($query) use ($request)
                            {
                                return $query->where('nama_negara','like','%'.$request->description.'%');
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
            $returnHTML   = view('master.negara.negara_list',['data' => $data,'array' => $array])->render();
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
            $count = Negara::where('nama_negara',$request->description)->count();
            if($count==0)
            {
                $kode_negara = autonumber('negara','kode_negara','N');
                $insert = Negara::create([
                                              'kode_negara'     => $kode_negara,
                                              'nama_negara'     => $request->description,
                                              'created_by'      => Auth::user()->id
                                            ]);
                 if($insert)
                 {
                    $json['status']  = 'success';
                    $json['msg']     = 'Data telah berhasil disimpan !';
                    activity_log(get_module_id('negara'), 'Create', $request->description, 'Berhasil menyimpan data !');
                }
                else
                {
                    $json['status'] = 'failed';
                    $json['msg']    = 'Data gagal disimpan ,terjadi kesalahan pada database !';
                    activity_log(get_module_id('negara'), 'Create', $request->description, 'Data gagal disimpan, terjadi kesalahan pada database ! ');
                }
            }
            else
            {
                $json['status'] = 'failed';
                $json['msg']    = 'Data gagal disimpan , data '.$request->description.' telah tersedia pada database !';
                activity_log(get_module_id('negara'), 'Create', $request->description, 'Data gagal disimpan, data telah tersedia pada database ! ');

            }
        }

        return response()->json($json);
    }

    function edit(Request $request)
    {
        $count = Negara::where('kode_negara',$request->id)->count();
        if($count==1)
        {

            $row        = Negara::where('kode_negara',$request->id)->first();
            $returnHTML = view('master.negara.edit',compact('row'))->render();
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
            $count = Negara::where('kode_negara',$request->id)->count();
            if($count==1)
            {
                $update = Negara::where('kode_negara',$request->id)
                          ->update([
                                      'nama_negara'  => $request->description,
                                      'updated_by' => Auth::user()->id
                                  ]);
                if($update)
                {
                    $json['status'] = 'success';
                    $json['msg']    = 'Data telah berhasil diubah !';
                    activity_log(get_module_id('negara'), 'Alter', $request->description, 'Berhasil merubah data !');

                }
                else
                {
                    $json['status'] = 'failed';
                    $json['msg']    = 'Data gagal diubah, terjadi kesalahan pada database !';
                    activity_log(get_module_id('negara'), 'Alter', $request->description, 'Data gagal diubah, terjadi kesalahan pada database ! ');
                }
            }
            else
            {
                $json['status'] = 'failed';
                $json['msg']    = 'Data gagal diubah, data tidak ditemukan !';
                activity_log(get_module_id('negara'), 'Alter', $request->description, 'Data gagal diubah, data tidak ditemukan !');

            }
        }
        return response()->json($json);
    }

    public function delete(Request $request)
    {
        $count =  Negara::where('kode_negara',$request->id)->count();
        if($count==1)
        {
            $delete = Negara::where('kode_negara',$request->id)->delete();
            if($delete)
            {
              $json['status'] = 'success';
              $json['msg']    = 'Data telah berhasil dihapus !';
              activity_log(get_module_id('negara'), 'Drop', $request->id, 'Data telah berhasil dihapus !');

            }
            else
            {
                $json['status'] = 'failed';
                $json['msg']    = 'Data gagal dihapus,terjadi kesalahan pada database !';
                activity_log(get_module_id('negara'), 'Drop', $request->id, 'Data gagal dihapus,terjadi kesalahan pada database !');
            }
        }
        else
        {
            $json['status'] = 'failed';
            $json['msg']    = 'Data gagal dihapus, data tidak ditemukan !';
            activity_log(get_module_id('negara'), 'Drop', $request->id, 'Data gagal dihapus, data tidak ditemukan !');
        }

        return response()->json($json);
    }


}
