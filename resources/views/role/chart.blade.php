<script type="text/javascript">

  jQuery(function(){
    new Highcharts.Chart({
      chart: {

        renderTo: 'chart_log_module',
        type: 'pie',



       options3d: {
          enabled: true,
          alpha: 0
        }





      }
      ,
      title: {
        text: 'Portal Users',
        x: -20
      }
      ,

         xAxis: {

            categories: [
            <?php
          foreach($user as $row)
          {

          ?>
            ['<?php echo strtoupper($row->name) ?>'],
            <?php  } ?>
            ]
        },
         plotOptions: {
             bar: {
          allowPointSelect: true,
          cursor: 'pointer',
          depth: 50,
          dataLabels: {
                enabled: true,

            },
         events: {
            click: function (event) {
                $("#user_id_val").val(event.point.name);

                    }
        },
      }
      }
      ,


      series: [{
        type: 'bar',
        name: 'Total Activity',
        data: [
          <?php
          foreach($user as $row_item)
          {

        ?>
        [
        '<?php echo strtoupper($row_item->name) ?>', <?php echo $row_item->jumlah;
        ?>
        ],
        <?php
      }
               ?>
              ]
               }
              ]
    }
                        );
  }
        );


  jQuery(function(){
    new Highcharts.Chart({
      chart: {
        renderTo: 'chart_log',
        type: 'pie',
        options3d: {
          enabled: true,
          alpha: 12
        }
      }
      ,
      title: {
        text: 'User Activity Group By Apps',
        x: -20
      }
      ,

       xAxis: {

            categories: [
            <?php
          foreach($module as $row)
          {
          $apps   = strtoupper($row->modules);
          ?>
            ['<?php echo $apps ?>'],
            <?php  } ?>
            ]
        },
   plotOptions: {
        pie: {
          allowPointSelect: true,
          cursor: 'pointer',
            dataLabels:
          {
                        enabled: true, format: '{point.name} = {point.y:.0f} ',
                        style: { color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black' }
          },
         events: {
            click: function (event) {
                $("#apps").val(event.point.name);

                    }
        },
        }
      }
      ,
      series: [{
        type: 'pie',
        name: 'User Activity',
        data: [
          <?php
          foreach($module as $row)
          {
        $apps   = strtoupper($row->modules);
        $jumlah  = $row->jumlah;
        ?>
        [
        '<?php echo $apps ?>', <?php echo $jumlah;
        ?>
        ],
        <?php
      }
               ?>
              ]
               }
              ]
    }
                        );
  }
        );
</script>
<br/>
<div class="row">
  <div class="col-lg-6 grid-margin stretch-card">
    <div id="chart_log_module" style="width:100%"></div>
  </div>
  <div class="col-lg-6 grid-margin stretch-card">
<div id="chart_log" style="height: 300px;"></div>
  </div>
</div>
