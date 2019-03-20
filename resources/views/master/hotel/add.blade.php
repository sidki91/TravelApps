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
                'kode_negara' : kode_negara
              },
        success: function(data)
        {
            $("#select_kota").html(data.html);
        },
    });
}
</script>
<form class="form-horizontal" role="form">

<div class="form-group">
      <label for="inputEmail3" class="col-sm-3 control-label">Negara</label>
      <div class="col-sm-9">
        <select class="form-control" id="kode_negara" style="width:212px" onchange="pilih_kota()">
          <option value="">Pilih</option>
        @foreach($negara as $row)
        <option value="{{$row->kode_negara}}">{{$row->nama_negara}}</option>
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
        <input type="text" id="nama_hotel" class="form-control"/>
        </div>
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-3 control-label">Alamat</label>
        <div class="col-sm-8">
        <div id="select_kota">
        <input type="text" id="alamat" class="form-control"/>
        </div>
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-3 control-label">Telepon</label>
        <div class="col-sm-5">
        <div id="select_kota">
        <input type="text" id="telepon" class="form-control"/>
        </div>
        </div>
    </div>


</form>
