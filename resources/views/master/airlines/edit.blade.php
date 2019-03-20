<form class="form-horizontal" role="form">
<input type="hidden" id="kode_airlines" value="{{$row->kode_airlines}}"/>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-3 control-label">Nama Airlines</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="description" name="description" value="{{$row->nama_airlines}}" placeholder="isi dengan nama airlines">
            <!-- <p class="help-block">Example block-level help text here.</p> -->
        </div>
    </div>
</form>
