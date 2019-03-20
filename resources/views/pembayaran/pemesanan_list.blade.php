<script type="text/javascript">
function add(key)
{
    $.ajax({
        type: 'POST',
        url: 'pembayaran/add_pemesanan',
        data: {
                '_token'       : $('input[name=_token]').val(),
                'id'           : key
              },
        success: function(data)
        {
            if(data.status=='success')
            {
                bootbox.hideAll();
                $("#nomor_pesanan").val(data.nomor_pesanan);
                $("#total_tagihan").val(data.total_tagihan);
                $("#sudah_dibayar").val(data.sudah_dibayar);
                $("#sisa_tagihan").val(data.sisa_tagihan);
                $("#sisa_tagihan_val").val(data.sisa_tagihan_val);
                $("#jumlah_bayar").focus();




            }
            else
            {
                bootbox.alert(data.msg)
            }
        },
    });
}
</script>
<div style="margin-top: -37px;"></div>

<table class="table table-striped" >
  <thead>
    <th>No</th>
    <th>ID pesanan</th>
    <th>Tgl Pesan</th>
    <th>Nama Paket</th>
    <th>Keterangan</th>
    <th>Jumlah</th>
    <th>Total Tagihan</th>
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
      <td style="text-align:center">{{$no}}</td>
      <td>{{$row->nomor_pesanan}}</td>
      <td>{{date('d M Y',strtotime($row->tgl_pesan))}}</td>
      <td>{{$row->paket->nama_paket}}</td>
      <td>{{$row->keterangan}}</td>
      <td style="text-align:center">{{$row->jumlah}}</td>
      <td>{{number_format($row->sisa_bayar)}}</td>
      <td>
        <button class="btn btn-primary btn-sm" onclick="add('{{$row->nomor_pesanan}}')" title="Edit">
        <i class="fa fa-pencil"></i>
        </button>
        </td>
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
