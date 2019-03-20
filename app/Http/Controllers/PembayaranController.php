<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pembayaran;
use App\Models\Pemesanan;
use Validator;
use Response;
use View;
use Auth;
use PDF;

class PembayaranController extends Controller
{
      public function __construct()
      {
           $this->middleware('auth');
      }

      protected $rules =
      [
          'tgl_pembayaran'  => 'required',
          'nomor_pesanan'   => 'required|max:100',
          'jumlah_bayar'    => 'required',

     ];

      public function index()
      {
          if(access_level_user('view','pembayaran')=='allow')
          {
              activity_log(get_module_id('pembayaran'), 'View', '', '');
              return view('pembayaran/index');
          }
          else
          {
              activity_log(get_module_id('pembayaran'), 'View', '', 'Error 403 : Forbidden');
              abort(403);
          }

      }

      public function list_data(Request $request)
      {
              $page = $request->page;
              $per_page = 10;
             if ($page != 1) $start = ($page-1) * $per_page;
             else $start=0;
             $tgl_awal  = date('Y-m-d',strtotime($request->tgl_awal));
             $tgl_akhir = date('Y-m-d',strtotime($request->tgl_akhir));

              $totalData = Pembayaran::with('user')
                            ->when($request->tgl_awal, function($query) use ($request)
                            {
                                      return $query->where('tgl_bayar','>=',date('Y-m-d',strtotime($request->tgl_awal)));
                            })
                            ->when($request->tgl_akhir, function($query) use ($request)
                            {
                                      return $query->where('tgl_bayar','<=',date('Y-m-d',strtotime($request->tgl_akhir)));
                            })
                            ->when($request->nomor_pembayaran, function($query) use ($request)
                            {
                                      return $query->where('no_pembayaran',$request->nomor_pembayaran);
                            })
                            ->when($request->jenis_pembayaran, function($query) use ($request)
                            {
                                      return $query->where('jenis_pembayaran',$request->jenis_pembayaran);
                            })
                            ->count();

                            $data = Pembayaran::with('user')
                              ->when($request->tgl_awal, function($query) use ($request)
                              {
                                        return $query->where('tgl_bayar','>=',date('Y-m-d',strtotime($request->tgl_awal)));
                              })
                              ->when($request->tgl_akhir, function($query) use ($request)
                              {
                                        return $query->where('tgl_bayar','<=',date('Y-m-d',strtotime($request->tgl_akhir)));
                              })
                              ->when($request->nomor_pembayaran, function($query) use ($request)
                              {
                                        return $query->where('no_pembayaran',$request->nomor_pembayaran);
                              })
                              ->when($request->jenis_pembayaran, function($query) use ($request)
                              {
                                        return $query->where('jenis_pembayaran',$request->jenis_pembayaran);
                              })
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
              if($request->output=='HTML' || $request->output=='')
              {
                  $json['html'] = view('pembayaran.pembayaran_list',['data' => $data,'array' => $array])->render();
                  $json['numPage'] = $numPage;
                  $json['numitem'] = $count;
                  $json['output']  = 'HTML';
              }
              else
              {
                  $json['html'] = view('pembayaran.pembayaran_list',['data' => $data,'array' => $array])->render();
                  $json['numPage'] = $numPage;
                  $json['numitem'] = $count;
                  $json['output']  = 'PDF';

                  if(!empty($request->nomor_pembayaran))
                  {
                      $json['link']   = "pembayaran/export_1/$tgl_awal/$tgl_akhir/$request->nomor_pembayaran";
                  }
                  else if(!empty($request->jenis_pembayaran))
                  {
                      $json['link']   = "pembayaran/export_2/$tgl_awal/$tgl_akhir/$request->jenis_pembayaran";
                  }
                  else
                  {
                    $json['link']   = "pembayaran/export_all/$tgl_awal/$tgl_akhir";

                  }
              }
              return response()->json($json);
      }

