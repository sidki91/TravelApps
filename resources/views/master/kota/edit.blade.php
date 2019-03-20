<style>
.select2-selection__rendered {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 12px;
}
</style>
<script>
$("#kode_negara").select2();
</script>
<form class="form-horizontal" role="form">
<input type="hidden" id="kode_kota" value="{{$row->kode_kota}}"/>
  <div class="form-group">
        <label for="inputEmail3" class="col-sm-3 control-label">Nama Negara</label>
        <div class="col-sm-9">
          <select class="form-control" id="kode_negara" style="width:200px">
            <option value="">Pilih</option>
          @foreach($negara as $row_item)
          <?php
            if($row_item->kode_negara == $row->kode_negara)
            {
                $selected = "selected='selected'";
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
        <label for="inputEmail3" class="col-sm-3 control-label">Nama Negara</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="description" name="description" value="{{$row->nama_kota}}" placeholder="isi dengan nama negara">
            <!-- <p class="help-block">Example block-level help text here.</p> -->
        </div>
    </div>
</form>
