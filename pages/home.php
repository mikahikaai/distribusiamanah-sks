<!-- Content Header (Page header) -->
<?php
$database = new Database;
$db = $database->getConnection();

$selectsql = "SELECT a.*, d.*, k1.nama as supir, k1.upah_borongan usupir1, k2.nama helper1, k2.upah_borongan uhelper2, k3.nama helper2, k3.upah_borongan uhelper2, do1.nama distro1, do1.jarak jdistro1, do2.nama distro2, do2.jarak jdistro2, do3.nama distro3, do3.jarak jdistro3
FROM distribusi d INNER JOIN armada a on d.id_plat = a.id
LEFT JOIN karyawan k1 on d.driver = k1.id
LEFT JOIN karyawan k2 on d.helper_1 = k2.id
LEFT JOIN karyawan k3 on d.helper_2 = k3.id
LEFT JOIN distributor do1 on d.nama_pel_1 = do1.id
LEFT JOIN distributor do2 on d.nama_pel_2 = do2.id
LEFT JOIN distributor do3 on d.nama_pel_3 = do3.id
WHERE jam_datang IS NULL
ORDER BY estimasi_jam_datang DESC; ";
$stmt = $db->prepare($selectsql);
$stmt->execute();
$num_rows = $stmt->rowCount();

if (isset($_SESSION['hasil_update_pw'])) {
  if ($_SESSION['hasil_update_pw']) {
?>
    <div id='hasil_update_pw'></div>
  <?php
  }
  unset($_SESSION['hasil_update_pw']);
}

if (isset($_SESSION['login_sukses'])) {
  if ($_SESSION['login_sukses']) {
  ?>
    <div id='login_sukses'></div>
<?php
  }
  unset($_SESSION['login_sukses']);
}



?>

<!-- Main content -->
<div class="content pt-3">
  <div class="container-fluid">
    <div class="row">
      <div class="col-6">
        <h3 class="mb-3">Dalam Perjalanan </h3>
      </div>
      <div class="col-6 text-right">
        <a class="btn btn-primary mb-3 mr-1" href="#carouselExampleIndicators2" role="button" data-slide="prev">
          <i class="fa fa-arrow-left"></i>
        </a>
        <a class="btn btn-primary mb-3 " href="#carouselExampleIndicators2" role="button" data-slide="next">
          <i class="fa fa-arrow-right"></i>
        </a>
      </div>
      <div class="col-12">
        <div id="carouselExampleIndicators2" class="carousel slide" data-ride="carousel">
          <div class="carousel-inner">
            <?php
            $no = 1;
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
              // var_dump($no);
              // die();
              $supir = $row['supir'] == NULL ? '-' : $row['supir'];
              $helper1 = $row['helper1'] == NULL ? '-' : $row['helper1'];
              $helper2 = $row['helper2'] == NULL ? '-' : $row['helper2'];
              $distro1 = $row['distro1'] == NULL ? '-' : $row['distro1'];
              $distro2 = $row['distro2'] == NULL ? '-' : $row['distro2'];
              $distro3 = $row['distro3'] == NULL ? '-' : $row['distro3'];
              $bongkar = $row['bongkar'] == 0 ? 'TIDAK' : 'YA';
              $total_cup = $row['cup1'] + $row['cup2'] + $row['cup3'];
              $total_330 = $row['a3301'] + $row['a3302'] + $row['a3303'];
              $total_500 = $row['a5001'] + $row['a5002'] + $row['a5003'];
              $total_600 = $row['a6001'] + $row['a6002'] + $row['a6003'];
              $total_refill = $row['refill1'] + $row['refill2'] + $row['refill3'];
              $estimasi_lama_perjalanan = date_diff(date_create($row['jam_berangkat']), date_create($row['estimasi_jam_datang']))->format('%d Hari %h Jam %i Menit %s Detik');
              if ($no == 1) {
                echo  "<div class='carousel-item active'>";
                echo  '<div class="row">';
              } else if ($no % 4 == 1) {
                echo  "<div class='carousel-item'>";
                echo  '<div class="row">';
              }
            ?>
              <div class="col-md-3 mb-3">
                <div class="card shadow-sm">
                  <h5 class="card-header"><?= $row['no_perjalanan']; ?></h5>
                  <div class="card-body">
                    <p class="card-text">Tujuan :<br> <?= implode(", ", array_filter(array($row['distro1'], $row['distro2'], $row['distro3']))); ?></p>
                    <p class="card-text">Tim Pengirim :<br> <?= implode(", ", array_filter(array($row['supir'], $row['helper1'], $row['helper2']))); ?> </p>
                    <p class="card-text">Muatan :<br>Cup = <?= $total_cup; ?>, A330 = <?= $total_330 ?>, A500 = <?= $total_500 ?>, A600 = <?= $total_600 ?>, Refill = <?= $total_refill ?> </p>
                    <p class="card-text">Estimasi Lama Perjalanan : <br> <?= $estimasi_lama_perjalanan; ?></p>
                    <p class="card-text">Estimasi Datang :<br> <?= date('d-m-Y H:i:s', strtotime($row['estimasi_jam_datang'])); ?> </p>
                  </div>
                  <a href="?page=distribusiupdate&id=<?= $row['id']; ?>" class="btn btn-primary d-block">Ubah</a>
                </div>
              </div>
            <?php
              if ($no % 4 == 0 or $no == $num_rows) {
                echo  '</div>';
                echo  '</div>';
              }
              $no++;
            }
            ?>
          </div>
        </div>
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
</div>

<?php
include_once "partials/scriptdatatables.php";
?>

<script>
  // Get cards
  var cards = $('.card-body');
  var maxHeight = 0;

  // Loop all cards and check height, if bigger than max then save it
  for (var i = 0; i < cards.length; i++) {
    if (maxHeight < $(cards[i]).outerHeight()) {
      maxHeight = $(cards[i]).outerHeight();
    }
  }
  // Set ALL card bodies to this height
  for (var i = 0; i < cards.length; i++) {
    $(cards[i]).height(maxHeight);
  }

  if ($('div#hasil_update_pw').length) {
    Swal.fire({
      title: 'Updated!',
      text: 'Password berhasil diubah',
      icon: 'success',
      confirmButtonText: 'OK'
    })
  }

  if ($('div#login_sukses').length) {
    let timerInterval
    let nama = "<?= ucfirst($_SESSION['nama']); ?>"
    Swal.fire({
      width: 'auto',
      showConfirmButton: false,
      position: 'top-end',
      html: '<h5>Selamat Datang ' + nama + ' !</h5>',
      timer: 3000,
      timerProgressBar: true,

      willClose: () => {
        clearInterval(timerInterval)
      }
    })
  };

  // $().ready(function() {
  //   let timerInterval
  //   let nama = "<?= ucfirst($_SESSION['nama']); ?>"
  //   Swal.fire({
  //     showConfirmButton: false,
  //     width: 'auto',
  //     position: 'top-end',
  //     html: '<h5>Selamat Datang ' + nama + ' !</h5>',
  //     timer: 3000,
  //     timerProgressBar: true,

  //     willClose: () => {
  //       clearInterval(timerInterval)
  //     }
  //   })
  // });
</script>
<!-- /.content -->