@extends('layouts.app')
@section('content')
@section('menu','Formulir Jamaah')
<style media="screen">
.dialogWide > .modal-dialog
{
     width: 40% !important;
}
</style>

<script src="{{ URL::asset('public/js/script.js')}}"></script>
<!--<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>-->
<div id='content'>
  &nbsp;&nbsp;&nbsp;
 @if(access_level_user('create','formulir')=='allow')
  <button class="btn btn-facebook" onclick="add()"><i class="fa fa-plus"></i> Create Data </button>
 @endif
 <button class="btn btn-info" onclick="search_data()"><i class="fa fa-search"></i> Search Data </button>
  <a href="#" class="btn btn-github" onclick="list_data('')"><i class="fa fa-refresh"></i> Refresh </a>
<p></p>
<input type="hidden" id="action_val" value="">
<div id="temp_view_table">
<table class="table table-hover">
  <thead>
    <th  style="text-align:center">No</th>
    <th>Kode Registrasi</th>
    <th>Nama Jamaah</th>
    <th>Kategori</th>
    <th>Kota/Kabupaten</th>
    <th>Action</th>
  </thead>
</table>
</div>
<p></p>
<div id="ajax_list_table"></div>
    <p></p>
  <div id="pagination" class="pagging">
     <div><a href="#" id="1"></a></div>
   </div>
 </div>
<!-- Modal created -->
<div id="addModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title" id="myModalLabel">@yield('title_modal')</h4>
          </div>
          <div class="modal-body">
            <div id="HTMLcontent">
            <form class="form-horizontal" role="form">
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-3 control-label">Name</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="name" name="name">
                        <!-- <p class="help-block">Example block-level help text here.</p> -->
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-3 control-label">Email</label>
                    <div class="col-sm-9">
                        <input type="email" class="form-control" id="email" name="email">
                        <!-- <p class="help-block">Example block-level help text here.</p> -->
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-3 control-label">Username</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="username" name="username">
                        <!-- <p class="help-block">Example block-level help text here.</p> -->
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-3 control-label">Password</label>
                    <div class="col-sm-9">
                        <input type="password" class="form-control" id="password" name="password">
                        <!-- <p class="help-block">Example block-level help text here.</p> -->
                    </div>
                </div>

            </form>
            </div
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-success add"><i class="fa fa-save"></i> Save changes</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-cancel"></i> Close</button>
          </div>
      </div><!-- /.modal-content -->
  </div>  <!-- /.modal-dialog -->
</div>  <!-- /.modal -->

<script type="text/javascript">
  var save_method;

function search_enter(e)
  {
    var key=e.keyCode || e.which;
    if(key==13){
      list_data();
    }

  }
