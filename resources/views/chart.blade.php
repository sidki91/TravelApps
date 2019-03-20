<script type="text/javascript">

jQuery(function(){
  new Highcharts.Chart({
    chart: {
      renderTo: 'container',
      type: 'column',
      options3d: {
        enabled: true,
        alpha: 0,
        beta: 0,
        depth: 70
      }
    }
    ,
    title: {
      text: 'Statistik Data Transaksi Tahun {{$tahun}}',
      x: -20
    }
    ,
    subtitle: {
      text: 'Data Laporan Transaksi BSTravel',
      x: -20
    }
    ,
    xAxis: {
      categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
                   'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des']
    }
    ,
    yAxis: {
      title: {
        text: 'Total Transaksi'
      }
    }
    ,
    series: [{
      name: 'Pemesanan',
      data: <?php echo json_encode($datas['grafik_pemesanan']);
      ?>
    }
             ,
             {
               name: 'Pembayaran',
               data: <?php echo json_encode($datas['grafik_pembayaran']);
               ?>
             }
             ,
             {
               name: 'Selisih',
               data: <?php echo json_encode($datas['grafik_selisih']);
               ?>
             }
             ,
            ]
  }
                      );
}
      );

  jQuery(function(){
   new Highcharts.Chart({
     chart: {
       renderTo: 'chart_by_jenis',
       type: 'bar',
       options3d: {
         enabled: true,
         alpha: 0

       }
     }
     ,
     title: {
       text: 'Chart Pemesanan berdasarkan Jenis ',
     }
     ,
     xAxis: {

           categories: [
           <?php
         foreach($jenis as $row)
         {

         ?>
           ['<?php echo $row->jenis_pemesanan ?>'],
           <?php  } ?>
           ]
       },
        yAxis: {
           min: 0,
           title: {
               text: 'Group By Jenis',
               align: 'high'
           },
           labels: {
               overflow: 'justify'
           }
       },

       plotOptions: {
       bar: {
         allowPointSelect: true,
         cursor: 'pointer',
         depth: 50,
         dataLabels: {
               enabled: true,

           },

       }
     }
     ,
     series: [{
       type: 'bar',
       name: 'JUMLAH PEMESANAN',
       data: [
         <?php
         foreach($jenis as $row)
         {


       ?>
       [
       '<?php echo $row->jenis_pemesanan ?>', <?php echo $row->jumlah;
       ?>
       ],
       <?php
     }
              ?>
             ]
              },

             ]
   }
                       );
 }
       );

       jQuery(function(){
   new Highcharts.Chart({
     chart: {
       renderTo: 'chart_by_kategori',
       type: 'pie',
       options3d: {
         enabled: true,
         alpha: 45
       }
     }
     ,
     title: {
       text: 'Chart Pemesanan berdasarkan Kategori',
     }
     ,
     legend: {
           enabled: true,
           borderWidth: 1,
           borderColor: 'gray',
           align: 'center',
           verticalAlign: 'top',
           layout: 'horizontal',
           x: 0, y: 50
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
         depth: 35,
          showInLegend: false,
       }
     }
     ,
     series: [{
       type: 'pie',
       name: 'Total Absen',
       data: [
         <?php
         foreach($kategori as $row_kategori)
         {

       ?>
       [
       '<?php echo $row_kategori->deskripsi ?>', <?php echo $row_kategori->jumlah;
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
<div class="row">
  <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
    <div class="card card-statistics">
      <div class="card-body">
        <div class="clearfix">
          <div class="float-left">
            <i class="mdi mdi-account-location text-info icon-lg"></i>
          </div>
          <div class="float-right">
            <p class="mb-0 text-right">Jamaah</p>
            <div class="fluid-container">
              <h3 class="font-weight-medium text-right mb-0">{{$jumlah_jamaah}}</h3>
            </div>
          </div>
        </div>
        <p class="text-muted mt-3 mb-0">
          <i class="mdi mdi-alert-octagon mr-1" aria-hidden="true"></i> Total Jamaah
        </p>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
    <div class="card card-statistics">
      <div class="card-body">
        <div class="clearfix">
          <div class="float-left">
            <i class="mdi mdi-receipt text-warning icon-lg"></i>
          </div>
          <div class="float-right">
            <p class="mb-0 text-right">Pemesanan</p>
            <div class="fluid-container">
              <h3 class="font-weight-medium text-right mb-0">{{$jumlah_pemesanan}}</h3>
            </div>
          </div>
        </div>
        <p class="text-muted mt-3 mb-0">
          <i class="mdi mdi-bookmark-outline mr-1" aria-hidden="true"></i> Total Pemesanan
        </p>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
    <div class="card card-statistics">
      <div class="card-body">
        <div class="clearfix">
          <div class="float-left">
            <i class="mdi mdi-poll-box text-success icon-lg"></i>
          </div>
          <div class="float-right">
            <p class="mb-0 text-right">Lunas</p>
            <div class="fluid-container">
              <h3 class="font-weight-medium text-right mb-0">{{$lunas}}</h3>
            </div>
          </div>
        </div>
        <p class="text-muted mt-3 mb-0">
          <i class="mdi mdi-currency-usd mr-1" aria-hidden="true"></i> Pemesanan Lunas
        </p>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
    <div class="card card-statistics">
      <div class="card-body">
        <div class="clearfix">
          <div class="float-left">
            <i class="mdi mdi-close-octagon text-danger icon-lg"></i>
          </div>
          <div class="float-right">
            <p class="mb-0 text-right">Belum Lunas</p>
            <div class="fluid-container">
              <h3 class="font-weight-medium text-right mb-0">{{$belum_lunas}}</h3>
            </div>
          </div>
        </div>
        <p class="text-muted mt-3 mb-0">
          <i class="mdi mdi-currency-usd mr-1" aria-hidden="true"></i> Pemesanan Belum Lunas
        </p>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-12 grid-margin">
    <div class="card">
      <div class="card-body">
        <div class="chart-container">
          <div id="container" height="80"></div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-lg-12 grid-margin">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Jumlah Transaksi Pemesanan Bulan {{\App\Models\Bulan::where('bulan',$bulan)->value('bulan_name')}} Tahun {{$tahun}}</h4>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th style="text-align:center">
                  No
                </th>
                <th>
                  Kategori
                </th>
                <th>
                  Kode Paket
                </th>
                <th>
                  Nama Paket
                </th>
                <th>
                  Jumlah Pemesanan
                </th>
                <th>
                  Total Harga Paket
                </th>
              </tr>
            </thead>
            <tbody>
              @if(count($favorite)>=1)
              @foreach($favorite as $no => $item)
              <tr>
                <td style="text-align:center">
                  {{++$no}}
                </td>
                <td>
                  {{\App\Models\KategoriPerjalanan::where('kode_kategori',$item->kode_kategori)->value('deskripsi')}}
                </td>
                <td>
                  {{$item->kode_paket}}
                </td>
                <td>
                  {{\App\Models\PaketPerjalanan::where('kode_paket',$item->kode_paket)->value('nama_paket')}}
                </td>
                <td>
                  {{number_format($item->jumlah)}}
                </td>
                <td class="text-danger">
                {{number_format($item->total_harga)}}
                </td>
              </tr>
              @endforeach
              @else
              <tr>
                <td colspan="6">
                  <div class="alert alert-danger alert-dismissable">
                    <button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
                  Tidak ada transaksi ditemukan !
                </div></td>
              </tr>
              @endif

              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-lg-6 grid-margin stretch-card">

      <div class="card">
        <div class="card-body">
          <div id="chart_by_jenis" style="height:300px"></div>
        </div>
      </div>

  </div>
  <div class="col-lg-6 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
      <div id="chart_by_kategori" style="height:300px"></div>
      </div>
    </div>
  </div>
</div>
