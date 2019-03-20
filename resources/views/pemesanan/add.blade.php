<style>
.select2-selection__rendered {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 12px;
}
.dialogWide > .modal-dialog
{
     width: 72% !important;
}
</style>
<script type="text/javascript">

  $("#bulan").select2();
  $("#kategori").select2();
  $("#paket").select2();
  $("#sub_paket").select2();
  $("#jenis").select2();
  $("#tgl_berangkat").datepicker();
  $("#tgl_pemesanan").datepicker();

  function get_paket()
  {
    pilih_paket();
    pilih_sub_paket();
    pilih_harga_paket();
  }
  function pilih_paket()
  {
      var kategori = $("#kategori").val();
      var bulan    = $("#bulan").val();
      $("#paket").val('');
      $("#sub_paket").val('');
      $.ajax({
          type: 'POST',
          url: 'pemesanan/pilih_paket',
          data: {
              '_token'             : $('input[name=_token]').val(),
              'kategori'           : kategori,
              'bulan'              : bulan

          },
          success: function(data)
          {

             $("#content_paket").html(data.html);


          },
      });

  }

  function pilih_sub_paket()
  {
      var paket = $("#paket").val();
      $.ajax({
          type: 'POST',
          url: 'pemesanan/pilih_sub_paket',
          data: {
              '_token'             : $('input[name=_token]').val(),
              'paket'              : paket

          },
          success: function(data)
          {
             $("#lama_perjalanan").val(data.lama_perjalanan);
             $("#lama_perjalanan_val").val(data.lama_perjalanan_val);
             $("#content_sub_paket").html(data.html);
             $("#tgl_berangkat").prop('disabled', false);


          },
      });

  }

  function pilih_harga_paket()
  {
      var sub_paket = $("#sub_paket").val();
      $.ajax({
          type: 'POST',
          url: 'pemesanan/pilih_harga_paket',
          data: {
              '_token'             : $('input[name=_token]').val(),
              'sub_paket'          : sub_paket

          },
          success: function(data)
          {

                   $("#harga_paket").val(data.harga);
                   $("#harga_paket_val").val(data.harga_val);


          },
      });

  }



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
    $("#jumlah_jamaah").keypress(validateNumber);
  });

  function list_data_item()
  {
    var nomor_pesanan = $("#nomor_pesanan").val();
        $.ajax({
            type: 'POST',
            url: 'pemesanan/list_data_item',
            data:
            {
                '_token'         : $('input[name=_token]').val(),
                'nomor_pesanan'  : nomor_pesanan,
                'jumlah'         : $("#jumlah_jamaah").val()
            },
            beforeSend      : function()
            {
                $('#content_detail').html('<center><img src="{{ URL::asset("public/assets/images/831.gif")}}" width="30px" height="30px"/> <img src="{{ URL::asset("public/assets/images/332.gif")}}"/></center>');
                $("#content_detail").hide();
                $("#content_detail").fadeIn("slow");
           },
            success: function(data)
            {
                  $("#content_detail").html(data.html);
            },
        });
  }
  function open_jamaah()
  {
      $.ajax({
          type: 'POST',
          url: 'pemesanan/open_jamaah',
          data: {
                  '_token'       : $('input[name=_token]').val(),
                },
          success: function(data)
          {
            bootbox.dialog(

                {
                    className: "dialogWide",
                    title   : '<div><b>Daftar Jamaah</b></div>',
                    message : (data.html)
                }
              );
          },
      });
  }
  function delete_item(nomor_pesanan,item,kode_registrasi)
  {

      $.ajax({
          type: 'POST',
          url: 'pemesanan/delete_item',
          data: {
              '_token'             : $('input[name=_token]').val(),
              'nomor_pesanan'      : nomor_pesanan,
              'item'               : item,
              'kode_registrasi'    : kode_registrasi

          },
          success: function(data)
          {

                   if(data.status=='success')
                   {
                      swal("Info System", data.msg, "success");
                      $("#total_harga_val").val(data.total_harga);
                      $("#jumlah_jamaah").val(data.jumlah);
                      list_data_item();
                   }
                   else
                   {
                     swal("Opps !", data.msg, "error");
                   }



          },
      });

  }

  function get_tgl_kembali()
  {
    $.ajax({
        type: 'POST',
        url: 'pemesanan/get_tgl_kembali',
        data: {
                '_token'          : $('input[name=_token]').val(),
                'tgl_berangkat'   : $("#tgl_berangkat").val(),
                'lama_perjalanan' : $("#lama_perjalanan_val").val()
              },
        success: function(data)
        {
            $("#tgl_kembali").val(data.tgl_kembali);
        },
    });
  }


