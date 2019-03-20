<style media="screen">
.select2-selection__rendered {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 12px;
}
</style>
<script type="text/javascript">
  $("#tgl_awal").datepicker();
  $("#tgl_akhir").datepicker();
  $("#jenis_pemesanan").select2();
  $("#kategori_search").select2();
  $("#paket_search").select2();
  $("#sub_paket_search").select2();
  $("#tgl_berangkat_search").datepicker();
  $("#tgl_kembali_search").datepicker();
  $("#output_data").select2();
  $("#status").select2();


  function pilih_paket_search()
  {
      var kategori = $("#kategori_search").val();
      $("#paket_search").val('');
      $("#sub_paket_search").val('');
      $.ajax({
          type: 'POST',
          url: 'pemesanan/pilih_paket_search',
          data: {
              '_token'             : $('input[name=_token]').val(),
              'kategori'           : kategori,
          },
          success: function(data)
          {

             $("#content_paket_search").html(data.html);


          },
      });

  }

  function pilih_sub_paket_search()
  {
      var paket = $("#paket_search").val();
      $.ajax({
          type: 'POST',
          url: 'pemesanan/pilih_sub_paket_search',
          data: {
              '_token'             : $('input[name=_token]').val(),
              'paket'              : paket

          },
          success: function(data)
          {
             $("#content_sub_paket_search").html(data.html);
          },
      });

  }
</script>

  <div class="card">
    <div class="card-body" style="margin-top:-22px">
      <form class="form-sample">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group row">
              <label class="col-sm-4 col-form-label">Tgl Pemesanan</label>
              <div class="col-sm-8">
                <div class="input-group">
                <div class="input-group-prepend">
                <input class="form-control input-group-text" type="text" id="tgl_awal" style="width:100px" value="{{date('m/01/Y')}}" >
                </div>
                <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                </div>
                <input class="form-control" type="text"  id="tgl_akhir" value="{{date('m/d/Y')}}">
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group row">
              <label class="col-sm-4 col-form-label">Nomor Pesanan</label>
              <div class="col-sm-6">
              <input type="text" class="form-control" id="nomor_pesanan">
            </div>
          </div>
        </div>

        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group row" style="margin-top:-10px">
              <label class="col-sm-4 col-form-label">Jenis</label>
              <div class="col-sm-8">
                <select class="form-control" id="jenis_pemesanan" style="width:150px">
                  <option value="">All</option>
                  <option value="Personal">Personal</option>
                  <option value="Group">Group</option>
                </select>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group row" style="margin-top:-10px">
              <label class="col-sm-4 col-form-label">Tgl Berangkat</label>
              <div class="col-sm-6">
                <div class="input-group">
                <input class="form-control" type="text" id="tgl_berangkat_search">
                <div class="input-group-append">
                <span class="input-group-text bg-transparent">
                <i class="fa fa-calendar"></i>
                </span>
                </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group row" style="margin-top:-10px">
              <label class="col-sm-4 col-form-label">Kategori</label>
              <div class="col-sm-8">
                <select class="form-control" id="kategori_search" style="width:150px" onchange="pilih_paket_search()">
                  <option value="">All</option>
                  @foreach($kategori as $row)
                  <option value="{{$row->kode_kategori}}">{{$row->deskripsi}}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group row" style="margin-top:-10px">
              <label class="col-sm-4 col-form-label">Tgl Kembali</label>
              <div class="col-sm-6">
                <div class="input-group">
                <input class="form-control" type="text" id="tgl_kembali_search">
                <div class="input-group-append">
                <span class="input-group-text bg-transparent">
                <i class="fa fa-calendar"></i>
                </span>
                </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group row" style="margin-top:-10px">
              <label class="col-sm-4 col-form-label">Paket</label>
              <div class="col-sm-8">
                <div id="content_paket_search">
                <select class="form-control" id="paket_search" style="width:200px" disabled>
                  <option value="">All</option>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group row" style="margin-top:-10px">
            <label class="col-sm-4 col-form-label">Status</label>
            <div class="col-sm-8">
              <div id="content_paket_search">
              <select class="form-control" id="status" style="width:150px" >
                <option value="">All</option>
                <option value="Open">Belum Lunas</option>
                <option value="Closed">Sudah Lunas</option>
              </select>
            </div>
          </div>
        </div>
      </div>

        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group row" style="margin-top:-10px">
              <label class="col-sm-4 col-form-label">Sub Paket</label>
              <div class="col-sm-8">
              <div id="content_sub_paket_search">
              <select class="form-control" id="sub_paket_search" style="width:200px" disabled>
                <option value="">All</option>
              </select>
            </div>
              </div>
            </div>
          </div>
        <div class="col-md-6">
          <div class="form-group row" style="margin-top:-10px">
            <label class="col-sm-4 col-form-label">Ouput</label>
            <div class="col-sm-8">
              <select class="form-control" id="output_data" style="width:150px">
                <option value="HTML">HTML</option>
                <option value="PDF">PDF</option>
              </select>
            </div>
          </div>
        </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group row">
              <label class="col-sm-4 col-form-label"></label>
              <div class="col-sm-8">
                <button type="button" name="button" class="btn btn-info" onclick="search()"><i class="fa fa-search"></i> Search</button>

            </div>
          </div>
        </div>

      </form>
    </div>
  </div>
