<?php
$database = new Database;
$db = $database->getConnection();

$validasi = "SELECT * FROM distributor WHERE id_da = ?";
$stmt = $db->prepare($validasi);
$stmt->bindParam(1, $_POST['id_da']);
$stmt->execute();

if ($stmt->rowCount() > 0) {
?>
  <div class="alert alert-danger alert-dismissable">
    <button class="close" type="button" data-dismiss="alert" aria-hidden="true">X</button>
    <h5><i class="icon fas fa-times"></i>Gagal</h5>
    Data sudah ada di database
  </div>
<?php
} else {

  if (isset($_POST['button_create'])) {
    $insertsql = "insert into distributor (id_da, nama, paket, alamat_dropping, no_telepon, jarak, lat, lng) values (?,?,?,?,?,?,?,?)";
    $stmt = $db->prepare($insertsql);
    $id_da = strtoupper($_POST['id_da']);
    $nama_distributor = strtoupper($_POST['nama']);
    $alamat_dropping_distributor = strtoupper($_POST['alamat_dropping']);
    $stmt->bindParam(1, $id_da);
    $stmt->bindParam(2, $nama_distributor);
    $stmt->bindParam(3, $_POST['paket']);
    $stmt->bindParam(4, $alamat_dropping_distributor);
    $stmt->bindParam(5, $_POST['no_telepon']);
    $stmt->bindParam(6, $_POST['jarak']);
    $stmt->bindParam(7, $_POST['lat']);
    $stmt->bindParam(8, $_POST['lng']);
    if ($stmt->execute()) {
      $_SESSION['hasil_create'] = true;
      $_SESSION['pesan'] = "Berhasil Menyimpan Data";
    } else {
      $_SESSION['hasil_create'] = false;
      $_SESSION['pesan'] = "Gagal Menyimpan Data";
    }
    echo '<meta http-equiv="refresh" content="0;url=?page=distributorread"/>';
    exit;
  }
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
          <li class="breadcrumb-item">Tambah Distributor</li>
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
      <h3 class="card-title">Data Tambah Distributor</h3>
    </div>
    <div class="card-body">
      <form action="" method="post">
        <div class="form-group">
          <label for="id_da">ID Distributor</label>
          <input type="text" name="id_da" class="form-control" value="<?= isset($_POST['button_create']) ? $_POST['id_da'] : '' ?>" style="text-transform: uppercase;" required>
        </div>
        <div class="form-group">
          <label for="nama">Nama Lengkap</label>
          <input type="text" name="nama" class="form-control" value="<?= isset($_POST['button_create']) ? $_POST['nama'] : '' ?>" style="text-transform: uppercase;" required>
        </div>
        <div class="form-group">
          <label for="paket">Paket</label>
          <select name="paket" class="form-control" required>
            <option value="">--Pilih Jenis Paket--</option>
            <?php
            $options = array('DISTRIBUTOR', 'SUB DISTRIBUTOR', 'BUKAN SUB/DISTRIBUTOR');
            foreach ($options as $option) {
              $selected = $_POST['paket'] == $option ? 'selected' : '';
              echo "<option value=\"" . $option . "\"" . $selected . ">" . $option . "</option>";
            }
            ?>
          </select>
        </div>
        <div class="form-group">
          <label for="alamat_dropping">Alamat Dropping</label>
          <input type="text" name="alamat_dropping" class="form-control" value="<?= isset($_POST['button_create']) ? $_POST['alamat_dropping'] : '' ?>" style="text-transform: uppercase;" required>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="jarak">Jarak Dari Pabrik</label>
              <input type="text" name="jarak" id="jarak" class="form-control" value="<?= isset($_POST['button_create']) ? $_POST['jarak'] : '' ?>" readonly>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="no_telepon">No. Telepon</label>
              <input type="text" name="no_telepon" class="form-control" onkeypress="return (event.charCode > 47 && event.charCode <58) || event.charCode == 46" min="0" maxlength="14" value="<?= isset($_POST['button_create']) ? $_POST['no_telepon'] : '' ?>" required>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <input type="hidden" id="lat" name="lat" class="form-control">
          </div>
          <div class="col-md-6">
            <input type="hidden" id="lng" name="lng" class="form-control">
          </div>
        </div>
        <label for="">Map</label>
        <div class="auto-search-wrapper mb-2">
          <input type="text" autocomplete="off" id="search" class="full-width form-control" placeholder="Ketik nama tempat yang ingin anda cari..." />
        </div>
        <div id="map"></div>
        <a href="?page=distributorread" class="btn btn-danger btn-sm float-right mt-2">
          <i class="fa fa-arrow-left"></i> Kembali
        </a>
        <button type="submit" name="button_create" class="btn btn-success btn-sm float-right mr-1 mt-2">
          <i class="fa fa-save"></i> Simpan
        </button>
      </form>
    </div>
  </div>
</div>

<?php
include_once "../partials/scriptdatatables.php";
?>

<!-- /.content -->
<script>
  new Autocomplete("search", {
    // default selects the first item in
    // the list of results
    selectFirst: true,

    // The number of characters entered should start searching
    howManyCharacters: 2,

    // onSearch
    onSearch: ({
      currentValue
    }) => {
      // You can also use static files
      // const api = '../static/search.json'
      const api = `https://nominatim.openstreetmap.org/search?format=geojson&limit=10&q=${encodeURI(
        currentValue
        )}`;

      /**
       * jquery
       */
      // return $.ajax({
      //     url: api,
      //     method: 'GET',
      //   })
      //   .done(function (data) {
      //     return data
      //   })
      //   .fail(function (xhr) {
      //     console.error(xhr);
      //   });

      // OR -------------------------------

      /**
       * axios
       * If you want to use axios you have to add the
       * axios library to head html
       * https://cdnjs.com/libraries/axios
       */
      // return axios.get(api)
      //   .then((response) => {
      //     return response.data;
      //   })
      //   .catch(error => {
      //     console.log(error);
      //   });

      // OR -------------------------------

      /**
       * Promise
       */
      return new Promise((resolve) => {
        fetch(api)
          .then((response) => response.json())
          .then((data) => {
            resolve(data.features);
          })
          .catch((error) => {
            console.error(error);
          });
      });
    },
    // nominatim GeoJSON format parse this part turns json into the list of
    // records that appears when you type.
    onResults: ({
      currentValue,
      matches,
      template
    }) => {
      const regex = new RegExp(currentValue, "gi");

      // if the result returns 0 we
      // show the no results element
      return matches === 0 ?
        template :
        matches
        .map((element) => {
          return `
          <li class="loupe">
            <p>
              ${element.properties.display_name.replace(
                regex,
                (str) => `<b>${str}</b>`
              )}
            </p>
          </li> `;
        })
        .join("");
    },

    // we add an action to enter or click
    onSubmit: ({
      object
    }) => {
      // remove all layers from the map
      map.eachLayer(function(layer) {
        if (!!layer.toGeoJSON) {
          map.removeLayer(layer);
        }
      });

      const {
        display_name
      } = object.properties;
      const [lng, lat] = object.geometry.coordinates;

      const marker = L.marker([lat, lng], {
        title: display_name,
      });

      marker.addTo(map).bindPopup(display_name);

      map.setView([lat, lng], 17);
    },

    // get index and data from li element after
    // hovering over li with the mouse or using
    // arrow keys ↓ | ↑
    onSelectedItem: ({
      index,
      element,
      object
    }) => {
      console.log("onSelectedItem:", index, element, object);
    },

    // the method presents no results element
    noResults: ({
        currentValue,
        template
      }) =>
      template(`<li>No results found: "${currentValue}"</li>`),
  });

  var lat = -3.4945066670586287;
  var lng = 114.81069624423982;
  var nama = "Amanah Distro (NEW)";
  var latPabrik = -3.4960839506671517;
  var lngPabrik = 114.81016825291921;

  let latLangPabrik = L.latLng(latPabrik, lngPabrik);
  let latLangDistro = L.latLng(lat, lng);

  if (!lat && !lng) {
    lat = latPabrik;
    lng = lngPabrik;
    nama = "Pabrik Air Minum Amanah";
  }

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

  var zoomHome = L.Control.zoomHome();
  zoomHome.addTo(map);

  var LeafIcon = L.Icon.extend({
    options: {
      iconSize: [38, 38],
      shadowSize: [50, 64],
      iconAnchor: [22, 45],
      shadowAnchor: [4, 62],
      popupAnchor: [-3, -45]
    }
  });

  L.control.coordinates({
    position: "bottomleft",
    decimals: 10, //optional default 4
    decimalSeperator: ".", //optional default "."
    labelTemplateLat: "Latitude: {y}", //optional default "Lat: {y}"
    labelTemplateLng: "Longitude: {x}", //optional default "Lng: {x}"
    enableUserInput: false, //optional default true
    useDMS: false, //optional default false
    useLatLngOrder: true, //ordering of labels, default false-> lng-lat
    markerType: L.marker, //optional default L.marker
    markerProps: {} //optional default {}
  }).addTo(map);

  var greenIcon = new LeafIcon({
    iconUrl: '../images/logooo cropped resized compressed.png',
    // shadowUrl: 'http://leafletjs.com/examples/custom-icons/leaf-shadow.png'
  })

  var pabrik;
  pabrik = L.marker([latPabrik, lngPabrik], {
      draggable: false
    }).bindPopup("Pabrik Air Minum Amanah")
    .addTo(map)
    .openPopup()
    .on("click", centered);
  document.getElementById('lat').value = pabrik.getLatLng().lat;
  document.getElementById('lng').value = pabrik.getLatLng().lng;

  map.addControl(new L.Control.Fullscreen());

  var marker;
  var control;
  map.on("click", function(e) {
    if (marker) { // check
      map.removeLayer(marker); // remove
    }
    marker = new L.marker(e.latlng, {
      //icon: greenIcon,
      draggable: true,
      autopan: true
    }).bindPopup("Distro Amanah" + " " + "(NEW)").addTo(map).openPopup().on("click", centered).on("dblclick", removed); // set
    // marker.on("dragend", function(e) {
    //   document.getElementById('lat').value = marker.getLatLng().lat;
    //   document.getElementById('lng').value = marker.getLatLng().lng;
    // });
    document.getElementById('lat').value = marker.getLatLng().lat;
    document.getElementById('lng').value = marker.getLatLng().lng;
    keSini(marker.getLatLng().lat, marker.getLatLng().lng);
  });
  control = L.Routing.control({
    waypoints: [latLangPabrik, latLangDistro],
    language: 'id'
  }).on("routesfound", function(e) {
    var jarak = e.routes[0].summary.totalDistance / 1000;
    document.getElementById('jarak').value = jarak.toFixed(2);
  })
  control.addTo(map);


  map.clicked = 0;

  function centered(e) {
    map.clicked = map.clicked + 1;
    setTimeout(function() {
      if (map.clicked == 1) {
        map.setView(e.target.getLatLng(), 18);
        map.clicked = 0;
      }
    }, 250);
  }

  function removed(e) {
    map.clicked = 0;
    map.removeLayer(e.target);
    document.getElementById('lat').value = lat;
    document.getElementById('lng').value = lng;
  }

  function keSini(lat, lng) {
    var latLng = L.latLng(lat, lng);
    control.spliceWaypoints(control.getWaypoints().length - 1, 1, latLng);
  }
</script>