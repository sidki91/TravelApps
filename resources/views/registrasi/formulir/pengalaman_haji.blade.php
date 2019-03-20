<div class="form-group row">
  <label for="exampleInputPassword2" class="col-sm-5 col-form-label">Keterangan Haji</label>
  <div class="col-sm-7">
    <div class="input-group">
    <div class="input-group-prepend">
    <?php if(!empty($jumlah_haji)){?>
    <input class="form-control input-group-text" type="text"  style="width:100px" placeholder="Jumlah" id="jumlah_haji" value="{{$jumlah_haji}}">
  <?php }else{?>
    <input class="form-control input-group-text" type="text"  style="width:100px" placeholder="Jumlah" id="jumlah_haji">
  <?php } ?>
    </div>
    <div class="input-group-prepend">
    <span class="input-group-text" style="font-size:11px">Kali</span>
    </div>
    <?php if(!empty($tahun_haji)){?>
    <input class="form-control" type="text"  placeholder="Tahun" id="tahun_haji" value="{{$tahun_haji}}">
  <?php }else{?>
      <input class="form-control" type="text"  placeholder="Tahun" id="tahun_haji">
  <?php } ?>
    </div>
      </div>
    </div>