function search()
{
    var tgl_awal  = $("#tgl_awal").val();
    var tgl_akhir = $("#tgl_akhir").val();
    if(tgl_awal=='')
    {
        bootbox.alert("Tgl awal registrasi wajib di isi !");

    }
    else if(tgl_akhir=='')
    {
        bootbox.alert("Tgl akhir registrasi wajib di isi !");
    }
    else
    {
      $("#action_val").val($("#ouput").val());
      bootbox.hideAll();
      list_data();
    }

}
function list_data(key)
{
   var page            = 1;
   var pagination      = '';
   var tgl_awal        = $("#tgl_awal").val();
   var tgl_akhir       = $("#tgl_akhir").val();
   var kode_registrasi = $("#kode_registrasi").val();
   var nama_jamaah     = $("#nama_jamaah").val();
   var output          = $("#action_val").val();

  $.ajax({
          type: "POST",
           url: 'formulir/list_data',
           data: {
               '_token'         : $('input[name=_token]').val(),
               'page'           : page,
               'tgl_awal'       : tgl_awal,
               'tgl_akhir'      : tgl_akhir,
               'kode_registrasi':kode_registrasi,
               'nama_jamaah'    :nama_jamaah,
               'key'            : key,
               'output'         : output,
            },
           beforeSend      : function()
           {
               $('#ajax_list_table').html('<center><img src="{{ URL::asset("public/assets/images/831.gif")}}" width="30px" height="30px"/> <img src="{{ URL::asset("public/assets/images/332.gif")}}"/></center>');
               $("#ajax_list_table").hide();
               $("#ajax_list_table").fadeIn("slow");
          },
           success: function (data)
           {
                 if(data.output=='HTML')
                 {
                     document.getElementById('temp_view_table').style.visibility='hidden';
                      $('#ajax_list_table').html(data.html);
                      if (page == 1) pagination += '<div class="cell_disabled"><span>First</span></div><div class="cell_disabled"><span>Previous</span></div>';
                      else pagination += '<div class="cell"><a href="#" id="1" onclick="pagging_click('+urutan+')" >First</a></div><div class="cell"><a href="#" id="' + (page - 1) + '" onclick="pagging_click('+(page - 1)+')" >Previous</span></a></div>';

                       for (var i=parseInt(page)-3; i<=parseInt(page)+3; i++)
                       {
                         if (i >= 1 && i <= data.numPage)
                                 {
                           pagination += '<div';
                           if (i == page) pagination += ' class="cell_active"><span>' + i + '</span>';
                           else pagination += ' class="cell"><a href="#" id="' + i + '" onclick="pagging_click('+i+')" >' + i + '</a>';
                           pagination += '</div>';
                         }
                       }

                       if (page == data.numPage) pagination += '<div class="cell_disabled"><span>Next</span></div><div class="cell_disabled"><span>Last</span></div>';
                       else pagination += '<div class="cell"><a href="#" id="' + (parseInt(page) + 1) + '" onclick="pagging_click('+ (parseInt(page) + 1) +')"  >Next</a></div><div class="cell"><a href="#" id="' + data.numPage + '" onclick="pagging_click('+data.numPage+')">Last</span></a></div>';
                       pagination += '<div class="cell"><a>Total Data : '+data.numitem+' Item</a></div>';

                       $('#pagination').html(pagination);
                 }
                 else
                 {
                       document.getElementById('temp_view_table').style.visibility='hidden';
                        $('#ajax_list_table').html(data.html);
                        if (page == 1) pagination += '<div class="cell_disabled"><span>First</span></div><div class="cell_disabled"><span>Previous</span></div>';
                        else pagination += '<div class="cell"><a href="#" id="1" onclick="pagging_click('+urutan+')" >First</a></div><div class="cell"><a href="#" id="' + (page - 1) + '" onclick="pagging_click('+(page - 1)+')" >Previous</span></a></div>';

                         for (var i=parseInt(page)-3; i<=parseInt(page)+3; i++)
                         {
                           if (i >= 1 && i <= data.numPage)
                                   {
                             pagination += '<div';
                             if (i == page) pagination += ' class="cell_active"><span>' + i + '</span>';
                             else pagination += ' class="cell"><a href="#" id="' + i + '" onclick="pagging_click('+i+')" >' + i + '</a>';
                             pagination += '</div>';
                           }
                         }

                         if (page == data.numPage) pagination += '<div class="cell_disabled"><span>Next</span></div><div class="cell_disabled"><span>Last</span></div>';
                         else pagination += '<div class="cell"><a href="#" id="' + (parseInt(page) + 1) + '" onclick="pagging_click('+ (parseInt(page) + 1) +')"  >Next</a></div><div class="cell"><a href="#" id="' + data.numPage + '" onclick="pagging_click('+data.numPage+')">Last</span></a></div>';
                         pagination += '<div class="cell"><a>Total Data : '+data.numitem+' Item</a></div>';

                         $('#pagination').html(pagination);
                         //reset export output ke HTML
                         $("#action_val").val('');
                         window.open(data.link);
                 }




             },
             error: function()
             {
                 swal("Opps !", 'sorry, an error occurred while displaying data !', "error");
             }
       });
}

