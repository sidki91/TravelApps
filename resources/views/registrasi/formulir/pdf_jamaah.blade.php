
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <link rel="stylesheet" href="{{ ltrim(public_path('css/pdf.css'), '/') }}" />
    <style>
  .border_bottom {
      border-bottom: 1px dashed #e4eaec;
      padding: 2;
      margin-bottom: 5px;
  }
  </style>

    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
  <div id="left">

  <img  src="{{ URL::asset('public/images/logo_company.png')}}" width="150" height="80"/>

  </div>
  @foreach($item as $row)
  <?php
      $keluarga_dihubungi = \App\Models\KeluargaHubungi::where('kode_registrasi',$row->kode_registrasi)->get();
  ?>
    <div class="clear"></div>
      <div style="text-align:center">
      <h3>FORMULIR PEDAFTARAN JAMAAH</h3>
    </div>
    <div id="head_1">
      <table>
        <tr>
          <thead>
            <th>Kode Registrasi</th>
            <th>:</th>
            <th>{{$row->kode_registrasi}}</th>
          </thead>
        </tr>
      </table>
    </div>
    <div class="border_bottom"></div>
    <div id="left">
      <!--<img width="20" height="20" src="{{ URL::asset('public/images/custom/clipboard_check.png')}}"/>-->

    <table class="zui-table zui-table-vertical" style="font-size: 9px !important;width:99%">
      <thead>
        <tbody>
          <tr>
            <thead>
              <th colspan="2">Identitas Diri</th>
            </thead>
            </tr>
            <tbody>
            <tr>
              <td>Nama Lengkap</td>
              <td>{{$row->nama_lengkap}}</td>
          </tr>
          <tr>
            <td>Nama Ayah Kandung</td>
            <td>{{$row->nama_ayah_kandung}}</td>
          </tr>
          <tr>
            <td>Tempat Lahir</td>
            <td>{{$row->tempat_lahir}}</td>
          </tr>
          <tr>
            <td>Tanggal Lahir</td>
            <td>{{date('d-m-Y',strtotime($row->tgl_lahir))}}</td>
          </tr>
          <tr>
            <td>Umur</td>
            <td>{{get_umur(date('d-m-Y',strtotime($row->tgl_lahir)))}}</td>
          </tr>
          <tr>
            <td>Golongan Darah</td>
            <td>{{$row->gol_darah}}</td>
          </tr>
          <tr>
            <td>Jenis Kelamin</td>
              <td>{{$row->jk}}</td>
          </tr>
          <tr>
            <td>Pendidikan</td>
            <td>{{$row->get_pendidikan->deskripsi}}</td>
          </tr>
          <tr>
            <td>Status</td>
              <td>{{$row->status}}</td>
          </tr>
          @if($row->status=='Menikah')
          <tr>
            <td>Tanggal Pernikahan</td>
            <td>{{date('d-m-Y',strtotime($row->tgl_pernikahan))}}</td>
          </tr>
          @endif
        </tbody>
      </table>
  </div>
  <div style="padding-left:300px"></div>
  <div id="right">
    <table class="zui-table zui-table-vertical" style="font-size: 9px !important;" width="100%">
    <thead>
      <tr>
        <thead>
          <th colspan="2">Alamat</th>
        </thead>
      </tr>
      <tbody>
        <tr>
            <td>Alamat Tempat Tinggal</td>
            <td>{{$row->alamat}}</td>
        </tr>
        <tr>
          <td>Nomor Rumah</td>
          <td>{{$row->tempat_lahir}}</td>
        </tr>
        <tr>
          <td>RT/RW</td>
          <td>{{$row->rt}}/{{$row->rw}}</td>
        </tr>
        <tr>
          <td>Kelurahan</td>
          <td>{{$row->get_kelurahan->nama}}</td>
        </tr>
        <tr>
          <td>Kecamatan</td>
          <td>{{$row->get_kecamatan->nama}}</td>
        </tr>
        <tr>
          <td>Kabupaten/Kota</td>
          <td>{{$row->kabupaten_kota->nama}}</td>
        </tr>
        <tr>
          <td>Provinsi</td>
          <td>{{$row->get_provinsi->nama}}</td>
        </tr>
        <tr>
          <td>Kode Pos</td>
          <td>{{$row->kode_pos}}</td>
        </tr>
        <tr>
          <td>Telepon</td>
          <td>{{$row->telepon}}</td>
        </tr>
          <tr>
          <td>Handphone</td>
          <td>{{$row->hp}}</td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="clear"></div>
  <p style="margin-top:-5px"></p>
  <div id="left_full">
    <table class="zui-table zui-table-vertical" style="font-size: 9px !important;width:100%">
    <thead>
      <tr>
        <thead>
          <tr>
            <th>Pekerjaan</th>
            <th>Nama Instansi</th>
            <th>Alamat Instansi</th>
            <th>Telepon Instansi</th>
          </tr>
        </thead>
      </tr>
      <tbody>
        <tr>
          <td>{{$row->get_pekerjaan->deskripsi}}</td>
          <td>{{$row->nama_instansi}}</td>
          <td>{{$row->alamat_instansi}}</td>
          <td>{{$row->telepon_instansi}}</td>
        </tr>
      </tbody>
    </table>
