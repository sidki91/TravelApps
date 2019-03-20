
<script type="text/javascript">
function search_enter(e)
  {
    var key=e.keyCode || e.which;
    if(key==13){
      list_data_kota();
    }

  }
function list_data_kota()
{
   var page = 1;
   var pagination = '';
   var description = $("#search_text").val();
  $.ajax({
          type: "POST",
           url: 'pemesanan/list_data_jamaah',
           data: {
               '_token'      : $('input[name=_token]').val(),
               'page'        : page,
               'description' : description
            },
           beforeSend      : function()
           {
               $('#ajax_list_table_kota').html('<center><img src="{{ URL::asset("public/assets/images/831.gif")}}" width="30px" height="30px"/> <img src="{{ URL::asset("public/assets/images/332.gif")}}"/></center>');
               $("#ajax_list_table_kota").hide();
               $("#ajax_list_table_kota").fadeIn("slow");
          },
           success: function (data)
           {

             document.getElementById('temp_view_table_kota').style.visibility='hidden';
              $('#ajax_list_table_kota').html(data.html);
              if (page == 1) pagination += '<div class="cell_disabled"><span>First</span></div><div class="cell_disabled"><span>Previous</span></div>';
              else pagination += '<div class="cell"><a href="#" id="1" onclick="pagging_click_kota('+urutan+')" >First</a></div><div class="cell"><a href="#" id="' + (page - 1) + '" onclick="pagging_click_kota('+(page - 1)+')" >Previous</span></a></div>';

               for (var i=parseInt(page)-3; i<=parseInt(page)+3; i++)
               {
                 if (i >= 1 && i <= data.numPage)
                         {
                   pagination += '<div';
                   if (i == page) pagination += ' class="cell_active"><span>' + i + '</span>';
                   else pagination += ' class="cell"><a href="#" id="' + i + '" onclick="pagging_click_kota('+i+')" >' + i + '</a>';
                   pagination += '</div>';
                 }
               }

               if (page == data.numPage) pagination += '<div class="cell_disabled"><span>Next</span></div><div class="cell_disabled"><span>Last</span></div>';
               else pagination += '<div class="cell"><a href="#" id="' + (parseInt(page) + 1) + '" onclick="pagging_click_kota('+ (parseInt(page) + 1) +')"  >Next</a></div><div class="cell"><a href="#" id="' + data.numPage + '" onclick="pagging_click_kota('+data.numPage+')">Last</span></a></div>';
               pagination += '<div class="cell"><a>Total Data : '+data.numitem+' Item</a></div>';

               $('#pagination_kota').html(pagination);

            },
            error: function()
            {
                swal("Opps !", 'sorry, an error occurred while displaying data !', "error");
            }
       });
}

function pagging_click_kota(id)
{
   var page   = id;
   var urutan = 1;
   var pagination = '';
   var description = $("#search_text").val();
   document.getElementById('temp_view_table_kota').style.visibility='visible';
  $.ajax({
          type: "POST",
           url: 'pemesanan/list_data_jamaah',
           data: {
               '_token': $('input[name=_token]').val(),
               'page'  : page,
               'description':description
            },
           beforeSend      : function()
           {
               $('#ajax_list_table_kota').html('<center><img src="{{ URL::asset("public/assets/images/831.gif")}}" width="30px" height="30px"/> <img src="{{ URL::asset("public/assets/images/332.gif")}}"/></center>');
               $("#ajax_list_table_kota").hide();
               $("#ajax_list_table_kota").fadeIn("slow");
          },
           success: function (data)
           {

             document.getElementById('temp_view_table_kota').style.visibility='hidden';
              $('#ajax_list_table_kota').html(data.html);
              if (page == 1) pagination += '<div class="cell_disabled"><span>First</span></div><div class="cell_disabled"><span>Previous</span></div>';
         else pagination += '<div class="cell"><a href="#" id="1" onclick="pagging_click_kota('+urutan+')" >First</a></div><div class="cell"><a href="#" id="' + (page - 1) + '" onclick="pagging_click_kota('+(page - 1)+')" >Previous</span></a></div>';

         for (var i=parseInt(page)-3; i<=parseInt(page)+3; i++)
               {
           if (i >= 1 && i <= data.numPage)
                   {
             pagination += '<div';
             if (i == page) pagination += ' class="cell_active"><span>' + i + '</span>';
             else pagination += ' class="cell"><a href="#" id="' + i + '" onclick="pagging_click_kota('+i+')" >' + i + '</a>';
             pagination += '</div>';
           }
         }

         if (page == data.numPage) pagination += '<div class="cell_disabled"><span>Next</span></div><div class="cell_disabled"><span>Last</span></div>';
         else pagination += '<div class="cell"><a href="#" id="' + (parseInt(page) + 1) + '" onclick="pagging_click_kota('+ (parseInt(page) + 1) +')"  >Next</a></div><div class="cell"><a href="#" id="' + data.numPage + '" onclick="pagging_click_kota('+data.numPage+')">Last</span></a></div>';
           pagination += '<div class="cell"><a>Total Data : '+data.numitem+' Item</a></div>';

         $('#pagination_kota').html(pagination);

       },
       error: function()
       {
           swal("Opps !", 'sorry, an error occurred while displaying data !', "error");
       }
       });
}
$(document).ready(function()
 {
    list_data_kota();
 });
</script>
<style>
#pagination_kota div { display: inline-block; margin-right: 5px; margin-top: 5px }
#pagination_kota .cell a { border-radius: 3px; font-size: 11px; color: #333; padding: 8px; text-decoration:none; border: 1px solid #d3d3d3; background-color: #f8f8f8; }
#pagination_kota .cell a:hover { border: 1px solid #c6c6c6; background-color: #f0f0f0;  }
#pagination_kota .cell_active span { border-radius: 3px; font-size: 11px; color: #333; padding: 8px; border: 1px solid #c6c6c6; background-color: #e9e9e9; }
#pagination_kota .cell_disabled span { border-radius: 3px; font-size: 11px; color: #777777; padding: 8px; border: 1px solid #dddddd; background-color: #ffffff; }

</style>
<div class="form-inline">
<div class="form-group">
    <label for="inputEmail3" class="col-sm-3 control-label">Pencarian</label>
    <div class="col-sm-9">
      <div class="input-group">
      <input class="form-control" type="text" id="search_text" placeholder="Silahkan isi dengan nama jamaah" onkeypress="search_enter(event)">
      <div class="input-group-append">
      <span class="input-group-text bg-transparent">
      <i class="fa fa-search" style="cursor:pointer" onclick="list_data_kota()"></i>
      </span>
      </div>

    </div>
  </div>
</div>
</div>
<hr>
<div id="temp_view_table_kota">
<table class="table table-striped">
  <thead>
    <th>No</th>
    <th>Kode Registrasi</th>
    <th>Tgl Registrasi</th>
    <th>Nama</th>
    <th>JK</th>
    <th>Kota/Kab</th>
    <th style="text-align:center">Action</th>
  </thead>
</table>
</div>
<p></p>
<div id="ajax_list_table_kota"></div>
<p></p>
<div id="pagination_kota" class="pagging">
    <div><a href="#" id="1"></a></div>
</div>
