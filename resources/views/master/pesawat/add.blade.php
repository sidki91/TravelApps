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
<div class="form-group">
      <label for="inputEmail3" class="col-sm-3 control-label">Nama Negara</label>
      <div class="col-sm-9">
        <select class="form-control" id="kode_airlines" style="width:200px">
          <option value="">Pilih</option>
        @foreach($airlines as $row_airlines)
        <option value="{{$row_airlines->kode_airlines}}">{{$row_airlines->nama_airlines}}</option>
        @endforeach
        </select>
      </div>
  </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-3 control-label">Nama Pesawat</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="nama_pesawat" name="nama_pesawat" placeholder="isi dengan nama pesawat">
            <!-- <p class="help-block">Example block-level help text here.</p> -->
        </div>
    </div>

</form>