function pagging_click(id)
{
   var key    = '';
   var page   = id;
   var urutan = 1;
   var pagination = '';
   var name_search = $("#name_search").val();
   document.getElementById('temp_view_table').style.visibility='visible';
  $.ajax({
          type: "POST",
           url: 'formulir/list_data',
           data: {
               '_token'     : $('input[name=_token]').val(),
               'page'       : page,
               'name_search':name_search,
               'key'        :key
            },
           beforeSend      : function()
           {
               $('#ajax_list_table').html('<center><img src="{{ URL::asset("public/assets/images/831.gif")}}" width="30px" height="30px"/> <img src="{{ URL::asset("public/assets/images/332.gif")}}"/></center>');
               $("#ajax_list_table").hide();
               $("#ajax_list_table").fadeIn("slow");
          },
           success: function (data)
           {

             document.getElementById('temp_view_table').style.visibility='hidden';
              $('#ajax_list_table').html(data.html);
              if (page == 1) pagination += '<div class="cell_disabled"><span>First</span></div><div class="cell_disabled"><span>Previous</span></div>';
         else pagination += '<div class="cell"><a href="#" id="1" onclick="pagging_click('+urutan+')" >First</a></div><div class="cell"><a href="#" id="' + (page - 1) + '" onclick="pagging_click('+(page - 1)+')" >Previous</span></a></div>';

         for (var i=parseInt(page)-3; i<=parseInt(page)+3; i++)
               {
           if (i >= 1 && i <= data.numPage)
                   {
             pagination += '<div';
             if (i == page) pagination += ' class="cell_active"><span>' + i + '</span>';
             else pagination += ' class="cell"><a href="#" id="' + i + '" onclick="pagging_click('+i+')" >' + i + '</a>';
             pagination += '</div>';
           }
         }

         if (page == data.numPage) pagination += '<div class="cell_disabled"><span>Next</span></div><div class="cell_disabled"><span>Last</span></div>';
         else pagination += '<div class="cell"><a href="#" id="' + (parseInt(page) + 1) + '" onclick="pagging_click('+ (parseInt(page) + 1) +')"  >Next</a></div><div class="cell"><a href="#" id="' + data.numPage + '" onclick="pagging_click('+data.numPage+')">Last</span></a></div>';
           pagination += '<div class="cell"><a>Total Data : '+data.numitem+' Item</a></div>';

         $('#pagination').html(pagination);

       },
       error: function()
       {
           swal("Opps !", 'sorry, an error occurred while displaying data !', "error");
       }
       });
}
$(document).ready(function()
 {
    list_data();
 });


