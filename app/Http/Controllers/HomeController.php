<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Bulan;
use App\Models\Formulir;
use App\Models\Pemesanan;
use App\Models\Pembayaran;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bulan = Bulan::all();
        return view('home',compact('bulan'));
    }

    public function chart(Request $request)
    {
        $tahun        = $request->tahun;
        $bulan        = $request->bulan;

        $jumlah_jamaah = Formulir::whereNull('deleted_at')
                                   ->whereYear('tgl_registrasi',$request->tahun)
                                   ->whereMonth('tgl_registrasi',$request->bulan)
                                   ->count();
        $jumlah_pemesanan = Pemesanan::whereNull('deleted_at')
                                       ->whereYear('tgl_pesan',$request->tahun)
                                       ->whereMonth('tgl_pesan',$request->bulan)
                                       ->count();

        $lunas           = Pemesanan::whereNull('deleted_at')
                                       ->whereYear('tgl_pesan',$request->tahun)
                                       ->whereMonth('tgl_pesan',$request->bulan)
                                       ->where('status_pembayaran','Closed')
                                       ->count();
        $belum_lunas           = Pemesanan::whereNull('deleted_at')
                                      ->whereYear('tgl_pesan',$request->tahun)
                                      ->whereMonth('tgl_pesan',$request->bulan)
                                      ->where('status_pembayaran','Open')
                                      ->count();
        $chart_pemesanan = Pemesanan::select(DB::raw("IFNULL((
                                                      SELECT
                                                      SUM(total_harga)AS total
                                                      FROM  pemesanan_paket
                                                      WHERE
                                                      YEAR(tgl_pesan) ='".$request->tahun."'
                                                      AND MONTH(tgl_pesan)='01'
                                                      AND deleted_at IS NULL  ),0)
                                                      AS Januari,
                                                      IFNULL((
                                                      SELECT
                                                      SUM(total_harga)AS total
                                                      FROM  pemesanan_paket
                                                      WHERE
                                                      YEAR(tgl_pesan) ='".$request->tahun."'
                                                      AND MONTH(tgl_pesan)='02'
                                                      AND deleted_at IS NULL  ),0)
                                                      AS Februari,
                                                      IFNULL((
                                                      SELECT
                                                      SUM(total_harga)AS total
                                                      FROM  pemesanan_paket
                                                      WHERE
                                                      YEAR(tgl_pesan) ='".$request->tahun."'
                                                      AND MONTH(tgl_pesan)='03'
                                                      AND deleted_at IS NULL  ),0)
                                                      AS Maret,
                                                      IFNULL((
                                                      SELECT
                                                      SUM(total_harga)AS total
                                                      FROM  pemesanan_paket
                                                      WHERE
                                                      YEAR(tgl_pesan) ='".$request->tahun."'
                                                      AND MONTH(tgl_pesan)='04'
                                                      AND deleted_at IS NULL  ),0)
                                                      AS April,
                                                      IFNULL((
                                                      SELECT
                                                      SUM(total_harga)AS total
                                                      FROM  pemesanan_paket
                                                      WHERE
                                                      YEAR(tgl_pesan) ='".$request->tahun."'
                                                      AND MONTH(tgl_pesan)='05'
                                                      AND deleted_at IS NULL  ),0)
                                                      AS Mei,
                                                      IFNULL((
                                                      SELECT
                                                      SUM(total_harga)AS total
                                                      FROM  pemesanan_paket
                                                      WHERE
                                                      YEAR(tgl_pesan) ='".$request->tahun."'
                                                      AND MONTH(tgl_pesan)='06'
                                                      AND deleted_at IS NULL  ),0)
                                                      AS Juni,
                                                      IFNULL((
                                                      SELECT
                                                      SUM(total_harga)AS total
                                                      FROM  pemesanan_paket
                                                      WHERE
                                                      YEAR(tgl_pesan) ='".$request->tahun."'
                                                      AND MONTH(tgl_pesan)='07'
                                                      AND deleted_at IS NULL  ),0)
                                                      AS Juli,
                                                      IFNULL((
                                                      SELECT
                                                      SUM(total_harga)AS total
                                                      FROM  pemesanan_paket
                                                      WHERE
                                                      YEAR(tgl_pesan) ='".$request->tahun."'
                                                      AND MONTH(tgl_pesan)='08'
                                                      AND deleted_at IS NULL  ),0)
                                                      AS Agustus,
                                                      IFNULL((
                                                      SELECT
                                                      SUM(total_harga)AS total
                                                      FROM  pemesanan_paket
                                                      WHERE
                                                      YEAR(tgl_pesan) ='".$request->tahun."'
                                                      AND MONTH(tgl_pesan)='09'
                                                      AND deleted_at IS NULL  ),0)
                                                      AS September,
                                                      IFNULL((
                                                      SELECT
                                                      SUM(total_harga)AS total
                                                      FROM  pemesanan_paket
                                                      WHERE
                                                      YEAR(tgl_pesan) ='".$request->tahun."'
                                                      AND MONTH(tgl_pesan)='10'
                                                      AND deleted_at IS NULL  ),0)
                                                      AS Oktober,
                                                      IFNULL((
                                                      SELECT
                                                      SUM(total_harga)AS total
                                                      FROM  pemesanan_paket
                                                      WHERE
                                                      YEAR(tgl_pesan) ='".$request->tahun."'
                                                      AND MONTH(tgl_pesan)='11'
                                                      AND deleted_at IS NULL  ),0)
                                                      AS November,
                                                      IFNULL((
                                                      SELECT
                                                      SUM(total_harga)AS total
                                                      FROM  pemesanan_paket
                                                      WHERE
                                                      YEAR(tgl_pesan) ='".$request->tahun."'
                                                      AND MONTH(tgl_pesan)='12'
                                                      AND deleted_at IS NULL  ),0)
                                                      AS Desember
                                                      "))
                                                      ->whereYear('tgl_pesan',$request->tahun)
                                                      ->groupBy(DB::raw('YEAR(tgl_pesan)'))
                                             ->get();

        foreach($chart_pemesanan as $row_item)
        {
                $datas['grafik_pemesanan'][]=(float)$row_item['Januari'];
                $datas['grafik_pemesanan'][]=(float)$row_item['Februari'];
                $datas['grafik_pemesanan'][]=(float)$row_item['Maret'];
                $datas['grafik_pemesanan'][]=(float)$row_item['April'];
                $datas['grafik_pemesanan'][]=(float)$row_item['Mei'];
                $datas['grafik_pemesanan'][]=(float)$row_item['Juni'];
                $datas['grafik_pemesanan'][]=(float)$row_item['Juli'];
                $datas['grafik_pemesanan'][]=(float)$row_item['Agustus'];
                $datas['grafik_pemesanan'][]=(float)$row_item['September'];
                $datas['grafik_pemesanan'][]=(float)$row_item['Oktober'];
                $datas['grafik_pemesanan'][]=(float)$row_item['November'];
                $datas['grafik_pemesanan'][]=(float)$row_item['Desember'];
        }


        $chart_pembayaran = Pemesanan::select(DB::raw("IFNULL((
                                                      SELECT
                                                      SUM(sudah_dibayar)AS sudah_dibayar
                                                      FROM  pemesanan_paket
                                                      WHERE
                                                      YEAR(tgl_pesan) ='".$request->tahun."'
                                                      AND MONTH(tgl_pesan)='01'
                                                      AND deleted_at IS NULL  ),0)
                                                      AS Januari,
                                                      IFNULL((
                                                      SELECT
                                                      SUM(sudah_dibayar)AS sudah_dibayar
                                                      FROM  pemesanan_paket
                                                      WHERE
                                                      YEAR(tgl_pesan) ='".$request->tahun."'
                                                      AND MONTH(tgl_pesan)='02'
                                                      AND deleted_at IS NULL  ),0)
                                                      AS Februari,
                                                      IFNULL((
                                                      SELECT
                                                      SUM(sudah_dibayar)AS sudah_dibayar
                                                      FROM  pemesanan_paket
                                                      WHERE
                                                      YEAR(tgl_pesan) ='".$request->tahun."'
                                                      AND MONTH(tgl_pesan)='03'
                                                      AND deleted_at IS NULL  ),0)
                                                      AS Maret,
                                                      IFNULL((
                                                      SELECT
                                                      SUM(sudah_dibayar)AS sudah_dibayar
                                                      FROM  pemesanan_paket
                                                      WHERE
                                                      YEAR(tgl_pesan) ='".$request->tahun."'
                                                      AND MONTH(tgl_pesan)='04'
                                                      AND deleted_at IS NULL  ),0)
                                                      AS April,
                                                      IFNULL((
                                                      SELECT
                                                      SUM(sudah_dibayar)AS sudah_dibayar
                                                      FROM  pemesanan_paket
                                                      WHERE
                                                      YEAR(tgl_pesan) ='".$request->tahun."'
                                                      AND MONTH(tgl_pesan)='05'
                                                      AND deleted_at IS NULL  ),0)
                                                      AS Mei,
                                                      IFNULL((
                                                      SELECT
                                                      SUM(sudah_dibayar)AS sudah_dibayar
                                                      FROM  pemesanan_paket
                                                      WHERE
                                                      YEAR(tgl_pesan) ='".$request->tahun."'
                                                      AND MONTH(tgl_pesan)='06'
                                                      AND deleted_at IS NULL  ),0)
                                                      AS Juni,
                                                      IFNULL((
                                                      SELECT
                                                      SUM(sudah_dibayar)AS sudah_dibayar
                                                      FROM  pemesanan_paket
                                                      WHERE
                                                      YEAR(tgl_pesan) ='".$request->tahun."'
                                                      AND MONTH(tgl_pesan)='07'
                                                      AND deleted_at IS NULL  ),0)
                                                      AS Juli,
                                                      IFNULL((
                                                      SELECT
                                                      SUM(sudah_dibayar)AS sudah_dibayar
                                                      FROM  pemesanan_paket
                                                      WHERE
                                                      YEAR(tgl_pesan) ='".$request->tahun."'
                                                      AND MONTH(tgl_pesan)='08'
                                                      AND deleted_at IS NULL  ),0)
                                                      AS Agustus,
                                                      IFNULL((
                                                      SELECT
                                                      SUM(sudah_dibayar)AS sudah_dibayar
                                                      FROM  pemesanan_paket
                                                      WHERE
                                                      YEAR(tgl_pesan) ='".$request->tahun."'
                                                      AND MONTH(tgl_pesan)='09'
                                                      AND deleted_at IS NULL  ),0)
                                                      AS September,
                                                      IFNULL((
                                                      SELECT
                                                      SUM(sudah_dibayar)AS sudah_dibayar
                                                      FROM  pemesanan_paket
                                                      WHERE
                                                      YEAR(tgl_pesan) ='".$request->tahun."'
                                                      AND MONTH(tgl_pesan)='10'
                                                      AND deleted_at IS NULL  ),0)
                                                      AS Oktober,
                                                      IFNULL((
                                                      SELECT
                                                      SUM(sudah_dibayar)AS sudah_dibayar
                                                      FROM  pemesanan_paket
                                                      WHERE
                                                      YEAR(tgl_pesan) ='".$request->tahun."'
                                                      AND MONTH(tgl_pesan)='11'
                                                      AND deleted_at IS NULL  ),0)
                                                      AS November,
                                                      IFNULL((
                                                      SELECT
                                                      SUM(sudah_dibayar)AS sudah_dibayar
                                                      FROM  pemesanan_paket
                                                      WHERE
                                                      YEAR(tgl_pesan) ='".$request->tahun."'
                                                      AND MONTH(tgl_pesan)='12'
                                                      AND deleted_at IS NULL  ),0)
                                                      AS Desember
                                                      "))
                                                      ->whereYear('tgl_pesan',$request->tahun)
                                                      ->groupBy(DB::raw('YEAR(tgl_pesan)'))
                                             ->get();

        foreach($chart_pembayaran as $row_pembayaran)
        {
                $datas['grafik_pembayaran'][]=(float)$row_pembayaran['Januari'];
                $datas['grafik_pembayaran'][]=(float)$row_pembayaran['Februari'];
                $datas['grafik_pembayaran'][]=(float)$row_pembayaran['Maret'];
                $datas['grafik_pembayaran'][]=(float)$row_pembayaran['April'];
                $datas['grafik_pembayaran'][]=(float)$row_pembayaran['Mei'];
                $datas['grafik_pembayaran'][]=(float)$row_pembayaran['Juni'];
                $datas['grafik_pembayaran'][]=(float)$row_pembayaran['Juli'];
                $datas['grafik_pembayaran'][]=(float)$row_pembayaran['Agustus'];
                $datas['grafik_pembayaran'][]=(float)$row_pembayaran['September'];
                $datas['grafik_pembayaran'][]=(float)$row_pembayaran['Oktober'];
                $datas['grafik_pembayaran'][]=(float)$row_pembayaran['November'];
                $datas['grafik_pembayaran'][]=(float)$row_pembayaran['Desember'];
        }


        $chart_selisih = Pemesanan::select(DB::raw("IFNULL((
                                                      SELECT
                                                      (SUM(total_harga)-(SUM(sudah_dibayar)-SUM(kembali)))AS sisa_bayar
                                                      FROM  pemesanan_paket
                                                      WHERE
                                                      YEAR(tgl_pesan) ='".$request->tahun."'
                                                      AND MONTH(tgl_pesan)='01'
                                                      AND deleted_at IS NULL  ),0)
                                                      AS Januari,
                                                      IFNULL((
                                                      SELECT
                                                      (SUM(total_harga)-(SUM(sudah_dibayar)-SUM(kembali)))AS sisa_bayar
                                                      FROM  pemesanan_paket
                                                      WHERE
                                                      YEAR(tgl_pesan) ='".$request->tahun."'
                                                      AND MONTH(tgl_pesan)='02'
                                                      AND deleted_at IS NULL  ),0)
                                                      AS Februari,
                                                      IFNULL((
                                                      SELECT
                                                      (SUM(total_harga)-(SUM(sudah_dibayar)-SUM(kembali)))AS sisa_bayar
                                                      FROM  pemesanan_paket
                                                      WHERE
                                                      YEAR(tgl_pesan) ='".$request->tahun."'
                                                      AND MONTH(tgl_pesan)='03'
                                                      AND deleted_at IS NULL  ),0)
                                                      AS Maret,
                                                      IFNULL((
                                                      SELECT
                                                      (SUM(total_harga)-(SUM(sudah_dibayar)-SUM(kembali)))AS sisa_bayar
                                                      FROM  pemesanan_paket
                                                      WHERE
                                                      YEAR(tgl_pesan) ='".$request->tahun."'
                                                      AND MONTH(tgl_pesan)='04'
                                                      AND deleted_at IS NULL  ),0)
                                                      AS April,
                                                      IFNULL((
                                                      SELECT
                                                      (SUM(total_harga)-(SUM(sudah_dibayar)-SUM(kembali)))AS sisa_bayar
                                                      FROM  pemesanan_paket
                                                      WHERE
                                                      YEAR(tgl_pesan) ='".$request->tahun."'
                                                      AND MONTH(tgl_pesan)='05'
                                                      AND deleted_at IS NULL  ),0)
                                                      AS Mei,
                                                      IFNULL((
                                                      SELECT
                                                      (SUM(total_harga)-(SUM(sudah_dibayar)-SUM(kembali)))AS sisa_bayar
                                                      FROM  pemesanan_paket
                                                      WHERE
                                                      YEAR(tgl_pesan) ='".$request->tahun."'
                                                      AND MONTH(tgl_pesan)='06'
                                                      AND deleted_at IS NULL  ),0)
                                                      AS Juni,
                                                      IFNULL((
                                                      SELECT
                                                      (SUM(total_harga)-(SUM(sudah_dibayar)-SUM(kembali)))AS sisa_bayar
                                                      FROM  pemesanan_paket
                                                      WHERE
                                                      YEAR(tgl_pesan) ='".$request->tahun."'
                                                      AND MONTH(tgl_pesan)='07'
                                                      AND deleted_at IS NULL  ),0)
                                                      AS Juli,
                                                      IFNULL((
                                                      SELECT
                                                      (SUM(total_harga)-(SUM(sudah_dibayar)-SUM(kembali)))AS sisa_bayar
                                                      FROM  pemesanan_paket
                                                      WHERE
                                                      YEAR(tgl_pesan) ='".$request->tahun."'
                                                      AND MONTH(tgl_pesan)='08'
                                                      AND deleted_at IS NULL  ),0)
                                                      AS Agustus,
                                                      IFNULL((
                                                      SELECT
                                                      (SUM(total_harga)-(SUM(sudah_dibayar)-SUM(kembali)))AS sisa_bayar
                                                      FROM  pemesanan_paket
                                                      WHERE
                                                      YEAR(tgl_pesan) ='".$request->tahun."'
                                                      AND MONTH(tgl_pesan)='09'
                                                      AND deleted_at IS NULL  ),0)
                                                      AS September,
                                                      IFNULL((
                                                      SELECT
                                                      (SUM(total_harga)-(SUM(sudah_dibayar)-SUM(kembali)))AS sisa_bayar
                                                      FROM  pemesanan_paket
                                                      WHERE
                                                      YEAR(tgl_pesan) ='".$request->tahun."'
                                                      AND MONTH(tgl_pesan)='10'
                                                      AND deleted_at IS NULL  ),0)
                                                      AS Oktober,
                                                      IFNULL((
                                                      SELECT
                                                      (SUM(total_harga)-(SUM(sudah_dibayar)-SUM(kembali)))AS sisa_bayar
                                                      FROM  pemesanan_paket
                                                      WHERE
                                                      YEAR(tgl_pesan) ='".$request->tahun."'
                                                      AND MONTH(tgl_pesan)='11'
                                                      AND deleted_at IS NULL  ),0)
                                                      AS November,
                                                      IFNULL((
                                                      SELECT
                                                     (SUM(total_harga)-(SUM(sudah_dibayar)-SUM(kembali)))AS sisa_bayar
                                                      FROM  pemesanan_paket
                                                      WHERE
                                                      YEAR(tgl_pesan) ='".$request->tahun."'
                                                      AND MONTH(tgl_pesan)='12'
                                                      AND deleted_at IS NULL  ),0)
                                                      AS Desember
                                                      "))
                                                      ->whereYear('tgl_pesan',$request->tahun)
                                                      ->groupBy(DB::raw('YEAR(tgl_pesan)'))
                                             ->get();

        foreach($chart_selisih as $row_selisih)
        {
                $datas['grafik_selisih'][]=(float)$row_selisih['Januari'];
                $datas['grafik_selisih'][]=(float)$row_selisih['Februari'];
                $datas['grafik_selisih'][]=(float)$row_selisih['Maret'];
                $datas['grafik_selisih'][]=(float)$row_selisih['April'];
                $datas['grafik_selisih'][]=(float)$row_selisih['Mei'];
                $datas['grafik_selisih'][]=(float)$row_selisih['Juni'];
                $datas['grafik_selisih'][]=(float)$row_selisih['Juli'];
                $datas['grafik_selisih'][]=(float)$row_selisih['Agustus'];
                $datas['grafik_selisih'][]=(float)$row_selisih['September'];
                $datas['grafik_selisih'][]=(float)$row_selisih['Oktober'];
                $datas['grafik_selisih'][]=(float)$row_selisih['November'];
                $datas['grafik_selisih'][]=(float)$row_selisih['Desember'];
        }

        $favorite_pesanan = "SELECT a.`kode_paket`,a.`kode_kategori`,COUNT(*)AS jumlah,SUM(total_harga)AS total_harga FROM pemesanan_paket a
                             WHERE deleted_at IS NULL
                             AND YEAR(tgl_pesan) ='".$request->tahun."' AND MONTH(tgl_pesan)='".$request->bulan."'
                             GROUP BY a.`kode_paket`,a.`kode_kategori`";
        $favorite         = DB::select($favorite_pesanan);

        $pemesanan_by_jenis = "SELECT jenis_pemesanan,COUNT(*)AS jumlah FROM pemesanan_paket
                               WHERE deleted_at IS NULL
                               AND YEAR(tgl_pesan) ='".$request->tahun."' AND MONTH(tgl_pesan)='".$request->bulan."'
                               GROUP BY jenis_pemesanan ";
        $jenis              = DB::select($pemesanan_by_jenis);

        $pemesanan_by_kategori = "SELECT a.kode_kategori,b.`deskripsi`,COUNT(*)AS jumlah FROM pemesanan_paket a
                                  LEFT JOIN kategori_perjalanan b ON a.`kode_kategori` = b.`kode_kategori`
                                  WHERE a.deleted_at IS NULL
                                  AND YEAR(tgl_pesan) ='".$request->tahun."' AND MONTH(tgl_pesan)='".$request->bulan."'
                                  GROUP BY a.kode_kategori,b.`deskripsi`";
        $kategori              = DB::select($pemesanan_by_kategori);

        $returnHTML   = view('chart',compact('jumlah_jamaah','jumlah_pemesanan','lunas','belum_lunas','row','datas','tahun','favorite','bulan','jenis','kategori'))->render();
        $json['html'] = $returnHTML;
        return response()->json($json);
    }
}
