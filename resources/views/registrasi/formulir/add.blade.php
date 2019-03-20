<style>
.select2-selection__rendered {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 12px;
}
</style>
<script>
  $("#tgl_lahir").datepicker();
  $("#tgl_dikeluarakan").datepicker();
  $("#program_dipilih").datepicker();
  $("#provinsi").select2();
  $("#kab_kota").select2();
  $("#kecamatan").select2();
  $("#kelurahan").select2();
  $("#kategori").select2();
  $("#paket").select2();
  $("#pendidikan").select2();
  $("#pekerjaan").select2();
  $("#gol_darah").select2();
  $("#sub_paket").select2();
  $("#tgl_pernikahan").datepicker();
  $("#nama_lengkap").focus();
  $(".card-title").html('Formulir Pendaftaran');

  function pengalaman_haji_action()
  {
     var pengalaman_haji = $("#pengalaman_haji:checked").val();

    $.ajax({
        type: 'POST',
        url: 'formulir/pengalaman_haji',
        data: {
            '_token'             : $('input[name=_token]').val(),
            'pengalaman_haji'    :pengalaman_haji

        },
        beforeSend      : function()
        {
            $('#content_pengalaman_haji').html('<left><img src="{{ URL::asset("public/images/ajax-loader2.gif")}}" /> </left>');
            $("#content_pengalaman_haji").hide();
            $("#content_pengalaman_haji").fadeIn("slow");
       },
        success: function(data)
        {

              $("#content_pengalaman_haji").html(data.html);

        },
    });

  }

  function pengalaman_umroh_action()
  {
     var pengalaman_umroh = $("#pengalaman_umroh:checked").val();

    $.ajax({
        type: 'POST',
        url: 'formulir/pengalaman_umroh',
        data: {
            '_token'             : $('input[name=_token]').val(),
            'pengalaman_umroh'    :pengalaman_umroh

        },
        beforeSend      : function()
        {
            $('#content_pengalaman_umroh').html('<left><img src="{{ URL::asset("public/images/ajax-loader2.gif")}}" /> </left>');
            $("#content_pengalaman_umroh").hide();
            $("#content_pengalaman_umroh").fadeIn("slow");
       },
        success: function(data)
        {

              $("#content_pengalaman_umroh").html(data.html);

        },
    });

  }

  function keterangan_menikah_action()
  {
     var keterangan_menikah = $("#keterangan_menikah:checked").val();

    $.ajax({
        type: 'POST',
        url: 'formulir/keterangan_menikah',
        data: {
            '_token'             : $('input[name=_token]').val(),
            'keterangan_menikah'    :keterangan_menikah

        },
        beforeSend      : function()
        {
            $('#content_keterangan_menikah').html('<left><img src="{{ URL::asset("public/images/ajax-loader2.gif")}}" /> </left>');
            $("#content_keterangan_menikah").hide();
            $("#content_keterangan_menikah").fadeIn("slow");
       },
        success: function(data)
        {

              $("#content_keterangan_menikah").html('');
              if(data.status=='success')
              {
                  $("#tgl_pernikahan").prop('disabled', false);
                  $("#tgl_pernikahan").focus();
              }
              else
              {
                 $("#tgl_pernikahan").prop('disabled', true);
                 //$("#tgl_pernikahan").prop('disabled', true).css("background-color","#0F0");
              }

        },
    });

  }

  function change_provinsi()
  {
     var provinsi = $("#provinsi").val();

    $.ajax({
        type: 'POST',
        url: 'formulir/change_provinsi',
        data: {
            '_token'             : $('input[name=_token]').val(),
            'provinsi'           :provinsi

        },
        beforeSend      : function()
        {
            $('#content_kabupaten_kota').html('<left><img src="{{ URL::asset("public/images/ajax-loader2.gif")}}" /> </left>');
            $("#content_kabupaten_kota").hide();
            $("#content_kabupaten_kota").fadeIn("slow");
       },
        success: function(data)
        {

              $("#content_kabupaten_kota").html(data.html);

        },
    });

  }

  function change_kabupaten_kota()
  {
     var kab_kota = $("#kab_kota").val();

    $.ajax({
        type: 'POST',
        url: 'formulir/change_kabupaten_kota',
        data: {
            '_token'             : $('input[name=_token]').val(),
            'kab_kota'           :kab_kota

        },
        beforeSend      : function()
        {
            $('#content_kecamatan').html('<left><img src="{{ URL::asset("public/images/ajax-loader2.gif")}}" /> </left>');
            $("#content_kecamatan").hide();
            $("#content_kecamatan").fadeIn("slow");
       },
        success: function(data)
        {

              $("#content_kecamatan").html(data.html);

        },
    });

  }


    function change_kecamatan()
    {
       var kecamatan = $("#kecamatan").val();

      $.ajax({
          type: 'POST',
          url: 'formulir/change_kecamatan',
          data: {
              '_token'             : $('input[name=_token]').val(),
              'kecamatan'          :kecamatan

          },
          beforeSend      : function()
          {
              $('#content_kelurahan').html('<left><img src="{{ URL::asset("public/images/ajax-loader2.gif")}}" /> </left>');
              $("#content_kelurahan").hide();
              $("#content_kelurahan").fadeIn("slow");
         },
          success: function(data)
          {

                $("#content_kelurahan").html(data.html);

          },
      });

    }

    function hitung_umur()
    {
       var tgl_Lahir = $("#tgl_lahir").val();
       $.ajax({
           type: 'POST',
           url: 'formulir/hitung_umur',
           data: {
               '_token'             : $('input[name=_token]').val(),
               'tgl_Lahir'          : tgl_Lahir

           },
           success: function(data)
           {

              $("#umur").val(data.umur);

           },
       });

    }

    function pilih_paket()
    {
        var kategori = $("#kategori").val();
        $("#paket").val('');
        $("#sub_paket").val('');
        $.ajax({
            type: 'POST',
            url: 'formulir/pilih_paket',
            data: {
                '_token'             : $('input[name=_token]').val(),
                'kategori'           : kategori

            },
            success: function(data)
            {

               $("#content_paket").html(data.html);
               pilih_sub_paket();
               pilih_harga_paket();

            },
        });

    }

    function pilih_sub_paket()
    {
        var paket = $("#paket").val();
        $.ajax({
            type: 'POST',
            url: 'formulir/pilih_sub_paket',
            data: {
                '_token'             : $('input[name=_token]').val(),
                'paket'              : paket

            },
            success: function(data)
            {

               $("#content_sub_paket").html(data.html);

            },
        });

    }


      function pilih_harga_paket()
      {
          var sub_paket = $("#sub_paket").val();
          $.ajax({
              type: 'POST',
              url: 'formulir/pilih_harga_paket',
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
                    <input class="form-control" type="text" id="tgl_lahir" onblur="hitung_umur()">
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
                    <select class="form-control" id="gol_darah">
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
                    <select class="form-control" id="pendidikan">
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
                  <select class="form-control"  id="pekerjaan">
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
                    <select class="form-control" id="provinsi" onchange="change_provinsi()">
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
                    <div id="content_kabupaten_kota">
                    <select class="form-control" id="kab_kota">
                      <option>Pilih</option>
                    </select>
                    </div>
                  </div>
                </div>

                <div class="form-group row">
                  <label for="exampleInputPassword2" class="col-sm-5 col-form-label">Kecamatan</label>
                  <div class="col-sm-7">
                    <div id="content_kecamatan">
                    <select class="form-control" id="kecamatan">
                      <option>Pilih</option>
                    </select>
                  </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="exampleInputPassword2" class="col-sm-5 col-form-label">Kelurahan</label>
                  <div class="col-sm-7">
                    <div id="content_kelurahan">
                    <select class="form-control" id="kelurahan">
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
                <br/>
                <p class="card-description" style="font-weight:bold;margin-top:0px">
                  Data Riwayat
                </p>
                <hr/>
                <div class="form-group row">
                  <label for="exampleInputPassword2" class="col-sm-5 col-form-label">Penyakit Yg Diderita</label>
                  <div class="col-sm-7">
                    <input type="text" class="form-control" id="penyakit_diderita">
                  </div>
                </div>
                <div class="form-group row">
                <label class="col-sm-5 col-form-label">Pengalaman Haji</label>
                <div class="col-sm-4">
                <div class="form-radio">
                <label class="form-check-label">
                <input id="pengalaman_haji" class="form-check-input" type="radio" checked="" value="Belum" name="pengalaman_haji" onclick="pengalaman_haji_action()">
                Belum
                <i class="input-helper"></i>
                </label>
                </div>
                </div>
                <div class="col-sm-3">
                <div class="form-radio">
                <label class="form-check-label">
                <input id="pengalaman_haji" class="form-check-input" type="radio" value="Sudah" name="pengalaman_haji" onclick="pengalaman_haji_action()">
                Sudah
                <i class="input-helper"></i>
                </label>
                </div>
                </div>
                </div>
                <div id="content_pengalaman_haji"></div>
                <div class="form-group row">
                <label class="col-sm-5 col-form-label">Pengalaman Umroh</label>
                <div class="col-sm-4">
                <div class="form-radio">
                <label class="form-check-label">
                <input id="pengalaman_umroh" class="form-check-input" type="radio" checked="" value="Belum" name="pengalaman_umroh" onclick="pengalaman_umroh_action()">
                Belum
                <i class="input-helper"></i>
                </label>
                </div>
                </div>
                <div class="col-sm-3">
                <div class="form-radio">
                <label class="form-check-label">
                <input id="pengalaman_umroh" class="form-check-input" type="radio" value="Sudah" name="pengalaman_umroh" onclick="pengalaman_umroh_action()">
                Sudah
                <i class="input-helper"></i>
                </label>
                </div>
                </div>
                </div>
                <div id="content_pengalaman_umroh"></div>
                </form>

            </div>
          </div>
        </div>
        </div>
        </div>
        <div id="content_info_tambahan"></div>
        <div id="content_info_tambahan_2"></div>
