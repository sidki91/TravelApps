@extends('layouts.app')
@section('content')
@section('title','User Management')
@section('class_role','active subdrop')
@section('class_ul_role','display: block')
@section('m_user','active')
@section('menu','Users')
<script src="{{ URL::asset('public/js/script.js')}}"></script>
&nbsp;&nbsp;&nbsp;
  @if(access_level_user('create','user')=='allow')
  <button class="btn btn-facebook add-data"><i class="mdi mdi-plus"></i> Create Data </button>
  @endif
  <button class="btn btn-github" onclick="list_data()"><i class="fa fa-refresh"></i> Refresh </button>
<p></p>
						<div class="form-inline">
						  <div class="form-group">
						   <div class="col-md-12">
              <input type="text" class="form-control" id="name_search" placeholder="Enter Name" onkeypress="search_enter(event)" style="width:450px">
              <button class="btn btn-success search-data" onclick="list_data()"><i class="fa fa-search"></i> Search</button>

              </div>
            </div>
              <div class="form-group">
						    </div>
						</div>

<div id="temp_view_table">
<table class="table table-hover">
  <thead>
    <th  style="text-align:center">No</th>
    <th>Name</th>
    <th>Email</th>
    <th>Username</th>
    <th>Created Date</th>
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
function clear()
{
    $("#name").val('');
    $("#email").val('');
    $("#username").val('');
    $("#password").val('');
}
function list_data()
{
   var page = 1;
   var pagination  = '';
   var name_search = $("#name_search").val();
  $.ajax({
          type: "POST",
           url: 'user/list_data',
           data: {
               '_token'      : $('input[name=_token]').val(),
               'page'        : page,
               'name_search' : name_search
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

function pagging_click(id)
{
   var page   = id;
   var urutan = 1;
   var pagination = '';
   var name_search = $("#name_search").val();
   document.getElementById('temp_view_table').style.visibility='visible';
  $.ajax({
          type: "POST",
           url: 'user/list_data',
           data: {
               '_token'     : $('input[name=_token]').val(),
               'page'       : page,
               'name_search':name_search
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


$(document).on('click', '.add-data', function() {
    save_method = 'add';
    clear();
    $.ajax({
        type: 'POST',
        url: 'user/add',
        data:
        {
            '_token': $('input[name=_token]').val()
        },
        success: function(data) {
          if(data.status=='success')
          {
              $("#addModal").modal("show");
              $('.modal-title').text('Add');
              $("#HTMLcontent").html(data.html);
              clear();

          }
          else
          {
              alertMsg(data.msg,'error');
          }
        },
    });


  });


  $('.modal-footer').on('click', '.add', function() {
    var url;
    if(save_method == 'add')
    {
      url = "user";
    }
    else
    {
      url = "user/update";
    }
      $.ajax({
          type: 'POST',
          url : url,
          data: {
              '_token'      : $('input[name=_token]').val(),
              'name'        : $('#name').val(),
              'email'       : $('#email').val(),
              'username'    : $('#username').val(),
              'password'    : $('#password').val(),
              'id'          : $('#id_user').val(),
              'priveleges'  : $('#priveleges').val()

          },
          success: function(data) {
            if(data.status=='error')
            {
                if (data.errors.name)
                {
                      alertMsg(data.errors.name,'error');
                }
                if (data.errors.email)
                {
                      alertMsg(data.errors.email,'error');
                }
                if (data.errors.username)
                {
                      alertMsg(data.errors.username,'error');
                }
                if (data.errors.password)
                {
                      alertMsg(data.errors.password,'error');
                }


            }
            if(data.status=='success')
            {
                $('#addModal').modal('hide');
                // alertMsg(data.msg,'success');
                swal("Info System", data.msg, "success");
                list_data();
            }
            if(data.status=='failed')
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
        url: 'user/edit',
        data: {
            '_token': $('input[name=_token]').val(),
            'id'    :id

        },
        success: function(data) {
          if(data.status=='success')
          {
              $("#addModal").modal("show");
              $('.modal-title').text('Edit');
              $("#HTMLcontent").html(data.html);
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
                  url: 'user/delete',
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

</script>

@endsection
