<style>
.select2-selection__rendered {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 12px;
}
</style>
<script>
$(".hubungan").select2();

function list_info_tambahan()
{
  var kode_registrasi = $("#kode_registrasi").val();
      $.ajax({
          type: 'POST',
          url: 'formulir/list_info_tambahan',
          data:
          {
              '_token': $('input[name=_token]').val(),
              'kode'  : kode_registrasi
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
function save_kel_ikut(key,item='')
{
    var nama_kel_ikut     = $("#nama_kel_ikut"+key).val()
    var hubungan_kel_ikut = $("#hubungan_kel_ikut"+key).val();
    var tlp_kel_ikut      = $("#tlp_kel_ikut"+key).val();
    var kode_registrasi   = $("#kode_registrasi").val();
    var url;

    if(nama_kel_ikut=='')
    {
      bootbox.alert('Silahkan isi nama keluarga !')
      $("#nama_kel_ikut"+key).focus();
    }
    else if(hubungan_kel_ikut=='')
    {
      bootbox.alert('Silahkan isi hubungan keluarga !');
      $("#hubungan_kel_ikut"+key).select2('open');
    }
    else if(tlp_kel_ikut=='')
    {
      bootbox.alert('Silahkan isi nomor telepon !');
      $("#tlp_kel_ikut"+key).focus();
    }
    else
    {
      if(item=='')
      {
          url = 'formulir/save_keluarga_ikut';
      }
      else
      {
        url = 'formulir/update_keluarga_ikut';
      }
        $.ajax({
            type: 'POST',
            url: url,
            data:
            {
                '_token'          : $('input[name=_token]').val(),
                'nama'            : nama_kel_ikut,
                'hubungan'        : hubungan_kel_ikut,
                'tlp'             : tlp_kel_ikut,
                'kode_registrasi' : kode_registrasi,
                'item'            : item
            },

            success: function(data)
            {
              if(data.status=='success')
              {
                  bootbox.alert(data.msg);
                  list_info_tambahan();
              }
              else
              {
                bootbox.alert(data.msg);
              }

            },
        });
    }
}

function delete_kel_ikut(key,item='')
{
  var kode_registrasi = $("#kode_registrasi").val();
      $.ajax({
          type: 'POST',
          url: 'formulir/delete_keluarga_ikut',
          data:
          {
              '_token': $('input[name=_token]').val(),
              'kode'  : kode_registrasi,
              'item'  : item
          },
          beforeSend      : function()
          {
              $('#content_info_tambahan').html('<center><img src="{{ URL::asset("public/assets/images/831.gif")}}" width="30px" height="30px"/> <img src="{{ URL::asset("public/assets/images/332.gif")}}"/></center>');
              $("#content_info_tambahan").hide();
              $("#content_info_tambahan").fadeIn("slow");
         },
          success: function(data)
          {
              if(data.status=='success')
              {
                  list_info_tambahan();
              }
              else
              {
                 bootbox.alert(data.msg);
                 list_info_tambahan();
              }
          },
      });
}

$('.add').click(function () {
		  var kode_registrasi = $("#kode_registrasi").val();
      var rowCount = $('#keluarga_ikut tr').length - 1;
      $.ajax({
          type: 'POST',
          url: 'formulir/add_item',
          data:
          {
              '_token'   : $('input[name=_token]').val(),
              'kode'     : kode_registrasi,
              'rowCount' : rowCount
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

		});

</script>
<hr/>
  <div class="col-12 grid-margin">
    <div class="card">
      <div class="card-body">
        <div class="form-sample">
          <div class="row">
            <div class="col-md-12">
              <p class="card-description" style="font-weight:bold">
                Keluarga Yg Ikut Bersama
              </p>
              <input type="button" class="btn btn-primary add" value="Add New Item">
              <p></p>
              <table class="table table-striped table-bordered" id="keluarga_ikut">
                <thead>
                  <tr>
                    <th>Nama</th>
                    <th>Hubungan</th>
                    <th>Telepon/HP</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody class="neworderbody">


                <?php $no = 1;?>
                @foreach($row as $key => $item)
                <tr>
                  <td><input type="text" class="form-control" id="nama_kel_ikut{{$no}}" value="{{$item->nama}}"></td>
                  <td><select class="form-control hubungan" id="hubungan_kel_ikut{{$no}}" style="width:150px">
                    <option value="">Pilih</option>
                    @foreach($hubungan as $row_hubungan)
                    <?php
                    if($item->kode_hubungan==$row_hubungan->kode_hubungan)
                    {
                        $selected ="selected='selected'";
                    }
                    else
                    {
                        $selected ="";
                    }
                    ?>
                    <option value="{{$row_hubungan->kode_hubungan}}" {{$selected}}>{{$row_hubungan->deskripsi}}</option>
                    @endforeach
                  </select>
                </td>
                  <td><input type="text" class="form-control" id="tlp_kel_ikut{{$no}}" value="{{$item->tlp}}"/></td>
                  <td>
                    <button class="btn btn-primary" onclick="save_kel_ikut('{{$no}}','{{$item->item}}')"><i class="fa fa-save"></i></button>
                    <button class="btn btn-danger" onclick="delete_kel_ikut('{{$no}}','{{$item->item}}')"><i class="fa fa-close"></i></button>
                  </td>
                </tr>
                <?php $no++;?>
                @endforeach
                @for($x=$jumlah+1;$x<=$count+1;$x++)
                <tr>
                  <td><input type="text" class="form-control" id="nama_kel_ikut{{$x}}"></td>
                  <td><select class="form-control hubungan" id="hubungan_kel_ikut{{$x}}" style="width:150px">
                    <option value="">Pilih</option>
                    @foreach($hubungan as $row_hubungan)
                    <option value="{{$row_hubungan->kode_hubungan}}">{{$row_hubungan->deskripsi}}</option>
                    @endforeach
                  </select>
                </td>
                  <td><input type="text" class="form-control" id="tlp_kel_ikut{{$x}}"/></td>
                  <td>
                    <button class="btn btn-primary" onclick="save_kel_ikut('{{$x}}')"><i class="fa fa-save"></i></button>
                    <button class="btn btn-danger" onclick="delete_kel_ikut('{{$x}}')"><i class="fa fa-close"></i></button>
                  </td>
                </tr>
                @endfor

              </tbody>
              </table>
            </div>
              </div>
            </div>
          </div>
        </div>
        </div>
