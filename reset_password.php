<?php
include "database/database.php";
$database = new Database;
$db = $database->getConnection();
session_start();

// var_dump($_SERVER["HTTP_HOST"]);
// die();

$errorreset = false;

if (isset($_GET['validasi'])) {
  $resetsql = "SELECT * FROM reset_password WHERE code = ? AND expired = '0'";
  $stmtreset = $db->prepare($resetsql);
  $stmtreset->bindParam(1, $_GET['validasi']);
  $stmtreset->execute();
  $rowreset = $stmtreset->fetch(PDO::FETCH_ASSOC);
  if ($stmtreset->rowCount() == 0) {
    $_SESSION['errorakses'] = true;
    echo '<meta http-equiv="refresh" content="0;url=/login.php"/>';
    die();
  }
}

if (isset($_POST['reset'])) {
  // $resetsql = "SELECT * FROM reset_password WHERE code = ? AND expired = '0'";
  // $stmtreset = $db->prepare($resetsql);
  // $stmtreset->bindParam(1, $_GET['validasi']);
  // $stmtreset->execute();
  // $rowreset = $stmtreset->fetch(PDO::FETCH_ASSOC);
  // if ($stmtreset->rowCount() == 0) {
  //   $_SESSION['errorakses'] = true;
  //   echo '<meta http-equiv="refresh" content="0;url=/login.php"/>';
  //   die();
  // }
  if ($_POST['password'] != $_POST['password2']) {
    $errorreset = true;
  } else {
    $updatepass = "UPDATE karyawan SET password = ? WHERE id = ?";
    $passwordbaru = md5($_POST['password']);
    $stmtupdatepass = $db->prepare($updatepass);
    $stmtupdatepass->bindParam(1, $passwordbaru);
    $stmtupdatepass->bindParam(2, $rowreset['id_karyawan']);
    $stmtupdatepass->execute();

    $updateexp = "UPDATE reset_password SET expired = '1' WHERE code = ?";
    $stmtupdateexp = $db->prepare($updateexp);
    $stmtupdateexp->bindParam(1, $_GET['validasi']);
    $stmtupdateexp->execute();
    $_SESSION['suksesreset'] = true;
    echo '<meta http-equiv="refresh" content="0;url=/login.php"/>';
  }
}


// var_dump($errorlogin);
// die();
?>
<!doctype html>
<html lang="en">

<head>
  <title>Reset Password | PT PKS</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="icon" href="../images/logooo cropped resized compressed.png" type="image/x-icon">

  <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

  <link rel="stylesheet" href="login/css/style.css">

  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">

</head>

<body class="img js-fullheight" style="background-image: url(login/images/bg.jpg);">
  <section class="ftco-section">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-6 text-center mb-5">
          <h2 class="heading-section">PT PANCURAN KAAPIT SENDANG</h2>
        </div>
      </div>
      <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
          <div class="login-wrap p-0">
            <div style='display: <?= $errorreset == true ? "block;" : "none;"; ?>'>
              <div class="alert alert-danger alert-dismissable">
                <button class="close" type="button" data-dismiss="alert" aria-hidden="true">X</button>
                <h5><i class="fas fa-times"></i> Gagal</h5>
                Password tidak sesuai
              </div>
            </div>
            <!-- <div style='display: <?= $suksesreset == true ? "block;" : "none;"; ?>'>
              <div class="alert alert-success alert-dismissable">
                <button class="close" type="button" data-dismiss="alert" aria-hidden="true">X</button>
                <h5><i class="fas fa-check"></i> Sukses</h5>
                Password berhasil direset
              </div>
            </div> -->
            <form action="" method="POST" class="signin-form">
              <div class="form-group">
                <input id="password-field" type="password" class="form-control" placeholder="Password Baru" name="password" required>
                <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
              </div>
              <div class="form-group">
                <input id="password-field2" type="password" class="form-control" placeholder="Konfirmasi Password Baru" name="password2" required>
                <span toggle="#password-field2" class="fa fa-fw fa-eye field-icon toggle-password"></span>
              </div>
              <div class="form-group">
                <button type="submit" class="form-control btn btn-primary submit px-3" name="reset">Reset</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

  <script src="login/js/jquery.min.js"></script>
  <script src="login/js/popper.js"></script>
  <script src="login/js/bootstrap.min.js"></script>
  <script src="login/js/main.js"></script>

</body>