@extends('layouts.app')
@section('content')
@section('menu','Pemesanan Paket Perjalanan')

<style media="screen">
.dialogWide > .modal-dialog
{
     width:65% !important;
}
</style>
<script src="{{ URL::asset('public/js/script.js')}}"></script>
<!--<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>-->
<div id='content'>
  &nbsp;&nbsp;&nbsp;
 @if(access_level_user('create','pemesanan')=='allow')
  <button class="btn btn-facebook add-data"><i class="fa fa-plus"></i> Create Data </button>
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
  function search_data()
  {
      $.ajax({
          type: 'POST',
          url: 'pemesanan/form_search',
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
        $("#action_val").val($("#output_data").val());
        bootbox.hideAll();
        list_data();
      }

  }
function list_data(key)
{
   var page = 1;
   var pagination  = '';

   var tgl_awal        = $("#tgl_awal").val();
   var tgl_akhir       = $("#tgl_akhir").val();
   var jenis_pemesanan = $("#jenis_pemesanan").val();
   var kategori        = $("#kategori_search").val();
   var paket           = $("#paket_search").val();
   var sub_paket       = $("#sub_paket_search").val();
   var tgl_berangkat   = $("#tgl_berangkat_search").val();
   var tgl_kembali     = $("#tgl_kembali_search").val();
   var nomor_pesanan   = $("#nomor_pesanan").val();
   var status          = $("#status").val();
   var output          = $("#action_val").val();
  $.ajax({
          type: "POST",
           url: 'pemesanan/list_data',
           data: {
               '_token'          : $('input[name=_token]').val(),
               'page'            : page,
               'tgl_awal'        : tgl_awal,
               'tgl_akhir'       : tgl_akhir,
               'jenis_pemesanan' : jenis_pemesanan,
               'kategori'        : kategori,
               'paket'           : paket,
               'sub_paket'       : sub_paket,
               'tgl_berangkat'   : tgl_berangkat,
               'tgl_kembali'     : tgl_kembali,
               'nomor_pesanan'   : nomor_pesanan,
               'status'          : status,
               'output'          : output,
               'key'             : key
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
   var tgl_awal        = $("#tgl_awal").val();
   var tgl_akhir       = $("#tgl_akhir").val();
   var jenis_pemesanan = $("#jenis_pemesanan").val();
   var kategori        = $("#kategori_search").val();
   var paket           = $("#paket_search").val();
   var sub_paket       = $("#sub_paket_search").val();
   var tgl_berangkat   = $("#tgl_berangkat_search").val();
   var tgl_kembali     = $("#tgl_kembali_search").val();
   var nomor_pesanan   = $("#nomor_pesanan").val();
   var status          = $("#status").val();
   var output          = $("#action_val").val();
   document.getElementById('temp_view_table').style.visibility='visible';
  $.ajax({
          type: "POST",
           url: 'pemesanan/list_data',
           data: {
             '_token'          : $('input[name=_token]').val(),
             'page'            : page,
             'tgl_awal'        : tgl_awal,
             'tgl_akhir'       : tgl_akhir,
             'jenis_pemesanan' : jenis_pemesanan,
             'kategori'        : kategori,
             'paket'           : paket,
             'sub_paket'       : sub_paket,
             'tgl_berangkat'   : tgl_berangkat,
             'tgl_kembali'     : tgl_kembali,
             'nomor_pesanan'   : nomor_pesanan,
             'status'          : status,
             'output'          : output,
             'key'             : key
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

                   $("#action_val").val('');
                   //window.open(data.link);
               }

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


$(document).on('click', '.add-data', function() {

    save_method = 'add';
    $.ajax({
        type: 'POST',
        url: 'pemesanan/add',
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

          }
          else
          {
              alertMsg(data.msg,'error');
          }
        },
    });


  });


  function edit(id)
  {
    save_method = 'update';
    $.ajax({
        type: 'POST',
        url: 'pemesanan/edit',
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

  function generate()
  {
    var url;
    var action = $("#action").val();
    if(action == 'add')
    {
      url = 'pemesanan/generate';
    }
    else
    {
      url = "pemesanan/update";
    }

      $.ajax({
          type: 'POST',
          url: url,
          data: {
              '_token'             : $('input[name=_token]').val(),
              'bulan'              : $("#bulan").val(),
              'jenis'              : $("#jenis").val(),
              'tgl_pemesanan'      : $("#tgl_pemesanan").val(),
              'keterangan'         : $("#keterangan").val(),
              'kategori'           : $("#kategori").val(),
              'lama_perjalanan'    : $("#lama_perjalanan_val").val(),
              'tgl_berangkat'      : $("#tgl_berangkat").val(),
              'tgl_kembali'        : $("#tgl_kembali").val(),
              'paket'              : $("#paket").val(),
              'sub_paket'          : $("#sub_paket").val(),
              'berangkat_dari'     : $("#berangkat_dari").val(),
              'harga'              : $("#harga_paket_val").val(),
              'jumlah_jamaah'      : $("#jumlah_jamaah").val(),
              'total_harga'        : $("#total_harga").val(),
              'nomor_pesanan'      : $("#nomor_pesanan").val()

          },
          beforeSend      : function()
          {
              $('#content_detail').html('<center><img src="{{ URL::asset("public/assets/images/831.gif")}}" width="30px" height="30px"/> <img src="{{ URL::asset("public/assets/images/332.gif")}}"/></center>');
              $("#content_detail").hide();
              $("#content_detail").fadeIn("slow");
         },
          success: function(data)
          {

              if(data.status=='error')
              {
                  if(data.errors.bulan)
                  {
                      alertMsg(data.errors.bulan,'error');
                  }
                  if(data.errors.kategori)
                  {
                      alertMsg(data.errors.kategori,'error');
                  }
                  if(data.errors.tgl_berangkat)
                  {
                      alertMsg(data.errors.tgl_berangkat,'error');
                  }
                  if(data.errors.paket)
                  {
                      alertMsg(data.errors.paket,'error');
                  }
                  if(data.errors.sub_paket)
                  {
                      alertMsg(data.errors.sub_paket,'error');
                  }
                  if(data.errors.berangkat_dari)
                  {
                      alertMsg(data.errors.berangkat_dari,'error');
                  }
                  if(data.errors.harga)
                  {
                      alertMsg(data.errors.harga,'error');
                  }
                  if(data.errors.jumlah_jamaah)
                  {
                      alertMsg(data.errors.jumlah_jamaah,'error');
                  }

                    $("#content_detail").html('');
              }

              if(data.status=='success')
              {
                $("#total_harga_val").val(data.total_harga);
                $("#nomor_pesanan").val(data.nomor_pesanan);
                if(action=='add')
                {
                    $("#content_detail").html(data.html);
                }
                else
                {
                    list_data_item();
                }


                //$("#generate_btn").prop('disabled', true);
              }
              else
              {
                 swal("Opps !", data.msg, "error");
                 list_data_item();
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
                  url: 'pemesanan/delete',
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

  function export_pemesanan(key='')
  {
      if(key=='')
      {
        var nomor_pesanan = $("#nomor_pesanan").val();
      }
      else
      {
        var nomor_pesanan = key;
      }

      $.ajax({
          type: 'POST',
          url: 'pemesanan/pdf_pesanan',
          data: {
                  '_token'        : $('input[name=_token]').val(),
                  'nomor_pesanan' : nomor_pesanan
                },
          success: function(data)
          {
            if(data.status=='success')
            {
                  window.open(data.link);
                  window.open(data.link_2);
            }
            else
            {
                bootbox.alert(data.msg)
            }
          },
      });
  }

  function finish()
  {
      window.location.href = '{{URL::to('pemesanan')}}';
      export_pemesanan();
  }


</script>

@endsection
