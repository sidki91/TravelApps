<form class="form-horizontal" role="form">
<input type="hidden" id="kode_pendidikan" value="{{$row->kode_pendidikan}}"/>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-3 control-label">Deskripsi</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="description" name="description" value="{{$row->deskripsi}}" placeholder="isi dengan nama pendidikan">
            <!-- <p class="help-block">Example block-level help text here.</p> -->
        </div>
    </div>
</form>
