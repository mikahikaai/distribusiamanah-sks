<!-- Content Header (Page header) -->
<?php
$database = new Database;
$db = $database->getConnection();

$tahun = date('Y');
$tanggal_awal = date_create($tahun . '-01-01')->setTime(0, 0, 0);
$tanggal_akhir = date_create($tahun . '-12-31')->setTime(23, 59, 59);

//rekap distribusi
$_SESSION['tgl_rekap_awal_distribusi'] = $tanggal_awal;
$_SESSION['tgl_rekap_akhir_distribusi'] = $tanggal_akhir;
$_SESSION['id_karyawan_rekap_distribusi'] = 'all';
$_SESSION['status_kedatangan_distribusi'] = '1';

// var_dump($arrayChartUpah);
// var_dump($arrayChartInsentif);
// die();

$tanggalBatasAwal = $tanggal_awal->format('Y-m-d H:i:s');
$tanggalBatasAkhir = $tanggal_akhir->format('Y-m-d H:i:s');

$selectsql = "SELECT *, k1.nama supir, k2.nama helper1, k3.nama helper2, do1.nama distro
FROM distribusi_barang db
LEFT JOIN distribusi_anggota da on da.id  = db.id_distribusi_anggota
LEFT JOIN pemesanan p on p.id = db.id_order
LEFT JOIN armada a on a.id = da.id_plat
LEFT JOIN karyawan k1 on da.driver = k1.id
LEFT JOIN karyawan k2 on da.helper_1 = k2.id
LEFT JOIN karyawan k3 on da.helper_2 = k3.id
LEFT JOIN distributor do1 on p.id_distro = do1.id
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
    <!-- <h3># Informasi Saat Ini</h3> -->
    <div class="row">
      <div class="col-6">
        <h3 class="mb-3" id="armadabelumdatang"># Dalam Perjalanan </h3>
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
              $distro1 = $row['distro'] == NULL ? '-' : $row['distro'];
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
                  <h5 class="card-header bg-info"><?= $row['no_perjalanan']; ?></h5>
                  <div class="card-body">
                    <p class="card-text">Tujuan :<br> <?= implode(", ", array_filter(array($row['distro']))); ?></p>
                    <p class="card-text">Tim Pengirim :<br> <?= implode(", ", array_filter(array($row['supir'], $row['helper1'], $row['helper2']))); ?> </p>
                    <p class="card-text">Muatan :<br>Cup = <?= $row['cup']; ?>, A330 = <?= $row['a330']; ?>, A500 = <?= $row['a500']; ?>, A600 = <?= $row['a600']; ?>, Refill = <?= $row['refill']; ?> </p>
                    <p class="card-text">Jam Berangkat :<br> <?= tanggal_indo($row['jam_berangkat']); ?> </p>
                    <p class="card-text">Estimasi Lama Perjalanan : <br> <?= $estimasi_lama_perjalanan; ?></p>
                    <p class="card-text">Estimasi Datang :<br> <?= tanggal_indo($row['estimasi_jam_datang']); ?> </p>
                  </div>
                  <a href="?page=distribusivalidasi&id=<?= $row['id']; ?>" class="btn btn-info d-block">Validasi <i class="fas fa-arrow-circle-right"></i></a>
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
include_once "../partials/scriptdatatables.php";
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
      timer: 2000,
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

  var arrayIndicator = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
  var arrayBackground1 = [];
  var arrayBorder1 = [];

  for (let i = 0; i < arrayIndicator.length; i++) {
    r = Math.floor(Math.random() * 255);
    g = Math.floor(Math.random() * 255);
    b = Math.floor(Math.random() * 255);
    arrayBackground1.push('rgba(' + r + ', ' + g + ', ' + b + ', ' + '0.2)');
    arrayBorder1.push('rgba(' + r + ', ' + g + ', ' + b + ', ' + '1)');
  }

  //chart keberangkatan
  var arrayChartJumlahKeberangkatan = <?= json_encode($arrayChartJumlahKeberangkatan); ?>;
  const ctxBerangkat = document.getElementById('myChart3').getContext('2d');
  const myChartBerangkat = new Chart(ctxBerangkat, {
    type: 'line',
    data: {
      labels: arrayIndicator,
      datasets: [{
        label: '# Jumlah Keberangkatan Tahun ' + new Date().getFullYear(),
        data: arrayChartJumlahKeberangkatan,
        backgroundColor: arrayBackground1,
        borderColor: arrayBorder1,
        borderWidth: 1
      }]
    },
    options: {
      plugins: {
        labels: {
          render: 'value',
          precision: 2
        }
      },
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });

  //chart persentase ketepatan waktu
  var jumlahDataTepatWaktu = <?= json_encode($jumlahDataTepatWaktu); ?>;
  var jumlahDataTidakTepatWaktu = <?= json_encode($jumlahDataTidakTepatWaktu); ?>;
  var label = ['Tepat Waktu', 'Tidak Tepat Waktu'];
  const ctxPersentaseKetepatanWaktu = document.getElementById('myChart4').getContext('2d');
  const myChartPersentaseKetepatanWaktu = new Chart(ctxPersentaseKetepatanWaktu, {
    type: 'doughnut',
    data: {
      labels: label,
      datasets: [{
        label: '# Persentase Ketepatan Waktu Selama Tahun ' + new Date().getFullYear(),
        data: [jumlahDataTepatWaktu, jumlahDataTidakTepatWaktu],
        backgroundColor: [arrayBackground1[0], arrayBackground1[1]],
        borderColor: [arrayBorder1[0], arrayBorder1[1]],
        // borderWidth: 1
      }]
    },
    options: {
      // responsive: true,
      // maintainAspectRatio: true,
      plugins: {
        labels: {
          render: 'percentage',
          precision: 2
        }
      },
    }
  });



  function toArmadaBelumDatang() {
    const element = document.getElementById("armadabelumdatang");
    element.scrollIntoView();
  }
</script>
<!-- /.content -->