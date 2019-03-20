<script>
$("#kode_kota").select2();
</script>
<select class="form-control" id="kode_kota" style="width:212px">
  <option value="">Pilih Negara</option>
  @foreach($kota as $row_kota)
  <?php
    if(!empty($kode_kota))
    {
        if($row_kota->kode_kota == $kode_kota)
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
        $selected ="";
    }

  ?>
  <option value="{{$row_kota->kode_kota}}" {{$selected}}>{{$row_kota->nama_kota}}</option>
  @endforeach
</select>
