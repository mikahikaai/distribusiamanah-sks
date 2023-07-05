<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Amanah | <?= $title ?></title>
  <link rel="icon" href="../images/logooo cropped resized compressed.png" type="image/x-icon">

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <!-- <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css"> -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">

  <script src="../plugins/fontawesome-free/js/all.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.2/dist/umd/popper.min.js" crossorigin="anonymous"></script>
  <script src="../plugins/tempusdominus-bootstrap-4/js/tempus-dominus.min.js"></script>
  <link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempus-dominus.min.css">
  <link rel="stylesheet" href="../plugins/cropper/cropper.min.css">
  <script src="../plugins/cropper/cropper.min.js"></script>

  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css" integrity="sha512-hoalWLoI8r4UszCkZ5kL8vayOGVae1oxXe/2A4AO6J9+580uKHDO3JdHb7NzwwzK5xr/Fs0W40kiNHxM9vyTtQ==" crossorigin="" />
  <link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/leaflet.fullscreen.css' rel='stylesheet' />
  <script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js" integrity="sha512-BB3hKbKWOc9Ez/TAwyWxNXeoV9c1v6FIeYiBieIWkpLjauysF18NzgR1MBNBXf8/KABdlkX68nAhlwcDFLGPCQ==" crossorigin=""></script>
  <script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/Leaflet.fullscreen.min.js'></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/tomik23/autocomplete@1.8.3/dist/css/autocomplete.min.css" />
  <script src="https://cdn.jsdelivr.net/gh/tomik23/autocomplete@1.8.3/dist/js/autocomplete.min.js"></script>
  <link rel="stylesheet" href="https://unpkg.com/leaflet-gesture-handling/dist/leaflet-gesture-handling.min.css" type="text/css">
  <script src="https://unpkg.com/leaflet-gesture-handling"></script>
  <script src="http://mrmufflon.github.io/Leaflet.Coordinates/dist/Leaflet.Coordinates-0.1.3.min.js" charset="utf-8"></script>
  <link rel="stylesheet" href="http://mrmufflon.github.io/Leaflet.Coordinates/dist/Leaflet.Coordinates-0.1.3.css" />
  <link rel="stylesheet" href="../plugins/leaflet-zoomhome/css/leaflet.zoomhome.css">
  <script src="../plugins/leaflet-zoomhome/js/leaflet.zoomhome.min.js"></script>
  <link rel="stylesheet" href="../plugins/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
  <script src="../plugins/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
  <script src="https://cdn.jsdelivr.net/gh/emn178/chartjs-plugin-labels/src/chartjs-plugin-labels.js"></script>

  <!-- <script src="https://cdn.jsdelivr.net/gh/Eonasdan/tempus-dominus@master/dist/js/tempus-dominus.js"></script>

  <link href="
https://cdn.jsdelivr.net/gh/Eonasdan/tempus-dominus@master/dist/css/tempus-dominus.css" rel="stylesheet" /> -->


  <style>
    .hover {
      background-color: #ECECEC;
    }

    table.dataTable tbody tr:nth-child(even) {
      background-color: #f2fbfc;
    }

    table.dataTable th {
      color: white;
      background-color: #6C757D;
    }

    table.dataTable tfoot {
      color: white;
      background-color: #A5A8AD;
    }

    #map {
      height: 80vh;
    }

    .leaflet-control-container .leaflet-routing-container {
      opacity: 0.2;
    }

    .leaflet-control-container:hover .leaflet-routing-container:hover {
      opacity: 1;
    }
  </style>
</head>