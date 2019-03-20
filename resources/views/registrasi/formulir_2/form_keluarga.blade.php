<style media="screen">
.clear{
    clear: both !important;

}
.modal-open .select2-dropdown {
z-index: 10060;
}

.modal-open .select2-close-mask {
z-index: 10055;
}

</style>
<script type="text/javascript">

  $("#gol_darah_kel").select2();
  $("#pendidikan_kel").select2();
  $("#pekerjaan_kel").select2();
  $("#provinsi_kel").select2();
  $("#kab_kota_kel").select2();
  $("#kecamatan_kel").select2();
  $("#kelurahan_kel").select2();
  $("#tgl_lahir_kel").datepicker();

  function change_provinsi_kel()
  {
     var provinsi = $("#provinsi_kel").val();

    $.ajax({
        type: 'POST',
        url: 'formulir/change_provinsi_kel',
        data: {
            '_token'             : $('input[name=_token]').val(),
            'provinsi'           :provinsi

        },
        beforeSend      : function()
        {
            $('#content_kabupaten_kota_kel').html('<left><img src="{{ URL::asset("public/images/ajax-loader2.gif")}}" /> </left>');
            $("#content_kabupaten_kota_kel").hide();
            $("#content_kabupaten_kota_kel").fadeIn("slow");
       },
        success: function(data)
        {

              $("#content_kabupaten_kota_kel").html(data.html);

        },
    });

  }

  function change_kabupaten_kota_kel()
  {
     var kab_kota = $("#kab_kota_kel").val();

    $.ajax({
        type: 'POST',
        url: 'formulir/change_kabupaten_kota_kel',
        data: {
            '_token'             : $('input[name=_token]').val(),
            'kab_kota'           :kab_kota

        },
        beforeSend      : function()
        {
            $('#content_kecamatan_kel').html('<left><img src="{{ URL::asset("public/images/ajax-loader2.gif")}}" /> </left>');
            $("#content_kecamatan_kel").hide();
            $("#content_kecamatan_kel").fadeIn("slow");
       },
        success: function(data)
        {

              $("#content_kecamatan_kel").html(data.html);

        },
    });

  }


    function change_kecamatan_kel()
    {
       var kecamatan = $("#kecamatan_kel").val();

      $.ajax({
          type: 'POST',
          url: 'formulir/change_kecamatan_kel',
          data: {
              '_token'             : $('input[name=_token]').val(),
              'kecamatan'          :kecamatan

          },
          beforeSend      : function()
          {
              $('#content_kelurahan_kel').html('<left><img src="{{ URL::asset("public/images/ajax-loader2.gif")}}" /> </left>');
              $("#content_kelurahan_kel").hide();
              $("#content_kelurahan_kel").fadeIn("slow");
         },
          success: function(data)
          {

                $("#content_kelurahan_kel").html(data.html);

          },
      });

    }
