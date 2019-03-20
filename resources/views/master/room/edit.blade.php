<style>
.select2-selection__rendered {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 12px;
}
</style>
<script>
$("#kode_hotel").select2();
$("#kode_service").select2();
$("#kode_kapasitas").select2();
$("#kode_negara").select2();
function change_hotel()
{
    var kode_negara = $("#kode_negara").val();
    var kode_hotel  = $("#kode_hotel_val").val();
    $.ajax({
        type: 'POST',
        url: 'room/change_hotel',
        data: {
                '_token'       : $('input[name=_token]').val(),
                'kode_negara'  : kode_negara,
                'kode_hotel'   : kode_hotel
              },
        success: function(data)
        {
            $("#content_hotel").html(data.html);
            change_info();
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
            
        },
    });
}

$(document).ready(function()
 {
    change_hotel();

 });
</script>
<form class="form-horizontal" role="form">
  <input type="hidden" id="kode_room" value="{{$row->kode_room}}"/>
  <input type="hidden" id="kode_hotel_val" value="{{$row->kode_hotel}}"/>
  <div class="form-group">
          <label for="inputEmail3" class="col-sm-3 control-label">Negara</label>
          <div class="col-sm-6">
            <select class="form-control" id="kode_negara" style="width:212px" onchange="change_hotel()" >
              <option value="">Pilih</option>
            @foreach($negara as $row_negara)
            <?php
              if(!empty($kode_negara))
              {
                  if($row_negara->kode_negara == $kode_negara)
                  {
                      $selected = "selected='selected'";
                  }
                  else
                  {
                      $selected = "";
                  }
              }


             ?>
            <option value="{{$row_negara->kode_negara}}" {{$selected}}>{{$row_negara->nama_negara}}</option>
            @endforeach
            </select>
          </div>
      </div>

<div class="form-group">
      <label for="inputEmail3" class="col-sm-3 control-label">Hotel</label>
      <div class="col-sm-9">
        <div id="content_hotel">
        <select class="form-control" id="kode_hotel" style="width:212px" >
          <option value="">Pilih</option>
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
      <div class="col-sm-5">
      <div id="select_kota">
      <input type="text" id="tipe_room" class="form-control" value="{{$row->tipe_room}}"/>
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
          <?php
              if($row_service->kode_service == $row->kode_service)
              {
                  $selected = "selected='selected'";
              }
              else
              {
                 $selected = "";
              }

          ?>
          <option value="{{$row_service->kode_service}}" {{$selected}}>{{$row_service->tipe_service}}</option>
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
          <?php
          if($row_kapasitas->kode_kapasitas == $row->kode_kapasitas)
          {
              $selected = "selected='selected'";
          }
          else
          {
             $selected = "";
          }
          ?>
          <option value="{{$row_kapasitas->kode_kapasitas}}" {{$selected}}>{{$row_kapasitas->deskripsi}}</option>
          @endforeach
        </select>
        </div>
        </div>
    </div>



</form>
