<script type="text/javascript">
  $("#tgl_awal").datepicker();
  $("#tgl_akhir").datepicker();
  $("#jenis_pembayaran").select2();
  $("#output_data").select2();

</script>
<form class="form-horizontal" role="form">

    <div class="form-group">
        <label for="inputEmail3" class="col-sm-4 control-label">Tgl Pembayaran</label>
        <div class="col-sm-8">
          <div class="input-group">
          <div class="input-group-prepend">
          <input class="form-control input-group-text" type="text" id="tgl_awal" style="width:130px" value="{{date('m/01/Y')}}" >
          </div>
          <div class="input-group-prepend">
          <span class="input-group-text"><i class="fa fa-calendar"></i></span>
          </div>
          <input class="form-control" type="text"  id="tgl_akhir" value="{{date('m/d/Y')}}">
          </div>
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-4 control-label">Nomor Pembayaran</label>
        <div class="col-sm-8">
        <div id="select_kota">
        <input type="text" id="nomor_pembayaran" class="form-control"/>
        </div>
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-4 control-label">Jenis Pembayaran</label>
        <div class="col-sm-8">
        <select class="form-control" id="jenis_pembayaran" style="width:140px">
          <option value="">All</option>
          <option value="Cash">Cash</option>
          <option value="Transfer">Transfer</option>
        </select>
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-4 control-label">Output</label>
        <div class="col-sm-8">
        <select class="form-control" id="output_data" style="width:140px">
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
