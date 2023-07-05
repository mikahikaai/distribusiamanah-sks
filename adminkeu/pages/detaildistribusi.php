<?php
$database = new Database;
$db = $database->getConnection();

if (isset($_GET['id'])) {
  $selectsql = "SELECT a.*, d.*, k1.nama as supir, k1.upah_borongan usupir, k2.nama helper1, k2.upah_borongan uhelper1, k3.nama helper2, k3.upah_borongan uhelper2, do1.nama distro1, do1.jarak jdistro1, do2.nama distro2, do2.jarak jdistro2, do3.nama distro3, do3.jarak jdistro3, do1.lat lat1, do2.lat lat2, do3.lat lat3, do1.lng lng1, do2.lng lng2, do3.lng lng3
    FROM distribusi d INNER JOIN armada a on d.id_plat = a.id
    LEFT JOIN karyawan k1 on d.driver = k1.id
    LEFT JOIN karyawan k2 on d.helper_1 = k2.id
    LEFT JOIN karyawan k3 on d.helper_2 = k3.id
    LEFT JOIN distributor do1 on d.nama_pel_1 = do1.id
    LEFT JOIN distributor do2 on d.nama_pel_2 = do2.id
    LEFT JOIN distributor do3 on d.nama_pel_3 = do3.id
    WHERE d.id=?";
  $stmt = $db->prepare($selectsql);
  $stmt->bindParam(1, $_GET['id']);
  $stmt->execute();

  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  $array1 = array((float)$row['lat1'], (float)$row['lng1'], $row['distro1'] . "<br>" . $row['jdistro1'] . " (km) dari pabrik");
  $array2 = array((float)$row['lat2'], (float)$row['lng2'], $row['distro2'] . "<br>" . $row['jdistro2'] . " (km) dari pabrik");
  $array3 = array((float)$row['lat3'], (float)$row['lng3'], $row['distro3'] . "<br>" . $row['jdistro3'] . " (km) dari pabrik");

  $array_coors = array_filter(array($array1, $array2, $array3));
  if ($array2[0] == null) {
    unset($array_coors[1]);
  }
  if ($array3[0] == null) {
    unset($array_coors[2]);
  }
  // var_dump($array_coors);
  // die();
}

?>
<script>
  var array_coors = <?= json_encode($array_coors); ?>;
  console.log(array_coors);
