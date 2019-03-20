<style>
.select2-selection__rendered {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 12px;
}
</style>
<script>
$("#kode_airlines").select2();
</script>
<form class="form-horizontal" role="form">
<input type="hidden" id="kode_pesawat" value="{{$row->kode_pesawat}}"/>
  <div class="form-group">
        <label for="inputEmail3" class="col-sm-3 control-label">Nama Airlines</label>
        <div class="col-sm-9">
          <select class="form-control" id="kode_airlines" style="width:200px">
            <option value="">Pilih</option>
          @foreach($airlines as $row_item)
          <?php
            if($row_item->kode_airlines == $row->kode_airlines)
            {
                $selected = "selected='selected'";
            }
            else
            {
                $selected = "";
            }

          ?>
          <option value="{{$row_item->kode_airlines}}" {{$selected}}>{{$row_item->nama_airlines}}</option>
          @endforeach
          </select>
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-3 control-label">Nama Pesawat</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="nama_pesawat" name="nama_pesawat" value="{{$row->nama_pesawat}}" placeholder="isi dengan nama pesawat">
            <!-- <p class="help-block">Example block-level help text here.</p> -->
        </div>
    </div>
</form>
