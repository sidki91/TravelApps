@extends('layouts.app')
@section('content')
@section('menu','Pembayaran')


<script src="{{ URL::asset('public/js/script.js')}}"></script>
<!--<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>-->
<div id='content'>
  &nbsp;&nbsp;&nbsp;
 @if(access_level_user('create','pemesanan')=='allow')
  <button class="btn btn-facebook" onclick="add_form()" ><i class="fa fa-plus"></i> Create Data </button>
 @endif
 <button class="btn btn-info" onclick="search_data()"><i class="fa fa-search"></i> Search Data </button>

  <a href="#" class="btn btn-github" onclick="list_data('')"><i class="fa fa-refresh"></i> Refresh </a>
<p></p>
<input type="hidden" id="action_val" value="">
<div id="temp_view_table">
<table class="table table-hover">
  <thead>
    <th  style="text-align:center">No</th>
    <th>No Pembayaran</th>
    <th>Tgl Bayar</th>
    <th>Nomor Pemesanan</th>
    <th>Total Tagihan</th>
    <th>Jumlah Bayar</th>
    <th>Sisa Bayar</th>
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

  function search_data()
  {
      $.ajax({
          type: 'POST',
          url: 'pembayaran/form_search',
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
   var name_search = $("#name_search").val();
  $.ajax({
          type: "POST",
           url: 'pembayaran/list_data',
           data: {
               '_token'           : $('input[name=_token]').val(),
               'page'             : page,
               'tgl_awal'         : $("#tgl_awal").val(),
               'tgl_akhir'        : $("#tgl_akhir").val(),
               'nomor_pembayaran' : $("#nomor_pembayaran").val(),
               'jenis_pembayaran' : $("#jenis_pembayaran").val(),
               'output'           : $("#action_val").val(),
               'key'              : key
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
   var name_search = $("#name_search").val();
   document.getElementById('temp_view_table').style.visibility='visible';
  $.ajax({
          type: "POST",
           url: 'pembayaran/list_data',
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


  function add_form()
  {
    save_method = 'add';
    $.ajax({
        type: 'POST',
        url: 'pembayaran/add',
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
  }







  function edit(id)
  {
    save_method = 'update';
    $.ajax({
        type: 'POST',
        url: 'pembayaran/edit',
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
      url = 'pembayaran/save';
    }
    else
    {
      url = "pembayaran/update";
    }

      $.ajax({
          type: 'POST',
          url: url,
          data: {
              '_token'             : $('input[name=_token]').val(),
              'tgl_pembayaran'     : $("#tgl_pembayaran").val(),
              'keterangan'         : $("#keterangan").val(),
              'jumlah_bayar'       : $("#jumlah_bayar_val").val(),
              'nomor_pesanan'      : $("#nomor_pesanan").val(),
              'jenis'              : $("#jenis").val()
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
                  if(data.errors.tgl_pembayaran)
                  {
                      alertMsg(data.errors.tgl_pembayaran,'error');
                  }
                  if(data.errors.nomor_pesanan)
                  {
                      alertMsg(data.errors.nomor_pesanan,'error');
                  }
                  if(data.errors.jumlah_bayar)
                  {
                      alertMsg(data.errors.jumlah_bayar,'error');
                  }
                  $("#content_detail").html('');
              }

              if(data.status=='success')
              {
                swal("Info System", data.msg, "success");
                $("#nomor_pembayaran").val(data.nomor_pembayaran);
                export_pembayaran();
                add_form();
              }


          },

      });

  }


  function delete_data(id,nomor_pesanan)
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
                  url: 'pembayaran/delete',
                  data: {
                      '_token'        : $('input[name=_token]').val(),
                      'id'            :id,
                      'nomor_pesanan' :nomor_pesanan

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

  function export_pembayaran(key='')
  {
      if(key=='')
      {
        var nomor_pembayaran = $("#nomor_pembayaran").val();
      }
      else
      {
        var nomor_pembayaran = key;
      }

      $.ajax({
          type: 'POST',
          url: 'pembayaran/pdf_pembayaran',
          data: {
                  '_token'           : $('input[name=_token]').val(),
                  'nomor_pembayaran' : nomor_pembayaran
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

  function finish()
  {
      window.location.href = '{{URL::to('pemesanan')}}';
      export_pemesanan();
  }


</script>

@endsection
