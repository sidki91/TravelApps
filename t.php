<div class="row">
  <div class="col-md-4">
    <div class="form-group row" style="margin-top:-10px" >
      <label class="col-sm-5 col-form-label">Kategori</label>
      <div class="col-sm-7">
      <select class="form-control" id="kategori" onchange="pilih_paket()" >
        <option value="">Pilih</option>
        @foreach($kategori as $row_kategori)
        <?php if($row->kode_kategori == $row_kategori->kode_kategori)
              {
                  $selected ="selected='selected'";
              }
              else
              {
                  $selected = "";
              }
        ?>
        <option value="{{$row_kategori->kode_kategori}}" {{$selected}}>{{$row_kategori->deskripsi}}</option>
        @endforeach
      </select>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group row" style="margin-top:-10px">
      <label class="col-sm-5 col-form-label">Tgl Berangkat</label>
      <div class="col-sm-7">
        <div class="input-group" style="width:175px">
        <input class="form-control" type="text" id="tgl_berangkat" value="{{date('m/d/Y',strtotime($row->tgl_berangkat))}}"/>
        <div class="input-group-append">
        <span class="input-group-text bg-transparent">
        <i class="fa fa-calendar"></i>
        </span>
        </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group row" style="margin-top:-10px">
      <label class="col-sm-5 col-form-label">Jumlah Jamaah</label>
      <div class="col-sm-7">
        <input type="text" class="form-control" id="jumlah_jamaah" onkeyup="this.value=this.value.replace(/[^\d]/,'')"  value="{{$row->jumlah}}"/>
      </div>
    </div>
  </div>
</div>
<div class="row">


  <div class="col-md-4">
    <div class="form-group row" style="margin-top:-10px">
      <label class="col-sm-5 col-form-label">Berangkat Dari </label>
      <div class="col-sm-7">
      <input type="text" class="form-control" id="berangkat_dari" value="{{$row->berangkat_dari}}"/>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group row" style="margin-top:-10px">
      <label class="col-sm-5 col-form-label">Total Harga</label>
      <div class="col-sm-7">
        <input type="text" class="form-control" id="total_harga_val" readonly value="{{number_format($row->total_harga)}}" />
      </div>
    </div>
  </div>
</div>
