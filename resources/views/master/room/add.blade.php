<style>
.select2-selection__rendered {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 12px;
}
</style>
<script>
$("#kode_negara").select2();
$("#kode_hotel").select2();
$("#kode_service").select2();
$("#kode_kapasitas").select2();

function change_hotel()
{
    var kode_negara = $("#kode_negara").val();
    $.ajax({
        type: 'POST',
        url: 'room/change_hotel',
        data: {
                '_token'       : $('input[name=_token]').val(),
                'kode_negara'  : kode_negara
              },
        success: function(data)
        {
            $("#content_hotel").html(data.html);
        },
    });
}
function change_info()
{
    var kode_hotel = $("#kode_hotel").val();
    $.ajax({
        type: 'POST',
        url: 'room/change_info',
        data: {
                '_token'      : $('input[name=_token]').val(),
                'kode_hotel'  : kode_hotel
              },
        success: function(data)
        {
            $("#negara").val(data.negara);
            $("#kota").val(data.kota);
            $("#tipe_room").focus();
        },
    });
}
</script>

<form class="form-horizontal" role="form">
<div class="form-group">
        <label for="inputEmail3" class="col-sm-3 control-label">Negara</label>
        <div class="col-sm-6">
          <select class="form-control" id="kode_negara" style="width:212px" onchange="change_hotel()" >
            <option value="">Pilih</option>
          @foreach($negara as $row)
          <option value="{{$row->kode_negara}}">{{$row->nama_negara}}</option>
          @endforeach
          </select>
        </div>
    </div>
<div class="form-group">
      <label for="inputEmail3" class="col-sm-3 control-label">Hotel</label>
      <div class="col-sm-9">
        <id id="content_hotel">
        <select class="form-control" id="kode_hotel" style="width:212px" disabled >
          <option value="">Pilih Hotel</option>
        </select>
      </div>
      </div>
  </div>

    <div class="form-group">
          <label for="inputEmail3" class="col-sm-3 control-label">Kota</label>
          <div class="col-sm-5">
          <input type="text" class="form-control" id="kota" readonly/>
          </div>
      </div>
      <div class="form-group">
          <label for="inputEmail3" class="col-sm-3 control-label">Tipe Room</label>
          <div class="col-sm-4">
          <div id="select_kota">
          <input type="text" id="tipe_room" class="form-control"/>
          </div>
          </div>
      </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-3 control-label">Service</label>
        <div class="col-sm-9">
        <div id="select_kota">
        <select class="form-control" id="kode_service" style="width:212px">
          <option value="">Pilih</option>
          @foreach($service as $row_service)
          <option value="{{$row_service->kode_service}}">{{$row_service->tipe_service}}</option>
          @endforeach
        </select>
        </div>
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-3 control-label">Kapasitas</label>
        <div class="col-sm-9">
        <div id="select_kota">
        <select class="form-control" id="kode_kapasitas" style="width:212px">
          <option value="">Pilih</option>
          @foreach($kapasitas as $row_kapasitas)
          <option value="{{$row_kapasitas->kode_kapasitas}}">{{$row_kapasitas->deskripsi}}</option>
          @endforeach
        </select>
        </div>
        </div>
    </div>



</form>