      public function export_1($tgl_awal,$tgl_akhir,$nomor_pembayaran)
      {

            $count  =  Pembayaran::with('user')
                                ->where('tgl_bayar','>=',date('Y-m-d',strtotime($tgl_awal)))
                                ->where('tgl_bayar','<=',date('Y-m-d',strtotime($tgl_akhir)))
                                ->where('no_pembayaran',$nomor_pembayaran)
                                ->count();
            if($count>=1)
            {
              $row =  Pembayaran::with('user')
                                  ->where('tgl_bayar','>=',date('Y-m-d',strtotime($tgl_awal)))
                                  ->where('tgl_bayar','<=',date('Y-m-d',strtotime($tgl_akhir)))
                                  ->where('no_pembayaran',$nomor_pembayaran)
                                  ->get();
              $total = Pembayaran::select(DB::raw('SUM(jumlah_bayar) as jumlah_bayar'))
                                  ->where('tgl_bayar','>=',date('Y-m-d',strtotime($tgl_awal)))
                                  ->where('tgl_bayar','<=',date('Y-m-d',strtotime($tgl_akhir)))
                                  ->where('no_pembayaran',$nomor_pembayaran)
                                  ->first();
                                  $data['row']   = $row;
                                  $data['total'] = $total;
                                  $pdf = PDF::loadView('pembayaran.export', $data,[], ['format' => 'A4-L']);
                                  return $pdf->stream('Repot Pembayaran.pdf');
            }
            else
            {
                dd("Data tidak ditemukan !, silahkan cek kembali spesifikasi pencarian");
            }
      }

      public function export_2($tgl_awal,$tgl_akhir,$jenis_pembayaran)
      {

            $count  =  Pembayaran::with('user')
                                ->where('tgl_bayar','>=',date('Y-m-d',strtotime($tgl_awal)))
                                ->where('tgl_bayar','<=',date('Y-m-d',strtotime($tgl_akhir)))
                                ->where('jenis_pembayaran',$jenis_pembayaran)
                                ->count();
            if($count>=1)
            {
              $row =  Pembayaran::with('user')
                                  ->where('tgl_bayar','>=',date('Y-m-d',strtotime($tgl_awal)))
                                  ->where('tgl_bayar','<=',date('Y-m-d',strtotime($tgl_akhir)))
                                  ->where('jenis_pembayaran',$jenis_pembayaran)
                                  ->get();
              $total = Pembayaran::select(DB::raw('SUM(jumlah_bayar) as jumlah_bayar'))
                                  ->where('tgl_bayar','>=',date('Y-m-d',strtotime($tgl_awal)))
                                  ->where('tgl_bayar','<=',date('Y-m-d',strtotime($tgl_akhir)))
                                  ->where('jenis_pembayaran',$jenis_pembayaran)
                                  ->first();
                                  $data['row']   = $row;
                                  $data['total'] = $total;

                                  $pdf = PDF::loadView('pembayaran.export', $data,[], ['format' => 'A4-L']);
                                  return $pdf->stream('Repot Pembayaran.pdf');
            }
            else
            {
                dd("Data tidak ditemukan !, silahkan cek kembali spesifikasi pencarian");
            }
      }

      public function export_all($tgl_awal,$tgl_akhir)
      {

            $count  =  Pembayaran::with('user')
                                ->where('tgl_bayar','>=',date('Y-m-d',strtotime($tgl_awal)))
                                ->where('tgl_bayar','<=',date('Y-m-d',strtotime($tgl_akhir)))
                                ->count();
            if($count>=1)
            {
              $row =  Pembayaran::with('user')
                                  ->where('tgl_bayar','>=',date('Y-m-d',strtotime($tgl_awal)))
                                  ->where('tgl_bayar','<=',date('Y-m-d',strtotime($tgl_akhir)))
                                  ->get();
              $total = Pembayaran::select(DB::raw('SUM(jumlah_bayar) as jumlah_bayar'))
                                  ->where('tgl_bayar','>=',date('Y-m-d',strtotime($tgl_awal)))
                                  ->where('tgl_bayar','<=',date('Y-m-d',strtotime($tgl_akhir)))
                                  ->first();
                                  $data['row']   = $row;
                                  $data['total'] = $total;
                                  $pdf = PDF::loadView('pembayaran.export', $data,[], ['format' => 'A4-L']);
                                  return $pdf->stream('Repot Pembayaran.pdf');
            }
            else
            {
                dd("Data tidak ditemukan !, silahkan cek kembali spesifikasi pencarian");
            }
      }


