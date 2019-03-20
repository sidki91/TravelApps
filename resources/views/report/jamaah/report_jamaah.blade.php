@extends('layouts.app')
@section('content')
@section('menu','Report Jamaah')
<script src="{{ URL::asset('public/js/script.js')}}"></script>

<script type="text/javascript">

  $(document).ready(function()
   {
     $("#tgl_registrasi").datepicker();
     $("#jenis").select2();
   });
</script>
<div class="form-sample">
  <input type="hidden" id="nomor_pembayaran">
  <input type="hidden" id="action" value="add">
  <div class="row">
    <div class="col-md-4">
      <div class="form-group row" style="margin-top:-10px">
        <label class="col-sm-5 col-form-label">Tgl Registrasi</label>
        <div class="col-sm-7">
          <div class="input-group" style="width:173px">
          <input class="form-control" type="text" id="tgl_registrasi" value="{{date('m/d/Y')}}">
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
        <label class="col-sm-5 col-form-label">No Pemesanan</label>
        <div class="col-sm-7">
          <div class="input-group" style="width:170px">
          <input class="form-control" type="text" id="nomor_pesanan">
          <div class="input-group-append">
          <span class="input-group-text bg-transparent">
          <i class="fa fa-search" style="cursor:pointer" onclick="open_pemesanan()"></i>
          </span>
          </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group row" style="margin-top:-10px">
        <label class="col-sm-5 col-form-label">Sisa Tagihan</label>
        <div class="col-sm-7">
          <input type="text" class="form-control" id="sisa_tagihan" readonly style="background-color:white" />
          <input type="hidden" id="sisa_tagihan_val"/>
        </div>
      </div>
    </div>
</div>
<div class="row">
  <div class="col-md-4">
    <div class="form-group row" style="margin-top:-10px">
      <label class="col-sm-5 col-form-label">No Registrasi</label>
      <div class="col-sm-7">
        <div class="input-group" style="width:170px">
        <input class="form-control" type="text" id="nomor_pesanan">
        <div class="input-group-append">
        <span class="input-group-text bg-transparent">
        <i class="fa fa-search" style="cursor:pointer" onclick="open_pemesanan()"></i>
        </span>
        </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group row" style="margin-top:-10px">
      <label class="col-sm-5 col-form-label">Total Tagihan</label>
      <div class="col-sm-7">
        <input type="text" class="form-control" id="total_tagihan"  readonly style="background-color:white" />

      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group row" style="margin-top:-10px">
      <label class="col-sm-5 col-form-label">Jumlah Bayar</label>
      <div class="col-sm-7">
        <input type="text" class="form-control" id="jumlah_bayar" onkeyup="money_format()" />
        <input type="hidden" id="jumlah_bayar_val"/>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-4">
    <div class="form-group row" style="margin-top:-10px" >
      <label class="col-sm-5 col-form-label">Keterangan</label>
      <div class="col-sm-7">
      <textarea id="keterangan" rows="4"  class="form-control"></textarea>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group row" style="margin-top:-10px">
      <label class="col-sm-5 col-form-label">Sudah Dibayar </label>
      <div class="col-sm-7">
      <input type="text" class="form-control" id="sudah_dibayar" readonly style="background-color:white"/>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="form-group row" style="margin-top:-10px">
      <label class="col-sm-5 col-form-label">Created By</label>
      <div class="col-sm-7">
        <input type="text" class="form-control" id="total_harga_val" readonly style="background-color:white"  value="{{Auth::user()->name}}" />
      </div>
    </div>
  </div>
</div>

      <button type="button" id="generate_btn" name="button" class="btn btn-info" onclick="generate()"><i class="fa fa-check"></i> Save</button>
      <button type="button" name="button" class="btn btn-danger"><i class="fa fa-close"></i> Cancel</button>
  </div>


</div>
@endsection
