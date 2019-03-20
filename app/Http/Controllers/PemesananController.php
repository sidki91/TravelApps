<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Bulan;
use App\Models\KategoriPerjalanan;
use App\Models\PaketPerjalanan;
use App\Models\HargaPaket;
use App\Models\Formulir;
use App\Models\Pemesanan;
use App\Models\PemesananDetail;
use App\Models\Pembayaran;
use Validator;
use Response;
use View;
use Auth;
use PDF;

class PemesananController extends Controller
{
      public function __construct()
      {
          $this->middleware('auth');
      }

      protected $rules =
      [
          'bulan'         => 'required|max:100',
          'kategori'      => 'required|max:30',
          'tgl_berangkat' => 'required|max:20',
          'kategori'      => 'required',
          'paket'         => 'required',
          'sub_paket'     => 'required',
          'berangkat_dari'=> 'required|max:100',
          'harga'         => 'required',
          'jumlah_jamaah' => 'required|numeric|min:1|max:5',
      ];

      public function index()
      {
          if(access_level_user('view','pemesanan')=='allow')
          {
              activity_log(get_module_id('pemesanan'), 'View', '', '');
              return view('pemesanan/index');
          }
          else
          {
              activity_log(get_module_id('pemesanan'), 'View', '', 'Error 403 : Forbidden');
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
              $tgl_berangkat = date('Y-m-d',strtotime($request->tgl_berangkat));
              $tgl_kembali   = date('Y-m-d',strtotime($request->tgl_kembali));

              $totalData = Pemesanan::with('user')
                            ->when($request->tgl_awal, function($query) use ($request)
                            {
                                      return $query->where('tgl_pesan','>=',date('Y-m-d',strtotime($request->tgl_awal)));
                            })
                            ->when($request->tgl_akhir, function($query) use ($request)
                            {
                                      return $query->where('tgl_pesan','<=',date('Y-m-d',strtotime($request->tgl_akhir)));
                            })
                            ->when($request->jenis_pemesanan, function($query) use ($request)
                            {
                                      return $query->where('jenis_pemesanan',$request->jenis_pemesanan);
                            })
                            ->when($request->kategori, function($query) use ($request)
                            {
                                      return $query->where('kode_kategori',$request->kategori);
                            })
                            ->when($request->paket, function($query) use ($request)
                            {
                                      return $query->where('kode_paket',$request->paket);
                            })
                            ->when($request->sub_paket, function($query) use ($request)
                            {
                                      return $query->where('kode_harga',$request->sub_paket);
                            })
                            ->when($request->tgl_berangkat, function($query) use ($request)
                            {
                                      return $query->where('tgl_berangkat',date('Y-m-d',strtotime($request->tgl_berangkat)));
                            })
                            ->when($request->tgl_kembali, function($query) use ($request)
                            {
                                      return $query->where('tgl_kembali',date('Y-m-d',strtotime($request->tgl_kembali)));
                            })
                            ->when($request->nomor_pesanan, function($query) use ($request)
                            {
                                      return $query->where('nomor_pesanan',$request->nomor_pesanan);
                            })
                            ->when($request->status, function($query) use ($request)
                            {
                                      return $query->where('status_pembayaran',$request->status);
                            })
                            ->whereNull('deleted_at')
                            ->count();

                            $data = Pemesanan::with('user')
                                              ->when($request->tgl_awal, function($query) use ($request)
                                              {
                                                        return $query->where('tgl_pesan','>=',date('Y-m-d',strtotime($request->tgl_awal)));
                                              })
                                              ->when($request->tgl_akhir, function($query) use ($request)
                                              {
                                                        return $query->where('tgl_pesan','<=',date('Y-m-d',strtotime($request->tgl_akhir)));
                                              })
                                              ->when($request->jenis_pemesanan, function($query) use ($request)
                                              {
                                                        return $query->where('jenis_pemesanan',$request->jenis_pemesanan);
                                              })
                                              ->when($request->kategori, function($query) use ($request)
                                              {
                                                        return $query->where('kode_kategori',$request->kategori);
                                              })
                                              ->when($request->paket, function($query) use ($request)
                                              {
                                                        return $query->where('kode_paket',$request->paket);
                                              })
                                              ->when($request->sub_paket, function($query) use ($request)
                                              {
                                                        return $query->where('kode_harga',$request->sub_paket);
                                              })
                                              ->when($request->tgl_berangkat, function($query) use ($request)
                                              {
                                                        return $query->where('tgl_berangkat',date('Y-m-d',strtotime($request->tgl_berangkat)));
                                              })
                                              ->when($request->tgl_kembali, function($query) use ($request)
                                              {
                                                        return $query->where('tgl_kembali',date('Y-m-d',strtotime($request->tgl_kembali)));
                                              })
                                              ->when($request->nomor_pesanan, function($query) use ($request)
                                              {
                                                        return $query->where('nomor_pesanan',$request->nomor_pesanan);
                                              })
                                              ->when($request->status, function($query) use ($request)
                                              {
                                                        return $query->where('status_pembayaran',$request->status);
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
              if($request->output=='HTML' || $request->output=='')
              {
                $json['html']    =  view('pemesanan.pemesanan_list',['data' => $data,'array' => $array])->render();
                $json['numPage'] = $numPage;
                $json['numitem'] = $count;
                $json['output']  = 'HTML';
              }
              else
              {
                $json['html'] = view('pemesanan.pemesanan_list',['data' => $data,'array' => $array])->render();
                $json['numPage'] = $numPage;
                $json['numitem'] = $count;
                $json['output']  = 'PDF';

                if(!empty($request->jenis_pemesanan) && !empty($request->kategori) && !empty($request->paket) && !empty($request->sub_paket)&& !empty($request->tgl_berangkat) && !empty($request->tgl_kembali))
                {
                  $json['link']     ="pemesanan/export_1/$tgl_awal/$tgl_akhir/$request->jenis_pemesanan/$request->kategori/$request->paket/$request->sub_paket/$tgl_berangkat/$tgl_kembali";
                }
                else if(!empty($request->jenis_pemesanan) && !empty($request->kategori) && !empty($request->paket) && !empty($request->sub_paket)&& !empty($request->tgl_berangkat))
                {
                  $json['link']     ="pemesanan/export_2/$tgl_awal/$tgl_akhir/$request->jenis_pemesanan/$request->kategori/$request->paket/$request->sub_paket/$tgl_berangkat";

                }
                else if(!empty($request->jenis_pemesanan) && !empty($request->kategori) && !empty($request->paket) && !empty($request->sub_paket))
                {
                  $json['link']     ="pemesanan/export_3/$tgl_awal/$tgl_akhir/$request->jenis_pemesanan/$request->kategori/$request->paket/$request->sub_paket";

                }
                else if(!empty($request->jenis_pemesanan) && !empty($request->kategori) && !empty($request->paket))
                {
                  $json['link']     ="pemesanan/export_4/$tgl_awal/$tgl_akhir/$request->jenis_pemesanan/$request->kategori/$request->paket";

                }
                else if(!empty($request->jenis_pemesanan) && !empty($request->kategori) && !empty($request->tgl_berangkat)&& !empty($request->tgl_kembali))
                {
                  $json['link']     ="pemesanan/export_6/$tgl_awal/$tgl_akhir/$request->jenis_pemesanan/$request->kategori/$tgl_berangkat/$tgl_kembali";

                }
                else if(!empty($request->jenis_pemesanan) && !empty($request->kategori))
                {
                  $json['link']     ="pemesanan/export_5/$tgl_awal/$tgl_akhir/$request->jenis_pemesanan/$request->kategori";

                }

                else if(!empty($request->jenis_pemesanan))
                {

                  $json['link']     ="pemesanan/export_7/$tgl_awal/$tgl_akhir/$request->jenis_pemesanan";

                }
                else if(!empty($request->kategori))
                {
                  $parameter        ='category';
                  $json['link']     ="pemesanan/export_8/$tgl_awal/$tgl_akhir/$parameter/$request->kategori";

                }
                else if(!empty($request->nomor_pesanan))
                {
                  $parameter        ='category';
                  $json['link']     ="pemesanan/export_9/$tgl_awal/$tgl_akhir/$request->nomor_pesanan";

                }
                else if(!empty($request->status))
                {
                  $parameter        ='category';
                  $json['link']     ="pemesanan/export_10/$tgl_awal/$tgl_akhir/$request->status";

                }

                else
                {
                  $json['link']     ="pemesanan/export_all/$tgl_awal/$tgl_akhir";
                }

              }

              return response()->json($json);
      }


      public function export_1($tgl_awal,$tgl_akhir,$jenis,$kategori,$paket,$sub_paket,$tgl_berangkat,$tgl_kembali)
      {

            $count  =  Pemesanan::with('bulan','kategori','paket','sub_paket','user')
                                ->where('tgl_pesan','>=',date('Y-m-d',strtotime($tgl_awal)))
                                ->where('tgl_pesan','<=',date('Y-m-d',strtotime($tgl_akhir)))
                                ->where('jenis_pemesanan',$jenis)
                                ->where('kode_kategori',$kategori)
                                ->where('kode_paket',$paket)
                                ->where('kode_harga',$sub_paket)
                                ->where('tgl_berangkat',date('Y-m-d',strtotime($tgl_berangkat)))
                                ->where('tgl_kembali',date('Y-m-d',strtotime($tgl_kembali)))
                                ->count();
            if($count>=1)
            {
              $row =  Pemesanan::with('bulan','kategori','paket','sub_paket','user')
                                  ->where('tgl_pesan','>=',date('Y-m-d',strtotime($tgl_awal)))
                                  ->where('tgl_pesan','<=',date('Y-m-d',strtotime($tgl_akhir)))
                                  ->where('jenis_pemesanan',$jenis)
                                  ->where('kode_kategori',$kategori)
                                  ->where('kode_paket',$paket)
                                  ->where('kode_harga',$sub_paket)
                                  ->where('tgl_berangkat',date('Y-m-d',strtotime($tgl_berangkat)))
                                  ->where('tgl_kembali',date('Y-m-d',strtotime($tgl_kembali)))
                                  ->get();
                                  $data['row']  = $row;
                                  $pdf = PDF::loadView('pemesanan.export', $data,[], ['format' => 'A4-P']);
                                  return $pdf->stream('Repot Pemesanan.pdf');
            }
            else
            {
                dd("Data tidak ditemukan !, silahkan cek kembali spesifikasi pencarian");
            }
      }

      public function export_2($tgl_awal,$tgl_akhir,$jenis,$kategori,$paket,$sub_paket,$tgl_berangkat)
      {

            $count  =  Pemesanan::with('bulan','kategori','paket','sub_paket','user')
                                ->where('tgl_pesan','>=',date('Y-m-d',strtotime($tgl_awal)))
                                ->where('tgl_pesan','<=',date('Y-m-d',strtotime($tgl_akhir)))
                                ->where('jenis_pemesanan',$jenis)
                                ->where('kode_kategori',$kategori)
                                ->where('kode_paket',$paket)
                                ->where('kode_harga',$sub_paket)
                                ->where('tgl_berangkat',date('Y-m-d',strtotime($tgl_berangkat)))
                                ->count();
            if($count>=1)
            {
              $row =  Pemesanan::with('bulan','kategori','paket','sub_paket','user')
                                  ->where('tgl_pesan','>=',date('Y-m-d',strtotime($tgl_awal)))
                                  ->where('tgl_pesan','<=',date('Y-m-d',strtotime($tgl_akhir)))
                                  ->where('jenis_pemesanan',$jenis)
                                  ->where('kode_kategori',$kategori)
                                  ->where('kode_paket',$paket)
                                  ->where('kode_harga',$sub_paket)
                                  ->where('tgl_berangkat',date('Y-m-d',strtotime($tgl_berangkat)))
                                 ->get();
                                  $data['row']  = $row;
                                  $pdf = PDF::loadView('pemesanan.export', $data,[], ['format' => 'A4-P']);
                                  return $pdf->stream('Repot Pemesanan.pdf');
            }
            else
            {
                dd("Data tidak ditemukan !, silahkan cek kembali spesifikasi pencarian");
            }
      }

      public function export_3($tgl_awal,$tgl_akhir,$jenis,$kategori,$paket,$sub_paket)
      {

            $count  =  Pemesanan::with('bulan','kategori','paket','sub_paket','user')
                                ->where('tgl_pesan','>=',date('Y-m-d',strtotime($tgl_awal)))
                                ->where('tgl_pesan','<=',date('Y-m-d',strtotime($tgl_akhir)))
                                ->where('jenis_pemesanan',$jenis)
                                ->where('kode_kategori',$kategori)
                                ->where('kode_paket',$paket)
                                ->where('kode_harga',$sub_paket)
                                ->count();
            if($count>=1)
            {
              $row =  Pemesanan::with('bulan','kategori','paket','sub_paket','user')
                                  ->where('tgl_pesan','>=',date('Y-m-d',strtotime($tgl_awal)))
                                  ->where('tgl_pesan','<=',date('Y-m-d',strtotime($tgl_akhir)))
                                  ->where('jenis_pemesanan',$jenis)
                                  ->where('kode_kategori',$kategori)
                                  ->where('kode_paket',$paket)
                                  ->where('kode_harga',$sub_paket)
                                 ->get();
                                  $data['row']  = $row;
                                  $pdf = PDF::loadView('pemesanan.export', $data,[], ['format' => 'A4-P']);
                                  return $pdf->stream('Repot Pemesanan.pdf');
            }
            else
            {
                dd("Data tidak ditemukan !, silahkan cek kembali spesifikasi pencarian");
            }
      }
      public function export_4($tgl_awal,$tgl_akhir,$jenis,$kategori,$paket)
      {

            $count  =  Pemesanan::with('bulan','kategori','paket','sub_paket','user')
                                ->where('tgl_pesan','>=',date('Y-m-d',strtotime($tgl_awal)))
                                ->where('tgl_pesan','<=',date('Y-m-d',strtotime($tgl_akhir)))
                                ->where('jenis_pemesanan',$jenis)
                                ->where('kode_kategori',$kategori)
                                ->where('kode_paket',$paket)
                                ->count();
            if($count>=1)
            {
              $row =  Pemesanan::with('bulan','kategori','paket','sub_paket','user')
                                  ->where('tgl_pesan','>=',date('Y-m-d',strtotime($tgl_awal)))
                                  ->where('tgl_pesan','<=',date('Y-m-d',strtotime($tgl_akhir)))
                                  ->where('jenis_pemesanan',$jenis)
                                  ->where('kode_kategori',$kategori)
                                  ->where('kode_paket',$paket)
                                 ->get();
                                  $data['row']  = $row;
                                  $pdf = PDF::loadView('pemesanan.export', $data,[], ['format' => 'A4-P']);
                                  return $pdf->stream('Repot Pemesanan.pdf');
            }
            else
            {
                dd("Data tidak ditemukan !, silahkan cek kembali spesifikasi pencarian");
            }
      }

      public function export_5($tgl_awal,$tgl_akhir,$jenis,$kategori)
      {

            $count  =  Pemesanan::with('bulan','kategori','paket','sub_paket','user')
                                ->where('tgl_pesan','>=',date('Y-m-d',strtotime($tgl_awal)))
                                ->where('tgl_pesan','<=',date('Y-m-d',strtotime($tgl_akhir)))
                                ->where('jenis_pemesanan',$jenis)
                                ->where('kode_kategori',$kategori)
                                ->count();
            if($count>=1)
            {
              $row =  Pemesanan::with('bulan','kategori','paket','sub_paket','user')
                                  ->where('tgl_pesan','>=',date('Y-m-d',strtotime($tgl_awal)))
                                  ->where('tgl_pesan','<=',date('Y-m-d',strtotime($tgl_akhir)))
                                  ->where('jenis_pemesanan',$jenis)
                                  ->where('kode_kategori',$kategori)
                                 ->get();
                                  $data['row']  = $row;
                                  $pdf = PDF::loadView('pemesanan.export', $data,[], ['format' => 'A4-P']);
                                  return $pdf->stream('Repot Pemesanan.pdf');
            }
            else
            {
                dd("Data tidak ditemukan !, silahkan cek kembali spesifikasi pencarian");
            }
      }

      public function export_6($tgl_awal,$tgl_akhir,$jenis,$kategori,$tgl_berangkat,$tgl_kembali)
      {

            $count  =  Pemesanan::with('bulan','kategori','paket','sub_paket','user')
                                ->where('tgl_pesan','>=',date('Y-m-d',strtotime($tgl_awal)))
                                ->where('tgl_pesan','<=',date('Y-m-d',strtotime($tgl_akhir)))
                                ->where('jenis_pemesanan',$jenis)
                                ->where('kode_kategori',$kategori)
                                ->where('tgl_berangkat',date('Y-m-d',strtotime($tgl_berangkat)))
                                ->where('tgl_kembali',date('Y-m-d',strtotime($tgl_kembali)))
                                ->count();
            if($count>=1)
            {
              $row =  Pemesanan::with('bulan','kategori','paket','sub_paket','user')
                                  ->where('tgl_pesan','>=',date('Y-m-d',strtotime($tgl_awal)))
                                  ->where('tgl_pesan','<=',date('Y-m-d',strtotime($tgl_akhir)))
                                  ->where('jenis_pemesanan',$jenis)
                                  ->where('kode_kategori',$kategori)
                                  >where('tgl_berangkat',date('Y-m-d',strtotime($tgl_berangkat)))
                                  ->where('tgl_kembali',date('Y-m-d',strtotime($tgl_kembali)))
                                  ->get();
                                  $data['row']  = $row;
                                  $pdf = PDF::loadView('pemesanan.export', $data,[], ['format' => 'A4-P']);
                                  return $pdf->stream('Repot Pemesanan.pdf');
            }
            else
            {
                dd("Data tidak ditemukan !, silahkan cek kembali spesifikasi pencarian");
            }
      }

      public function export_7($tgl_awal,$tgl_akhir,$jenis)
      {

            $count  =  Pemesanan::with('bulan','kategori','paket','sub_paket','user')
                                ->where('tgl_pesan','>=',date('Y-m-d',strtotime($tgl_awal)))
                                ->where('tgl_pesan','<=',date('Y-m-d',strtotime($tgl_akhir)))
                                ->where('jenis_pemesanan',$jenis)
                                ->count();
            if($count>=1)
            {
              $row =  Pemesanan::with('bulan','kategori','paket','sub_paket','user')
                                  ->where('tgl_pesan','>=',date('Y-m-d',strtotime($tgl_awal)))
                                  ->where('tgl_pesan','<=',date('Y-m-d',strtotime($tgl_akhir)))
                                  ->where('jenis_pemesanan',$jenis)
                                  ->get();
                                  $data['row']  = $row;
                                  $pdf = PDF::loadView('pemesanan.export', $data,[], ['format' => 'A4-P']);
                                  return $pdf->stream('Repot Pemesanan.pdf');
            }
            else
            {
                dd("Data tidak ditemukan !, silahkan cek kembali spesifikasi pencarian");
            }
      }

      public function export_8($tgl_awal,$tgl_akhir,$paramenter,$kode_kategori)
      {

            $count  =  Pemesanan::with('bulan','kategori','paket','sub_paket','user')
                                ->where('tgl_pesan','>=',date('Y-m-d',strtotime($tgl_awal)))
                                ->where('tgl_pesan','<=',date('Y-m-d',strtotime($tgl_akhir)))
                                ->where('kode_kategori',$kode_kategori)
                                ->count();
            if($count>=1)
            {
              $row =  Pemesanan::with('bulan','kategori','paket','sub_paket','user')
                                  ->where('tgl_pesan','>=',date('Y-m-d',strtotime($tgl_awal)))
                                  ->where('tgl_pesan','<=',date('Y-m-d',strtotime($tgl_akhir)))
                                  ->where('kode_kategori',$kode_kategori)
                                  ->get();
                                  $data['row']  = $row;
                                  $pdf = PDF::loadView('pemesanan.export', $data,[], ['format' => 'A4-P']);
                                  return $pdf->stream('Repot Pemesanan.pdf');
            }
            else
            {
                dd("Data tidak ditemukan !, silahkan cek kembali spesifikasi pencarian");
            }
      }

      public function export_9($tgl_awal,$tgl_akhir,$nomor_pesanan)
      {

            $count  =  Pemesanan::with('bulan','kategori','paket','sub_paket','user')
                                ->where('tgl_pesan','>=',date('Y-m-d',strtotime($tgl_awal)))
                                ->where('tgl_pesan','<=',date('Y-m-d',strtotime($tgl_akhir)))
                                ->where('nomor_pesanan',$nomor_pesanan)
                                ->count();
            if($count>=1)
            {
              $row =  Pemesanan::with('bulan','kategori','paket','sub_paket','user')
                                  ->where('tgl_pesan','>=',date('Y-m-d',strtotime($tgl_awal)))
                                  ->where('tgl_pesan','<=',date('Y-m-d',strtotime($tgl_akhir)))
                                  ->where('nomor_pesanan',$nomor_pesanan)
                                  ->get();
                                  $data['row']  = $row;
                                  $pdf = PDF::loadView('pemesanan.export', $data,[], ['format' => 'A4-P']);
                                  return $pdf->stream('Repot Pemesanan.pdf');
            }
            else
            {
                dd("Data tidak ditemukan !, silahkan cek kembali spesifikasi pencarian");
            }
      }

      public function export_10($tgl_awal,$tgl_akhir,$status)
      {

            $count  =  Pemesanan::with('bulan','kategori','paket','sub_paket','user')
                                ->where('tgl_pesan','>=',date('Y-m-d',strtotime($tgl_awal)))
                                ->where('tgl_pesan','<=',date('Y-m-d',strtotime($tgl_akhir)))
                                ->where('status_pembayaran',$status)
                                ->count();
            if($count>=1)
            {
              $row =  Pemesanan::with('bulan','kategori','paket','sub_paket','user')
                                  ->where('tgl_pesan','>=',date('Y-m-d',strtotime($tgl_awal)))
                                  ->where('tgl_pesan','<=',date('Y-m-d',strtotime($tgl_akhir)))
                                  ->where('status_pembayaran',$status)
                                  ->get();
                                  $data['row']  = $row;
                                  $pdf = PDF::loadView('pemesanan.export', $data,[], ['format' => 'A4-P']);
                                  return $pdf->stream('Repot Pemesanan.pdf');
            }
            else
            {
                dd("Data tidak ditemukan !, silahkan cek kembali spesifikasi pencarian");
            }
      }

      public function export_all($tgl_awal,$tgl_akhir)
      {

            $count  =  Pemesanan::with('bulan','kategori','paket','sub_paket','user')
                                ->where('tgl_pesan','>=',date('Y-m-d',strtotime($tgl_awal)))
                                ->where('tgl_pesan','<=',date('Y-m-d',strtotime($tgl_akhir)))
                                ->count();
            if($count>=1)
            {
              $row =  Pemesanan::with('bulan','kategori','paket','sub_paket','user')
                                  ->where('tgl_pesan','>=',date('Y-m-d',strtotime($tgl_awal)))
                                  ->where('tgl_pesan','<=',date('Y-m-d',strtotime($tgl_akhir)))
                                  ->get();
                                  $data['row']  = $row;
                                  $pdf = PDF::loadView('pemesanan.export', $data,[], ['format' => 'A4-P']);
                                  return $pdf->stream('Repot Pemesanan.pdf');
            }
            else
            {
                dd("Data tidak ditemukan !, silahkan cek kembali spesifikasi pencarian");
            }
      }



      public function add()
      {
            $bulan      = Bulan::all();
            $kategori   = KategoriPerjalanan::all();
            $returnHTML = view('pemesanan.add',compact('bulan','kategori'))->render();
            return response()->json(['status' => 'success','html' => $returnHTML]);
      }

      public function pilih_paket(Request $request)
      {
          $data       = PaketPerjalanan::where('kode_kategori',$request->kategori)
                                         ->where('bulan',$request->bulan)
                                        ->get();
          $returnHTML = view('pemesanan.paket',compact('data'))->render();
          $json['html'] = $returnHTML;

          return response()->json($json);
      }

      public function pilih_paket_search(Request $request)
      {
          $data       = PaketPerjalanan::where('kode_kategori',$request->kategori)
                                         ->get();
          $returnHTML = view('pemesanan.paket_search',compact('data'))->render();
          $json['html'] = $returnHTML;

          return response()->json($json);
      }

      public function get_tgl_kembali(Request $request)
      {

          $tgl_kembali = date('m/d/Y', strtotime('+'.$request->lama_perjalanan.' days', strtotime(date('Y-m-d',strtotime($request->tgl_berangkat)))));
          $json['tgl_kembali'] = $tgl_kembali;
          return response()->json($json);
      }
      public function pilih_sub_paket(Request $request)
      {
          $row        = HargaPaket::with('kapasitas')
                              ->where('kode_paket',$request->paket)
                              ->get();
          $lama_perjalanan             = PaketPerjalanan::where('kode_paket',$request->paket)->value('lama_perjalanan');
          $returnHTML                  = view('pemesanan.sub_paket',compact('row'))->render();
          $json['lama_perjalanan']     = $lama_perjalanan.' Hari';
          $json['lama_perjalanan_val'] = $lama_perjalanan;
          $json['html']                = $returnHTML;
          return response()->json($json);
      }

      public function pilih_sub_paket_search(Request $request)
      {
          $row        = HargaPaket::with('kapasitas')
                              ->where('kode_paket',$request->paket)
                              ->get();
          $returnHTML                  = view('pemesanan.sub_paket_search',compact('row'))->render();
          $json['html']                = $returnHTML;
          return response()->json($json);
      }

      public function pilih_harga_paket(Request $request)
      {
             if(!empty($request->sub_paket))
             {
                 $row = HargaPaket::where('kode_harga',$request->sub_paket)->first();
                 $json['harga']     = number_format($row->harga);
                 $json['harga_val'] = $row->harga;
             }
             else
             {
                 $json['harga']     = 0;
                 $json['harga_val'] = 0;
             }

             return response()->json($json);
      }

      public function generate(Request $request)
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

                    $kode_pemesanan = autonumber_transaction('pemesanan_paket','nomor_pesanan','ID','tgl_pesan');
                    $insert = Pemesanan::create([
                                                'nomor_pesanan'     => $kode_pemesanan,
                                                'tgl_pesan'         => date('Y-m-d',strtotime($request->tgl_pemesanan)),
                                                'jenis_pemesanan'   => $request->jenis,
                                                'jam_pesan'         => \Carbon\Carbon::now()->toDateTimeString(),
                                                'kode_kategori'     => $request->kategori,
                                                'kode_paket'        => $request->paket,
                                                'bulan_paket'       => $request->bulan,
                                                'kode_harga'        => $request->sub_paket,
                                                'harga'             => $request->harga,
                                                'jumlah'            => $request->jumlah_jamaah,
                                                'lama_perjalanan'   => $request->lama_perjalanan,
                                                'tgl_berangkat'     => date('Y-m-d',strtotime($request->tgl_berangkat)),
                                                'tgl_kembali'       => date('Y-m-d',strtotime($request->tgl_kembali)),
                                                'berangkat_dari'    => $request->berangkat_dari,
                                                'keterangan'        => $request->keterangan,
                                                'status_pembayaran' => 'Open',
                                                'created_by'        => auth::user()->id
                                              ]);
                    if($insert)
                    {
                       $total_harga = Pemesanan::select(DB::raw('SUM(harga*jumlah)AS total_harga'))
                                                            ->where('nomor_pesanan',$kode_pemesanan)
                                                            ->first();
                       $update = Pemesanan::where('nomor_pesanan',$kode_pemesanan)
                                            ->update([
                                              'total_harga'   => $total_harga->total_harga,
                                              'sudah_dibayar' => 0,
                                              'sisa_bayar'    => $total_harga->total_harga,
                                              'updated_by'    => Auth::user()->id
                                            ]);
                       $json['status']        = 'success';
                       $json['total_harga']   =  number_format($total_harga->total_harga);
                       $jumlah                = $request->jumlah_jamaah;
                       $json['nomor_pesanan'] = $kode_pemesanan;
                       $json['html']          = view('pemesanan.item',compact('jumlah'))->render();
                       activity_log(get_module_id('pemesanan'), 'Create', $kode_pemesanan, 'Berhasil menyimpan data !');
                   }
                   else
                   {
                       $json['status'] = 'failed';
                       $json['msg']    = 'Data gagal disimpan ,terjadi kesalahan pada database !';
                       activity_log(get_module_id('pemesanan'), 'Create', $kode_pemesanan, 'Data gagal disimpan, terjadi kesalahan pada database ! ');
                   }

            }

              return response()->json($json);
      }

      public function open_jamaah()
      {
            $returnHTML   = view('pemesanan.diplay_jamaah')->render();
            $json['html'] = $returnHTML;

            return response()->json($json);
      }

      public function list_data_jamaah(Request $request)
      {
              $page = $request->page;
              $per_page = 10;
             if ($page != 1) $start = ($page-1) * $per_page;
             else $start=0;

              $totalData = Formulir::with('user','kabupaten_kota')
                            ->when($request->description, function($query) use ($request)
                            {
                                      return $query->where('nama_lengkap','like','%'.$request->description.'%');
                            })
                            ->whereNull('deleted_at')
                            ->count();

                            $data = Formulir::with('user','kabupaten_kota')
                                            ->when($request->description, function($query) use ($request)
                                            {
                                                return $query->where('nama_lengkap','like','%'.$request->description.'%');
                                            })
                                             ->whereNull('deleted_at')
                                             ->offset($start)
                                             ->limit($per_page)
                                             ->orderBy('created_at','DESC')
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
              $returnHTML   = view('pemesanan.jamaah_list',['data' => $data,'array' => $array])->render();
              return response()->json(array('success' => true, 'html'=>$returnHTML,'numPage'=>$numPage,'numitem' => $count) );
      }

      public function ambil_data_jamaah(Request $request)
      {
          $count = Formulir::where('kode_registrasi',$request->id)->count();
          if($count==1)
          {
              $row  = Formulir::where('kode_registrasi',$request->id)->first();
              $count_item = PemesananDetail::where('nomor_pesanan',$request->nomor_pesanan)
                                             ->where('kode_registrasi',$row->kode_registrasi)
                                             ->count();
              if($count_item==0)
              {
                  $item = autonumber_transaction_line('pemesanan_paket_item','item','D','nomor_pesanan',$request->nomor_pesanan);
                  $insert = PemesananDetail::create([
                                                      'nomor_pesanan'  => $request->nomor_pesanan,
                                                      'item'           => $item,
                                                      'kode_registrasi'=> $row->kode_registrasi,
                                                      'kode_paket'     => $request->paket,
                                                      'harga'          => $request->harga,
                                                      'created_by'     => Auth::user()->id
                                                   ]);
                  if($insert)
                  {
                      $json['status'] = 'success';
                      $json['msg']    = 'Data jamaah telah berhasil ditambahkan !';
                  }
                  else
                  {
                    $json['status'] = 'failed';
                    $json['msg']    = 'Data gagal disimpan, terjadi kesalahan pada database !';
                  }
              }
              else
              {
                  $json['status'] = 'failed';
                  $json['msg']    = 'Data gagal disimpan, Data dengan kode '.$row->kode_registrasi.' telah tersedia pada database !';
              }



          }

          return response()->json($json);
      }

      public function list_data_item(Request $request)
      {
              $count        = PemesananDetail::with('jamaah')
                                           ->where('nomor_pesanan',$request->nomor_pesanan)
                                           ->count();

              $item         = PemesananDetail::with('jamaah')
                                               ->where('nomor_pesanan',$request->nomor_pesanan)
                                               ->get();

          $rows = $request->jumlah + 1;

          $returnHTML   = view('pemesanan.item_list',compact('item','count','rows'))->render();
          $json['html'] = $returnHTML;

          return response()->json($json);
      }

      public function delete_item(Request $request)
      {
          $validasi = Pembayaran::where('nomor_pesanan',$request->nomor_pesanan)->count();
          if($validasi==0)
          {
                  $count = PemesananDetail::where('nomor_pesanan',$request->nomor_pesanan)
                                            ->where('item',$request->item)
                                            ->where('kode_registrasi',$request->kode_registrasi)
                                            ->count();
                  if($count==1)
                  {
                      $delete = PemesananDetail::where('nomor_pesanan',$request->nomor_pesanan)
                                                ->where('item',$request->item)
                                                ->where('kode_registrasi',$request->kode_registrasi)
                                                ->delete();
                      if($delete)
                      {
                        $sum_detail = Pemesanan::select(DB::raw('COUNT(*)AS jumlah,SUM(harga)AS total_harga'))
                                                             ->where('nomor_pesanan',$request->nomor_pesanan)
                                                             ->first();
                        $update = Pemesanan::where('nomor_pesanan',$request->nomor_pesanan)
                                             ->update([
                                               'jumlah'        => $sum_detail->jumlah,
                                               'total_harga'   => $sum_detail->total_harga,
                                               'sisa_bayar'    => $sum_detail->total_harga,
                                               'updated_by'    => Auth::user()->id
                                             ]);
                          $row = Pemesanan::where('nomor_pesanan',$request->nomor_pesanan)->first();
                          $json['jumlah']      = $row->jumlah;
                          $json['total_harga'] = number_format($row->total_harga);
                          $json['status'] = 'success';
                          $json['msg']    = 'Data berhasil dihapus !';
                      }
                      else
                      {
                         $json['status']  = 'failed';
                         $json['msg']     = 'Data gagal dihapus, terjadi kesalahan pada database !';
                      }
                  }
                  else
                  {
                      $json['status'] = 'failed';
                      $json['msg']    = 'Data gagal dihapus, data tidak ditemukan !';
                  }
          }
          else
          {
              $json['status'] = 'failed';
              $json['msg']    = 'Data gagal diproses, system mendeteksi telah adanya transaksi pembayaran dengan nomor pemesanan '.$request->nomor_pesanan.' !';
          }



          return response()->json($json);
      }

      public function edit(Request $request)
      {
          $data = Pemesanan::where('nomor_pesanan',$request->id);
          if($data->count()==1)
          {
              $row = $data->first();
              $bulan      = Bulan::all();
              $kategori   = KategoriPerjalanan::all();
              $paket      = PaketPerjalanan::where('bulan',$row->bulan_paket)->get();
              $sub_paket  = HargaPaket::with('kapasitas')->where('kode_paket',$row->kode_paket)->get();
              $returnHTML = view('pemesanan.edit',compact('bulan','kategori','paket','sub_paket','row'))->render();
              return response()->json(['status' => 'success','html' => $returnHTML]);

          }
      }

      public function update(Request $request)
      {
           $validasi = Pembayaran::where('nomor_pesanan',$request->nomor_pesanan)->count();
           if($validasi==0)
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

                           $kode_pemesanan = autonumber_transaction('pemesanan_paket','nomor_pesanan','ID','tgl_pesan');
                           $insert = Pemesanan::where('nomor_pesanan',$request->nomor_pesanan)
                                               ->update([
                                                       'tgl_pesan'         => date('Y-m-d',strtotime($request->tgl_pemesanan)),
                                                       'jam_pesan'         => \Carbon\Carbon::now()->toDateTimeString(),
                                                       'jenis_pemesanan'   => $request->jenis,
                                                       'kode_kategori'     => $request->kategori,
                                                       'kode_paket'        => $request->paket,
                                                       'bulan_paket'       => $request->bulan,
                                                       'kode_harga'        => $request->sub_paket,
                                                       'harga'             => $request->harga,
                                                       'jumlah'            => $request->jumlah_jamaah,
                                                       'lama_perjalanan'   => $request->lama_perjalanan,
                                                       'tgl_berangkat'     => date('Y-m-d',strtotime($request->tgl_berangkat)),
                                                       'tgl_kembali'       => date('Y-m-d',strtotime($request->tgl_kembali)),
                                                       'berangkat_dari'    => $request->berangkat_dari,
                                                       'status_pembayaran' => 'Open',
                                                       'keterangan'        => $request->keterangan,
                                                       'updated_by'        => auth::user()->id
                                                     ]);
                           if($insert)
                           {
                              $total_harga = Pemesanan::select(DB::raw('SUM(harga*jumlah)AS total_harga'))
                                                                   ->where('nomor_pesanan',$request->nomor_pesanan)
                                                                   ->first();
                              $update = Pemesanan::where('nomor_pesanan',$request->nomor_pesanan)
                                                   ->update([
                                                     'total_harga'   => $total_harga->total_harga,
                                                     'sudah_dibayar' => 0,
                                                     'sisa_bayar'    => $total_harga->total_harga,
                                                     'updated_by'    => Auth::user()->id
                                                   ]);

                              $json['status']        = 'success';
                              $json['total_harga']   =  number_format($total_harga->total_harga);
                              $jumlah                = $request->jumlah_jamaah;
                              $json['nomor_pesanan'] = $request->nomor_pesanan;
                              $json['html']          = view('pemesanan.item',compact('jumlah'))->render();
                              activity_log(get_module_id('pemesanan'), 'Create', $request->nomor_pesanan, 'Berhasil menyimpan data !');
                          }
                          else
                          {
                              $json['status'] = 'failed';
                              $json['msg']    = 'Data gagal disimpan ,terjadi kesalahan pada database !';
                              activity_log(get_module_id('pemesanan'), 'Create', $request->nomor_pesanan, 'Data gagal disimpan, terjadi kesalahan pada database ! ');
                          }

                   }
           }
           else
           {
              $json['status'] = 'failed';
              $json['msg']    = 'Data Gagal Diupdate !, system mendeteksi adanya transaksi pembayaran dengan nomor pesanan '.$request->nomor_pesanan.' !';
           }



