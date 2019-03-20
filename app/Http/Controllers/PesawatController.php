<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Airlines;
use App\Models\Pesawat;
use Validator;
use Response;
use View;
use Auth;

class PesawatController extends Controller
{
      public function __construct()
      {
          $this->middleware('auth');
      }

      protected $rules =
      [   'kode_airlines' => 'required|max:100',
          'nama_pesawat'  => 'required|max:100',

      ];

      public function index()
      {
          if(access_level_user('view','pesawat')=='allow')
          {
              activity_log(get_module_id('pesawat'), 'View', '', '');
              return view('master/pesawat/index');
          }
          else
          {
              activity_log(get_module_id('pesawat'), 'View', '', 'Error 403 : Forbidden');
              abort(403);
          }

      }

      public function list_data(Request $request)
      {
              $page = $request->page;
              $per_page = 10;
            	if ($page != 1) $start = ($page-1) * $per_page;
            	else $start=0;

              $totalData = Pesawat::with('user','airlines')
                            ->when($request->description, function($query) use ($request)
                            {
                                      return $query->where('nama_pesawat','like','%'.$request->description.'%');
                            })
                            ->whereNull('deleted_at')
                            ->count();

              $data = Pesawat::with('user','airlines')
                              ->when($request->description, function($query) use ($request)
                              {
                                  return $query->where('nama_pesawat','like','%'.$request->description.'%');
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
              $returnHTML   = view('master.pesawat.pesawat_list',['data' => $data,'array' => $array])->render();
              return response()->json(array('success' => true, 'html'=>$returnHTML,'numPage'=>$numPage,'numitem' => $count) );
      }

      public function add()
      {
          $airlines     = Airlines::all();
          $returnHTML = view('master.pesawat.add',compact('airlines'))->render();
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
              $count = Pesawat::where('nama_pesawat',$request->nama_pesawat)
                                ->where('kode_airlines',$request->kode_airlines)
                                ->count();
              if($count==0)
              {
                  $kode_pesawat = autonumber('pesawat','kode_pesawat','PW');
                  $insert = Pesawat::create([
                                                'kode_pesawat'    => $kode_pesawat,
                                                'kode_airlines'   => $request->kode_airlines,
                                                'nama_pesawat'    => $request->nama_pesawat,
                                                'created_by'      => Auth::user()->id
                                              ]);
                   if($insert)
                   {
                      $json['status']  = 'success';
                      $json['msg']     = 'Data telah berhasil disimpan !';
                      activity_log(get_module_id('pesawat'), 'Create', $request->nama_pesawat, 'Berhasil menyimpan data !');
                  }
                  else
                  {
                      $json['status'] = 'failed';
                      $json['msg']    = 'Data gagal disimpan ,terjadi kesalahan pada database !';
                      activity_log(get_module_id('pesawat'), 'Create', $request->nama_pesawat, 'Data gagal disimpan, terjadi kesalahan pada database ! ');
                  }
              }
              else
              {
                  $json['status'] = 'failed';
                  $json['msg']    = 'Data gagal disimpan , data '.$request->nama_pesawat.' telah tersedia pada database !';
                  activity_log(get_module_id('pesawat'), 'Create', $request->nama_pesawat, 'Data gagal disimpan, data telah tersedia pada database ! ');

              }
          }

          return response()->json($json);
      }

      function edit(Request $request)
      {
          $count = Pesawat::where('kode_pesawat',$request->id)->count();
          if($count==1)
          {
              $airlines   = Airlines::all();
              $row        = Pesawat::where('kode_pesawat',$request->id)->first();
              $returnHTML = view('master.pesawat.edit',compact('row','airlines'))->render();
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
              $count = Pesawat::where('kode_pesawat',$request->id)->count();
              if($count==1)
              {
                  $update = Pesawat::where('kode_pesawat',$request->id)
                            ->update([
                                        'kode_airlines'  => $request->kode_airlines,
                                        'nama_pesawat'   => $request->nama_pesawat,
                                        'updated_by'     => Auth::user()->id
                                    ]);
                  if($update)
                  {
                      $json['status'] = 'success';
                      $json['msg']    = 'Data telah berhasil diubah !';
                      activity_log(get_module_id('pesawat'), 'Alter', $request->nama_pesawat, 'Berhasil merubah data !');

                  }
                  else
                  {
                      $json['status'] = 'failed';
                      $json['msg']    = 'Data gagal diubah, terjadi kesalahan pada database !';
                      activity_log(get_module_id('pesawat'), 'Alter', $request->nama_pesawat, 'Data gagal diubah, terjadi kesalahan pada database ! ');
                  }
              }
              else
              {
                  $json['status'] = 'failed';
                  $json['msg']    = 'Data gagal diubah, data tidak ditemukan !';
                  activity_log(get_module_id('pesawat'), 'Alter', $request->nama_pesawat, 'Data gagal diubah, data tidak ditemukan !');

              }
          }
          return response()->json($json);
      }

      public function delete(Request $request)
      {
          $count =  Pesawat::where('kode_pesawat',$request->id)->count();
          if($count==1)
          {
              $delete = Pesawat::where('kode_pesawat',$request->id)->delete();
              if($delete)
              {
                $json['status'] = 'success';
                $json['msg']    = 'Data telah berhasil dihapus !';
                activity_log(get_module_id('pesawat'), 'Drop', $request->id, 'Data telah berhasil dihapus !');

              }
              else
              {
                  $json['status'] = 'failed';
                  $json['msg']    = 'Data gagal dihapus,terjadi kesalahan pada database !';
                  activity_log(get_module_id('pesawat'), 'Drop', $request->id, 'Data gagal dihapus,terjadi kesalahan pada database !');
              }
          }
          else
          {
              $json['status'] = 'failed';
              $json['msg']    = 'Data gagal dihapus, data tidak ditemukan !';
              activity_log(get_module_id('pesawat'), 'Drop', $request->id, 'Data gagal dihapus, data tidak ditemukan !');
          }

          return response()->json($json);
      }


}
