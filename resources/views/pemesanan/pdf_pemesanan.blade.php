
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
    <div class="clear"></div>
      <div style="text-align:center">
      <h3>Form Pemesanan Paket Perjalanan </h3>
    </div>
    <div id="head_1">
      <table>
        <tr>
          <thead>
            <tr>
              <th>No Pemesanan</th>
              <th>:</th>
              <th style="text-align:left">{{$row->nomor_pesanan}}</th>
            </tr>
          </thead>
        </tr>
      </table>
    </div>
    <div class="border_bottom"></div>
    <div id="left">
    <table class="zui-table zui-table-vertical" style="font-size: 9px !important;width:99%;border-spacing: 5px; border-collapse: separate">
      <thead>
        <tr>
          <td>Peket Bulan</td>
          <td>{{$row->bulan->bulan_name}}</td>
        </tr>
        <tr>
          <td>Tanggal Pemesanan</td>
          <td>{{date('d M Y',strtotime($row->tgl_pesan))}}</td>
        </tr>
        <tr>
          <td>Jam Pemesanan</td>
          <td>{{date('H:i:s A',strtotime($row->jam_pesan))}}</td>
        </tr>
        <tr>
          <td>Jenis</td>
          <td>{{$row->jenis_pemesanan}}</td>
        </tr>
        <tr>
          <td>Kategori</td>
          <td>{{$row->kategori->deskripsi}}</td>
        </tr>
        <tr>
          <td>Paket</td>
          <td>{{$row->paket->nama_paket}}</td>
        </tr>
        <tr>
          <td>Lama Perjalanan</td>
          <td>{{$row->lama_perjalanan}} Hari</td>
        </tr>
        <tr>
          <td>Sub Paket</td>
          <td>{{\App\Models\Kapasitas::where('kode_kapasitas',$row->sub_paket->kode_kapasitas)->value('deskripsi')}}</td>
        </tr>


      </thead>
    </table>
  </div>
  <div id="right">
    <table class="zui-table zui-table-vertical" style="font-size: 9px !important;width:99%;border-spacing: 5px; border-collapse: separate">
      <thead>

        <tr>
          <td>Tanggal Berangkat</td>
          <td>{{date('d M Y',strtotime($row->tgl_berangkat))}}</td>
        </tr>
        <tr>
          <td>Tanggal Kembali</td>
          <td>{{date('d M Y',strtotime($row->tgl_kembali))}}</td>
        </tr>
        <tr>
          <td>Berangkat Dari</td>
          <td>{{$row->berangkat_dari}}</td>
        </tr>
        <tr>
          <td>Keterangan</td>
          <td>{{$row->keterangan}}</td>
        </tr>
        <tr>
          <td>Harga Paket/Per orang</td>
          <td>{{number_format($row->harga)}}</td>
        </tr>
        <tr>
          <td>Jumlah Jamaah</td>
          <td>{{number_format($row->jumlah)}}</td>
        </tr>
        <tr>
          <td>Total Harga</td>
          <td>{{number_format($row->total_harga)}}</td>
        </tr>
        <tr>
          <td>Opertor</td>
          <td>{{$row->user->name}}</td>
        </tr>
      </thead>
    </table>
  </div>
    <div class="clear"></div>
      <h4>Data Jamaah </h4>
      <div id="left_full">
        <table class="zui-table zui-table-vertical" style="font-size: 9px !important;width:100%">
        <thead>
          <tr>
            <thead>
              <tr>
                <th style="width:10px;text-align:center">No</th>
                <th style="width:120px">Kode Registrasi</th>
                <th>Nama Jamaah</th>
                <th>Jk</th>
                <th>HP</th>
                <th>Kota/Kab</th>
              </tr>
            </thead>
          </tr>
          <tbody>
            @foreach($item as $key =>$row_item)
            <tr>
              <td style="text-align:center">{{++$key}}</td>
              <td>{{$row_item->jamaah->kode_registrasi}}</td>
              <td>{{$row_item->jamaah->nama_lengkap}}</td>
              <td>{{$row_item->jamaah->jk}}</td>
              <td>{{$row_item->jamaah->hp}}</td>
              <td>{{\App\Models\Kabupaten::where('id_kab',$row_item->jamaah->kabupaten)->value('nama')}}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>


  </body>
</html>