function add()
{


    save_method = 'add';

    $.ajax({
        type: 'POST',
        url: 'formulir/add',
        data:
        {
            '_token': $('input[name=_token]').val()
        },
        beforeSend      : function()
        {
            $('#content').html('<center><img src="{{ URL::asset("public/assets/images/831.gif")}}" width="30px" height="30px"/> <img src="{{ URL::asset("public/assets/images/332.gif")}}"/></center>');
            $("#content").hide();
            $("#content").fadeIn("slow");
       },
        success: function(data) {
          if(data.status=='success')
          {
              $("#content").html(data.html);
              clear();
          }
          else
          {
              alertMsg(data.msg,'error');
          }
        },
    });


}


  function info_tambahan()
  {
        $.ajax({
            type: 'POST',
            url: 'formulir/info_tambahan',
            data:
            {
                '_token': $('input[name=_token]').val()
            },
            beforeSend      : function()
            {
                $('#content_info_tambahan').html('<center><img src="{{ URL::asset("public/assets/images/831.gif")}}" width="30px" height="30px"/> <img src="{{ URL::asset("public/assets/images/332.gif")}}"/></center>');
                $("#content_info_tambahan").hide();
                $("#content_info_tambahan").fadeIn("slow");
           },
            success: function(data)
            {
                  $("#content_info_tambahan").html(data.html);
            },
        });
  }

  function info_tambahan_2()
  {
        $.ajax({
            type: 'POST',
            url: 'formulir/info_tambahan_2',
            data:
            {
                '_token': $('input[name=_token]').val()
            },
            beforeSend      : function()
            {
                $('#content_info_tambahan_2').html('<center><img src="{{ URL::asset("public/assets/images/831.gif")}}" width="30px" height="30px"/> <img src="{{ URL::asset("public/assets/images/332.gif")}}"/></center>');
                $("#content_info_tambahan_2").hide();
                $("#content_info_tambahan_2").fadeIn("slow");
           },
            success: function(data)
            {
                  $("#content_info_tambahan_2").html(data.html);
            },
        });
  }


  function save()
  {
      var nama_lengkap        = $("#nama_lengkap").val();
      var nama_ayah           = $("#nama_ayah").val();
      var tempat_lahir        = $("#tempat_lahir").val();
      var tgl_lahir           = $("#tgl_lahir").val();
      var umur                = $("#umur").val();
      var gol_darah           = $("#gol_darah").val();
      var jenis_kelamin       = $("#jenis_kelamin:checked").val();
      var pendidikan          = $("#pendidikan").val();
      var keterangan_menikah  = $("#keterangan_menikah:checked").val();
      var tgl_pernikahan      = $("#tgl_pernikahan").val();
      var pekerjaan           = $("#pekerjaan").val();
      var nama_instansi       = $("#nama_instansi").val();
      var alamat_instansi     = $("#alamat_instansi").val();
      var telp_instansi       = $("#telp_instansi").val();
      var nomor_pasport       = $("#nomor_pasport").val();
      var tgl_dikeluarakan    = $("#tgl_dikeluarakan").val();
      var tempat_dikeluarkan  = $("#tempat_dikeluarkan").val();
      var masa_berlaku        = $("#masa_berlaku").val();
      var alamat              = $("#alamat").val();
      var rt                  = $("#rt").val();
      var rw                  = $("#rw").val();
      var nomor_rumah         = $("#nomor_rumah").val();
      var provinsi            = $("#provinsi").val();
      var kab_kota            = $("#kab_kota").val();
      var kecamatan           = $("#kecamatan").val();
      var kelurahan           = $("#kelurahan").val();
      var kode_pos            = $("#kode_pos").val();
      var telepon             = $("#telepon").val();
      var handphone           = $("#handphone").val();
      var email               = $("#email").val();
      var kategori            = $("#kategori").val();
      var paket               = $("#paket").val();
      var sub_paket           = $("#sub_paket").val();
      var harga_paket_val     = $("#harga_paket_val").val();
      var program_dipilih     = $("#program_dipilih").val();
      var berangkat_dari      = $("#berangkat_dari").val();
      var penyakit_diderita   = $("#penyakit_diderita").val();
      var pengalaman_haji     = $("#pengalaman_haji:checked").val();
      var jumlah_haji         = $("#jumlah_haji").val();
      var tahun_haji          = $("#tahun_haji").val();
      var pengalaman_umroh    = $("#pengalaman_umroh:checked").val();
      var jumlah_umroh        = $("#jumlah_umroh").val();
      var tahun_umroh         = $("#tahun_umroh").val();
      var kode_registrasi     = $("#kode_registrasi").val();
      var url;
      if(kode_registrasi=='')
      {
          url = 'formulir/save';
      }
      else
      {
         url = 'formulir/update';
      }

      swal({
          title: "Info System",
          text: "Apakah anda yakin ingin menyimpan data ini ?",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete)
          {
              $.ajax({
                  type: 'POST',
                  url: url,
                  data: {
                      '_token'              : $('input[name=_token]').val(),
                      'nama_lengkap'        : nama_lengkap,
                      'nama_ayah'           : nama_ayah,
                      'tempat_lahir'        : tempat_lahir,
                      'tgl_lahir'           : tgl_lahir,
                      'umur'                : umur,
                      'gol_darah'           : gol_darah,
                      'jenis_kelamin'       : jenis_kelamin,
                      'pendidikan'          : pendidikan,
                      'keterangan_menikah'  : keterangan_menikah,
                      'tgl_pernikahan'      : tgl_pernikahan,
                      'pekerjaan'           : pekerjaan,
                      'nama_instansi'       : nama_instansi,
                      'alamat_instansi'     : alamat_instansi,
                      'telp_instansi'       : telp_instansi,
                      'nomor_pasport'       : nomor_pasport,
                      'tgl_dikeluarakan'    : tgl_dikeluarakan,
                      'tempat_dikeluarkan'  : tempat_dikeluarkan,
                      'masa_berlaku'        : masa_berlaku,
                      'alamat'              : alamat,
                      'rt'                  : rt,
                      'rw'                  : rw,
                      'nomor_rumah'         : nomor_rumah,
                      'provinsi'            : provinsi,
                      'kab_kota'            : kab_kota,
                      'kecamatan'           : kecamatan,
                      'kelurahan'           : kelurahan,
                      'kode_pos'            : kode_pos,
                      'telepon'             : telepon,
                      'handphone'           : handphone,
                      'email'               : email,
                      'penyakit_diderita'   : penyakit_diderita,
                      'pengalaman_haji'     : pengalaman_haji,
                      'jumlah_haji'         : jumlah_haji,
                      'tahun_haji'          : tahun_haji,
                      'pengalaman_umroh'    : pengalaman_umroh,
                      'jumlah_umroh'        : jumlah_umroh,
                      'tahun_umroh'         : tahun_umroh,
                      'kode_registrasi'     : kode_registrasi

                  },
                  success: function(data) {
                  if(data.status=='error')
                  {
                      if (data.errors.nama_lengkap)
                      {
                            alertMsg(data.errors.nama_lengkap,'error');
                      }
                      if (data.errors.nama_ayah)
                      {
                            alertMsg(data.errors.nama_ayah,'error');
                      }
                      if (data.errors.tempat_lahir)
                      {
                            alertMsg(data.errors.tempat_lahir,'error');
                      }
                      if (data.errors.tgl_lahir)
                      {
                            alertMsg(data.errors.tgl_lahir,'error');
                      }
                      if (data.errors.umur)
                      {
                            alertMsg(data.errors.umur,'error');
                      }
                      if (data.errors.gol_darah)
                      {
                            alertMsg(data.errors.gol_darah,'error');
                      }
                      if (data.errors.jenis_kelamin)
                      {
                            alertMsg(data.errors.jenis_kelamin,'error');
                      }
                      if (data.errors.pendidikan)
                      {
                            alertMsg(data.errors.pendidikan,'error');
                      }
                      if (data.errors.keterangan_menikah)
                      {
                            alertMsg(data.errors.keterangan_menikah,'error');
                      }
                      if (data.errors.pekerjaan)
                      {
                            alertMsg(data.errors.pekerjaan,'error');
                      }
                      if (data.errors.nama_instansi)
                      {
                            alertMsg(data.errors.nama_instansi,'error');
                      }
                      if (data.errors.alamat_instansi)
                      {
                            alertMsg(data.errors.alamat_instansi,'error');
                      }
                      if (data.errors.telp_instansi)
                      {
                            alertMsg(data.errors.telp_instansi,'error');
                      }
                      if (data.errors.nomor_pasport)
                      {
                            alertMsg(data.errors.nomor_pasport,'error');
                      }
                      if (data.errors.tgl_dikeluarakan)
                      {
                            alertMsg(data.errors.tgl_dikeluarakan,'error');
                      }
                      if (data.errors.tgl_dikeluarakan)
                      {
                            alertMsg(data.errors.tgl_dikeluarakan,'error');
                      }
                      if (data.errors.tempat_dikeluarkan)
                      {
                            alertMsg(data.errors.tempat_dikeluarkan,'error');
                      }
                      if (data.errors.masa_berlaku)
                      {
                            alertMsg(data.errors.masa_berlaku,'error');
                      }
                      if (data.errors.alamat)
                      {
                            alertMsg(data.errors.alamat,'error');
                      }
                      if (data.errors.rt)
                      {
                            alertMsg(data.errors.rt,'error');
                      }
                      if (data.errors.rw)
                      {
                            alertMsg(data.errors.rw,'error');
                      }

                      if (data.errors.provinsi)
                      {
                            alertMsg(data.errors.provinsi,'error');
                      }
                      if (data.errors.kab_kota)
                      {
                            alertMsg(data.errors.kab_kota,'error');
                      }
                      if (data.errors.kecamatan)
                      {
                            alertMsg(data.errors.kecamatan,'error');
                      }
                      if (data.errors.kelurahan)
                      {
                            alertMsg(data.errors.kelurahan,'error');
                      }
                      if (data.errors.handphone)
                      {
                            alertMsg(data.errors.handphone,'error');
                      }




                  }
                    if(data.status=='success')
                    {
                          swal("Good job!", data.msg, "success");
                          //list_data();
                          $("#kode_registrasi").val(data.kode_registrasi);
                          if(kode_registrasi=='')
                          {
                            //info_tambahan();
                            info_tambahan_2();
                          }
                          else
                          {
                            //list_info_tambahan();
                            list_info_tambahan2();
                          }

                        if(data.action=='update')
                        {
                            $("#jumlah_haji_value").val(data.jumlah_haji);
                            $("#tahun_haji_value").val(data.tahun_haji);
                            $("#jumlah_umroh_value").val(data.jumlah_umroh);
                            $("#tahun_umroh_value").val(data.tahun_umroh);
                        }

                    }
                    else
                    {
                        swal("Not Good !", data.msg, "error");
                        //list_data();
                    }
                  },
              });
          }
          else
          {
            return false;
          }
      });
  }
  function edit(id)
  {
    save_method = 'update';
    $.ajax({
        type: 'POST',
        url: 'formulir/edit',
        data: {
            '_token': $('input[name=_token]').val(),
            'id'    :id

        },
        beforeSend      : function()
        {
            $('#content').html('<center><img src="{{ URL::asset("public/assets/images/831.gif")}}" width="30px" height="30px"/> <img src="{{ URL::asset("public/assets/images/332.gif")}}"/></center>');
            $("#content").hide();
            $("#content").fadeIn("slow");
       },
        success: function(data) {
          if(data.status=='success')
          {
              $("#content").html(data.html);
          }
          else
          {
              alertMsg(data.msg,'error');
          }
        },
    });

  }

  function delete_data(id)
  {
      swal({
          title: "Info System",
          text: "Are you sure you want to delete this data ?",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete)
          {
              $.ajax({
                  type: 'POST',
                  url: 'formulir/delete',
                  data: {
                      '_token': $('input[name=_token]').val(),
                      'id'    :id

                  },
                  success: function(data) {
                    if(data.status=='success')
                    {
                          swal("Good job!", data.msg, "success");
                          list_data();
                    }
                    else
                    {
                        swal("Not Good !", data.msg, "error");
                        list_data();
                    }
                  },
              });
          }
          else
          {
            return false;
          }
      });
  }

  function search_data()
  {
      $.ajax({
          type: 'POST',
          url: 'formulir/form_search',
          data: {
                  '_token'       : $('input[name=_token]').val(),
                },
          success: function(data)
          {
            bootbox.dialog(
                {
                    className: "dialogWide",
                    title   : '<div><b>Search</b></div>',
                    message : (data.html)
                }
              );
          },
      });
  }

  function export_pdf(kode_registrasi='')
  {
    if(kode_registrasi=='')
    {
        var kode_registrasi_val = $("#kode_registrasi").val();
    }
    else
    {
      var kode_registrasi_val = kode_registrasi;
    }
        $.ajax({
            type: 'POST',
            url: 'formulir/export_pdf',
            data:
            {
                '_token': $('input[name=_token]').val(),
                'kode'  : kode_registrasi_val
            },

            success: function(data)
            {
                if(data.status=='success')
                {
                      window.open(data.link);
                }
                else
                {
                    bootbox.alert(data.msg)
                }
            },
        });
  }




</script>

@endsection