      public function add()
      {

            $returnHTML = view('pembayaran.add')->render();
            return response()->json(['status' => 'success','html' => $returnHTML]);
      }

      public function open_pemesanan()
      {
            $returnHTML   = view('pembayaran.display_pemesanan')->render();
            $json['html'] = $returnHTML;

            return response()->json($json);
      }

      public function list_data_pemesanan(Request $request)
      {
              $page = $request->page;
              $per_page = 10;
             if ($page != 1) $start = ($page-1) * $per_page;
             else $start=0;

              $totalData = Pemesanan::with('paket')
                            ->when($request->description, function($query) use ($request)
                            {
                                      return $query->where('nomor_pesanan','like','%'.$request->description.'%');
                            })
                            ->where('status_pembayaran','Open')
                            ->count();

                            $data = Pemesanan::with('paket')
                                            ->when($request->description, function($query) use ($request)
                                            {
                                                return $query->where('nomor_pesanan','like','%'.$request->description.'%');
                                            })
                                             ->where('status_pembayaran','Open')
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
              $returnHTML   = view('pembayaran.pemesanan_list',['data' => $data,'array' => $array])->render();
              return response()->json(array('success' => true, 'html'=>$returnHTML,'numPage'=>$numPage,'numitem' => $count) );
      }

      public function add_pemesanan(Request $request)
      {
          $count = Pemesanan::where('nomor_pesanan',$request->id)
                              ->where('status_pembayaran','Open')
                              ->count();
          if($count==1)
          {
              $row = Pemesanan::where('nomor_pesanan',$request->id)
                                  ->where('status_pembayaran','Open')
                                  ->first();
              $json['status'] = 'success';
              $json['nomor_pesanan']    = $row->nomor_pesanan;
              $json['total_tagihan']    = number_format($row->total_harga);
              $json['sudah_dibayar']    = number_format($row->sudah_dibayar);
              $json['sisa_tagihan']     = number_format($row->sisa_bayar);
              $json['sisa_tagihan_val'] = $row->sisa_bayar;
          }
          else
          {
              $json['status'] = 'failed';
              $json['msg']    = 'Data tidak ditemukan !';
          }

          return response()->json($json);
      }

      public function save(Request $request)
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

                   $kode_pembayaran = autonumber_transaction('pembayaran','no_pembayaran','PM','tgl_bayar');
                   $row             = Pemesanan::where('nomor_pesanan',$request->nomor_pesanan)
                                                ->where('status_pembayaran','Open')
                                                ->first();

