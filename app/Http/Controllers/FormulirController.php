<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Paket;
use App\Models\Pendidikan;
use App\Models\Pekerjaan;
use App\Models\Status;
use App\Models\Formulir;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Hubungan;
use App\Models\KeluargaIkut;
use App\Models\KeluargaHubungi;
use App\Models\KategoriPerjalanan;
use App\Models\PaketPerjalanan;
use App\Models\HargaPaket;
use Validator;
use Response;
use View;
use Auth;
use PDF;

class FormulirController extends Controller
{
      public function __construct()
      {
          $this->middleware('auth');
      }

      protected $rules =
      [
          'nama_lengkap'         => 'required|max:100',
          'nama_ayah'            => 'required|max:100',
          'tempat_lahir'         => 'required|max:100',
          'tgl_lahir'            => 'required|max:100',
          'umur'                 => 'required|max:100',
          'gol_darah'            => 'required|max:100',
          'jenis_kelamin'        => 'required|max:100',
          'pendidikan'           => 'required|max:100',
          'keterangan_menikah'   => 'required|max:100',
          'pekerjaan'            => 'required|max:100',
          'nama_instansi'        => 'required|max:100',
          'alamat_instansi'      => 'required|max:100',
          'telp_instansi'        => 'required|max:100',
          'nomor_pasport'        => 'required|max:100',
          'tgl_dikeluarakan'     => 'required|max:100',
          'tempat_dikeluarkan'   => 'required|max:100',
          'masa_berlaku'         => 'required|max:100',
          'alamat'               => 'required',
          'rt'                   => 'required|numeric|min:2',
          'rw'                   => 'required|numeric|min:2',
          'provinsi'             => 'required|max:100',
          'kab_kota'             => 'required|max:100',
          'kecamatan'            => 'required|max:100',
          'kelurahan'            => 'required|max:100',
          'handphone'            => 'required|max:100',


      ];

