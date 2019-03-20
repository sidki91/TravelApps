<style>
.select2-selection__rendered {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 12px;
}
</style>
<script>
$("#kode_kategori").select2();
$("#bulan").select2();


function open_kota()
{
    $.ajax({
        type: 'POST',
        url: 'paket_perjalanan/open_kota',
        data: {
                '_token'       : $('input[name=_token]').val(),
              },
        success: function(data)
        {
          bootbox.dialog(
              {
                  title   : '<div><b>Daftar Kota</b></div>',
                  message : (data.html)
              }
            );
        },
    });
}

function form_harga()
{
    $.ajax({
        type: 'POST',
        url: 'paket_perjalanan/form_harga',
        data: {
                '_token'       : $('input[name=_token]').val(),
                'token_id'     : $("#token_id").val()
              },
        success: function(data)
        {
          bootbox.dialog(
              {
                  title   : '<div><b>Form Harga Paket</b></div>',
                  message : (data.html)
              }
            );
        },
    });
}

</script>
 <div class="col-12 grid-margin">
   <input type="hidden" id="token_id" value="{{ csrf_token() }}"/>

   <div class="row">
    <div class="col-md-6">
      <div class="form-group row">
        <label class="col-sm-5 col-form-label">Bulan</label>
        <div class="col-sm-7">
          <select class="form-control" id="bulan" style="width:200px">
            <option value="">Pilih</option>
          @foreach($bulan as $row_bulan)
          <option value="{{$row_bulan->bulan}}">{{$row_bulan->bulan_name}}</option>
          @endforeach
          </select>
        </div>
      </div>
      <div class="form-group row">
        <label class="col-sm-5 col-form-label">Kategori Paket</label>
        <div class="col-sm-7">
          <select class="form-control" id="kode_kategori" style="width:200px">
            <option value="">Pilih</option>
          @foreach($kategori as $row_kategori)
          <option value="{{$row_kategori->kode_kategori}}">{{$row_kategori->deskripsi}}</option>
          @endforeach
          </select>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group row">
        <label class="col-sm-4 col-form-label">Kegiatan</label>
        <div class="col-sm-8">
          <textarea id="kegiatan" class="form-control" rows="3"></textarea>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
     <div class="col-md-6">
       <div class="form-group row">
         <label class="col-sm-5 col-form-label">Nama Paket</label>
         <div class="col-sm-7">
              <input type="text" class="form-control" id="nama_paket" />
         </div>
       </div>
     </div>
     <div class="col-md-6">
       <div class="form-group row">
         <label class="col-sm-4 col-form-label">Harga</label>
         <div class="col-sm-8">
           <button type="button" class="btn btn-info" onclick="form_harga()"><i class="fa fa-plus"></i> Add</button>

         </div>
       </div>
     </div>
   </div>
   <div class="row">
      <div class="col-md-6">
        <div class="form-group row">
          <label class="col-sm-5 col-form-label">Tujuan Kota</label>
          <div class="col-sm-7">
            <div class="input-group">
            <input class="form-control" type="text" id="tujuan_kota">
            <input type="hidden" id="kode_kota"/>
            <div class="input-group-append">
            <span class="input-group-text bg-transparent">
            <i class="fa fa-search" style="cursor:pointer" onclick="open_kota()"></i>
            </span>
            </div>

          </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group row">
          <label class="col-sm-4 col-form-label">Keterangan</label>
          <div class="col-sm-8">
              <textarea id="keterangan" class="form-control" rows="5"></textarea>
          </div>
        </div>
      </div>
    </div>
    <div class="row" style="margin-top:-52px">
       <div class="col-md-6">
         <div class="form-group row">
           <label class="col-sm-5 col-form-label">Lama Perjalanan</label>
           <div class="col-sm-7">
                <input type="text" id="lama_perjalanan" class="form-control"/>
           </div>
         </div>
       </div>
       <div class="col-md-6">
         <div class="form-group row">
           <label class="col-sm-4 col-form-label"></label>
           <div class="col-sm-8">

           </div>
         </div>
       </div>
     </div>