</script>
<!-- Main content -->
<div class="content">
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Data Detail Distribusi <br> No. Perjalanan : <span class="font-weight-bold"> [<?= $row['no_perjalanan']; ?>] </span></h3>

    </div>
    <div class="card-body">
      <form action="" method="post">
        <input type="hidden" value="<?= $row['no_perjalanan']; ?>" name="no_perjalanan">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Tujuan 1</h4>
          </div>
          <div class="card-body">
            <div class="form-group">
              <label for="nama_pel_1">Distributor</label>
              <input type="text" name="nama_pel_1" class="form-control" value="<?= $row['distro1'] ?>" readonly>
              <input type="hidden" name="jarak1" class="form-control" value="<?= $row['jdistro1'] ?>" readonly>
            </div>
            <div class="row">
              <div class="col-md">
                <div class="form-group">
                  <label for="cup1">Muatan Cup</label>
                  <input type="number" name="cup1" class="form-control" value="<?= $row['cup1'] ?>" readonly>
                </div>
              </div>
              <div class="col-md">
                <div class="form-group">
                  <label for="a3301">Muatan A330</label>
                  <input type="number" name="a3301" class="form-control" value="<?= $row['a3301'] ?>" readonly>
                </div>
              </div>
              <div class="col-md">
                <div class="form-group">
                  <label for="a5001">Muatan A500</label>
                  <input type="number" name="a5001" class="form-control" value="<?= $row['a5001'] ?>" readonly>
                </div>
              </div>
              <div class="col-md">
                <div class="form-group">
                  <label for="a6001">Muatan A600</label>
                  <input type="number" name="a6001" class="form-control" value="<?= $row['a6001'] ?>" readonly>
                </div>
              </div>
              <div class="col-md">
                <div class="form-group">
                  <label for="refill1">Muatan Refill</label>
                  <input type="number" name="refill1" class="form-control" value="<?= $row['refill1'] ?>" readonly>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Tujuan 2</h4>
          </div>
          <div class="card-body">
            <div class="form-group">
              <label for="nama_pel_2">Distributor</label>
              <input type="text" name="nama_pel_2" class="form-control" value="<?= $row['distro2'] ?>" readonly>
              <input type="hidden" name="jarak2" class="form-control" value="<?= $row['jdistro2'] ?>" readonly>
            </div>
            <div class="row">
              <div class="col-md">
                <div class="form-group">
                  <label for="cup2">Muatan Cup</label>
                  <input type="number" name="cup2" class="form-control" value="<?= $row['cup2'] ?>" readonly>
                </div>
              </div>
              <div class="col-md">
                <div class="form-group">
                  <label for="a3302">Muatan A330</label>
                  <input type="number" name="a3302" class="form-control" value="<?= $row['a3302'] ?>" readonly>
                </div>
              </div>
              <div class="col-md">
                <div class="form-group">
                  <label for="a5002">Muatan A500</label>
                  <input type="number" name="a5002" class="form-control" value="<?= $row['a5002'] ?>" readonly>
                </div>
              </div>
              <div class="col-md">
                <div class="form-group">
                  <label for="a6002">Muatan A600</label>
                  <input type="number" name="a6002" class="form-control" value="<?= $row['a6002'] ?>" readonly>
                </div>
              </div>
              <div class="col-md">
                <div class="form-group">
                  <label for="refill2">Muatan Refill</label>
                  <input type="number" name="refill2" class="form-control" value="<?= $row['refill2'] ?>" readonly>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Tujuan 3</h4>
          </div>
          <div class="card-body">
            <div class="form-group">
              <label for="nama_pel_3">Distributor</label>
              <input type="text" name="nama_pel_3" class="form-control" value="<?= $row['distro3'] ?>" readonly>
              <input type="hidden" name="jarak3" class="form-control" value="<?= $row['jdistro3'] ?>" readonly>
            </div>
            <div class="row">
              <div class="col-md">
                <div class="form-group">
                  <label for="cup3">Muatan Cup</label>
                  <input type="number" name="cup3" class="form-control" value="<?= $row['cup3'] ?>" readonly>
                </div>
              </div>
              <div class="col-md">
                <div class="form-group">
                  <label for="a3303">Muatan A330</label>
                  <input type="number" name="a3303" class="form-control" value="<?= $row['a3303'] ?>" readonly>
                </div>
              </div>
              <div class="col-md">
                <div class="form-group">
                  <label for="a5003">Muatan A500</label>
                  <input type="number" name="a5003" class="form-control" value="<?= $row['a5003'] ?>" readonly>
                </div>
              </div>
              <div class="col-md">
                <div class="form-group">
                  <label for="a6003">Muatan A600</label>
                  <input type="number" name="a6003" class="form-control" value="<?= $row['a6003'] ?>" readonly>
                </div>
              </div>
              <div class="col-md">
                <div class="form-group">
                  <label for="refill3">Muatan Refill</label>
                  <input type="number" name="refill3" class="form-control" value="<?= $row['refill3'] ?>" readonly>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label for="id_plat">Armada</label>
          <input type="text" name="id_plat" class="form-control" value="<?= $row['plat'], " - ", $row['jenis_mobil']; ?>" readonly>
          <input type="hidden" name="kateg_mobil" class="form-control" value="<?= $row['kateg_mobil'] ?>" readonly>
        </div>
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Tim Pengirim</h4>
          </div>
          <div class="card-body">
            <div class="form-group">
              <div class="row">
                <div class="col-md-4">
                  <label for="driver">Supir</label>
                  <input type="text" name="driver" class="form-control" value="<?= $row['supir']; ?>" readonly>
                  <input type="hidden" name="usupir" class="form-control" value="<?= $row['usupir']; ?>" readonly>
                </div>
                <div class="col-md-4">
                  <label for="helper_1">Helper 1</label>
                  <input type="text" name="helper_1" class="form-control" value="<?= $row['helper1']; ?>" readonly>
                  <input type="hidden" name="uhelper1" class="form-control" value="<?= $row['uhelper1']; ?>" readonly>
                </div>
                <div class="col-md-4">
                  <label for="helper_2">Helper 2</label>
                  <input type="text" name="helper_2" class="form-control" value="<?= $row['helper2']; ?>" readonly>
                  <input type="hidden" name="uhelper2" class="form-control" value="<?= $row['uhelper2']; ?>" readonly>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="row">
            <div class="col-md-4">
              <label for="jam_berangkat">Jam Keberangkatan</label>
              <input type="text" name="jam_berangkat" class="form-control" value="<?= date('d/m/Y H:i:s', strtotime($row['jam_berangkat'])); ?>" readonly>
              <input type="hidden" name="jam_berangkat2" class="form-control" value="<?= $row['jam_berangkat']; ?>" readonly>
            </div>
            <div class="col-md-4">
              <label for="estimasi_jam_datang">Estimasi Kedatangan</label>
              <input type="text" name="estimasi_jam_datang" class="form-control" value="<?= date('d/m/Y H:i:s', strtotime($row['estimasi_jam_datang'])); ?>" readonly>
            </div>
            <div class="col-md-4">
              <label for="jam_datang">Jam Kedatangan</label>
              <input type='text' class='form-control' data-td-target='#datetimepicker1' placeholder="dd/mm/yyyy hh:mm" name="jam_datang" value="<?= date('d/m/Y H:i:s', strtotime($row['jam_datang'])); ?>" readonly>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="form-check">
            <input class="form-check-input " type="checkbox" value="1" id="flexCheckDefault" name="bongkar" <?= $row['bongkar'] == 1 ? 'checked' : ''; ?> readonly>
            <label class="form-check-label text-bold" for="flexCheckDefault">
              Bongkar muatan?
            </label>
          </div>
        </div>
        <div id="map"></div>
        <button type="button" class="btn btn-sm btn-danger float-right mt-2" onclick="history.back();"><i class="fa fa-arrow-left"></i> Kembali</button>
      </form>

    </div>
  </div>