      public function index()
      {
          if(access_level_user('view','formulir')=='allow')
          {
              activity_log(get_module_id('formulir'), 'View', '', '');
              return view('registrasi/formulir/index');
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
              $tgl_awal  = date('Y-m-d',strtotime($request->tgl_awal));
              $tgl_akhir = date('Y-m-d',strtotime($request->tgl_akhir));

              $totalData = Formulir::with('user','kabupaten_kota')
                            ->when($request->tgl_awal, function($query) use ($request)
                            {
                                      return $query->where('tgl_registrasi','>=',date('Y-m-d',strtotime($request->tgl_awal)));
                            })
                            ->when($request->tgl_akhir, function($query) use ($request)
                            {
                                      return $query->where('tgl_registrasi','<=',date('Y-m-d',strtotime($request->tgl_akhir)));
                            })
                            ->when($request->kode_registrasi, function($query) use ($request)
                            {
                                      return $query->where('kode_registrasi','like','%'.$request->kode_registrasi.'%');
                            })
                            ->when($request->nama_jamaah, function($query) use ($request)
                            {
                                      return $query->where('nama_lengkap','like','%'.$request->nama_jamaah.'%');
                            })
                            ->whereNull('deleted_at')
                            ->count();

                            $data = Formulir::with('user','kabupaten_kota')
                                              ->when($request->tgl_awal, function($query) use ($request)
                                              {
                                                        return $query->where('tgl_registrasi','>=',date('Y-m-d',strtotime($request->tgl_awal)));
                                              })
                                              ->when($request->tgl_akhir, function($query) use ($request)
                                              {
                                                        return $query->where('tgl_registrasi','<=',date('Y-m-d',strtotime($request->tgl_akhir)));
                                              })
                                              ->when($request->kode_registrasi, function($query) use ($request)
                                              {
                                                        return $query->where('kode_registrasi','like','%'.$request->kode_registrasi.'%');
                                              })
                                              ->when($request->nama_jamaah, function($query) use ($request)
                                              {
                                                        return $query->where('nama_lengkap','like','%'.$request->nama_jamaah.'%');
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
                  $json['html'] = view('registrasi.formulir.formulir_list',['data' => $data,'array' => $array])->render();
                  $json['numPage'] = $numPage;
                  $json['numitem'] = $count;
                  $json['output']  = 'HTML';
              }
              else
              {
                  $json['html'] = view('registrasi.formulir.formulir_list',['data' => $data,'array' => $array])->render();
                  $json['numPage'] = $numPage;
                  $json['numitem'] = $count;
                  $json['output']  = 'PDF';
                  if(!empty($request->kode_registrasi))
                  {
                      $json['link']   = "formulir/export_jamaah_by_kode/$tgl_awal/$tgl_akhir/$request->kode_registrasi";
                  }
                  else if(!empty($request->nama_jamaah))
                  {
                    $name='name';
                    $json['link']   = "formulir/export_jamaah_by_nama/$tgl_awal/$tgl_akhir/$name/$request->nama_jamaah";
                  }
                  else
                  {
                      $json['link']   = 'formulir/export_all/'.$tgl_awal.'/'.$tgl_akhir.'';
                  }

              }
                return response()->json($json);
      }
      function export($kode)
      {


         $count =  Formulir::with('user','get_provinsi','kabupaten_kota','get_kecamatan','get_kelurahan','get_pendidikan','get_kategori_perjalanan','get_paket_perjalanan')
                           ->where('kode_registrasi',$kode)
                           ->count();
        if($count>=1)
        {
            $row =  Formulir::with('user','get_provinsi','kabupaten_kota','get_kecamatan','get_kelurahan','get_pendidikan','get_kategori_perjalanan','get_paket_perjalanan')
                              ->where('kode_registrasi',$kode)
                              ->get();
            $data['item'] = $row;
            $pdf = PDF::loadView('registrasi.formulir.pdf_jamaah', $data,[], ['format' => 'A4-P']);
            return $pdf->stream('repot_jamaah.pdf');
        }
        else
        {
            dd("Maaf Data tidak ditemukan !,Silahkan cek kembali spesifikasi pencarian ");
        }

      }
      function export_jamaah_by_kode($tgl_awal,$tgl_akhir,$kode)
      {


         $count =  Formulir::with('user','get_provinsi','kabupaten_kota','get_kecamatan','get_kelurahan','get_pendidikan','get_kategori_perjalanan','get_paket_perjalanan')
                           ->where('tgl_registrasi','>=',date('Y-m-d',strtotime($tgl_awal)))
                           ->where('tgl_registrasi','<=',date('Y-m-d',strtotime($tgl_akhir)))
                           ->where('kode_registrasi','like','%'.$kode.'%')
                           ->count();
        if($count>=1)
        {
            $row =  Formulir::with('user','get_provinsi','kabupaten_kota','get_kecamatan','get_kelurahan','get_pendidikan','get_kategori_perjalanan','get_paket_perjalanan')
                              ->where('tgl_registrasi','>=',date('Y-m-d',strtotime($tgl_awal)))
                              ->where('tgl_registrasi','<=',date('Y-m-d',strtotime($tgl_akhir)))
                              ->where('kode_registrasi','like','%'.$kode.'%')
                              ->get();
            $data['item'] = $row;
            $pdf = PDF::loadView('registrasi.formulir.pdf_jamaah', $data,[], ['format' => 'A4-P']);
            return $pdf->stream('repot_jamaah.pdf');
        }
        else
        {
            dd("Maaf Data tidak ditemukan !,Silahkan cek kembali spesifikasi pencarian ");
        }

      }

      function export_jamaah_by_nama($tgl_awal,$tgl_akhir,$filter,$nama)
      {


         $count =  Formulir::with('user','get_provinsi','kabupaten_kota','get_kecamatan','get_kelurahan','get_pendidikan','get_kategori_perjalanan','get_paket_perjalanan')
                           ->where('tgl_registrasi','>=',date('Y-m-d',strtotime($tgl_awal)))
                           ->where('tgl_registrasi','<=',date('Y-m-d',strtotime($tgl_akhir)))
                           ->where('nama_lengkap','like','%'.$nama.'%')
                           ->count();
        if($count>=1)
        {
            $row =  Formulir::with('user','get_provinsi','kabupaten_kota','get_kecamatan','get_kelurahan','get_pendidikan','get_kategori_perjalanan','get_paket_perjalanan')
                              ->where('tgl_registrasi','>=',date('Y-m-d',strtotime($tgl_awal)))
                              ->where('tgl_registrasi','<=',date('Y-m-d',strtotime($tgl_akhir)))
                              ->where('nama_lengkap','like','%'.$nama.'%')
                              ->get();
            $data['item'] = $row;
            $pdf = PDF::loadView('registrasi.formulir.pdf_jamaah', $data,[], ['format' => 'A4-P']);
            return $pdf->stream('repot_jamaah.pdf');
        }
        else
        {
            dd("Maaf Data tidak ditemukan !,Silahkan cek kembali spesifikasi pencarian ");
        }

      }
      function export_all($tgl_awal,$tgl_akhir)
      {


         $count =  Formulir::with('user','get_provinsi','kabupaten_kota','get_kecamatan','get_kelurahan','get_pendidikan','get_kategori_perjalanan','get_paket_perjalanan')
                           ->where('tgl_registrasi','>=',date('Y-m-d',strtotime($tgl_awal)))
                           ->where('tgl_registrasi','<=',date('Y-m-d',strtotime($tgl_akhir)))
                           ->count();
        if($count>=1)
        {
            $row =  Formulir::with('user','get_provinsi','kabupaten_kota','get_kecamatan','get_kelurahan','get_pendidikan','get_kategori_perjalanan','get_paket_perjalanan')
                              ->where('tgl_registrasi','>=',date('Y-m-d',strtotime($tgl_awal)))
                              ->where('tgl_registrasi','<=',date('Y-m-d',strtotime($tgl_akhir)))
                              ->get();
            $data['item'] = $row;
            $pdf = PDF::loadView('registrasi.formulir.pdf_jamaah', $data,[], ['format' => 'A4-P']);
            return $pdf->stream('repot_jamaah.pdf');
        }
        else
        {
            dd("Maaf Data tidak ditemukan !,Silahkan cek kembali spesifikasi pencarian ");
        }

      }

      public function add()
      {
          $paket      = Paket::all();
          $pendidikan = Pendidikan::all();
          $pekerjaan  = Pekerjaan::all();
          $status     = Status::all();
          $provinsi   = Provinsi::all();
          $kategori   = KategoriPerjalanan::all();
          #$kode_registrasi = autonumber_transaction('formulir','kode_registrasi','R');
          $returnHTML = view('registrasi.formulir.add',compact('paket','pendidikan','pekerjaan','status','provinsi','hubungan','kategori'))->render();
          return response()->json(['status' => 'success','html' => $returnHTML]);
      }

      public function pilih_paket(Request $request)
      {
          $data       = PaketPerjalanan::where('kode_kategori',$request->kategori)->get();
          $returnHTML = view('registrasi.formulir.paket',compact('data'))->render();
          $json['html'] = $returnHTML;

          return response()->json($json);
      }

      public function pilih_sub_paket(Request $request)
      {
          $row        = HargaPaket::with('kapasitas')
                              ->where('kode_paket',$request->paket)
                              ->get();

          $returnHTML = view('registrasi.formulir.sub_paket',compact('row'))->render();
          $json['html'] = $returnHTML;
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

      public function edit(Request $request)
      {
          $count = Formulir::where('kode_registrasi',$request->id)->count();
          if($count==1)
          {
              $row        = Formulir::where('kode_registrasi',$request->id)->first();
              $pendidikan = Pendidikan::all();
              $pekerjaan  = Pekerjaan::all();
              $status     = Status::all();
              $provinsi   = Provinsi::all();
              $kabupaten_kota  = Kabupaten::where('id_prov',$row->provinsi)->get();
              $kecamatan  = Kecamatan::where('id_kab',$row->kabupaten)->get();
              $kelurahan  = Kelurahan::where('id_kec',$row->kecamatan)->get();
              $kategori   = KategoriPerjalanan::all();
              $paket      = PaketPerjalanan::where('kode_kategori',$row->kategori_perjalanan)->get();
              $sub_paket  = HargaPaket::with('kapasitas')->where('kode_paket',$row->kode_paket)->get();
              #$kode_registrasi = autonumber_transaction('formulir','kode_registrasi','R');
              $returnHTML = view('registrasi.formulir.edit',compact('kategori','paket','sub_paket','pendidikan','pekerjaan','status','provinsi','kabupaten_kota','kecamatan','kelurahan','hubungan','row'))->render();
              return response()->json(['status' => 'success','html' => $returnHTML]);
          }
      }

      public function pengalaman_haji(Request $request)
      {
          if($request->pengalaman_haji=='Sudah')
          {
            if(!empty($request->jumlah_haji) && !empty($request->tahun_haji))
            {
                $jumlah_haji = $request->jumlah_haji;
                $tahun_haji  = $request->tahun_haji;
                $returnHTML = view('registrasi.formulir.pengalaman_haji',compact('jumlah_haji','tahun_haji'))->render();
            }
            else
            {
              $jumlah_haji = '';
              $tahun_haji  = '';
              $returnHTML = view('registrasi.formulir.pengalaman_haji',compact('jumlah_haji','tahun_haji'))->render();

            }

          }
          else
          {
            $returnHTML = '';
          }

          return response()->json(['html' => $returnHTML]);
      }

      public function pengalaman_umroh(Request $request)
      {
          if($request->pengalaman_umroh=='Sudah')
          {
                if(!empty($request->jumlah_umroh) && !empty($request->tahun_umroh))
                {
                    $jumlah_umroh = $request->jumlah_umroh;
                    $tahun_umroh = $request->tahun_umroh;
                    $returnHTML = view('registrasi.formulir.pengalaman_umroh',compact('jumlah_umroh','tahun_umroh'))->render();
                }
                else
                {
                  $jumlah_umroh = '';
                  $tahun_umroh  = '';
                  $returnHTML = view('registrasi.formulir.pengalaman_umroh',compact('jumlah_umroh','tahun_umroh'))->render();

                }

          }
          else
          {
            $returnHTML = '';
          }

          return response()->json(['html' => $returnHTML]);
      }

      public function keterangan_menikah(Request $request)
      {
            if($request->keterangan_menikah=='Menikah')
            {
                $json['status'] = 'success';
            }
            else
            {
               $json['status'] = 'failed';
            }

              return response()->json($json);
      }

      public function change_provinsi(Request $request)
      {
          $row = Kabupaten::where('id_prov',$request->provinsi)->get();
          $returnHTML = view('registrasi.formulir.kab_kota',compact('row'))->render();
          $json['html'] = $returnHTML;
          return response()->json($json);
      }

      public function change_kabupaten_kota(Request $request)
      {
          $row = Kecamatan::where('id_kab',$request->kab_kota)->get();
          $returnHTML = view('registrasi.formulir.Kecamatan',compact('row'))->render();
          $json['html'] = $returnHTML;
          return response()->json($json);
      }

      public function change_kecamatan(Request $request)
      {
          $row = Kelurahan::where('id_kec',$request->kecamatan)->get();
          $returnHTML = view('registrasi.formulir.kelurahan',compact('row'))->render();
          $json['html'] = $returnHTML;
          return response()->json($json);
      }


      public function hitung_umur(Request $request)
      {
          $tgl_lahir = date('d-m-Y',strtotime($request->tgl_Lahir));
          $umur      = get_masehi($tgl_lahir);
          $json['umur'] = $umur;
          return response()->json($json);
      }

      public function save(Request $request)
      {
          $validator = Validator::make(Input::all(),$this->rules);
          if($validator->fails())
          {
              $json['status'] ='error';
              $json['errors'] = $validator->getMessageBag()->toArray();
              return response()->json($json);
          }
          else
          {
             $kode_registrasi = autonumber_transaction('formulir','kode_registrasi','R');
             $insert = Formulir::create([
                                'kode_registrasi'       => $kode_registrasi,
                                'tgl_registrasi'        => \Carbon\Carbon::now(),
                                'nama_lengkap'          => $request->nama_lengkap,
                                'nama_ayah_kandung'     => $request->nama_ayah,
                                'tempat_lahir'          => $request->tempat_lahir,
                                'tgl_lahir'             => date('Y-m-d',strtotime($request->tgl_lahir)),
                                'umur'                  => $request->umur,
                                'gol_darah'             => $request->gol_darah,
                                'jk'                    => $request->jenis_kelamin,
                                'pendidikan'            => $request->pendidikan,
                                'status'                => $request->keterangan_menikah,
                                'tgl_pernikahan'        => date('Y-m-d',strtotime($request->tgl_pernikahan)),
                                'pekerjaan'             => $request->pekerjaan,
                                'nama_instansi'         => $request->nama_instansi,
                                'alamat_instansi'       => $request->alamat_instansi,
                                'telepon_instansi'      => $request->telp_instansi,
                                'nomor_pasport'         => $request->nomor_pasport,
                                'tgl_dikeluarkan'       => date('Y-m-d',strtotime($request->tgl_dikeluarakan)),
                                'tempat_dikeluarkan'    => $request->tempat_dikeluarkan,
                                'masa_berlaku'          => $request->masa_berlaku,
                                'alamat'                => $request->alamat,
                                'rt'                    => $request->rt,
                                'rw'                    => $request->rw,
                                'nomor'                 => $request->nomor_rumah,
                                'provinsi'              => $request->provinsi,
                                'kabupaten'             => $request->kab_kota,
                                'kecamatan'             => $request->kecamatan,
                                'kelurahan'             => $request->kelurahan,
                                'kode_pos'              => $request->kode_pos,
                                'telepon'               => $request->telepon,
                                'hp'                    => $request->handphone,
                                'email'                 => $request->email,
                                'penyakit_derita'       => $request->penyakit_diderita,
                                'pengalaman_haji'       => $request->pengalaman_haji,
                                'jumlah_haji'           => $request->jumlah_haji,
                                'terakhir_tahun_haji'   => $request->tahun_haji,
                                'pengalaman_umroh'      => $request->pengalaman_umroh,
                                'jumlah_umroh'          => $request->jumlah_umroh,
                                'terakhir_tahun_umroh'  => $request->tahun_umroh,
                                'created_by'            => Auth::user()->id

                             ]);
             if($insert)
             {
                 $json['status']          = 'success';
                 $json['kode_registrasi'] = $kode_registrasi;
                 $json['msg']             = 'Data telah berhasil disimpan';
                 activity_log(get_module_id('formulir'), 'Create', $kode_registrasi, 'Berhasil menyimpan data !');
             }
             else
             {
                 $json['status'] = 'failed';
                 $json['msg']    = 'Data gagal disimpan, terjadi kesalahan pada database !';
                 activity_log(get_module_id('formulir'), 'Create',$kode_registrasi, 'Data gagal disimpan, terjadi kesalahan pada database ! ');

             }

          }

          return response()->json($json);
      }

      public function update(Request $request)
      {
          $validator = Validator::make(Input::all(),$this->rules);
          if($validator->fails())
          {
              $json['status'] ='error';
              $json['errors'] = $validator->getMessageBag()->toArray();
              return response()->json($json);
          }
          else
          {
             $update = Formulir::where('kode_registrasi',$request->kode_registrasi)
                                ->update([
                                'tgl_registrasi'    => \Carbon\Carbon::now(),
                                'nama_lengkap'          => $request->nama_lengkap,
                                'nama_ayah_kandung'     => $request->nama_ayah,
                                'tempat_lahir'          => $request->tempat_lahir,
                                'tgl_lahir'             => date('Y-m-d',strtotime($request->tgl_lahir)),
                                'umur'                  => $request->umur,
                                'gol_darah'             => $request->gol_darah,
                                'jk'                    => $request->jenis_kelamin,
                                'pendidikan'            => $request->pendidikan,
                                'status'                => $request->keterangan_menikah,
                                'tgl_pernikahan'        => date('Y-m-d',strtotime($request->tgl_pernikahan)),
                                'pekerjaan'             => $request->pekerjaan,
                                'nama_instansi'         => $request->nama_instansi,
                                'alamat_instansi'       => $request->alamat_instansi,
                                'telepon_instansi'      => $request->telp_instansi,
                                'nomor_pasport'         => $request->nomor_pasport,
                                'tgl_dikeluarkan'       => date('Y-m-d',strtotime($request->tgl_dikeluarakan)),
                                'tempat_dikeluarkan'    => $request->tempat_dikeluarkan,
                                'masa_berlaku'          => $request->masa_berlaku,
                                'alamat'                => $request->alamat,
                                'rt'                    => $request->rt,
                                'rw'                    => $request->rw,
                                'nomor'                 => $request->nomor_rumah,
                                'provinsi'              => $request->provinsi,
                                'kabupaten'             => $request->kab_kota,
                                'kecamatan'             => $request->kecamatan,
                                'kelurahan'             => $request->kelurahan,
                                'kode_pos'              => $request->kode_pos,
                                'telepon'               => $request->telepon,
                                'hp'                    => $request->handphone,
                                'email'                 => $request->email,
                                'penyakit_derita'       => $request->penyakit_diderita,
                                'pengalaman_haji'       => $request->pengalaman_haji,
                                'jumlah_haji'           => $request->jumlah_haji,
                                'terakhir_tahun_haji'   => $request->tahun_haji,
                                'pengalaman_umroh'      => $request->pengalaman_umroh,
                                'jumlah_umroh'          => $request->jumlah_umroh,
                                'terakhir_tahun_umroh'  => $request->tahun_umroh,
                                'updated_by'           => Auth::user()->id

                             ]);
             if($update)
             {
                 $json['status']          = 'success';
                 $json['kode_registrasi'] = $request->kode_registrasi;
                 $json['msg']             = 'Data telah berhasil diubah';
                 $json['jumlah_haji']     = $request->jumlah_haji;
                 $json['tahun_haji']      = $request->tahun_haji;
                 $json['jumlah_umroh']    = $request->jumlah_umroh;
                 $json['tahun_umroh']     = $request->tahun_umroh;
                 $json['action']          = 'update';
                 activity_log(get_module_id('formulir'), 'Create', $request->kode_registrasi, 'Berhasil menyimpan data !');
             }
             else
             {
                 $json['status'] = 'failed';
                 $json['msg']    = 'Data gagal disimpan, terjadi kesalahan pada database !';
                 activity_log(get_module_id('formulir'), 'Create',$request->kode_registrasi, 'Data gagal disimpan, terjadi kesalahan pada database ! ');

             }

          }

          return response()->json($json);
      }
      function info_tambahan()
      {
          $hubungan     = Hubungan::all();
          $returnHTML   = view('registrasi.formulir.info_tambahan',compact('hubungan'))->render();
          $json['html'] = $returnHTML;
          return response()->json($json);
      }

      function add_item(Request $request)
      {
        $hubungan     = Hubungan::all();
        $row          = KeluargaIkut::where('kode_registrasi',$request->kode)->get();
        $jumlah       = KeluargaIkut::where('kode_registrasi',$request->kode)->count();
        $count        = $request->rowCount;
        $returnHTML   = view('registrasi.formulir.add_info_tambahan',compact('hubungan','row','count','jumlah'))->render();
        $json['html'] = $returnHTML;
        return response()->json($json);
      }

      function info_tambahan_2()
      {
          $returnHTML   = view('registrasi.formulir.info_tambahan_2')->render();
          $json['html'] = $returnHTML;
          return response()->json($json);
      }

      function add_item2(Request $request)
      {

        $row          = KeluargaHubungi::where('kode_registrasi',$request->kode)->get();
        $jumlah       = KeluargaHubungi::where('kode_registrasi',$request->kode)->count();
        $count        = $request->rowCount;
        $returnHTML   = view('registrasi.formulir.add_info_tambahan2',compact('row','count','jumlah'))->render();
        $json['html'] = $returnHTML;
        return response()->json($json);
      }

      function list_info_tambahan2(Request $request)
      {
          $count = KeluargaHubungi::where('kode_registrasi',$request->kode)->count();
          if($count>=1)
          {
              $row          = KeluargaHubungi::where('kode_registrasi',$request->kode)->get();
              $returnHTML   = view('registrasi.formulir.list_info_tambahan2',compact('row','count'))->render();
          }
          else
          {

              $returnHTML   = view('registrasi.formulir.info_tambahan_2')->render();
          }

          $json['html']   = $returnHTML;
          return response()->json($json);
      }

      function list_info_tambahan(Request $request)
      {
          $count = KeluargaIkut::where('kode_registrasi',$request->kode)->count();
          if($count>=1)
          {
              $hubungan     = Hubungan::all();
              $row          = KeluargaIkut::where('kode_registrasi',$request->kode)->get();
              $returnHTML   = view('registrasi.formulir.list_info_tambahan',compact('hubungan','row','count'))->render();
          }
          else
          {

            $hubungan     = Hubungan::all();
            $returnHTML   = view('registrasi.formulir.info_tambahan',compact('hubungan'))->render();
          }

          $json['html']   = $returnHTML;
          return response()->json($json);
      }

      function open_form_kel_ikut(Request $request)
      {
          $hubungan     = Hubungan::all();
          $pendidikan   = Pendidikan::all();
          $pekerjaan    = Pekerjaan::all();
          $provinsi     = Provinsi::all();
          $returnHTML   = view('registrasi.formulir.form_keluarga',compact('hubungan','pendidikan','pekerjaan','provinsi'))->render();
          $json['html'] = $returnHTML;

          return response()->json($json);
      }

      function save_keluarga_ikut(Request $request)
      {
          $count = KeluargaIkut::where('kode_registrasi',$request->kode_registrasi)
                                 ->where('nama',$request->nama)
                                 ->count();
          if($count==0)
          {
              $no_item = autonumber_transaction_line('keluarga_ikut','item','K','kode_registrasi',$request->kode_registrasi);
              $insert = KeluargaIkut::create([
                'kode_registrasi' => $request->kode_registrasi,
                'item'            => $no_item,
                'nama'            => $request->nama,
                'kode_hubungan'   => $request->hubungan,
                'tlp'             => $request->tlp,
                'created_by'      => Auth::user()->id
              ]);

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

          }
          else
          {
              $json['status'] = 'failed';
              $json['msg']    = 'Data gagal disimpan , Data Keluarga kode '.$request->kode_registrasi.' telah tersedia pada database !';

          }

          return response()->json($json);
      }

      function update_keluarga_ikut(Request $request)
      {
          $count = KeluargaIkut::where('kode_registrasi',$request->kode_registrasi)
                                 ->where('item',$request->item)
                                 ->count();
          if($count==1)
          {
              $update = KeluargaIkut::where('kode_registrasi',$request->kode_registrasi)
                                     ->where('item',$request->item)
                                     ->update([
                                       'nama'            => $request->nama,
                                       'kode_hubungan'   => $request->hubungan,
                                       'tlp'             => $request->tlp,
                                       'updated_by'      => Auth::user()->id
                                     ]);
             if($update)
             {
                 $json['status'] = 'success';
                 $json['msg']    = 'Data telah berhasil diubah !';
             }
             else
             {
                 $json['status'] = 'failed';
                 $json['msg']    = 'Data gagal diubah, terjadi kesalahan pada database !';
             }
          }
          else
          {
              $json['status'] = 'failed';
              $json['msg']    = 'Data gagal diubah , Data Keluarga kode '.$request->kode_registrasi.' tidak ditemukan !';

          }

          return response()->json($json);
      }

      function delete_keluarga_ikut(Request $request)
      {
          $count = KeluargaIkut::where('kode_registrasi',$request->kode)
                                 ->where('item',$request->item)
                                 ->count();
          if($count==1)
          {
              $delete = KeluargaIkut::where('kode_registrasi',$request->kode)
                                     ->where('item',$request->item)
                                     ->delete();
               if($delete)
               {
                   $json['status'] = 'success';
                   $json['msg']    = 'Data telah berhasil dihapus !';
               }
               else
               {
                   $json['status'] = 'failed';
                   $json['msg']    = 'Data gagal dihapus, terjadi kesalahan pada database !';
               }
          }
          else
          {
              $json['status'] = 'failed';
              $json['msg']    = 'Data gagal dihapus , Data item kosong !';
          }

          return response()->json($json);
      }
      function save_keluarga_dihubungi(Request $request)
      {
            $count = KeluargaHubungi::where('kode_registrasi',$request->kode_registrasi)
                                   ->where('nama',$request->nama)
                                   ->count();
            if($count==0)
            {
                $no_item = autonumber_transaction_line('keluarga_dihubungi','item','P','kode_registrasi',$request->kode_registrasi);
                $insert = KeluargaHubungi::create([
                  'kode_registrasi' => $request->kode_registrasi,
                  'item'            => $no_item,
                  'nama'            => $request->nama,
                  'alamat'          => $request->alamat,
                  'telp'            => $request->tlp,
                  'created_by'      => Auth::user()->id
                ]);

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

            }
            else
            {
                $json['status'] = 'failed';
                $json['msg']    = 'Data gagal disimpan , Data Keluarga kode '.$request->kode_registrasi.' telah tersedia pada database !';

            }

            return response()->json($json);
      }

      function update_keluarga_dihubungi(Request $request)
      {
              $count = KeluargaHubungi::where('kode_registrasi',$request->kode_registrasi)
                                      ->where('item',$request->item)
                                      ->count();
              if($count==1)
              {
                    $update = KeluargaHubungi::where('kode_registrasi',$request->kode_registrasi)
                                            ->where('item',$request->item)
                                            ->update([
                                              'nama'            => $request->nama,
                                              'alamat'          => $request->alamat,
                                              'telp'            => $request->tlp,
                                            ]);
                    if($update)
                    {
                        $json['status'] = 'success';
                        $json['msg']    = 'Data telah berhasil diubah !';
                    }
                    else
                    {
                        $json['status'] = 'failed';
                        $json['msg']    = 'Data gagal diubah, terjadi kesalahan pada database !';
                    }
             }
             else
             {
                 $json['status'] = 'failed';
                 $json['msg']    = 'Data gagal diubah , Data item kosong !';
             }

            return response()->json($json);
      }
      function delete_keluarga_dihubungi(Request $request)
      {
              $count = KeluargaHubungi::where('kode_registrasi',$request->kode)
                                      ->where('item',$request->item)
                                      ->count();
              if($count==1)
              {
                    $delete = KeluargaHubungi::where('kode_registrasi',$request->kode)
                                            ->where('item',$request->item)
                                            ->delete();
                    if($delete)
                    {
                        $json['status'] = 'success';
                        $json['msg']    = 'Data telah berhasil dihapus !';
                    }
                    else
                    {
                        $json['status'] = 'failed';
                        $json['msg']    = 'Data gagal dihapus, terjadi kesalahan pada database !';
                    }
               }
               else
               {
                   $json['status'] = 'failed';
                   $json['msg']    = 'Data gagal dihapus , Data item kosong !';
               }

               return response()->json($json);
     }

     public function delete(Request $request)
     {
        $count = Formulir::where('kode_registrasi',$request->id)->count();
        if($count==1)
        {
              $delete = Formulir::where('kode_registrasi',$request->id)->delete();
              if($delete)
              {
                  $delete_keluarga_ikut      = KeluargaIkut::where('kode_registrasi',$request->id)->delete();
                  $delete_keluarga_dihubungi = KeluargaHubungi::where('kode_registrasi',$request->id)->delete();
                  $json['status'] = 'success';
                  $json['msg']    = 'Data telah berhasil dihapus !';
                  activity_log(get_module_id('formulir'), 'Delete', $request->id, 'Berhasil menghapus data !');

              }
              else
              {
                  $json['status'] = 'failed';
                  $json['msg']    = 'Data gagal dihapus, terjadi kesalahan pada database !';
                  activity_log(get_module_id('formulir'), 'Delete', $request->id, 'terjadi kesalahan pada database !');

              }
         }
         else
         {
             $json['status'] = 'failed';
             $json['msg']    = 'Data gagal dihapus , Data tidak ditemukan !';
             activity_log(get_module_id('formulir'), 'Delete', $request->id, 'Data tidak ditemukan !');

         }

         return response()->json($json);
     }



 function export_pdf(Request $request)
 {
    $count = Formulir::where('kode_registrasi',$request->kode)->count();
    if($count==1)
    {
        $json['status'] = 'success';
        $json['link']   = 'formulir/export/'.$request->kode.'';
    }
    else
    {
        $json['status'] = 'failed';$
        $json['msg']    = 'Data tidak ditemukan, silahkan cek kembali !';
    }


    return response()->json($json);
 }



 function form_search()
 {
    $returnHTML   = view('registrasi.formulir.form_search')->render();
    $json['html'] = $returnHTML;
    return response()->json($json);
 }


}
