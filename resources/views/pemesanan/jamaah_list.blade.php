<script type="text/javascript">
function ambil_data(id)
{
    $.ajax({
        type: 'POST',
        url: 'pemesanan/ambil_data_jamaah',
        data: {
                '_token'       : $('input[name=_token]').val(),
                'id'           : id,
                'nomor_pesanan': $("#nomor_pesanan").val(),
                'paket'        : $("#paket").val(),
                'harga'        : $("#harga_paket_val").val()

              },
        success: function(data)
        {
            if(data.status=='success')
            {

                 bootbox.hideAll();
                 list_data_item();
                 //swal("Info System", data.msg, "success");
            }
            else
            {
                bootbox.alert(data.msg);
            }
        },
    });
}
</script>
<div style="margin-top: -49px;"></div>
<table class="table table-striped">
  <thead>
    <th>No</th>
    <th>Kode Registrasi</th>
    <th>Tgl Registrasi</th>
    <th>Nama</th>
    <th>JK</th>
    <th>Kota/Kab</th>
    <th>Action</th>
  </thead>
  <tbody>
    @foreach($data as $number => $row)
    <tr>
      <td>{{++$number}}</td>
      <td>{{$row->kode_registrasi}}</td>
      <td>{{date('d M Y',strtotime($row->tgl_registrasi))}}</td>
      <td>{{$row->nama_lengkap}}</td>
      <td>{{$row->jk}}</td>
      <td>{{$row->kabupaten_kota->nama}}</td>
      <td><button class="btn btn-xs btn-success btn-icon btn-default btn-outline" type="button" onclick="ambil_data('{{$row->kode_registrasi}}')">
          <i class="fa fa-pencil" aria-hidden="true">
          </i>
        </button></td>
    </tr>
    @endforeach
  </tbody>
</table>