</div>

<?php
include_once "../partials/scriptdatatables.php";
?>

<script>
  $(document).ready(function() {
    $(":checkbox").bind("click", false);
    $("#datetimepicker1").keydown(function(event) {
      return false;
    });
  });
  var latPabrik = -3.4960839506671517;
  var lngPabrik = 114.81016825291921;
  var nama = 'Tujuan 1<br>PT Pancuran Kaapit Sendang';
  // var latLangPabrik = L.latLang(latPabrik, lngPabrik);

  const points = [
    [latPabrik, lngPabrik, "Pabrik Air Minum Amanah"],
  ];

  // console.log(array_coors);

  var latLngDistribusi = [];
  var coors = [];
  latLngDistribusi.push(L.latLng(latPabrik, lngPabrik));
  for (var i = 0; i < array_coors.length; i++) {
    points.push(array_coors[i]);
    coors = array_coors[i];
    latLngDistribusi.push(L.latLng(coors[0], coors[1]));
    // console.log(coors[0], coors[1]);
  }
  latLngDistribusi.push(L.latLng(latPabrik, lngPabrik));

  console.log(latLngDistribusi);

  var map = L.map('map', {
    zoomControl: false,
    center: [latPabrik, lngPabrik],
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

  googleHybrid = L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
    maxZoom: 20,
    subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
  }).addTo(map);

  map.addControl(new L.Control.Fullscreen());

  L.Routing.control({
    waypoints: latLngDistribusi,
    language: 'id',
  }).addTo(map);

  // adding all markers to the featureGroups array
  let featureGroups = [];
  for (let i = 0; i < points.length; i++) {
    const [lat, lng, title] = points[i];
    var ket;
    if (i > 0) {
      ket = 'Tujuan ' + i + "<br>";
    } else {
      ket = '';
    }
    featureGroups.push(L.marker([lat, lng]).bindPopup(ket + title));
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
    padding: [0, 0], // adding padding to map
  });

  function centered(e) {
    map.setView(e.target.getLatLng(), 18);
  }
</script>
<!-- /.content -->