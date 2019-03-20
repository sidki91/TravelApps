<script type="text/javascript">
function save_item(key)
{
    $.ajax({
        type: 'POST',
        url: 'paket_perjalanan/update_harga_paket',
        data: {
                '_token'          : $('input[name=_token]').val(),
                'kode_kapasitas'  : $("#kode_kapasitas_"+key).val(),
                'harga'           : $("#harga_val_"+key).val(),
                'kode_paket'      : $("#kode_paket").val()
              },
        success: function(data)
        {
          if(data.status=='success')
          {
              bootbox.alert(data.msg);
          }
          else
          {
              bootbox.alert(data.msg);
          }
        },
    });
}

function money_format(key)
{
        $("#harga_"+key).maskMoney({
        prefix:'', allowNegative: true, thousands:',', decimal:',', affixesStay: false}
                                    );
        var angka = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#harga_"+key).val()))));
        $("#harga_val_"+key).val(angka);
        var harga = $("#harga_val_"+key).val();
}
</script>
<table class="table table-striped">
  <thead>
    <th>Kapasitas Ruangan</th>
    <th>Harga</th>
    <th>Action</th>
  </thead>
  <tbody>
    <?php
    $no     = 1;
    $urutan = 1;
     ?>
    @foreach($kapasitas as $number => $row)
    <?php
    $harga = \App\Models\HargaPaket::where('kode_kapasitas',$row->kode_kapasitas)
                                    ->where('kode_paket',$kode_paket)
                                    ->value('harga');
    ?>
    <tr>
      <td>
        <input type="text" class="form-control" value="{{$row->deskripsi}}" readonly style="background-color:white"/>
        <input type="hidden" id="kode_kapasitas_{{$urutan}}" value="{{$row->kode_kapasitas}}"/>
      </td>
      <td>
        <input type="text" class="form-control" id="harga_{{$urutan}}" value="{{number_format($harga)}}" onkeyup="money_format('{{$no}}')"/>
        <input type="hidden" id="harga_val_{{$urutan}}" value="{{$harga}}"/>
      </td>
      <td><button class="btn btn-xs btn-success btn-icon btn-default btn-outline" type="button" onclick="save_item('{{$no}}')">
          <i class="fa fa-save" aria-hidden="true">
          </i>
        </button></td>
    </tr>
    <?php
    $no++;
    $urutan++;
    ?>
    @endforeach
  </tbody>
</table>
