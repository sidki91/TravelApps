<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Kapasitas;
use App\Models\KategoriPerjalanan;
use App\Models\PaketPerjalanan;
use App\Models\Kota;
use App\Models\HargaPaket;
use App\Models\Bulan;
use Validator;
use Response;
use View;
use Auth;

class PaketPerjalananController extends Controller
{
     public function __construct()
     {
          $this->middleware('auth');
     }

     protected $rules =
     [
         'bulan'           => 'required|max:50',
         'kode_kategori'   => 'required|max:100',
         'nama_paket'      => 'required|max:300',
         'kode_kota'       => 'required|max:100',
         'lama_perjalanan' => 'required|numeric|min:1'

    ];

     public function index()
     {
         if(access_level_user('view','paket_perjalanan')=='allow')
         {
             activity_log(get_module_id('paket_perjalanan'), 'View', '', '');
             return view('master/paket_perjalanan/index');
         }
         else
         {
             activity_log(get_module_id('paket_perjalanan'), 'View', '', 'Error 403 : Forbidden');
             abort(403);
         }

     }

     public function list_data(Request $request)
     {
             $page = $request->page;
             $per_page = 10;
             if ($page != 1) $start = ($page-1) * $per_page;
             else $start=0;

             $totalData = PaketPerjalanan::with('user','kategori_paket','kota','get_bulan')
                           ->when($request->description, function($query) use ($request)
                           {
                                     return $query->where('nama_paket','like','%'.$request->description.'%');
                           })
                           ->whereNull('deleted_at')
                           ->count();

             $data = PaketPerjalanan::with('user','kategori_paket','kota','get_bulan')
                             ->when($request->description, function($query) use ($request)
                             {
                                 return $query->where('nama_paket','like','%'.$request->description.'%');
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
             $returnHTML   = view('master.paket_perjalanan.paket_perjalanan',['data' => $data,'array' => $array])->render();
             return response()->json(array('success' => true, 'html'=>$returnHTML,'numPage'=>$numPage,'numitem' => $count) );
     }

     public function add()
     {
         $kategori     = KategoriPerjalanan::all();
         $bulan        = Bulan::all();
         $returnHTML = view('master.paket_perjalanan.add',compact('kategori','bulan'))->render();
         $json['status'] = 'success';
         $json['html']   = $returnHTML;
         return response()->json($json);
     }

     function display_kota()
     {
        $kota         = Kota::with('negara')->get();
        $returnHTML   = view('master.paket_perjalanan.display_kota',compact('kota'))->render();
        $json['html'] = $returnHTML;
        return response()->json($json);
     }

     public function list_data_kota(Request $request)
     {
             $page = $request->page;
             $per_page = 5;
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
             $returnHTML   = view('master.paket_perjalanan.kota_list',['data' => $data,'array' => $array])->render();
             return response()->json(array('success' => true, 'html'=>$returnHTML,'numPage'=>$numPage,'numitem' => $count) );
     }

     public function ambil_data_kota(Request $request)
     {
        $count = Kota::where('kode_kota',$request->id)->count();
        if($count==1)
        {
            $row = Kota::where('kode_kota',$request->id)->first();
            $json['status'] = 'success';
            $json['tujuan_kota']   = $row->nama_kota;
            $json['kode_kota']     = $row->kode_kota;
        }
        else
        {
            $json['status'] = 'failed';
            $json['msg']    = 'Data tidak ditemukan !';
        }

        return response()->json($json);
     }

     function form_harga(Request $request)
     {
        $kapasitas    = Kapasitas::all();
        $token_id     = $request->token_id;
        $returnHTML   = view('master.paket_perjalanan.form_harga',compact('kapasitas','token_id'))->render();
        $json['html'] = $returnHTML;
        return response()->json($json);
     }

     public function save_harga_paket(Request $request)
     {
        $count = db::table('harga_paket_temp')
                     ->where('kode_kapasitas',$request->kode_kapasitas)
                     ->where('token',$request->token)
                     ->count();
        if($count==0)
        {
            $insert = db::table('harga_paket_temp')
                          ->insert([
                              'token'           => $request->token,
                              'kode_kapasitas'  => $request->kode_kapasitas,
                              'harga'           => $request->harga,
                              'created_by'      => Auth::user()->id
                          ]);
        }
        else
        {
            $insert =DB::table('harga_paket_temp')
                        ->where('token', $request->token)
                        ->where('kode_kapasitas',$request->kode_kapasitas)
                        ->update(['harga' => $request->harga]);
        }

        if($insert)
        {
            $json['status'] = 'success';
            $json['msg']    = 'Data telah berhasil disimpan !';
        }
        else
        {
            $json['status'] = 'failed';
            $json['msg']    = 'Data gagal disimpan, terjadi kesalahan pada database !';
        }

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
             $count = PaketPerjalanan::where('kode_kategori',$request->kode_kategori)
                                      ->where('nama_paket',$request->nama_paket)
                                      ->count();
             if($count==0)
             {
                 $kode_paket = autonumber('paket_perjalanan','kode_paket','PRJ');
                 $insert = PaketPerjalanan::create([

                                               'kode_paket'      => $kode_paket,
                                               'bulan'           => $request->bulan,
                                               'kode_kategori'   => $request->kode_kategori,
                                               'nama_paket'      => $request->nama_paket,
                                               'tujuan_kota'     => $request->kode_kota,
                                               'lama_perjalanan' => $request->lama_perjalanan,
                                               'kegiatan'        => $request->kegiatan,
                                               'keterangan'      => $request->keterangan,
                                               'created_by'      => Auth::user()->id
                                             ]);
                  if($insert)
                  {
                     $harga_temp      = db::table('harga_paket_temp')
                                            ->where('token',$request->token_id)
                                            ->get();
                     foreach($harga_temp as $row)
                     {
                            $kode_harga = autonumber('harga_paket','kode_harga','HR');
                            $insert_harga = HargaPaket::create([
                                                                  'kode_harga'      => $kode_harga,
                                                                  'kode_paket'      => $kode_paket,
                                                                  'kode_kapasitas'  => $row->kode_kapasitas,
                                                                  'harga'           => $row->harga,
                                                                  'created_by'      => $row->created_by
                                                              ]);
                     };
                           $delete_harga_temp = db::table('harga_paket_temp')
                                                   ->where('token',$request->token_id)
                                                   ->delete();


                     $json['status']  = 'success';
                     $json['msg']     = 'Data telah berhasil disimpan !';
                     activity_log(get_module_id('paket_perjalanan'), 'Create', $request->nama_paket, 'Berhasil menyimpan data !');
                 }
                 else
                 {
                     $json['status'] = 'failed';
                     $json['msg']    = 'Data gagal disimpan ,terjadi kesalahan pada database !';
                     activity_log(get_module_id('paket_perjalanan'), 'Create', $request->nama_paket, 'Data gagal disimpan, terjadi kesalahan pada database ! ');
                 }
             }
             else
             {
                 $json['status'] = 'failed';
                 $json['msg']    = 'Data gagal disimpan , data '.$request->nama_paket.' telah tersedia pada database !';
                 activity_log(get_module_id('paket_perjalanan'), 'Create', $request->nama_paket, 'Data gagal disimpan, data telah tersedia pada database ! ');

             }
         }

         return response()->json($json);
     }

     function edit(Request $request)
     {
         $count = PaketPerjalanan::where('kode_paket',$request->id)->count();
         if($count==1)
         {

             $row        = PaketPerjalanan::with('kota')
                                            ->where('kode_paket',$request->id)->first();
             $kategori   = KategoriPerjalanan::all();
             $bulan      = Bulan::all();
             $returnHTML = view('master.paket_perjalanan.edit',compact('row','kategori','bulan'))->render();
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

     function check_harga(Request $request)
     {
        $kapasitas    = Kapasitas::all();
        $kode_paket   = $request->kode_paket;
        $returnHTML   = view('master.paket_perjalanan.check_harga',compact('kapasitas','kode_paket'))->render();
        $json['html'] = $returnHTML;
        return response()->json($json);
     }

     function update_harga_paket(Request $request)
     {
        $count = HargaPaket::where('kode_paket',$request->kode_paket)
                             ->where('kode_kapasitas',$request->kode_kapasitas)
                             ->count();
        if($count==0)
        {
            $insert = HargaPaket::create([
                                        'kode_paket'     => $request->kode_paket,
                                        'kode_kapasitas' => $request->kode_kapasitas,
                                        'harga'          => $request->harga,
                                        'created_by'     => Auth::user()->id
                                      ]);
        }
        else
        {
            $insert = HargaPaket::where('kode_paket',$request->kode_paket)
                                  ->where('kode_kapasitas',$request->kode_kapasitas)
                                  ->update([
                                    'harga'      => $request->harga,
                                    'updated_by' => Auth::user()->id
                                  ]);
        }

        if($insert)
        {
            $json['status'] = 'success';
            $json['msg']    = 'Data telah berhasil disimpan !';
        }
        else
        {
            $json['status'] = 'failed';
            $json['msg']    = 'Data gagal disimpan, terjadi kesalahan pada database !';
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
             $count = PaketPerjalanan::where('kode_paket',$request->id)->count();
             if($count==1)
             {
                 $update = PaketPerjalanan::where('kode_paket',$request->id)
                           ->update([
                                       'bulan'           => $request->bulan,
                                       'kode_kategori'   => $request->kode_kategori,
                                       'nama_paket'      => $request->nama_paket,
                                       'tujuan_kota'     => $request->kode_kota,
                                       'lama_perjalanan' => $request->lama_perjalanan,
                                       'kegiatan'        => $request->kegiatan,
                                       'keterangan'      => $request->keterangan,
                                       'updated_by'      => Auth::user()->id
                                   ]);
                 if($update)
                 {
                     $json['status'] = 'success';
                     $json['msg']    = 'Data telah berhasil diubah !';
                     activity_log(get_module_id('paket_perjalanan'), 'Alter', $request->nama_paket, 'Berhasil merubah data !');

                 }
                 else
                 {
                     $json['status'] = 'failed';
                     $json['msg']    = 'Data gagal diubah, terjadi kesalahan pada database !';
                     activity_log(get_module_id('paket_perjalanan'), 'Alter', $request->description, 'Data gagal diubah, terjadi kesalahan pada database ! ');
                 }
             }
             else
             {
                 $json['status'] = 'failed';
                 $json['msg']    = 'Data gagal diubah, data tidak ditemukan !';
                 activity_log(get_module_id('paket_perjalanan'), 'Alter', $request->description, 'Data gagal diubah, data tidak ditemukan !');

             }
         }
         return response()->json($json);
     }

     public function delete(Request $request)
     {
         $count =  PaketPerjalanan::where('kode_paket',$request->id)->count();
         if($count==1)
         {
             $delete = PaketPerjalanan::where('kode_paket',$request->id)->delete();
             if($delete)
             {
               $json['status'] = 'success';
               $json['msg']    = 'Data telah berhasil dihapus !';
               activity_log(get_module_id('paket_perjalanan'), 'Drop', $request->id, 'Data telah berhasil dihapus !');

             }
             else
             {
                 $json['status'] = 'failed';
                 $json['msg']    = 'Data gagal dihapus,terjadi kesalahan pada database !';
                 activity_log(get_module_id('paket_perjalanan'), 'Drop', $request->id, 'Data gagal dihapus,terjadi kesalahan pada database !');
             }
         }
         else
         {
             $json['status'] = 'failed';
             $json['msg']    = 'Data gagal dihapus, data tidak ditemukan !';
             activity_log(get_module_id('paket_perjalanan'), 'Drop', $request->id, 'Data gagal dihapus, data tidak ditemukan !');
         }

         return response()->json($json);
     }
}
