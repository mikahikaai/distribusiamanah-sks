<?php
include "database/database.php";

$database = new Database;
$db = $database->getConnection();

$main_select = "SELECT *, k1.nama supir, k2.nama helper1, k3.nama helper2, da.id id_distribusi_anggota, db.status status_perjalanan
FROM distribusi_anggota da LEFT JOIN distribusi_barang db on da.id = db.id_distribusi_anggota
INNER JOIN armada a ON a.id = da.id_plat
LEFT JOIN karyawan k1 ON k1.id = da.driver
LEFT JOIN karyawan k2 ON k2.id = da.helper_1
LEFT JOIN karyawan k3 ON k3.id = da.helper_2
INNER JOIN pemesanan p ON p.id = db.id_order
INNER JOIN distributor d ON d.id = p.id_distro
WHERE db.no_resi = ?";
$stmt_da = $db->prepare($main_select);
$stmt_da->bindParam(1, $_GET['resi']);
$stmt_da->execute();
$row_da = $stmt_da->fetch(PDO::FETCH_ASSOC);




// var_dump(isset($_SESSION['errorlogin']));
// die();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="plugins/bootstrap-5.2.3-dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <script src="plugins/bootstrap-5.2.3-dist/js/bootstrap.min.js"></script>
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script src="plugins/fontawesome-free/js/all.min.js"></script>
  <title>Amanah | Lacak Barang</title>
  <style>
    p {
      font-family: 'Times New Roman', Times, serif
    }

    .modal-backdrop {
      background-color: black;
    }

    .modal-content {
      background-color: whitesmoke;
      opacity: 0.9;

    }

    .modal-header {
      background-color: #61BD9A;
    }

    .nav-item {
      font-size: large;
      font-weight: 600;
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #61BD9A;">
    <div class="container">
      <a class="navbar-brand" href="./">
        <img width="40%" src="images/Air-Amanah-Palangkaraya-Logo-fix.webp" alt="">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="d-flex">
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="./">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="#">Profil Perusahaan</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </nav>
  <!-- Content disini -->
  <?php if ($stmt_da->rowCount() > 0) { ?>
    <div class="content">
      <div class="container mt-3">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Nomor Resi - <span style="font-weight: bold;"><?= $row_da['no_resi'] ?></span> - <?= $row_da['status_perjalanan'] ?></h3>
            <a href="./" class="btn btn-danger btn-sm float-end">
              <i class="fa fa-arrow-left"></i> Kembali
            </a>
          </div>
          <div class="card-body">
            <div class="form-group">
              <label for="id_plat">Armada</label>
              <input type="text" name="id_plat" class="form-control" value="<?= $row_da['jenis_mobil'] . " - " . $row_da['plat'] ?>" readonly>
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
                      <input type="text" name="driver" class="form-control" value="<?= $row_da['supir'] ?>" readonly>
                    </div>
                    <div class="col-md-4">
                      <label for="helper_1">Helper 1</label>
                      <input type="text" name="helper_1" class="form-control" value="<?= $row_da['helper1'] ?>" readonly>
                    </div>
                    <div class="col-md-4">
                      <label for="helper_2">Helper 2</label>
                      <input type="text" name="helper_1" class="form-control" value="<?= $row_da['helper2'] ?>" readonly>
                    </div>
                  </div>
                </div>
              </div>
            </div>


            <div class="card">
              <div class="card-header">
                <h4 id="new_tujuan" class="card-title">Tujuan</h4>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-4" id="xyz1">
                    <div class="form-group">
                      <label for="nama_pel_1">Distributor</label>
                      <input type="text" name="nama_pel_1" value="<?= $row_da['nama'] ?>" class="form-control" readonly>
                    </div>
                  </div>
                  <div class="col-md-4" id="xyz2">
                    <div class="form-group">
                      <label for="pesanan">Nomor Order</label>
                      <input type="text" name="pesanan" value="<?= $row_da['nomor_order'] ?>" class="form-control" readonly>
                    </div>
                  </div>
                  <div class="col-md-4" id="xyz3">
                    <div class="form-group">
                      <label for="tgl_order">Tanggal Order</label>
                      <input type="text" class="form-control" name="tgl_order" value="<?= tanggal_indo($row_da['tgl_order']) ?>" readonly>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md">
                    <div class="form-group">
                      <label for="cup1">Muatan Cup</label>
                      <input type="number" name="cup1" class="form-control" value="<?= $row_da['cup'] ?>" readonly>
                    </div>
                  </div>
                  <div class="col-md">
                    <div class="form-group">
                      <label for="a3301">Muatan A330</label>
                      <input type="number" name="a3301" class="form-control" value="<?= $row_da['a330'] ?>" readonly>
                    </div>
                  </div>
                  <div class="col-md">
                    <div class="form-group">
                      <label for="a5001">Muatan A500</label>
                      <input type="number" name="a5001" class="form-control" value="<?= $row_da['a500'] ?>" readonly>
                    </div>
                  </div>
                  <div class="col-md">
                    <div class="form-group">
                      <label for="a6001">Muatan A600</label>
                      <input type="number" name="a6001" class="form-control" value="<?= $row_da['a600'] ?>" readonly>
                    </div>
                  </div>
                  <div class="col-md">
                    <div class="form-group">
                      <label for="refill1">Muatan Refill</label>
                      <input type="number" name="refill1" class="form-control" value="<?= $row_da['refill'] ?>" readonly>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  <?php } else { ?>
    <div class="card">
      <div class="card-header d-flex justify-content-center"><span style="font-size: x-large; font-weight: bolder;">DATA TIDAK DITEMUKAN</span></div>
    </div>
  <?php } ?>

  <!-- Akhir content disini -->
  <footer class="fixed-bottom">
    <div class="container-fluid bg-secondary">
      <div class="container text-light py-2">
        <b>Copyright &#169; <span class="text-danger">Fatharani Ihza</span> | NPM 18710045.</b> All Right Reserved
      </div>
    </div>
  </footer>
</body>

<?php
function tanggal_indo($date, $cetak_hari = false)
{
  $hari = array(
    1 =>    'Senin',
    'Selasa',
    'Rabu',
    'Kamis',
    'Jumat',
    'Sabtu',
    'Minggu'
  );

  $bulan = array(
    1 =>   'Januari',
    'Februari',
    'Maret',
    'April',
    'Mei',
    'Juni',
    'Juli',
    'Agustus',
    'September',
    'Oktober',
    'November',
    'Desember'
  );
  $split = explode(' ', $date);
  $split_tanggal = explode('-', $split[0]);
  if (count($split) == 1) {
    $tgl_indo = $split_tanggal[2] . ' ' . $bulan[(int)$split_tanggal[1]] . ' ' . $split_tanggal[0];
  } else {
    $split_waktu = explode(':', $split[1]);
    $tgl_indo = $split_tanggal[2] . ' ' . $bulan[(int)$split_tanggal[1]] . ' ' . $split_tanggal[0] . ' ' . $split_waktu[0] . ':' . $split_waktu[1] . ':' . $split_waktu[2];
  }

  if ($cetak_hari) {
    $num = date('N', strtotime($date));
    return $hari[$num] . ', ' . $tgl_indo;
  }
  return $tgl_indo;
}
?>

</html>

<script src="./plugins/sweetalert2/sweetalert2.all.min.js"></script>
<script src="./plugins/jquery/jquery.min.js"></script>

<script>
  AOS.init({
    once: true
  });

  if ($('div#errorlogin').length) {
    Swal.fire({
      title: 'Login Gagal',
      text: 'Username atau Password Salah!',
      icon: 'error',
      confirmButtonText: 'OK'
    })
  };
</script>