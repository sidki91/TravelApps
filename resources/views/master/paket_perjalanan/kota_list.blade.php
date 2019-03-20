<script type="text/javascript">
function ambil_data(id)
{
    $.ajax({
        type: 'POST',
        url: 'paket_perjalanan/ambil_data_kota',
        data: {
                '_token'       : $('input[name=_token]').val(),
                'id'           : id
              },
        success: function(data)
        {
            if(data.status=='success')
            {
                 bootbox.hideAll()
                $("#tujuan_kota").val(data.tujuan_kota);
                $("#kode_kota").val(data.kode_kota);
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
    <th>Kode Kota</th>
    <th>Nama Kota</th>
    <th>Nama Negara</th>
    <th>Action</th>
  </thead>
  <tbody>
    @foreach($data as $number => $row)
    <tr>
      <td>{{++$number}}</td>
      <td>{{$row->kode_kota}}</td>
      <td>{{$row->nama_kota}}</td>
      <td>{{$row->negara->nama_negara}}</td>
      <td><button class="btn btn-xs btn-success btn-icon btn-default btn-outline" type="button" onclick="ambil_data('{{$row->kode_kota}}')">
          <i class="fa fa-pencil" aria-hidden="true">
          </i>
        </button></td>
    </tr>
    @endforeach
  </tbody>
</table>