</script>
<div class="form-sample">
  <input type="hidden" id="nomor_pesanan">
  <input type="hidden" id="action" value="add">
  <div class="row">
    <div class="col-md-4">
      <div class="form-group row">
        <label class="col-sm-5 col-form-label">Bulan</label>
        <div class="col-sm-7">
        <select class="form-control" id="bulan" onchange="get_paket()">
          <option value="">Pilih</option>
          @foreach($bulan as $row_bulan)
          <?php
            if(date('m') == $row_bulan->bulan)
            {
                $selected = "selected='selected'";
            }
            else
            {
                $selected = "";
            }


          ?>
          <option value="{{$row_bulan->bulan}}" {{$selected}}>{{$row_bulan->bulan_name}}</option>
          @endforeach
        </select>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group row">
        <label class="col-sm-5 col-form-label">Lama Perjalanan</label>
        <div class="col-sm-7">
          <input type="text" id="lama_perjalanan" class="form-control" readonly style="background-color:white"/>
          <input type="hidden" id="lama_perjalanan_val"/>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group row">
        <label class="col-sm-5 col-form-label">Keterangan</label>
        <div class="col-sm-7">
      <input type="text" class="form-control" id="keterangan">
        </div>
      </div>
    </div>


  </div>
  <div class="row">
    <div class="col-md-4">
      <div class="form-group row" style="margin-top:-10px">
        <label class="col-sm-5 col-form-label">Tgl Pemesanan</label>
        <div class="col-sm-7">
          <div class="input-group" style="width:175px">
          <input class="form-control" type="text" id="tgl_pemesanan" value="{{date('m/d/Y')}}">
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
        <label class="col-sm-5 col-form-label">Sub Paket</label>
        <div class="col-sm-7">
          <div id="content_sub_paket">
        <select class="form-control" id="sub_paket" disabled>
          <option value="">Pilih</option>
        </select>
      </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group row" style="margin-top:-10px">
        <label class="col-sm-5 col-form-label">Harga Paket</label>
        <div class="col-sm-7">
          <input type="text" class="form-control" id="harga_paket" readonly style="background-color:white" />
          <input type="hidden" id="harga_paket_val"/>
        </div>
      </div>
    </div>
</div>
<div class="row">
  <div class="col-md-4">
    <div class="form-group row" style="margin-top:-10px" >
      <label class="col-sm-5 col-form-label">Jenis</label>
      <div class="col-sm-7">
      <select class="form-control" id="jenis">
        <option value="Personal">Personal</option>
        <option value="Group">Group</option>
      </select>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group row" style="margin-top:-10px">
      <label class="col-sm-5 col-form-label">Tgl Berangkat</label>
      <div class="col-sm-7">
        <div class="input-group" style="width:174px">
        <input class="form-control" type="text" id="tgl_berangkat" disabled onchange="get_tgl_kembali()">
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
        <input type="text" class="form-control" id="jumlah_jamaah" onkeyup="this.value=this.value.replace(/[^\d]/,'')" />
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-4">
    <div class="form-group row" style="margin-top:-10px" >
      <label class="col-sm-5 col-form-label">Kategori</label>
      <div class="col-sm-7">
      <select class="form-control" id="kategori" onchange="pilih_paket()" >
        <option value="">Pilih</option>
        @foreach($kategori as $row_kategori)
        <option value="{{$row_kategori->kode_kategori}}">{{$row_kategori->deskripsi}}</option>
        @endforeach
      </select>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group row" style="margin-top:-10px">
      <label class="col-sm-5 col-form-label">Tgl Kembali</label>
      <div class="col-sm-7">
        <div class="input-group" style="width:174px">
        <input class="form-control" type="text" id="tgl_kembali" readonly style="background-color:white">
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
      <label class="col-sm-5 col-form-label">Total Harga</label>
      <div class="col-sm-7">
        <input type="text" class="form-control" id="total_harga_val" readonly value="0" style="background-color:white" />
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-4">
    <div class="form-group row" style="margin-top:-10px">
      <label class="col-sm-5 col-form-label">Paket</label>
      <div class="col-sm-7">
      <div id="content_paket">
      <select class="form-control" id="paket" disabled>
        <option value="">Pilih</option>
      </select>
    </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group row" style="margin-top:-10px">
      <label class="col-sm-5 col-form-label">Berangkat Dari </label>
      <div class="col-sm-7">
      <input type="text" class="form-control" id="berangkat_dari"/>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group row" style="margin-top:-10px">
      <label class="col-sm-5 col-form-label">Dibuat Oleh </label>
      <div class="col-sm-7">
      <input type="text" class="form-control" id="dibuat_oleh" value="{{Auth::user()->name}}" readonly style="background-color:white"/>
      </div>
    </div>
  </div>
</div>

      <button type="button" id="generate_btn" name="button" class="btn btn-info" onclick="generate()"><i class="fa fa-check"></i> Generate</button>
      <button type="button" name="button" class="btn btn-danger"><i class="fa fa-close"></i> Cancel</button>
  </div>
  <div id="content_detail"></div>

</div>
