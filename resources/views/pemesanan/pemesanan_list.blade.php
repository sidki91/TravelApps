<script type="text/javascript">
function export_pdf(kode_registrasi)
{
      $.ajax({
          type: 'POST',
          url: 'formulir/export_pdf',
          data:
          {
              '_token': $('input[name=_token]').val(),
              'kode'  : kode_registrasi
          },

          success: function(data)
          {
              if(data.status=='success')
              {
                    window.open(data.link);
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
    <th style="text-align:center">No</th>
    <th>ID Pesanan</th>
    <th>Tgl Pesan</th>
    <th>Nama Paket</th>
    <th>Bulan Paket</th>
    <th style="text-align:center">Jumlah</th>
    <th>Total Harga</th>
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
      <td>{{$row->nomor_pesanan}}</td>
      <td>{{date('d M Y',strtotime($row->tgl_pesan))}}</td>
      <td>{{$row->kode_paket}}</td>
      <td>{{$row->bulan_paket}}</td>
      <td style="text-align:center">{{$row->jumlah}}</td>
      <td>{{number_format($row->total_harga)}}</td>
      <td>
        <button class="btn btn-primary btn-sm" onclick="edit('{{$row->nomor_pesanan}}')" title="Edit">
        <i class="fa fa-pencil"></i>
        </button>
        <button class="btn btn-youtube btn-sm" onclick="delete_data('{{$row->nomor_pesanan}}')" title="Delete">
        <i class="fa  fa-trash-o"></i>
        </button>
        <button class="btn btn-success btn-sm" onclick="export_pemesanan('{{$row->nomor_pesanan}}')" title="PDF"><i class="fa fa-file-pdf-o"></i> </button>
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
