<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Hotel;
use App\Models\Service;
use App\Models\Kapasitas;
use App\Models\Negara;
use App\Models\Kota;
use App\Models\Room;
use Validator;
use Response;
use View;
use Auth;


class RoomController extends Controller
{
      public function __construct()
      {
          $this->middleware('auth');
      }

      protected $rules =
      [
          'tipe_room'      => 'required|max:100',
          'kode_hotel'     => 'required|max:100',
          'kode_service'   => 'required|max:100',
          'kode_kapasitas' => 'required|max:100',

      ];

      public function index()
      {
          if(access_level_user('view','room')=='allow')
          {
              activity_log(get_module_id('service'), 'View', '', '');
              return view('master/room/index');
          }
          else
          {
              activity_log(get_module_id('room'), 'View', '', 'Error 403 : Forbidden');
              abort(403);
          }

      }

      public function list_data(Request $request)
      {
              $page = $request->page;
              $per_page = 10;
            	if ($page != 1) $start = ($page-1) * $per_page;
            	else $start=0;

              $totalData = Room::with('user','hotel','service','kapasitas')
                            ->when($request->description, function($query) use ($request)
                            {
                                      return $query->where('tipe_room','like','%'.$request->description.'%');
                            })
                            ->whereNull('deleted_at')
                            ->count();

              $data = Room::with('user','hotel','service','kapasitas')
                              ->when($request->description, function($query) use ($request)
                              {
                                  return $query->where('tipe_room','like','%'.$request->description.'%');
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
              $returnHTML   = view('master.room.room_list',['data' => $data,'array' => $array])->render();
              return response()->json(array('success' => true, 'html'=>$returnHTML,'numPage'=>$numPage,'numitem' => $count) );
      }

      public function add()
      {
          $negara      = Negara::all();
          $service     = Service::all();
          $kapasitas   = Kapasitas::all();
          $returnHTML  = view('master.room.add',compact('negara','service','kapasitas'))->render();
          $json['status'] = 'success';
          $json['html']   = $returnHTML;
          return response()->json($json);
      }

      public function change_hotel(Request $request)
      {

          $hotel      = Hotel::where('kode_negara',$request->kode_negara)->get();
          $kode_hotel = $request->kode_hotel;
          $returnHTML = view('master.room.select_hotel',compact('hotel','kode_hotel'))->render();
          return response()->json(['html' => $returnHTML]);
      }

      Public function change_info(Request $request)
      {
          $row = Hotel::with('kota')
                       ->where('kode_hotel',$request->kode_hotel)->first();
          $json['kota'] = $row->kota->nama_kota;
          return response()->json($json);
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
               $count = Room::where('tipe_room',$request->tipe_room)
                              ->where('kode_hotel',$request->kode_hotel)
                              ->count();
               if($count==0)
               {
                   $kode_room = autonumber('room','kode_room','RM');
                   $insert = Room::create([
                                               'kode_room'      => $kode_room,
                                               'tipe_room'      => $request->tipe_room,
                                               'kode_hotel'     => $request->kode_hotel,
                                               'kode_service'   => $request->kode_service,
                                               'kode_kapasitas' => $request->kode_kapasitas,
                                               'created_by'     => auth::user()->id
                                             ]);
                   if($insert)
                   {
                      $json['status']  = 'success';
                      $json['msg']     = 'Data telah berhasil disimpan !';
                      activity_log(get_module_id('room'), 'Create', $request->tipe_room, 'Berhasil menyimpan data !');
                  }
                  else
                  {
                      $json['status'] = 'failed';
                      $json['msg']    = 'Data gagal disimpan ,terjadi kesalahan pada database !';
                      activity_log(get_module_id('room'), 'Create', $request->tipe_room, 'Data gagal disimpan, terjadi kesalahan pada database ! ');
                  }
               }
               else
               {
                   $json['status'] = 'failed';
                   $json['msg']    = 'Data gagal disimpan , data '.$request->tipe_room.' telah tersedia pada database !';
                   activity_log(get_module_id('room'), 'Create', $request->tipe_room, 'Data gagal disimpan, data telah tersedia pada database ! ');

               }
           }

             return response()->json($json);
      }

      function edit(Request $request)
      {
          $count = Room::where('kode_room',$request->id)->count();
          if($count==1)
          {

              $row         = Room::where('kode_room',$request->id)->first();
              $negara      = Negara::all();
              $service     = Service::all();
              $kapasitas   = Kapasitas::all();
              $kode_negara = Room::with('hotel')->first()->hotel->kode_negara;
              $returnHTML = view('master.room.edit',compact('row','negara','service','kapasitas','kode_negara'))->render();
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
              $count = Room::where('kode_room',$request->id)->count();
              if($count==1)
              {
                  $update = Room::where('kode_room',$request->id)
                            ->update([
                                        'tipe_room'      => $request->tipe_room,
                                        'kode_hotel'     => $request->kode_hotel,
                                        'kode_service'   => $request->kode_service,
                                        'kode_kapasitas' => $request->kode_kapasitas,
                                        'updated_by'     => Auth::user()->id
                                    ]);
                  if($update)
                  {
                      $json['status'] = 'success';
                      $json['msg']    = 'Data telah berhasil diubah !';
                      activity_log(get_module_id('room'), 'Alter', $request->tipe_room, 'Berhasil merubah data !');

                  }
                  else
                  {
                      $json['status'] = 'failed';
                      $json['msg']    = 'Data gagal diubah, terjadi kesalahan pada database !';
                      activity_log(get_module_id('room'), 'Alter', $request->tipe_room, 'Data gagal diubah, terjadi kesalahan pada database ! ');
                  }
              }
              else
              {
                  $json['status'] = 'failed';
                  $json['msg']    = 'Data gagal diubah, data tidak ditemukan !';
                  activity_log(get_module_id('room'), 'Alter', $request->tipe_room, 'Data gagal diubah, data tidak ditemukan !');

              }
          }
          return response()->json($json);
      }

      public function delete(Request $request)
      {
          $count =  Room::where('kode_room',$request->id)->count();
          if($count==1)
          {
              $delete = Room::where('kode_room',$request->id)->delete();
              if($delete)
              {
                $json['status'] = 'success';
                $json['msg']    = 'Data telah berhasil dihapus !';
                activity_log(get_module_id('room'), 'Drop', $request->id, 'Data telah berhasil dihapus !');

              }
              else
              {
                  $json['status'] = 'failed';
                  $json['msg']    = 'Data gagal dihapus,terjadi kesalahan pada database !';
                  activity_log(get_module_id('room'), 'Drop', $request->id, 'Data gagal dihapus,terjadi kesalahan pada database !');
              }
          }
          else
          {
              $json['status'] = 'failed';
              $json['msg']    = 'Data gagal dihapus, data tidak ditemukan !';
              activity_log(get_module_id('room'), 'Drop', $request->id, 'Data gagal dihapus, data tidak ditemukan !');
          }

          return response()->json($json);
      }


}
