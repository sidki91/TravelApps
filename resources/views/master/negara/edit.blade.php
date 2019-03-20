<form class="form-horizontal" role="form">
<input type="hidden" id="kode_negara" value="{{$row->kode_negara}}"/>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-3 control-label">Nama Negara</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="description" name="description" value="{{$row->nama_negara}}" placeholder="isi dengan nama negara">
            <!-- <p class="help-block">Example block-level help text here.</p> -->
        </div>
    </div>
</form>
