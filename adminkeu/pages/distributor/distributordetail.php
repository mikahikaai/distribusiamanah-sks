<?php
$database = new Database;
$db = $database->getConnection();

if (isset($_GET['id'])) {
  $selectsql = "SELECT * FROM distributor where id=?";
  $stmt = $db->prepare($selectsql);
  $stmt->bindParam(1, $_GET['id']);
  $stmt->execute();

  $row = $stmt->fetch(PDO::FETCH_ASSOC);
}

?>
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Distributor</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="?page=home">Home</a></li>
          <li class="breadcrumb-item"><a href="?page=distributorread">Distributor</a></li>
          <li class="breadcrumb-item active">Detail Distributor</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Data Detail Distributor</h3>
    </div>
    <div class="card-body">
      <form action="" method="post">
        <div class="form-group">
          <label for="id_da">ID Distributor</label>
          <input type="text" class="form-control" value="<?= $row['id_da']; ?>" style="text-transform: uppercase;" readonly>
        </div>
        <div class="form-group">
          <label for="nama">Nama Lengkap</label>
          <input type="text" class="form-control" value="<?= $row['nama']; ?>" style="text-transform: uppercase;" readonly>
        </div>
        <div class="form-group">
          <label for="paket">Paket</label>
          <input type="text" class="form-control" value="<?= $row['paket']; ?>" readonly>
        </div>
        <div class="form-group">
          <label for="alamat_dropping">Alamat Dropping</label>
          <input type="text" class="form-control" value="<?= $row['alamat_dropping']; ?>" style="text-transform: uppercase;" readonly>
        </div>
        <div class="form-group">
          <label for="no_telepon">No. Telepon</label>
          <input type="text" class="form-control" value="<?= $row['no_telepon']; ?>" readonly>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="jarak">Jarak Dari Pabrik</label>
              <input type="text" class="form-control" value="<?= $row['jarak']; ?>" readonly>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="status_keaktifan">Status Keaktifan</label>
              <input type="text" class="form-control" value="<?= $row['status_keaktifan']; ?>" readonly>
            </div>
          </div>
        </div>
        <!-- <label for="">Map</label>
        <div id="map"></div> -->
        <button type="button" class="btn btn-danger btn-sm float-right mr-1 mt-2" onclick="history.back()">
          <i class="fa fa-arrow-left"></i> Kembali
        </button>
      </form>
    </div>
  </div>
</div>

<?php
include_once "../partials/scriptdatatables.php";
?>

<script>
  var lat = <?= $row['lat']; ?>;
  var lng = <?= $row['lng']; ?>;
  var nama = "<?= $row['nama']; ?>";
  var latPabrik = -3.4960839506671517;
  var lngPabrik = 114.81016825291921;

  if (!lat && !lng) {
    lat = latPabrik;
    lng = lngPabrik;
    nama = "Pabrik Air Minum Amanah";
  }

  var latLangPabrik = L.latLng(latPabrik, lngPabrik);
  let latLangDistro = L.latLng(lat, lng);
  // console.log(latLangPabrik);

  var map = L.map('map', {
    zoomControl: false,
    center: [lat, lng],
    zoom: 18,
    gestureHandling: true,
    gestureHandlingOptions: {
      text: {
        touch: "Gunakan 2 jari untuk menggeser map",
        scroll: "Gunakan Ctrl + Scroll untuk memperbesar map",
        scrollMac: "Gunakan \u2318 + scroll untuk memperbesar map"
      },
      duration: 1000
    }
  });

  var LeafIcon = L.Icon.extend({
    options: {
      iconSize: [38, 38],
      shadowSize: [50, 64],
      iconAnchor: [22, 45],
      shadowAnchor: [4, 62],
      popupAnchor: [-3, -45]
    }
  });

  var zoomHome = L.Control.zoomHome();
  zoomHome.addTo(map);

  var greenIcon = new LeafIcon({
    iconUrl: '../images/logooo cropped resized compressed.png',
    // shadowUrl: 'http://leafletjs.com/examples/custom-icons/leaf-shadow.png'
  })

  let wp1 = new L.Routing.Waypoint(latLangPabrik);
  let wp2 = new L.Routing.Waypoint(latLangDistro);

  L.Routing.control({
    waypoints: [latLangPabrik, latLangDistro],
    language: 'id',
  }).addTo(map);

  // L.Routing.Formatter({
  //   language : 'id'
  // })

  // let routeUs = L.Routing.osrmv1();
  // routeUs.route([wp1, wp2], (err, routes) => {
  //   if (!err) {
  //     let best = 100000000000000;
  //     let bestRoute = 0;
  //     for (i in routes) {
  //       if (routes[i].summary.totalDistance < best) {
  //         bestRoute = i;
  //         best = routes[i].summary.totalDistance;
  //       }
  //     }
  //     L.Routing.line(routes[bestRoute], {
  //       styles: [{
  //         color: 'red',
  //         weight: '1'
  //       }]
  //     }).addTo(map);

  //   }
  // })

  const points = [
    [latPabrik, lngPabrik, "Pabrik Air Minum Amanah"],
    [lat, lng, nama],
  ];

  googleHybrid = L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
    maxZoom: 20,
    subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
  }).addTo(map);

  map.addControl(new L.Control.Fullscreen());

  // adding all markers to the featureGroups array
  let featureGroups = [];
  for (let i = 0; i < points.length; i++) {
    const [lat, lng, title] = points[i];
    featureGroups.push(L.marker([lat, lng]).bindPopup(title));
  }

  // adding all markers to the map
  for (let i = 0; i < featureGroups.length; i++) {
    featureGroups[i].addTo(map);
  }

  // Extended `LayerGroup` that makes it easy
  // to do the same for all layers of its members
  let group = new L.featureGroup(featureGroups);

  // method fitBounds sets a map view that
  // contains the given geographical bounds
  map.fitBounds(group.getBounds(), {
    padding: [50, 50], // adding padding to map
  });

  L.marker([lat, lng], {
      // icon: greenIcon,
    }).addTo(map)
    .bindPopup(nama)
    .openPopup().on("click", centered);

  function centered(e) {
    map.setView(e.target.getLatLng(), 18);
  }
</script>