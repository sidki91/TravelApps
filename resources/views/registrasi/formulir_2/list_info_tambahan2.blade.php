<style>
.select2-selection__rendered {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 12px;
}
</style>
<script>
function list_info_tambahan2()
{
  var kode_registrasi = $("#kode_registrasi").val();
      $.ajax({
          type: 'POST',
          url: 'formulir/list_info_tambahan2',
          data:
          {
              '_token': $('input[name=_token]').val(),
              'kode'  : kode_registrasi
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
function save_dihubungi(key,item='')
{
    var nama_dihubungi     = $("#nama_dihubungi"+key).val()
    var alamat_dihubungi   = $("#alamat_dihubungi"+key).val();
    var telepon_dihubungi  = $("#telepon_dihubungi"+key).val();
    var kode_registrasi    = $("#kode_registrasi").val();
    var url;
    if(nama_dihubungi=='')
    {
        $("#nama_dihubungi"+key).focus();
        bootbox.alert('Silahkan isi nama yg dapat dihubungi !');
    }
    else if(alamat_dihubungi=='')
    {
        $("#alamat_dihubungi"+key).focus();
        bootbox.alert('Silahkan isi alamat yg dapat dihubungi !');
    }
    else if(telepon_dihubungi=='')
    {
      $("#telepon_dihubungi"+key).focus();
      bootbox.alert('Silahkan isi telepon yg dapat dihubungi !');
    }
    else
    {
      if(item=='')
      {
          url = 'formulir/save_keluarga_dihubungi';
      }
      else
      {
          url = 'formulir/update_keluarga_dihubungi'
      }
          $.ajax({
              type: 'POST',
              url:  url,
              data:
              {
                  '_token'          : $('input[name=_token]').val(),
                  'nama'            : nama_dihubungi,
                  'alamat'          : alamat_dihubungi,
                  'tlp'             : telepon_dihubungi,
                  'kode_registrasi' : kode_registrasi,
                  'item'            : item
              },

              success: function(data)
              {
                  if(data.status=='success')
                  {
                      bootbox.alert(data.msg);
                      list_info_tambahan2();
                  }
                  else
                  {
                    bootbox.alert(data.msg);
                    list_info_tambahan2();
                  }

              },
          });
    }

}

function finish()
{
  swal({
      title: "Info System",
      text: "Pendaftaran selesai, simpan seluruh data ? ",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
          if (willDelete)
          {
             window.location.href = '{{URL::to('formulir')}}';

          }
          else
          {
              return false;
          }
      })
}
$('.add_item').click(function () {
		  var kode_registrasi = $("#kode_registrasi").val();
      var rowCount = $('#keluarga_dihubungi tr').length - 1;
      $.ajax({
          type: 'POST',
          url: 'formulir/add_item2',
          data:
          {
              '_token'   : $('input[name=_token]').val(),
              'kode'     : kode_registrasi,
              'rowCount' : rowCount
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

		});
function delete_dihubungi(key,item='')
{
  var kode_registrasi = $("#kode_registrasi").val();
      $.ajax({
          type: 'POST',
          url: 'formulir/delete_keluarga_dihubungi',
          data:
          {
              '_token': $('input[name=_token]').val(),
              'kode'  : kode_registrasi,
              'item'  : item
          },
          beforeSend      : function()
          {
              $('#content_info_tambahan_2').html('<center><img src="{{ URL::asset("public/assets/images/831.gif")}}" width="30px" height="30px"/> <img src="{{ URL::asset("public/assets/images/332.gif")}}"/></center>');
              $("#content_info_tambahan_2").hide();
              $("#content_info_tambahan_2").fadeIn("slow");
         },
          success: function(data)
          {
              if(data.status=='success')
              {
                  list_info_tambahan2();
              }
              else
              {
                 bootbox.alert(data.msg);
                 list_info_tambahan2();
              }
          },
      });
}

</script>
<hr/>
  <div class="col-12 grid-margin">
    <div class="card">
      <div class="card-body">
        <div class="form-sample">
          <div class="row">
            <div class="col-md-12">
              <p class="card-description" style="font-weight:bold">
                Keluarga Yg Dapat Dihubungi Ketika Darurat
              </p>
              <input type="button" class="btn btn-primary add_item" value="Add New Item">
              <p></p>
              <table class="table table-striped table-bordered" id="keluarga_dihubungi">
                <thead>
                <tr>
                  <th>Nama</th>
                  <th>Alamat</th>
                  <th>Telepon/HP</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody class="neworderbody">
                <?php $no = 1;
                  if($count>=1){
                ?>
                @foreach($row as $key => $item)
                <tr>
                  <td><input type="text" class="form-control" id="nama_dihubungi{{$no}}" value="{{$item->nama}}"/></td>
                  <td><input type="text" class="form-control" id="alamat_dihubungi{{$no}}" value="{{$item->alamat}}"/></td>
                  <td><input type="text" class="form-control" id="telepon_dihubungi{{$no}}" value="{{$item->telp}}"/></td>
                  <td>
                    <button class="btn btn-primary" onclick="save_dihubungi('{{$no}}','{{$item->item}}')"><i class="fa fa-save"></i></button>
                    <button class="btn btn-danger" onclick="delete_dihubungi('{{$no}}','{{$item->item}}')"><i class="fa fa-close"></i></button>
                  </td>
                </tr>
                <?php $no++;?>
                @endforeach
              <?php } ?>
                <?php if($count<3)
                {
                  if($count==1)
                  {
                    $jumlah_row = 3-$count;
                  }
                  else
                  {
                    $jumlah_row = 4-$count;
                  }

                ?>
                @for($x=$count+1;$x<=$jumlah_row+1;$x++)
                <tr>
                  <td><input type="text" class="form-control" id="nama_dihubungi{{$x}}"/></td>
                  <td><input type="text" class="form-control" id="alamat_dihubungi{{$x}}"/></td>
                  <td><input type="text" class="form-control" id="telepon_dihubungi{{$x}}"/></td>
                  <td>
                    <button class="btn btn-primary" onclick="save_dihubungi('{{$x}}')"><i class="fa fa-save"></i></button>
                    <button class="btn btn-danger" onclick="delete_dihubungi('{{$x}}')"><i class="fa fa-close"></i></button>
                  </td>
                </tr>
                @endfor
              <?php } ?>
              </tbody>
              </table>
            </div>
              </div>
            </div>
            <p></p>


          </div>
          <button type="button" class="btn btn-success mr-2" onclick="finish()"><i class="fa fa-check"></i> Selesai</button>

        </div>

        </div>
