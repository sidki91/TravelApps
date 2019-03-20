<form class="form-horizontal" role="form">
<input type="hidden" id="kode_service" value="{{$row->kode_service}}"/>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-3 control-label">Tipe Service</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="tipe_service" name="description" value="{{$row->tipe_service}}" placeholder="isi dengan tipe service">
            <!-- <p class="help-block">Example block-level help text here.</p> -->
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-3 control-label">Keterangan</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="keterangan" name="description" value="{{$row->keterangan}}" placeholder="isi dengan keterangan">
            <!-- <p class="help-block">Example block-level help text here.</p> -->
        </div>
    </div>
</form>