</script>
<div class="col-12 grid-margin">
  <div class="card">
    <div class="card-body">
      <form class="form-sample">
        <div class="row">
          <div class="col-md-6">

            <p class="card-description" style="font-weight:bold">
              Identitas Diri
            </p>
            <hr/>
            <form class="forms-sample">
              <!--<div class="form-group row">
                <label for="exampleInputEmail2" class="col-sm-5 col-form-label">Kode Registrasi</label>
                <div class="col-sm-5">
                  <input type="text" id="kode_registrasi" class="form-control" readonly value="">
                </div>
              </div>-->
              <input type="hidden" id="kode_registrasi"/>
              <div class="form-group row">
                <label for="exampleInputPassword2" class="col-sm-5 col-form-label">Nama Lengkap</label>
                <div class="col-sm-7">
                  <input type="text" class="form-control" id="nama_lengkap">
                </div>
              </div>
              <div class="form-group row">
                <label for="exampleInputPassword2" class="col-sm-5 col-form-label">Nama Ayah Kandung</label>
                <div class="col-sm-7">
                  <input type="text" class="form-control" id="nama_ayah">
                </div>
              </div>
              <div class="form-group row">
                <label for="exampleInputPassword2" class="col-sm-5 col-form-label">Tempat Lahir</label>
                <div class="col-sm-7">
                <input type="text" class="form-control" id="tempat_lahir"/>
                </div>
              </div>
              <div class="form-group row">
                <label for="exampleInputPassword2" class="col-sm-5 col-form-label">Tanggal Lahir</label>
                <div class="col-sm-7">
                  <div class="input-group">
                  <input class="form-control" type="text" id="tgl_lahir_kel" onblur="hitung_umur()">
                  <div class="input-group-append">
                  <span class="input-group-text bg-transparent">
                  <i class="fa fa-calendar"></i>
                  </span>
                  </div>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label for="exampleInputPassword2" class="col-sm-5 col-form-label">Umur</label>
                <div class="col-sm-7">
                  <input type="text" class="form-control" id="umur" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label for="exampleInputPassword2" class="col-sm-5 col-form-label">Golongan Darah</label>
                <div class="col-sm-4">
                  <select class="form-control" id="gol_darah_kel">
                    <option value="">Pilih</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="AB">AB</option>
                    <option value="O">O</option>
                  </select>
                </div>
              </div>
              <div class="form-group row">
              <label class="col-sm-5 col-form-label">Jenis Kelamin</label>
              <div class="col-sm-4">
              <div class="form-radio">
              <label class="form-check-label">
              <input id="jenis_kelamin" name="jenis_kelamin" class="form-check-input" type="radio" checked="" value="Pria" >
              Pria
              <i class="input-helper"></i>
              </label>
              </div>
              </div>
              <div class="col-sm-3">
              <div class="form-radio">
              <label class="form-check-label">
              <input id="jenis_kelamin" name="jenis_kelamin" class="form-check-input" type="radio" value="Wanita" >
              Wanita
              <i class="input-helper"></i>
              </label>
              </div>
              </div>
              </div>
              <div class="form-group row">
                <label for="exampleInputPassword2" class="col-sm-5 col-form-label">Pendidikan</label>
                <div class="col-sm-7">
                  <select class="form-control" id="pendidikan_kel" style="width:120px">
                    <option value="">Pilih</option>
                    @foreach($pendidikan as $row_pendidikan)
                    <option value="{{$row_pendidikan->kode_pendidikan}}">{{$row_pendidikan->deskripsi}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="form-group row">
              <label class="col-sm-5 col-form-label">Status</label>
              <div class="col-sm-4">
              <div class="form-radio">
              <label class="form-check-label">
              <input id="keterangan_menikah" class="form-check-input" type="radio" checked="" value="Lajang" name="keterangan_menikah" onclick="keterangan_menikah_action()">
              Lajang
              <i class="input-helper"></i>
              </label>
              </div>
              </div>
              <div class="col-sm-3">
              <div class="form-radio">
              <label class="form-check-label">
              <input id="keterangan_menikah" class="form-check-input" type="radio" value="Menikah" name="keterangan_menikah" onclick="keterangan_menikah_action()">
              Menikah
              <i class="input-helper"></i>
              </label>
              </div>
              </div>
              </div>
              <div class="form-group row">
                <label for="exampleInputPassword2" class="col-sm-5 col-form-label">Tanggal Pernikahan</label>
                <div class="col-sm-7">
                  <div class="input-group">
                  <input class="form-control" type="text" id="tgl_pernikahan" disabled>
                  <div class="input-group-append">
                  <span class="input-group-text bg-transparent">
                  <i class="fa fa-calendar"></i>
                  </span>
                  </div>

                </div>
              </div>
            </div>
            <br/>
            <p class="card-description" style="font-weight:bold;margin-top:33px">
              Data Pekerjaan
            </p>
            <hr/>
            <div class="form-group row">
              <label for="exampleInputPassword2" class="col-sm-5 col-form-label">Pekerjaan</label>
              <div class="col-sm-7">
              <select class="form-control"  id="pekerjaan_kel" style="width:180px">
                <option value="">Pilih</option>
                @foreach($pekerjaan as $row_pekerjaan)
                <option value="{{$row_pekerjaan->kode_pekerjaan}}">{{$row_pekerjaan->deskripsi}}</option>
                @endforeach
              </select>
              </div>
            </div>
            <div class="form-group row">
              <label for="exampleInputPassword2" class="col-sm-5 col-form-label">Nama Instansi</label>
              <div class="col-sm-7">
                <input type="text" class="form-control" id="nama_instansi">
              </div>
            </div>
            <div class="form-group row">
              <label for="exampleInputPassword2" class="col-sm-5 col-form-label">Alamat Instansi</label>
              <div class="col-sm-7">
                <input type="text" class="form-control" id="alamat_instansi">
              </div>
            </div>
            <div class="form-group row">
              <label for="exampleInputPassword2" class="col-sm-5 col-form-label">Telepon Instansi</label>
              <div class="col-sm-7">
                <input type="text" class="form-control" id="telp_instansi">
              </div>
            </div>
            <br/>
            <p class="card-description" style="font-weight:bold">
              Data Pasport
            </p>
            <hr/>
            <div class="form-group row">
              <label for="exampleInputPassword2" class="col-sm-5 col-form-label">Nomor Pasport</label>
              <div class="col-sm-7">
                <input type="text" class="form-control" id="nomor_pasport">
              </div>
            </div>
            <div class="form-group row">
              <label for="exampleInputPassword2" class="col-sm-5 col-form-label">Tanggal Dikeluarkan</label>
              <div class="col-sm-7">
                <div class="input-group">
                <input class="form-control" type="text" id="tgl_dikeluarakan">
                <div class="input-group-append">
                <span class="input-group-text bg-transparent">
                <i class="fa fa-calendar"></i>
                </span>
                </div>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <label for="exampleInputPassword2" class="col-sm-5 col-form-label">Tempat Dikeluarkan</label>
              <div class="col-sm-7">
                <input type="text" class="form-control" id="tempat_dikeluarkan">
              </div>
            </div>
            <div class="form-group row">
              <label for="exampleInputPassword2" class="col-sm-5 col-form-label">Masa Berlaku Hingga</label>
              <div class="col-sm-7">
                <input type="text" class="form-control" id="masa_berlaku">
              </div>
            </div>
            <button type="button" class="btn btn-success mr-2" onclick="save()"><i class="fa fa-save"></i> Simpan</button>
            <button class="btn btn-danger"><i class="fa fa-close"></i> Batal</button>
          </form>
          </div>

                      <div class="col-md-6">
                        <p class="card-description" style="font-weight:bold">
                          Alamat
                        </p>
                        <hr/>
                        <form class="forms-sample">
                          <div class="form-group row">
                            <label for="exampleInputEmail2" class="col-sm-5 col-form-label">Alamat Tempat Tinggal</label>
                            <div class="col-sm-7">
                              <input type="text" class="form-control" id="alamat"/>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label for="exampleInputPassword2" class="col-sm-5 col-form-label">RT/RW</label>
                            <div class="col-sm-7">
                              <div class="input-group">
                              <div class="input-group-prepend">
                              <input class="form-control input-group-text" type="text" id="rt" style="width:100px" placeholder="RT">
                              </div>
                              <div class="input-group-prepend">
                              <span class="input-group-text">/</span>
                              </div>
                              <input class="form-control" type="text"  placeholder="RW" id="rw">
                              </div>
                                </div>
                              </div>

                              <div class="form-group row">
                                <label for="exampleInputPassword2" class="col-sm-5 col-form-label">Nomor Rumah</label>
                                <div class="col-sm-7">
                                  <input type="text" class="form-control" id="nomor_rumah">
                                </div>
                              </div>

                              <div class="form-group row">
                                <label for="exampleInputPassword2" class="col-sm-5 col-form-label">Provinsi</label>
                                <div class="col-sm-7">
                                  <select class="form-control" id="provinsi_kel" onchange="change_provinsi_kel()" style="width:225px">
                                    <option>Pilih</option>
                                    @foreach($provinsi as $row)
                                    <option value="{{$row->id_prov}}">{{$row->nama}}</option>
                                    @endforeach
                                  </select>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="exampleInputPassword2" class="col-sm-5 col-form-label">Kabupaten/Kota</label>
                                <div class="col-sm-7">
                                  <div id="content_kabupaten_kota_kel">
                                  <select class="form-control" id="kab_kota_kel" style="width:225px">
                                    <option>Pilih</option>
                                  </select>
                                  </div>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="exampleInputPassword2" class="col-sm-5 col-form-label">Kecamatan</label>
                                <div class="col-sm-7">
                                  <div id="content_kecamatan_kel">
                                  <select class="form-control" id="kecamatan_kel" style="width:225px">
                                    <option>Pilih</option>
                                  </select>
                                </div>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="exampleInputPassword2" class="col-sm-5 col-form-label">Kelurahan</label>
                                <div class="col-sm-7">
                                  <div id="content_kelurahan_kel">
                                  <select class="form-control" id="kelurahan_kel" style="width:225px">
                                    <option>Pilih</option>
                                  </select>
                                </div>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="exampleInputPassword2" class="col-sm-5 col-form-label">Kode Pos</label>
                                <div class="col-sm-4">
                                  <input type="text" class="form-control" id="kode_pos">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="exampleInputPassword2" class="col-sm-5 col-form-label">Telepon</label>
                                <div class="col-sm-7">
                                  <input type="text" class="form-control" id="telepon">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="exampleInputPassword2" class="col-sm-5 col-form-label">Handphone</label>
                                <div class="col-sm-7">
                                  <input type="text" class="form-control" id="handphone">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="exampleInputPassword2" class="col-sm-5 col-form-label">E-mail</label>
                                <div class="col-sm-7">
                                  <input type="text" class="form-control" id="email">
                                </div>
                              </div>

                            </div>
                          </form>


        </div>
      </form>
    </div>
    </div>
    </div>