              return response()->json($json);
      }

      public function delete(Request $request)
      {
           $validasi = Pembayaran::where('nomor_pesanan',$request->id)->count();
           if($validasi==0)
           {
                 $count = Pemesanan::where('nomor_pesanan',$request->id)->count();
                 if($count==1)
                 {
                     $delete = Pemesanan::where('nomor_pesanan',$request->id)->delete();
                     if($delete)
                     {
                       $delete_item = PemesananDetail::where('nomor_pesanan',$request->id)->delete();
                       $json['status'] = 'success';
                       $json['msg']    = 'Data telah berhasil dihapus !';
                       activity_log(get_module_id('pemesanan'), 'Drop', $request->id, 'Data telah berhasil dihapus !');

                     }
                     else
                     {
                         $json['status'] = 'failed';
                         $json['msg']    = 'Data gagal dihapus,terjadi kesalahan pada database !';
                         activity_log(get_module_id('pemesanan'), 'Drop', $request->id, 'Data gagal dihapus,terjadi kesalahan pada database !');
                     }
                 }
                 else
                 {
                     $json['status'] = 'failed';
                     $json['msg']    = 'Data gagal dihapus, data tidak ditemukan !';
                     activity_log(get_module_id('pemesanan'), 'Drop', $request->id, 'Data gagal dihapus, data tidak ditemukan !');
                 }

           }
           else
           {
              $json['status'] = 'failed';
              $json['msg']    = 'Data gagal dihapus, system mendeteksi adanya transaksi dengan nomor pesanan '.$request->id.' ';
           }

           return response()->json($json);
      }

      public function pdf_pesanan(Request $request)
      {
        $count = Pemesanan::where('nomor_pesanan',$request->nomor_pesanan)->count();
        if($count==1)
        {
            $json['status'] = 'success';
            $json['link']   = 'pemesanan/export_pesanan/'.$request->nomor_pesanan.'';
            $json['link_2'] = 'pemesanan/export_jamaah/'.$request->nomor_pesanan.'';
        }
        else
        {
            $json['status'] = 'failed';
            $json['msg']    = 'Data tidak ditemukan, silahkan cek kembali !';
        }


        return response()->json($json);
      }

      public function export_pesanan($nomor_pesanan)
      {

        $row  =  Pemesanan::with('bulan','kategori','paket','sub_paket','user')->where('nomor_pesanan',$nomor_pesanan)->first();
        $item =  PemesananDetail::with('jamaah')->where('nomor_pesanan',$nomor_pesanan)->get();
        $data['row']  = $row;
        $data['item'] = $item;
        $pdf = PDF::loadView('pemesanan.pdf_pemesanan', $data,[], ['format' => 'A4-P']);
        return $pdf->stream('pemesanan_'.$nomor_pesanan.'.pdf');
      }

      public function export_jamaah($nomor_pesanan)
      {

        $item =  PemesananDetail::with('jamaah')->where('nomor_pesanan',$nomor_pesanan)->get();
        $data['item'] = $item;
        $pdf = PDF::loadView('pemesanan.pdf_jamaah', $data,[], ['format' => 'A4-P']);
        return $pdf->stream('jamaah_'.$nomor_pesanan.'.pdf');
      }

      function form_search()
      {
         $kategori     = KategoriPerjalanan::all();
         $returnHTML   = view('pemesanan.form_search',compact('kategori'))->render();
         $json['html'] = $returnHTML;
         return response()->json($json);
      }
}