                   $insert = Pembayaran::create([
                                               'no_pembayaran'    => $kode_pembayaran,
                                               'tgl_bayar'        => date('Y-m-d',strtotime($request->tgl_pembayaran)),
                                               'jenis_pembayaran' => $request->jenis,
                                               'nomor_pesanan'    => $request->nomor_pesanan,
                                               'total_tagihan'    => $row->sisa_bayar,
                                               'jumlah_bayar'     => $request->jumlah_bayar,
                                               'keterangan'       => $request->keterangan,
                                               'created_by'       => auth::user()->id
                                             ]);
                   if($insert)
                   {
                     if($request->jumlah_bayar> $row->sisa_bayar)
                     {
                        $kembali = Pembayaran::select(DB::raw('SUM(jumlah_bayar)-SUM(total_tagihan)as kembali'))
                                               ->where('no_pembayaran',$kode_pembayaran)
                                               ->first();
                         $update = Pembayaran::where('no_pembayaran',$kode_pembayaran)
                                               ->where('nomor_pesanan',$request->nomor_pesanan)
                                               ->update([
                                                 'sisa_bayar' => 0,
                                                 'kembali'    => $kembali->kembali
                                               ]);

                         $sudah_dibayar = Pembayaran::select(DB::raw('SUM(jumlah_bayar) as jumlah_bayar'))
                                                      ->where('nomor_pesanan',$request->nomor_pesanan)
                                                      ->first();

                         $update_pemesanan = Pemesanan::where('nomor_pesanan',$request->nomor_pesanan)
                                                      ->update([
                                                        'sudah_dibayar'  => $sudah_dibayar->jumlah_bayar,
                                                        'sisa_bayar'     => 0,
                                                      ]);

                          $kembali_pesanan = Pemesanan::select(DB::raw('SUM(sudah_dibayar)-SUM(total_harga)as kembali'))
                                                          ->where('nomor_pesanan',$request->nomor_pesanan)
                                                          ->first();
                          $update_pemesanan = Pemesanan::where('nomor_pesanan',$request->nomor_pesanan)
                                                       ->update([
                                                         'kembali'  => $kembali_pesanan->kembali,
                                                       ]);

                     }
                     else
                     {
                         $row = Pembayaran::select(DB::raw('SUM(total_tagihan)-SUM(jumlah_bayar)as sisa_bayar,SUM(jumlah_bayar)as jumlah_bayar'))
                                                 ->where('no_pembayaran',$kode_pembayaran)
                                                 ->first();

                         $update = Pembayaran::where('no_pembayaran',$kode_pembayaran)
                                               ->where('nomor_pesanan',$request->nomor_pesanan)
                                               ->update([
                                                 'sisa_bayar' => $row->sisa_bayar,
                                                 'kembali'    => 0
                                               ]);

                         $row_2 = Pembayaran::select(DB::raw('SUM(jumlah_bayar) as jumlah_bayar'))
                                                      ->where('nomor_pesanan',$request->nomor_pesanan)
                                                      ->first();

                         $update_pemesanan = Pemesanan::where('nomor_pesanan',$request->nomor_pesanan)
                                                        ->update([
                                                          'sudah_dibayar'  => $row_2->jumlah_bayar,
                                                          'kembali'        => 0
                                                        ]);
                        $sisa_bayar = Pemesanan::select(DB::raw('SUM(total_harga)-SUM(sudah_dibayar)as sisa_bayar'))
                                                        ->where('nomor_pesanan',$request->nomor_pesanan)
                                                        ->first();
                        $update_pemesanan = Pemesanan::where('nomor_pesanan',$request->nomor_pesanan)
                                                     ->update([
                                                       'sisa_bayar'  => $sisa_bayar->sisa_bayar,
                                                     ]);
                     }

                      $row_pembayaran = Pembayaran::where('no_pembayaran',$kode_pembayaran)
                                           ->where('nomor_pesanan',$request->nomor_pesanan)
                                           ->first();
                      if($row_pembayaran->sisa_bayar==0)
                      {
                          $update_status = Pemesanan::where('nomor_pesanan',$request->nomor_pesanan)
                                                         ->update([
                                                           'status_pembayaran'  =>'Closed'
                                                         ]);
                          $update_status_2 = Pembayaran::where('no_pembayaran',$kode_pembayaran)
                                               ->where('nomor_pesanan',$request->nomor_pesanan)
                                               ->update([
                                                 'status' => 'Lunas'
                                               ]);
                      }
                      $json['status']  = 'success';
                      $json['msg']     = 'Data telah berhasil disimpan !';
                      $json['nomor_pembayaran'] = $kode_pembayaran;
                      activity_log(get_module_id('pembayaran'), 'Create', $kode_pembayaran, 'Berhasil menyimpan data !');
                  }
                  else
                  {
                      $json['status'] = 'failed';
                      $json['msg']    = 'Data gagal disimpan ,terjadi kesalahan pada database !';
                      activity_log(get_module_id('pembayaran'), 'Create', $kode_pembayaran, 'Data gagal disimpan, terjadi kesalahan pada database ! ');
                  }

           }

             return response()->json($json);
      }

      public function delete(Request $request)
      {
           $validasi = Pembayaran::where('nomor_pesanan',$request->nomor_pesanan)
                                   ->where('status','Lunas')
                                   ->count();
           if($validasi==0)
           {
               $count = Pembayaran::where('no_pembayaran',$request->id)->count();
               if($count==1)
               {
                   $delete = Pembayaran::where('no_pembayaran',$request->id)->delete();
                   if($delete)
                   {

                     $sudah_dibayar = Pembayaran::select(DB::raw('COALESCE(SUM(jumlah_bayar),0)as jumlah_bayar'))
                                                  ->where('nomor_pesanan',$request->nomor_pesanan)
                                                  ->first();

                     $update_pemesanan = Pemesanan::where('nomor_pesanan',$request->nomor_pesanan)
                                                  ->update([
                                                    'sudah_dibayar'  => $sudah_dibayar->jumlah_bayar,
                                                  ]);

                      $row_2 = Pemesanan::select(DB::raw('SUM(total_harga)-SUM(sudah_dibayar)as sisa_bayar'))
                                                      ->where('nomor_pesanan',$request->nomor_pesanan)
                                                      ->first();

                      $update_pemesanan = Pemesanan::where('nomor_pesanan',$request->nomor_pesanan)
                                                   ->update([
                                                     'kembali'    => 0,
                                                     'sisa_bayar' => $row_2->sisa_bayar
                                                   ]);
                     $json['status'] = 'success';
                     $json['msg']    = 'Data telah berhasil dihapus !';
                     activity_log(get_module_id('pembayaran'), 'Drop', $request->id, 'Data telah berhasil dihapus !');

                   }
                   else
                   {
                       $json['status'] = 'failed';
                       $json['msg']    = 'Data gagal dihapus,terjadi kesalahan pada database !';
                       activity_log(get_module_id('pembayaran'), 'Drop', $request->id, 'Data gagal dihapus,terjadi kesalahan pada database !');
                   }
               }
               else
               {
                   $json['status'] = 'failed';
                   $json['msg']    = 'Data gagal dihapus, data tidak ditemukan !';
                   activity_log(get_module_id('pembayaran'), 'Drop', $request->id, 'Data gagal dihapus, data tidak ditemukan !');
               }

           }
           else
           {
              $json['status'] = 'failed';
              $json['msg']    = 'Data gagal dihapus, system mendeteksi nomor pesanan '.$request->nomor_pesanan.' telah melunasi pembayaran ';
           }

           return response()->json($json);
      }


      public function pdf_pembayaran(Request $request)
      {
          $count = Pembayaran::where('no_pembayaran',$request->nomor_pembayaran)->count();
          if($count==1)
          {
              $json['status'] = 'success';
              $json['link']   = 'pembayaran/export_pembayaran/'.$request->nomor_pembayaran.'';
          }
          else
          {
              $json['status'] = 'failed';
              $json['msg']    = 'Data tidak ditemukan, silahkan cek kembali !';
          }


          return response()->json($json);
      }

      public function export_pembayaran($nomor_pembayaran)
      {

        $row  =  Pembayaran::where('no_pembayaran',$nomor_pembayaran)->first();
        $data['row']  = $row;
        $pdf = PDF::loadView('pembayaran.pdf_pembayaran', $data,[], ['format' => array(165.1,139.7),'L','','5','5','8','8','5']);
        return $pdf->stream('pembayaran'.$nomor_pembayaran.'.pdf');
      }

      function form_search()
      {
         $returnHTML   = view('pembayaran.form_search')->render();
         $json['html'] = $returnHTML;
         return response()->json($json);
      }

}
