<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Hotel;
use App\Models\Negara;
use App\Models\Kota;
use Validator;
use Response;
use View;
use Auth;

class HotelController extends Controller
{
      public function __construct()
      {
          $this->middleware('auth');
      }

      protected $rules =
      [
          'kode_negara' => 'required|max:100',
          'kode_kota'   => 'required|max:100',
          'nama_hotel'  => 'required|max:100',
          'alamat'      => 'required|max:100',

      ];

      public function index()
      {
          if(access_level_user('view','hotel')=='allow')
          {
              activity_log(get_module_id('hotel'), 'View', '', '');
              return view('master/hotel/index');
          }
          else
          {
              activity_log(get_module_id('hotel'), 'View', '', 'Error 403 : Forbidden');
              abort(403);
          }

      }

      public function list_data(Request $request)
      {
              $page = $request->page;
              $per_page = 10;
            	if ($page != 1) $start = ($page-1) * $per_page;
            	else $start=0;

              $totalData = Hotel::with('user','kota')
                            ->when($request->description, function($query) use ($request)
                            {
                                      return $query->where('nama_hotel','like','%'.$request->description.'%');
                            })
                            ->whereNull('deleted_at')
                            ->count();

              $data = Hotel::with('user','kota')
                              ->when($request->description, function($query) use ($request)
                              {
                                  return $query->where('nama_hotel','like','%'.$request->description.'%');
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
              $returnHTML   = view('master.hotel.hotel_list',['data' => $data,'array' => $array])->render();
              return response()->json(array('success' => true, 'html'=>$returnHTML,'numPage'=>$numPage,'numitem' => $count) );
      }

      public function add()
      {
          $negara       = Negara::all();
          $returnHTML = view('master.hotel.add',compact('negara'))->render();
          $json['status'] = 'success';
          $json['html']   = $returnHTML;
          return response()->json($json);
      }

      public function pilih_kota(Request $request)
      {
            $count = Kota::where('kode_negara',$request->kode_negara)->count();
            if($count>=1)
            {
                $kota       = Kota::where('kode_negara',$request->kode_negara)->get();
                $kode_kota  = Hotel::where('kode_hotel',$request->kode_hotel)->value('kode_kota');
                $returnHTML = view('master.hotel.select_kota',compact('kota','count','kode_kota'))->render();
            }
            else
            {
              $returnHTML = view('master.hotel.select_kota',compact('count'))->render();
            }

            return response()->json(['html' =>$returnHTML]);
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
               $count = Hotel::where('nama_hotel',$request->nama_hotel)->count();
               if($count==0)
               {
                   $kode_hotel = autonumber('hotel','kode_hotel','HT');
                   $insert = Hotel::create([
                                               'kode_hotel'    => $kode_hotel,
                                               'kode_negara'   => $request->kode_negara,
                                               'kode_kota'     => $request->kode_kota,
                                               'nama_hotel'    => $request->nama_hotel,
                                               'alamat'        => $request->alamat,
                                               'telepon'       => $request->telepon,
                                               'created_by'    => auth::user()->id
                                             ]);
                   if($insert)
                   {
                      $json['status']  = 'success';
                      $json['msg']     = 'Data telah berhasil disimpan !';
                      activity_log(get_module_id('hotel'), 'Create', $request->nama_hotel, 'Berhasil menyimpan data !');
                  }
                  else
                  {
                      $json['status'] = 'failed';
                      $json['msg']    = 'Data gagal disimpan ,terjadi kesalahan pada database !';
                      activity_log(get_module_id('hotel'), 'Create', $request->nama_hotel, 'Data gagal disimpan, terjadi kesalahan pada database ! ');
                  }
               }
               else
               {
                   $json['status'] = 'failed';
                   $json['msg']    = 'Data gagal disimpan , data '.$request->nama_hotel.' telah tersedia pada database !';
                   activity_log(get_module_id('hotel'), 'Create', $request->nama_hotel, 'Data gagal disimpan, data telah tersedia pada database ! ');

               }
           }

             return response()->json($json);
      }

      function edit(Request $request)
      {
          $count = Hotel::where('kode_hotel',$request->id)->count();
          if($count==1)
          {

              $row        = Hotel::where('kode_hotel',$request->id)->first();
              $negara     = Negara::all();
              $returnHTML = view('master.hotel.edit',compact('row','negara'))->render();
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
              $count = Hotel::where('kode_hotel',$request->id)->count();
              if($count==1)
              {
                  $update = Hotel::where('kode_hotel',$request->id)
                            ->update([
                                        'kode_negara'    => $request->kode_negara,
                                        'kode_kota'      => $request->kode_kota,
                                        'nama_hotel'     => $request->nama_hotel,
                                        'alamat'         => $request->alamat,
                                        'telepon'        => $request->telepon,
                                        'updated_by'     => Auth::user()->id
                                    ]);
                  if($update)
                  {
                      $json['status'] = 'success';
                      $json['msg']    = 'Data telah berhasil diubah !';
                      activity_log(get_module_id('hotel'), 'Alter', $request->nama_hotel, 'Berhasil merubah data !');

                  }
                  else
                  {
                      $json['status'] = 'failed';
                      $json['msg']    = 'Data gagal diubah, terjadi kesalahan pada database !';
                      activity_log(get_module_id('hotel'), 'Alter', $request->nama_hotel, 'Data gagal diubah, terjadi kesalahan pada database ! ');
                  }
              }
              else
              {
                  $json['status'] = 'failed';
                  $json['msg']    = 'Data gagal diubah, data tidak ditemukan !';
                  activity_log(get_module_id('hotel'), 'Alter', $request->nama_hotel, 'Data gagal diubah, data tidak ditemukan !');

              }
          }
          return response()->json($json);
      }

      public function delete(Request $request)
      {
          $count =  Hotel::where('kode_hotel',$request->id)->count();
          if($count==1)
          {
              $delete = Hotel::where('kode_hotel',$request->id)->delete();
              if($delete)
              {
                $json['status'] = 'success';
                $json['msg']    = 'Data telah berhasil dihapus !';
                activity_log(get_module_id('hotel'), 'Drop', $request->id, 'Data telah berhasil dihapus !');

              }
              else
              {
                  $json['status'] = 'failed';
                  $json['msg']    = 'Data gagal dihapus,terjadi kesalahan pada database !';
                  activity_log(get_module_id('hotel'), 'Drop', $request->id, 'Data gagal dihapus,terjadi kesalahan pada database !');
              }
          }
          else
          {
              $json['status'] = 'failed';
              $json['msg']    = 'Data gagal dihapus, data tidak ditemukan !';
              activity_log(get_module_id('hotel'), 'Drop', $request->id, 'Data gagal dihapus, data tidak ditemukan !');
          }

          return response()->json($json);
      }



}
