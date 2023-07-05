<?php
include "database/database.php";
$database = new Database;
$db = $database->getConnection();
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'plugins/php-mailer/src/Exception.php';
require 'plugins/php-mailer/src/PHPMailer.php';
require 'plugins/php-mailer/src/SMTP.php';


$erroremail = false;
$suksesreset = false;

if (isset($_POST['kirim'])) {
  $select = 'SELECT * FROM karyawan WHERE email = ? OR username = ?';
  $stmtselect = $db->prepare($select);
  $stmtselect->bindParam(1, $_POST['email']);
  $stmtselect->bindParam(2, $_POST['email']);
  $stmtselect->execute();
  $rowselect = $stmtselect->fetch(PDO::FETCH_ASSOC);

  if ($stmtselect->rowCount() == 0) {
    $erroremail = true;
  } else {
    $emailTo = $rowselect["email"]; //email kamu atau email penerima link reset
    $code = uniqid(true); //Untuk kode atau parameter acak
    $query = 'INSERT INTO reset_password VALUES (NULL,?,?,?,"0")';
    $tgl_reset = date('Y-m-d');
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $tgl_reset);
    $stmt->bindParam(2, $rowselect['id']);
    $stmt->bindParam(3, $code);
    $stmt->execute();

    $mail = new PHPMailer(true);                              // Passing `true` enables exceptions

    try {
      //Server settings
      //$mail->SMTPDebug = 2;                                 // Enable verbose debug output
      $mail->isSMTP();                                      // Set mailer to use SMTP
      $mail->Host = 'smtp.gmail.com';                     // Specify main and backup SMTP servers
      $mail->SMTPAuth = true;                               // Enable SMTP authentication
      $mail->Username = "mikahikaai10000@gmail.com";             // SMTP username
      $mail->Password = 'itodwtwalxmuhaes';                         // SMTP password
      $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
      $mail->Port = 587;                                    // TCP port to connect to

      //Recipients
      $mail->setFrom("admin@gmail.com", "Admin PKS"); //email pengirim
      $mail->addAddress($emailTo); // Email penerima
      $mail->addReplyTo("no-reply@gmail.com");

      //Content
      $url = "http://" . $_SERVER["HTTP_HOST"] . "/reset_password.php?validasi=$code"; //sesuaikan berdasarkan link server dan nama file
      $mail->isHTML(true);                                  // Set email format to HTML
      $mail->Subject = "Link reset password";
      $mail->Body    = "<h1>Permintaan reset password</h1><p> Silahkan klik <a href='$url'>link ini</a> untuk mereset password</p>";
      //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
      $mail->send();

      $suksesreset = true;
    } catch (Exception $e) {
      echo 'Message could not be sent.';
      echo 'Mailer Error: ' . $mail->ErrorInfo;
    }
  }
}


// var_dump($errorlogin);
// die();
?>
<!doctype html>
<html lang="en">

<head>
  <title>Lupa Password | PT PKS</title>
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
            <div style='display: <?= $erroremail == true ? "block;" : "none;"; ?>'>
              <div class="alert alert-danger alert-dismissable">
                <button class="close" type="button" data-dismiss="alert" aria-hidden="true">X</button>
                <h5><i class="fas fa-times"></i> Gagal</h5>
                Email tidak ditemukan
              </div>
            </div>
            <div style='display: <?= $suksesreset == true ? "block;" : "none;"; ?>'>
              <div class="alert alert-success alert-dismissable">
                <button class="close" type="button" data-dismiss="alert" aria-hidden="true">X</button>
                <h5><i class="fas fa-check"></i> Sukses</h5>
                Link reset password sudah dikirimkan ke email anda
              </div>
            </div>
            <span class="d-flex justify-content-center mb-3" style="font-weight: bold; font-size: 20px;">Reset Password</span>
            <form action="" method="POST" class="signin-form">
              <div class="form-group">
                <input type="text" class="form-control" placeholder="Masukkan e-mail atau Username" name="email" value="<?= $_POST['username'] ?? '' ?>" required>
              </div>
              <!-- <div class="form-group">
                <input id="password-field" type="password" class="form-control" placeholder="Password" name="password" required>
                <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
              </div> -->
              <div class="form-group">
                <button type="submit" class="form-control btn btn-primary submit px-3" name="kirim">Kirim</button>
              </div>
              <div class="form-group">
                <a href="./login.php" class="float-right mr-2" style="color: #fff"><u>Login?</u></a>
              </div>
              <!-- <div class="form-group d-md-flex">
                <div class="w-50">
                  <label class="checkbox-wrap checkbox-primary">Ingat Saya
                    <input type="checkbox" checked>
                    <span class="checkmark"></span>
                  </label>
                </div>
                <div class="w-50 text-md-right">
                  <a href="#" style="color: #fff">Lupa Password</a>
                </div>
              </div> -->
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