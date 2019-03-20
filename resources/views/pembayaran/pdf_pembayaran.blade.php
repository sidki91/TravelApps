
<html lang="en" dir="ltr">
  <head>

    <style>
    body{
       font: normal 12px Arial, sans-serif;
    }

  .border_bottom {
      border-bottom: 1px dashed #e4eaec;
      padding: 2;
      margin-bottom: 5px;
  }
  .zui-table {
  border: solid 1px #DDEEEE;
  border-collapse: collapse;
  border-spacing: 0;
  font: normal 11px Arial, sans-serif;
 padding: 2px;
  }
  .zui-table thead th {
      background-color: #DDEFEF;
      border: solid 1px #DDEEEE;
      color: #000;
      padding: 10px;
      text-align: left;
  }
  .zui-table tbody td {
      border: solid 1px #DDEEEE;
      color: #333;
      padding: 6px;
      text-shadow: 1px 1px 1px #fff;
  }
  .zui-table-vertical tbody td {
      border-top: none;
      border-bottom: none;
  }
  #right {
    margin-left: 250px;
    /* Change this to whatever the width of your left column is*/
  }
  </style>

    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <div class="clear"></div>

      <div style="text-align:center">
        <h3>Kwitansi Pembayaran </h3>
    </div>
    <div class="border_bottom"></div>
    <div id="left">
    <table class="zui-table zui-table-vertical" style="font-size: 8px !important;width:99%;border-spacing: 5px; border-collapse: separate">
      <thead>
        <tr>
          <td>Nomor Pembayaran</td>
          <td>:</td>
          <td>{{$row->no_pembayaran}}</td>
        </tr>
        <tr>
          <td>Tanggal Pembayaran</td>
          <td>:</td>
          <td>{{date('d M Y',strtotime($row->tgl_bayar))}}</td>
        </tr>
        <tr>
          <td>Jenis Pembayaran</td>
          <td>:</td>
          <td>{{$row->jenis_pembayaran}}</td>
        </tr>
        <tr>
          <td>Nomor Pesanan</td>
          <td>:</td>
          <td>{{$row->nomor_pesanan}}</td>
        </tr>
        <tr>
          <td>Keterangan</td>
          <td>:</td>
          @if($row->keterangan=='')
          <td>-</td>
          @else
          <td>{{$row->keterangan}}</td>
          @endif
        </tr>
        <tr>
          <td>Total Tagihan</td>
          <td>:</td>
          <td>{{number_format($row->total_tagihan)}}</td>
        </tr>
        <tr>
          <td>Jumlah Bayar</td>
          <td>:</td>
          <td>{{number_format($row->jumlah_bayar)}}</td>
        </tr>
        <tr>
          <td>Sisa Bayar</td>
          <td>:</td>
          <td>{{number_format($row->sisa_bayar)}}</td>
        </tr>
        <tr>
          <td>Kembali</td>
          <td>:</td>
          <td>{{number_format($row->kembali)}}</td>
        </tr>
        <tr>
          <td>Status</td>
          <td>:</td>
          @if($row->status=='')
          <td>-</td>
          @else
          <td>{{$row->status}}</td>
          @endif
        </tr>
      </thead>
    </table>
  </div>
  <div align="right">

  </div>
  <div id="right">
  <table>
    <tr>
      <thead>
        <tr>
          <th colspan="9"></th>
        </tr>
        <tr>
          <th colspan="9" style="text-align:right;padding-right:-50px">Tangerang,{{date('d M Y')}}</th>

        </tr>
        <tr>
        <th style="width:30%">Penyetor</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>Petugas</th>
      </tr>
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





  </body>
</html>
