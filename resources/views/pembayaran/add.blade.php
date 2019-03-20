<style>
.select2-selection__rendered {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 12px;
}
.dialogWide > .modal-dialog
{
     width: 80% !important;
}
</style>
<script type="text/javascript">

  $("#jenis").select2();
  $("#tgl_pembayaran").datepicker();

  function validateNumber(event)
  {
      var key = window.event ? event.keyCode : event.which;
      if (event.keyCode === 8 || event.keyCode === 46) {
          return true;
      } else if ( key < 48 || key > 57 ) {
          return false;
      } else {
          return true;
      }
  }
  $(document).ready(function()
  {
    $("#jumlah_bayar").keypress(validateNumber);
  });


  function open_pemesanan()
  {
      $.ajax({
          type: 'POST',
          url: 'pembayaran/open_pemesanan',
          data: {
                  '_token'       : $('input[name=_token]').val(),
                },
          success: function(data)
          {
            bootbox.dialog(

                {
                    className: "dialogWide",
                    title   : '<div><b>Daftar Pemesanan</b></div>',
                    message : (data.html)
                }
              );
          },
      });
  }

  function money_format()
  {
          $("#jumlah_bayar").maskMoney({
          prefix:'', allowNegative: true, thousands:',', decimal:',', affixesStay: false}
                                      );
          var angka = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#jumlah_bayar").val()))));
          $("#jumlah_bayar_val").val(angka);

  }



</script>
<div class="form-sample">
  <input type="hidden" id="nomor_pembayaran">
  <input type="hidden" id="action" value="add">
  <div class="row">
    <div class="col-md-4">
      <div class="form-group row" style="margin-top:-10px">
        <label class="col-sm-5 col-form-label">Tgl Pembayaran</label>
        <div class="col-sm-7">
          <div class="input-group" style="width:173px">
          <input class="form-control" type="text" id="tgl_pembayaran" value="{{date('m/d/Y')}}">
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
    <div class="form-group row" style="margin-top:-10px" >
      <label class="col-sm-5 col-form-label">Pembayaran</label>
      <div class="col-sm-7">
      <select class="form-control" id="jenis">
        <option value="Cash">Cash</option>
        <option value="Transfer">Transfer</option>
      </select>
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
  <div id="content_detail"></div>

</div>
