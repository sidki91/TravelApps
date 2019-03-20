<div style="margin-top: -37px;"></div>

<table class="table table-striped"  >
  <thead>
    <th  style="text-align:center">No</th>
    <th>No Pembayaran</th>
    <th>Tgl Bayar</th>
    <th>Nomor Pemesanan</th>
    <th>Total Tagihan</th>
    <th>Jumlah Bayar</th>
    <th>Sisa Bayar</th>
    <th>Action</th>
  </thead>
  <tbody>
    <?php
    $bates=$array['perpage'];
    $klik=$array['page'];
    $klik1=$klik-1;
    if ($klik=='1')
    {
    $no = 1;
    }
    else
    {
    $no = ($bates * $klik1)+1;
    }

    ?>
    @if($array['count']>=1)
    @foreach($data as $key => $row)


    <tr>
      <td style="text-align:center" >{{$no}}</td>
      <td>{{$row->no_pembayaran}}</td>
      <td>{{date('d M Y',strtotime($row->tgl_bayar))}}</td>
      <td>{{$row->nomor_pesanan}}</td>
      <td>{{number_format($row->total_tagihan)}}</td>
      <td>{{number_format($row->jumlah_bayar)}}</td>
      <td>{{number_format($row->sisa_bayar)}}</td>
      <td>
        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
    <div class="btn-group" role="group">
      <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Tools
      </button>
      <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
        @if($row->status=='')
        <a class="dropdown-item" style="cursor:pointer" onclick="delete_data('{{$row->no_pembayaran}}','{{$row->nomor_pesanan}}')">Delete</a>
        @endif
        <a class="dropdown-item" style="cursor:pointer" onclick="export_pembayaran('{{$row->no_pembayaran}}')">Pdf</a>
      </div>
    </div>
  </div>  </td>
    </tr>
<?php $no++; ?>
    @endforeach
    @else
    <tr>
      <td colspan="8">
        <div class="alert alert-danger alert-dismissable">
          <button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
        Data is not available in the database, please check again.
      </div>
    </td>
    </tr>
    @endif
  </tbody>
</table>
