<script type="text/javascript">
  $("#tgl_awal").datepicker();
  $("#tgl_akhir").datepicker();
  $("#ouput").select2();

</script>
<form class="form-horizontal" role="form">

    <div class="form-group">
        <label for="inputEmail3" class="col-sm-4 control-label">Tgl Registrasi</label>
        <div class="col-sm-8">
          <div class="input-group">
          <div class="input-group-prepend">
          <input class="form-control input-group-text" type="text" id="tgl_awal" style="width:130px" value="{{date('m/d/Y')}}" >
          </div>
          <div class="input-group-prepend">
          <span class="input-group-text"><i class="fa fa-calendar"></i></span>
          </div>
          <input class="form-control" type="text"  id="tgl_akhir" value="{{date('m/d/Y')}}">
          </div>
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-4 control-label">Kode Registrasi</label>
        <div class="col-sm-8">
        <div id="select_kota">
        <input type="text" id="kode_registrasi" class="form-control"/>
        </div>
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-4 control-label">Nama Jamaah</label>
        <div class="col-sm-8">
        <div id="select_kota">
        <input type="text" id="nama_jamaah" class="form-control"/>
        </div>
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-4 control-label">Output</label>
        <div class="col-sm-8">
        <select class="form-control" id="ouput" style="width:140px">
          <option value="HTML">HTML</option>
          <option value="PDF">PDF</option>
        </select>

        </div>
    </div>

    <div class="form-group">
        <label for="inputEmail3" class="col-sm-3 control-label"></label>
        <div class="col-sm-5">
      <button type="button" name="button" class="btn btn-info" onclick="search()"><i class="fa fa-search"></i> Search</button>
        </div>
    </div>

</form>
