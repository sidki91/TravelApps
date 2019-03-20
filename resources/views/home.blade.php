@extends('layouts.app')

@section('content')
<script src="{{ URL::asset('public/js/grafik/jquery_grafik.min.js')}}"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js">
</script>


<script src="{{ URL::asset('public/js/grafik/highcharts.js')}}">
</script>
<script src="{{ URL::asset('public/js/grafik/highcharts-3d.js')}}">
</script>
<script src="{{ URL::asset('public/js/grafik/exporting.js')}}">
</script>
<style media="screen">
.select2-selection__rendered {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 12px;
}
</style>
<script type="text/javascript">


 function chart()
 {
     var tahun     = $("#tahun").val();
     var bulan     = $("#bulan").val();
     var data =
     {
 	      'tahun'       : tahun,
        'bulan'       : bulan,
        '_token'      : $('input[name=_token]').val(),

     };

       $.ajax({
       url         	: "home/chart",
       dataType    	: "json",
       type			: "post",
       data			: data,
       beforeSend      : function()
         {
             $('#content_cart').html('<center><img src="{{ URL::asset("public/images/facebook.gif")}}" width="30px" height="30px"/>loading data . . .</center>');
             $("#content_cart").hide();
             $("#content_cart").fadeIn("slow");


         },
       success  : function (result)
       {
         $('#content_cart').html(result.html).fadeIn("slow");
       }
     }
           );

 }

 $(document).ready(function()
  {
     $("#tahun").select2();
     $("#bulan").select2();
     chart();
  });
</script>
<div class="row purchace-popup">
        <div class="col-12">
          <span class="d-block d-md-flex align-items-center">
          <div class="col-6 grid-margin">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-5 col-form-label">Tahun</label>
                  <div class="col-sm-7">
                    <select class="form-control" data-plugin="select2" id="tahun" name="tahun">
                            <?php $sekarang=date('Y')-1;
                    for ($i=$sekarang; $i<=$sekarang +30 ; $i++){
                    if($i==date('Y'))
                    {
                    $selected="selected='selected'";
                    }
                    else
                    {
                    $selected="";
                    }
                    ?>
                            <option value="<?php echo $i; ?>"
                                    <?=$selected?> >
                            <?php echo $i; ?>
                            </option>
                          <?php } ?>
                          </select>
                  </div>
                </div>
                <div class="form-group row" style="margin-top:-10px">
                  <label class="col-sm-5 col-form-label">Bulan</label>
                  <div class="col-sm-7">
                        <select class="form-control" id="bulan" name="bulan" data-plugin="select2">
                            <?php
                      foreach($bulan as $row_month):
                      if($row_month->bulan==date('m'))
                      {
                      $selected="selected='selected'";
                      }
                      else
                      {
                      $selected="";
                      }
                      ?>
                            <option value="<?=$row_month->bulan?>"
                                    <?=$selected?>>
                            <?=$row_month->bulan_name?>
                            </option>
                          <?php endforeach
                      ?>
                    </select>
                  </div>
                </div>
                <div class="form-group row" style="margin-top:-10px">
                  <label class="col-sm-5 col-form-label"></label>
                  <div class="col-sm-7">
                <button class="btn btn-success" type="button" onclick="chart()">
                <i class="icon wb-search">
                </i> Search
              </button>
            </div>
          </div>
              </div>
            </div>
          </div>
          </span>
        </div>
      </div>
<div id="content_cart"></div>

</div>
@endsection
