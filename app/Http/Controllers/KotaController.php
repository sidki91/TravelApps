<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Kota;
use App\Models\Negara;
use Validator;
use Response;
use View;
use Auth;

class KotaController extends Controller
{
      public function __construct()
      {
          $this->middleware('auth');
      }

      protected $rules =
      [   'kode_negara' => 'required|max:100',
          'nama_kota'   => 'required|max:100',

      ];

      public function index()
      {
          if(access_level_user('view','kota')=='allow')
          {
              activity_log(get_module_id('kota'), 'View', '', '');
              return view('master/kota/index');
          }
          else
          {
              activity_log(get_module_id('kota'), 'View', '', 'Error 403 : Forbidden');
              abort(403);
          }

      }

      public function list_data(Request $request)
      {
              $page = $request->page;
              $per_page = 10;
            	if ($page != 1) $start = ($page-1) * $per_page;
            	else $start=0;

              $totalData = Kota::with('user','negara')
                            ->when($request->description, function($query) use ($request)
                            {
                                      return $query->where('nama_kota','like','%'.$request->description.'%');
                            })
                            ->whereNull('deleted_at')
                            ->count();

              $data = Kota::with('user','negara')
                              ->when($request->description, function($query) use ($request)
                              {
                                  return $query->where('nama_kota','like','%'.$request->description.'%');
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
              $returnHTML   = view('master.kota.kota_list',['data' => $data,'array' => $array])->render();
              return response()->json(array('success' => true, 'html'=>$returnHTML,'numPage'=>$numPage,'numitem' => $count) );
      }

      public function add()
      {
          $negara     = Negara::all();
          $returnHTML = view('master.kota.add',compact('negara'))->render();
          $json['status'] = 'success';
          $json['html']   = $returnHTML;
          return response()->json($json);
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
              $count = Kota::where('nama_kota',$request->nama_kota)->count();
              if($count==0)
              {
                  $kode_kota = autonumber('kota','kode_kota','K');
                  $insert = Kota::create([
                                                'kode_negara'     => $request->kode_negara,
                                                'kode_kota'       => $kode_kota,
                                                'nama_kota'       => $request->nama_kota,
                                                'created_by'      => Auth::user()->id
                                              ]);
                   if($insert)
                   {
                      $json['status']  = 'success';
                      $json['msg']     = 'Data telah berhasil disimpan !';
                      activity_log(get_module_id('kota'), 'Create', $request->nama_kota, 'Berhasil menyimpan data !');
                  }
                  else
                  {
                      $json['status'] = 'failed';
                      $json['msg']    = 'Data gagal disimpan ,terjadi kesalahan pada database !';
                      activity_log(get_module_id('kota'), 'Create', $request->nama_kota, 'Data gagal disimpan, terjadi kesalahan pada database ! ');
                  }
              }
              else
              {
                  $json['status'] = 'failed';
                  $json['msg']    = 'Data gagal disimpan , data '.$request->nama_kota.' telah tersedia pada database !';
                  activity_log(get_module_id('kota'), 'Create', $request->nama_kota, 'Data gagal disimpan, data telah tersedia pada database ! ');

              }
          }

          return response()->json($json);
      }

      function edit(Request $request)
      {
          $count = Kota::where('kode_kota',$request->id)->count();
          if($count==1)
          {
              $negara     = Negara::all();
              $row        = Kota::where('kode_kota',$request->id)->first();
              $returnHTML = view('master.kota.edit',compact('row','negara'))->render();
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
              $count = Kota::where('kode_kota',$request->id)->count();
              if($count==1)
              {
                  $update = Kota::where('kode_kota',$request->id)
                            ->update([
                                        'kode_negara'  => $request->kode_negara,
                                        'nama_kota'    => $request->nama_kota,
                                        'updated_by'   => Auth::user()->id
                                    ]);
                  if($update)
                  {
                      $json['status'] = 'success';
                      $json['msg']    = 'Data telah berhasil diubah !';
                      activity_log(get_module_id('kota'), 'Alter', $request->nama_kota, 'Berhasil merubah data !');

                  }
                  else
                  {
                      $json['status'] = 'failed';
                      $json['msg']    = 'Data gagal diubah, terjadi kesalahan pada database !';
                      activity_log(get_module_id('kota'), 'Alter', $request->nama_kota, 'Data gagal diubah, terjadi kesalahan pada database ! ');
                  }
              }
              else
              {
                  $json['status'] = 'failed';
                  $json['msg']    = 'Data gagal diubah, data tidak ditemukan !';
                  activity_log(get_module_id('kota'), 'Alter', $request->nama_kota, 'Data gagal diubah, data tidak ditemukan !');

              }
          }
          return response()->json($json);
      }

      public function delete(Request $request)
      {
          $count =  Kota::where('kode_kota',$request->id)->count();
          if($count==1)
          {
              $delete = Kota::where('kode_kota',$request->id)->delete();
              if($delete)
              {
                $json['status'] = 'success';
                $json['msg']    = 'Data telah berhasil dihapus !';
                activity_log(get_module_id('kota'), 'Drop', $request->id, 'Data telah berhasil dihapus !');

              }
              else
              {
                  $json['status'] = 'failed';
                  $json['msg']    = 'Data gagal dihapus,terjadi kesalahan pada database !';
                  activity_log(get_module_id('kota'), 'Drop', $request->id, 'Data gagal dihapus,terjadi kesalahan pada database !');
              }
          }
          else
          {
              $json['status'] = 'failed';
              $json['msg']    = 'Data gagal dihapus, data tidak ditemukan !';
              activity_log(get_module_id('kota'), 'Drop', $request->id, 'Data gagal dihapus, data tidak ditemukan !');
          }

          return response()->json($json);
      }
}