</div>
<p style="margin-top:-5px"></p>
<div id="left_full">
  <table class="zui-table zui-table-vertical" style="font-size: 9px !important;width:100%">
  <thead>
    <tr>
      <thead>
        <tr>
          <th>Nomor Pasport</th>
          <th>Tanggal Dikeluarkan</th>
          <th>Tempat Dikeluarkan</th>
          <th>Masa Berlaku Hingga</th>
        </tr>
      </thead>
    </tr>
    <tbody>
      <tr>
        <td>{{$row->nomor_pasport}}</td>
        <td>{{date('d-m-Y',strtotime($row->tgl_dikeluarkan))}}</td>
        <td>{{$row->tempat_dikeluarkan}}</td>
        <td>{{$row->masa_berlaku}}</td>
      </tr>
    </tbody>
  </table>
</div>
<p style="margin-top:-5px"></p>
<div id="left_full">
  <table class="zui-table zui-table-vertical" style="font-size: 9px !important;width:100%">
  <thead>
    <tr>
      <thead>
        <tr>
          <th>Penyakit Yg Diderita</th>
          <th>Pengalama Haji</th>
          <th>Jumlah / Tahun</th>
          <th>Pengalaman Umroh</th>
          <th>Jumlah / Tahun</th>
        </tr>
      </thead>
    </tr>
    <tbody>
      <tr>
        <td>{{$row->penyakit_derita}}</td>
        <td>{{$row->pengalaman_haji}}</td>
        <td>{{number_format($row->jumlah_haji)}} Kali / Tahun {{$row->terakhir_tahun_haji}}</td>
        <td>{{$row->pengalaman_umroh}}</td>
        <td>{{number_format($row->jumlah_umroh)}} Kali / Tahun {{$row->terakhir_tahun_umroh}}</td>
      </tr>
    </tbody>
  </table>
</div>
<p style="margin-top:-5px"></p>
<div id="left_full">
  <font style="font-size:12px;font-weight:bold">Keluarga Yg Dapat Dihubungi </font>
  <p style="margin-top:-5px"></p>

  <table class="zui-table zui-table-vertical" style="font-size: 9px !important;width:100%">
  <thead>
    <tr>
      <thead>
        <tr>
          <th>Nama Keluarga</th>
          <th>Alamat</th>
          <th>Nomor Telepon</th>
        </tr>
      </thead>
    </tr>
    <tbody>
      @foreach($keluarga_dihubungi as $row_detail)
      <tr>
        <td>{{$row_detail->nama}}</td>
        <td>{{$row_detail->alamat}}</td>
        <td>{{$row_detail->telp}}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
<div class="clear"></div>
<p style="margin-bottom:12px"></p>

<div id="left">
  <img class="img-polaroid"  src="{{ URL::asset('public/images/3x4.png')}}"/>


</div>
<div id="right">
<table>
  <tr>
    <thead>
      <th style="width:30%">Calon Jamaah</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
      <th>Petugas</th>
    </thead>
    <tbody>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><hr class="new4"></hr></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><hr class="new4"></hr></td>
      </tr>
    </tbody>
  </tr>
</table>
</div>
<p style="margin-bottom:42px"></p>


  @endforeach

  </body>
</html>
