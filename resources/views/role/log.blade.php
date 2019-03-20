@extends('layouts.app')
@section('content')
@section('title','Log User Access')
@section('class_log','active subdrop')



<script src="{{ URL::asset('public/js/script.js')}}"></script>
<script src="{{ URL::asset('public/js/grafik/jquery_grafik.min.js')}}"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js">
</script>


<script src="{{ URL::asset('public/js/grafik/highcharts.js')}}">
</script>
<script src="{{ URL::asset('public/js/grafik/highcharts-3d.js')}}">
</script>
<script src="{{ URL::asset('public/js/grafik/exporting.js')}}">
</script>
<style>
    #left {
      width: 50%;
      float: left;
    }
    #right {
      margin-left: 200px;
      /* Change this to whatever the width of your left column is*/
    }
    .clear {
      clear: both;
    }
  </style>
  <script type="text/javascript">
  $(document).ready(function()
   {
      $("#tgl_awal").datepicker();
      $("#tgl_akhir").datepicker();
   });
  </script>

  &nbsp;&nbsp;&nbsp;
  <button class="btn btn-github" onclick="list_data()"><i class="fa fa-refresh"></i> Refresh </button>

<p></p>
<form class="form-inline" method="post">
<input type="hidden" id="user_id_val"/>
<input type="hidden" id="apps"/>
<input type="hidden" id="time"/>
 <div class="form-group">
   <div class="col-sm-10">
     <div class="input-group">
     <div class="input-group-prepend">
     <input class="form-control input-group-text" type="text" id="tgl_awal" style="width:190px" value="{{date('m/01/Y')}}" >
     </div>
     <div class="input-group-prepend">
     <span class="input-group-text"><i class="fa fa-calendar"></i></span>
     </div>
     <input class="form-control" type="text"  id="tgl_akhir" value="{{date('m/d/Y')}}">
     </div>
       </div>
 </div>
 <div class="form-group" style="margin-left: -85px;">
   <button type="button" class="btn btn-success" onclick="list_data()"><i class="fa fa-search"></i> Search</button>
   <!--<button type="submit" class="btn btn-danger"><i class="icon wb-print"></i> Export</button>-->
   </div>
</form>
            <div id="content_cart"></div>
<div id="temp_view_table">
<table class="table table-hover">
  <thead>
    <th  style="text-align:center">No</th>
    <th>Module</th>
    <th>Activity</th>
    <th>User</th>
    <th>Log Date</th>
    <th>Desc</th>
    <th>Browser</th>
    <th>Platform</th>
  </thead>
</table>
</div>
<p></p>
<div id="ajax_list_table"></div>
    <p></p>
  <div id="pagination" class="pagging">
     <div><a href="#" id="1"></a></div>
   </div>


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


function chart()
{
    var tgl_awal     = $("#tgl_awal").val();
    var tgl_akhir    = $("#tgl_akhir").val();
    var user_id      = $("#user_id_val").val();
    var time         = $("#time").val();
    var apps         = $("#apps").val();
    var data =
    {
	     'tgl_awal'    : tgl_awal,
       'tgl_akhir'   : tgl_akhir,
       'user_id'     : user_id,
       'time'        : time,
       'apps'        : apps,
       '_token'      : $('input[name=_token]').val(),

    };

      $.ajax({
      url         	: "log/display_chart_log",
      dataType    	: "json",
      type			: "post",
      data			: data,
      beforeSend      : function()
        {
            $('#content_cart').html('<center><img src="{{ URL::asset("public/assets/images/831.gif")}}" width="30px" height="30px"/> <img src="{{ URL::asset("public/assets/images/332.gif")}}"/></center>');
            $("#content_cart").hide();
            $("#content_cart").fadeIn("slow");


        },
      success  : function (result)
      {
        $('#content_cart').html(result.content).fadeIn("slow");
      }
    }
          );

}
function list_data()
{
   var page = 1;
   var pagination  = '';
   var tgl_awal     = $("#tgl_awal").val();
   var tgl_akhir    = $("#tgl_akhir").val();
  $.ajax({
          type: "POST",
           url: 'log/list_data',
           data: {
               '_token'      : $('input[name=_token]').val(),
               'page'        : page,
               'tgl_awal'    : tgl_awal,
               'tgl_akhir'   : tgl_akhir
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
               chart();
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
           url: 'log/list_data',
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






</script>
</div>
@endsection
