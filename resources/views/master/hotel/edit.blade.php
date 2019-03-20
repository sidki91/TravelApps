<style>
.select2-selection__rendered {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 12px;
}
</style>
<script>
$("#kode_negara").select2();
$("#kode_kota").select2();
function pilih_kota()
{
    var kode_negara = $("#kode_negara").val();
    $.ajax({
        type: 'POST',
        url: 'hotel/pilih_kota',
        data: {
                '_token'      : $('input[name=_token]').val(),
                'kode_negara' : kode_negara,
                'kode_hotel'  : $("#kode_hotel").val()
              },
        success: function(data)
        {
            $("#select_kota").html(data.html);
        },
    });
}
$(document).ready(function()
 {
    pilih_kota();
 });
</script>
<form class="form-horizontal" role="form">
<input type="hidden" id="kode_hotel" value="{{$row->kode_hotel}}"/>
<div class="form-group">
      <label for="inputEmail3" class="col-sm-3 control-label">Negara</label>
      <div class="col-sm-9">
        <select class="form-control" id="kode_negara" style="width:212px" onchange="pilih_kota()">
          <option value="">Pilih</option>
        @foreach($negara as $row_item)
        <?php if($row_item->kode_negara==$row->kode_negara)
                {
                    $selected ="selected='selected'";
                }
                else
                {
                    $selected = "";
                }
        ?>
        <option value="{{$row_item->kode_negara}}" {{$selected}}>{{$row_item->nama_negara}}</option>
        @endforeach
        </select>
      </div>
  </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-3 control-label">Nama Kota</label>
        <div class="col-sm-9">
        <div id="select_kota">
        <select class="form-control" id="kode_kota" style="width:212px">
          <option value="">Pilih Negara</option>
        </select>
        </div>
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-3 control-label">Nama Hotel</label>
        <div class="col-sm-5">
        <div id="select_kota">
        <input type="text" id="nama_hotel" class="form-control" value="{{$row->nama_hotel}}"/>
        </div>
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-3 control-label">Alamat</label>
        <div class="col-sm-8">
        <div id="select_kota">
        <input type="text" id="alamat" class="form-control" value="{{$row->alamat}}"/>
        </div>
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-3 control-label">Telepon</label>
        <div class="col-sm-5">
        <div id="select_kota">
        <input type="text" id="telepon" class="form-control" value="{{$row->telepon}}"/>
        </div>
        </div>
    </div>
</form>
