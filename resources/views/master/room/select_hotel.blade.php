<script>
$("#kode_hotel").select2();
</script>
<select class="form-control" id="kode_hotel" style="width:212px" onchange="change_info()">
  <option value="">Pilih Hotel</option>
  @foreach($hotel as $row)
  <?php
    if(!empty($kode_hotel))
    {
        if($row->kode_hotel == $kode_hotel)
        {
            $selected = "selected='selected'";
        }
        else
        {
            $selected = "";
        }
    }
    else
    {
        $selected = "";
    }

  ?>
  <option value="{{$row->kode_hotel}}" {{$selected}}>{{$row->nama_hotel}}</option>
  @endforeach
</select>
