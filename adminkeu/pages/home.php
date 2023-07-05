<!-- Content Header (Page header) -->
<?php
$database = new Database;
$db = $database->getConnection();

$tahun = date('Y');
$tanggal_awal = date_create($tahun . '-01-01')->setTime(0, 0, 0);
$tanggal_akhir = date_create($tahun . '-12-31')->setTime(23, 59, 59);

$select = "SELECT sum(cup) total_cup, sum(a330) total_a330, sum(a500) total_a500, sum(a600) total_a600, sum(refill) total_refill FROM pemesanan WHERE YEAR(tgl_order) = ?";
$stmt = $db->prepare($select);
$stmt->bindParam(1, $tahun);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

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
    <h3># Akumulasi Pemesanan Seluruh Barang</h3>
    <div class="row mt-3">
      <div class="col-lg col-4">
        <!-- small box -->
        <div class="small-box bg-danger">
          <div class="inner">
            <h3><?= number_format($row['total_cup'], 0, ',', '.') ?></h3>
            <p>Cup 240 ML</p>
          </div>
          <div class="icon">
            <i class="fa-solid fa-box"></i>
          </div>
          <a href="?page=dataorder" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg col-4">
        <!-- small box -->
        <div class="small-box bg-success">
          <div class="inner">
            <h3><?= number_format($row['total_a330'], 0, ',', '.') ?></h3>
            <p>Amigol 330 ML</p>
          </div>
          <div class="icon">
            <i class="fa-solid fa-box"></i>
          </div>
          <a href="?page=dataorder" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg col-4">
        <!-- small box -->
        <div class="small-box bg-primary">
          <div class="inner">
            <h3><?= number_format($row['total_a500'], 0, ',', '.') ?></h3>
            <p>Amigol 500 ML</p>
          </div>
          <div class="icon">
            <i class="fa-solid fa-box"></i>
          </div>
          <a href="?page=dataorder" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg col-6">
        <!-- small box -->
        <div class="small-box bg-warning">
          <div class="inner">
            <h3><?= number_format($row['total_a600'], 0, ',', '.') ?></h3>
            <p>Amigol 600 ML</p>
          </div>
          <div class="icon">
            <i class="fa-solid fa-box"></i>
          </div>
          <a href="?page=dataorder" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>

      <div class="col-lg col-6">
        <!-- small box -->
        <div class="small-box bg-secondary">
          <div class="inner">
            <h3><?= number_format($row['total_refill'], 0, ',', '.') ?></h3>
            <p>Refill 19 Liter</p>
          </div>
          <div class="icon">
            <i class="fa-solid fa-bottle-water"></i>
          </div>
          <a href="?page=dataorder" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
    </div>
    <!-- /.container-fluid -->
    <!-- <div class="row">
      <div class="col-md-6">
        <h3 class="mb-3"># Data Grafik Upah Karyawan Tahun <?= date('Y'); ?> </h3>
        <canvas id="myChart"></canvas>
      </div>
      <div class="col-md-6">
        <h3 class="mb-3"># Data Grafik Insentif Karyawan Tahun <?= date('Y'); ?> </h3>
        <canvas id="myChart2"></canvas>
      </div>
    </div> -->
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

  //chart upah
  var arrayIndicator = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
  var arrayChartUpah = <?= json_encode($arrayChartUpah); ?>;
  var arrayBackground1 = [];
  var arrayBorder1 = [];

  for (let i = 0; i < arrayIndicator.length; i++) {
    r = Math.floor(Math.random() * 255);
    g = Math.floor(Math.random() * 255);
    b = Math.floor(Math.random() * 255);
    arrayBackground1.push('rgba(' + r + ', ' + g + ', ' + b + ', ' + '0.2)');
    arrayBorder1.push('rgba(' + r + ', ' + g + ', ' + b + ', ' + '1)');
  }

  Chart.Legend.prototype.afterFit = function() {
    this.height = this.height + 10;
  };

  const ctxUpah = document.getElementById('myChart').getContext('2d');
  const myChartUpah = new Chart(ctxUpah, {
    type: 'bar',
    data: {
      labels: arrayIndicator,
      datasets: [{
        label: '# Jumlah Upah Tahun ' + new Date().getFullYear(),
        data: arrayChartUpah,
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

  //chart insentif
  var arrayChartInsentif = <?= json_encode($arrayChartInsentif); ?>;
  const ctxInsentif = document.getElementById('myChart2').getContext('2d');
  const myChartInsentif = new Chart(ctxInsentif, {
    type: 'bar',
    data: {
      labels: arrayIndicator,
      datasets: [{
        label: '# Jumlah Insentif Tahun ' + new Date().getFullYear(),
        data: arrayChartInsentif,
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
</script>
<!-- /.content -->