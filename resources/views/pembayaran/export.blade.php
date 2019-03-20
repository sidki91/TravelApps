
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
      <h3>Laporan Pembayaran </h3>
    </div>
    <div class="border_bottom"></div>

  <div class="clear"></div>
    <div id="left_full">
      <table class="zui-table zui-table-vertical" style="font-size: 9px !important;width:100%">
      <thead>
        <tr>
          <thead>
            <tr>
              <th style="width:10px;text-align:center">No</th>
              <th>No Pembayaran</th>
              <th>Tgl Bayar</th>
              <th>Jenis</th>
              <th>No Pesanan</th>
              <th>Total Tagihan</th>
              <th>Sisa Bayar</th>
              <th>Keterangan</th>
              <th>Status</th>
              <th>Jumlah Bayar</th>
            </tr>
          </thead>
        </tr>
        <tbody>
          @foreach($row as $number => $pembayaran_item)
          <tr>
            <td style="text-align:center">{{++$number}}</td>
            <td>{{$pembayaran_item->no_pembayaran}}</td>
            <td>{{date('d M Y',strtotime($pembayaran_item->tgl_bayar))}}</td>
            <td>{{$pembayaran_item->jenis_pembayaran}}</td>
            <td>{{$pembayaran_item->nomor_pesanan}}</td>
            <td>{{number_format($pembayaran_item->total_tagihan)}}</td>
            <td>{{number_format($pembayaran_item->sisa_bayar)}}</td>
            <td>{{$pembayaran_item->keterangan}}</td>
            @if($pembayaran_item->status=='')
            <td>Belum Lunas</td>
            @else
            <td>Sudah Lunas</td>
            @endif
            <td>{{number_format($pembayaran_item->jumlah_bayar)}}</td>
          </tr>
          @endforeach
          <tr>
            <th colspan="9">Total</th>
            <th>{{number_format($total->jumlah_bayar)}}</th>
          </tr>
        </tbody>
      </table>
    </div>


  </body>
</html>
