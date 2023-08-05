<?php
include "database/database.php";
$database = new Database;
$db = $database->getConnection();
session_start();
// var_dump($_SERVER);
// die();

// var_dump($_SERVER["HTTP_HOST"]);
// die();

if (isset($_SESSION['suksesreset'])) {
  $suksesreset = true;
} else {
  $suksesreset = false;
}

if (isset($_SESSION['errorakses'])) {
  $errorakses = true;
} else {
  $errorakses = false;
}



if (isset($_COOKIE['id']) && isset($_COOKIE['keylog'])) {
  $loginsql = "SELECT * FROM karyawan WHERE id=?";
  $stmt = $db->prepare($loginsql);
  $stmt->bindParam(1, $_COOKIE['id']);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($_COOKIE['keylog'] === hash('sha256', $row['username'])) {
    $_SESSION['id'] = $row['id'];
    $_SESSION['username'] = $row['username'];
    $_SESSION['jabatan'] = $row['jabatan'];
    $_SESSION['nama'] = $row['nama'];
    $_SESSION['foto'] = $row['foto'];
    $_SESSION['login_sukses'] = true;
  }
}

if (isset($_SESSION['jabatan'])) {
  if ($_SESSION['jabatan'] == "ADMINKEU") {
    echo '<meta http-equiv="refresh" content="0;url=/adminkeu/"/>';
  } else if ($_SESSION['jabatan'] == "SPVDISTRIBUSI") {
    echo '<meta http-equiv="refresh" content="0;url=/spvdist/"/>';
  } else if ($_SESSION['jabatan'] == "DRIVER" or $_SESSION['jabatan'] == "HELPER") {
    echo '<meta http-equiv="refresh" content="0;url=/karyawan/"/>';
  } else if ($_SESSION['jabatan'] == "MGRDISTRIBUSI") {
    echo '<meta http-equiv="refresh" content="0;url=/mgrdist/"/>';
  }
  die();
}

