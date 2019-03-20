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
<div class="form-group">
      <label for="inputEmail3" class="col-sm-3 control-label">Nama Negara</label>
      <div class="col-sm-9">
        <select class="form-control" id="kode_negara" style="width:200px">
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
            <input type="text" class="form-control" id="description" name="description" placeholder="isi dengan nama kota">
            <!-- <p class="help-block">Example block-level help text here.</p> -->
        </div>
    </div>

</form>
