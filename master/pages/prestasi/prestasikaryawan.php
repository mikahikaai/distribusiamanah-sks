<?php
include_once "../partials/cssdatatables.php";
?>

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Rekap Prestasi Keberangkatan</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="?page=home">Home</a></li>
          <li class="breadcrumb-item active">Rekap Prestasi Keberangkatan</li>
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
      <h3 class="card-title font-weight-bold">Data Rekap Prestasi Keberangkatan<br>
        Periode : <?= tanggal_indo($_SESSION['tgl_prestasi_awal']->format('Y-m-d')) . " sd " .  tanggal_indo($_SESSION['tgl_prestasi_akhir']->format('Y-m-d')); ?>
      </h3>
      <!-- <a href="report/reportprestasikaryawan.php" target="_blank" class="btn btn-warning btn-sm float-right">
        <i class="fa fa-file-pdf"></i> Export PDF
      </a> -->
    </div>
    <div class="card-body">
      <table id="mytable" class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>No.</th>
            <th>Nama</th>
            <th>Tepat Waktu</th>
            <th>Terlambat</th>
            <th>Total Berangkat</th>
            <th>Keterangan</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $tgl_rekap_awal = $_SESSION['tgl_prestasi_awal']->format('Y-m-d H:i:s');
          $tgl_rekap_akhir = $_SESSION['tgl_prestasi_akhir']->format('Y-m-d H:i:s');
          $database = new Database;
          $db = $database->getConnection();

          $selectSql = "SELECT k.nama,
          (SELECT count(*) FROM distribusi_anggota da WHERE (driver = k.id or helper_1 = k.id or helper_2 = k.id) AND jam_datang <= estimasi_jam_datang + INTERVAL 15 MINUTE AND (jam_berangkat BETWEEN :awal AND :akhir)) tepat_waktu,
          (SELECT count(*) FROM distribusi_anggota da WHERE (driver = k.id or helper_1 = k.id or helper_2 = k.id) AND jam_datang > estimasi_jam_datang + INTERVAL 15 MINUTE AND (jam_berangkat BETWEEN :awal AND :akhir)) tidak_tepat_waktu,
          (SELECT count(*) FROM distribusi_anggota da WHERE (driver = k.id or helper_1 = k.id or helper_2 = k.id) AND jam_datang is not null AND (jam_berangkat BETWEEN :awal and :akhir)) total_berangkat
          FROM karyawan k
          WHERE k.id = IF(:id = 'all', k.id, :id)
          HAVING total_berangkat > 0 
          ";
          $stmt = $db->prepare($selectSql);
          $stmt->bindParam('awal', $tgl_rekap_awal);
          $stmt->bindParam('akhir', $tgl_rekap_akhir);
          $stmt->bindParam('id', $_SESSION['id_karyawan_prestasi']);
          $stmt->execute();

          $no = 1;
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= $row['nama'] ?></td>
              <td><?= $row['tepat_waktu'] . "x (" . (round($row['tepat_waktu'] / $row['total_berangkat'], 2)) * 100 . "%)" ?></td>
              <td><?= $row['tidak_tepat_waktu'] . "x (" . (round($row['tidak_tepat_waktu'] / $row['total_berangkat'], 2)) * 100 . "%)" ?></td>
              <td><?= $row['total_berangkat'] ?></td>
              <td>
                <?php
                if ($row['tepat_waktu'] / $row['total_berangkat'] >= 0.8) {
                  echo "<div style='color: green;'>Sangat Baik</div>";
                } else if ($row['tepat_waktu'] / $row['total_berangkat'] >= 0.6 and $row['tepat_waktu'] / $row['total_berangkat'] < 0.8) {
                  echo "<div style='color: blue;'>Baik</div>";
                } else if ($row['tepat_waktu'] / $row['total_berangkat'] >= 0.3 and $row['tepat_waktu'] / $row['total_berangkat'] < 0.6) {
                  echo "<div style='color: orange;'>Buruk</div>";
                } else if ($row['tepat_waktu'] / $row['total_berangkat'] < 0.3) {
                  echo "<div style='color: red;'>Sangat Buruk</div>";
                }
                ?>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>

    </div>
  </div>
</div>
<!-- /.content -->
<?php
include_once "../partials/scriptdatatables.php";
?>
<script>
  $(function() {
    $('#mytable').DataTable();
  });
</script>