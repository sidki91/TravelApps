<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>BST System Administration</title>
  <!-- plugins:css -->

   <link href="{{ URL::asset('public/vendors/iconfonts/mdi/css/materialdesignicons.min.css') }}" rel="stylesheet" />
   <link href="{{ URL::asset('public/vendors/css/vendor.bundle.base.css') }}" rel="stylesheet" />
   <link href="{{ URL::asset('public/vendors/css/vendor.bundle.addons.css') }}" rel="stylesheet" />
   <link rel="stylesheet" href="{{ URL::asset('public/vendors/iconfonts/font-awesome/css/font-awesome.css')}}">


  <link rel="stylesheet" href="{{ URL::asset('public/css/style.css')}}">
  <link rel="stylesheet" href="{{ URL::asset('public/css/notify-metro.css')}}">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
  <link rel="stylesheet" href="{{ URL::asset('public/css/select2.css')}}">




  <link rel="shortcut icon" href="images/favicon.png" />
  <style>
  #pagination div { display: inline-block; margin-right: 5px; margin-top: 5px }
  #pagination .cell a { border-radius: 3px; font-size: 11px; color: #333; padding: 8px; text-decoration:none; border: 1px solid #d3d3d3; background-color: #f8f8f8; }
  #pagination .cell a:hover { border: 1px solid #c6c6c6; background-color: #f0f0f0;  }
  #pagination .cell_active span { border-radius: 3px; font-size: 11px; color: #333; padding: 8px; border: 1px solid #c6c6c6; background-color: #e9e9e9; }
  #pagination .cell_disabled span { border-radius: 3px; font-size: 11px; color: #777777; padding: 8px; border: 1px solid #dddddd; background-color: #ffffff; }

  </style>
  <script>
function alertMsg(message,clasStyle)
{
  $.notify({
      title: 'Info System',
      text: message,
      image: "<i class='fa fa-warning'></i>"
  }, {
      style: 'metro',
      className: clasStyle,
      globalPosition:'top right',
      showAnimation: "show",
      showDuration: 0,
      hideDuration: 0,
      autoHideDelay: 3000,
      autoHide: true,
      clickToHide: true
  });

}
</script>
</head>