if (isset($_POST['login'])) {
  $_SESSION['errorlogin'] = false;
  $loginsql = "SELECT * FROM karyawan WHERE username=? and password=?";
  $stmt = $db->prepare($loginsql);
  $stmt->bindParam(1, $_POST['username']);
  $md5 = md5($_POST['password']);
  $stmt->bindParam(2, $md5);
  $stmt->execute();

  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($stmt->rowCount() > 0) {
    $_SESSION['id'] = $row['id'];
    $_SESSION['username'] = $row['username'];
    $_SESSION['jabatan'] = $row['jabatan'];
    $_SESSION['nama'] = $row['nama'];
    $_SESSION['foto'] = $row['foto'];
    $_SESSION['login_sukses'] = true;

    if (isset($_POST['remember'])) {
      setcookie('id', $row['id'], time() + 60 * 60 * 24 * 7);
      setcookie('keylog', hash('sha256', $row['username']), time() + 60 * 60 * 24 * 7);
    }

    if ($_SESSION['jabatan'] == "ADMINKEU") {
      echo '<meta http-equiv="refresh" content="0;url=/adminkeu/"/>';
      die();
    } else if ($_SESSION['jabatan'] == "SPVDISTRIBUSI") {
      echo '<meta http-equiv="refresh" content="0;url=/spvdist"/>';
      die();
    } else if ($_SESSION['jabatan'] == "DRIVER" || $_SESSION['jabatan'] == "HELPER") {
      echo '<meta http-equiv="refresh" content="0;url=/karyawan"/>';
      die();
    } else if ($_SESSION['jabatan'] == "MGRDISTRIBUSI") {
      echo '<meta http-equiv="refresh" content="0;url=/mgrdist"/>';
      die();
    }
  } else {
    $_SESSION['errorlogin'] = true;
    if (isset($_SESSION['errorlogin'])) {
      if ($_SESSION['errorlogin']) {
?>
        <div id='errorlogin'></div>
<?php
        unset($_SESSION['errorlogin']);
      }
    }
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="plugins/bootstrap-5.2.3-dist/css/bootstrap.min.css">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <script src="plugins/bootstrap-5.2.3-dist/js/bootstrap.min.js"></script>
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <title>Amanah | Profil Perusahaan</title>
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
      <a class="navbar-brand" href="/">
        <img width="40%" src="images/Air-Amanah-Palangkaraya-Logo-fix.webp" alt="">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="d-flex">
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" aria-current="page" href="/">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="/profile.php">Profil Perusahaan</a>
            </li>
            <li class="nav-item">
              <!-- Button trigger modal -->
              <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#staticBackdrop" id="login">
                <span style="font-weight: bold;">Login</span>
              </button>

              <!-- Modal -->
              <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="staticBackdropLabel">Login</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="" method="post">
                      <div class="modal-body">
                        <div class="container-fluid">
                          <label for="username">Username</label>
                          <input type="text" class="form-control" name="username" placeholder="Masukkan Username...">
                          <label for="password">Password</label>
                          <input type="password" class="form-control" name="password" placeholder="Masukkan Password...">
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="login" class="btn btn-primary">Login</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>

            </li>
          </ul>
        </div>
      </div>
    </div>
  </nav>
  <div class="container">
    <div class="card mt-2">
      <div class="card-header">
        <h5 class="card-title d-flex justify-content-center">
          <span class="fs-2 fw-bolder">Profil Perusahaan PT Pancuran Kaapit Sendang</span>
        </h5>
      </div>
      <div class="card-body">
        <p align="justify" style="font-size: x-large;">PT. Pancuran Kaapit Sendang adalah satu dari delapan belas perusahaan yang berada dibawah naungan Amanah Group, dan menjadi perusahaan keempat dari perusahaan-perusahaan sebelumnya, yaitu PT. Amanah Anugerah Adi Mulia, PT. Gunung Limo, dan PT. Safari Samudera Raya.</p>

        <p align="justify" style="font-size: x-large;">Mulai dirintis sejak tahun 2006 dan mulai beroperasi pada bulan Mei 2009, PT. Pancuran Kaapit Sendang bergerak di bidang agrowisata (Amanah Borneo Park) serta produksi dan distribusi Air Minum Dalam Kemasan (AMDK) dengan merek Amanah. Ada 5 (lima) tipe kemasan AMDK yang diproduksi dan didistribusikan saat ini, yaitu: Cup 240 ml, Botol 330 ml, Botol 500 ml, Botol 600 ml dan Galon 19 liter.</p>

        <img style="width: 100%;" src="./images/carousel4.png" alt="bg-profile">

        <p></p>

        <p align="justify" style="font-size: x-large;">PT. Pancuran Kaapit Sendang berlokasi di Jl. Taruna Bhakti Kelurahan Palam Kecamatan Cempaka Kota Banjarbaru, Telp/ Fax: 05116176347. Untuk menunjang kegiatan penjualan dan pemasaran AMDK di Banjarbaru, Banjarmasin, dan sekitarnya, dibukalah 2 (dua) buah depo di Banjarbaru dan Banjarmasin. Depo Banjarbaru terletak di Jl. A. Yani Km. 35,5 dan Depo Banjarmasin berada di Jl. Adhyaksa.</p>

        <p align="justify" style="font-size: x-large;">Area pemasaran meliputi Kalimantan Selatan, Kalimantan Tengah, dan Kalimantan Timur. Ada 5 (lima) agen penjualan dan pemasaran yang memasarkan di area-area tersebut, 2 (dua) agen di Kaltim, yaitu di Samarinda dan Balikpapan, 1 (satu) agen di Kalsel, yaitu di Batulicin, dan 2 (dua) agen di Kalteng, yaitu di Palangka Raya dan Sampit.
          Saat ini ada sekitar 131 orang yang bekerja di PT. Pancuran Kaapit Sendang, dan sebagian besar adalah warga sekitar Perusahaan.</p>
      </div>
    </div>
  </div>

  <footer>
    <div class="container-fluid bg-secondary sticky-bottom">
      <div class="container text-light py-2">
        <b>Copyright &#169; <span class="text-danger">Fatharani Ihza</span> | NPM 18710045.</b> All Right Reserved
      </div>
    </div>
  </footer>
</body>

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