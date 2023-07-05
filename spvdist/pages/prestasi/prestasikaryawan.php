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
    </div>
    <div class="card-body">
      <table id="mytable" class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>No.</th>
            <th>Nama</th>
            <th>Total Berangkat</th>
            <th>Tepat Waktu</th>
            <th>Terlambat</th>
            <th>Keterangan</th>
            <th>Opsi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $tgl_rekap_awal = $_SESSION['tgl_prestasi_awal']->format('Y-m-d H:i:s');
          $tgl_rekap_akhir = $_SESSION['tgl_prestasi_akhir']->format('Y-m-d H:i:s');
          $database = new Database;
          $db = $database->getConnection();

          $selectSql = "SELECT k1.nama, COUNT(k1.nama) total_berangkat, k1.id id_karyawan,
          (SELECT COUNT(*) FROM gaji u LEFT JOIN distribusi d ON u.id_distribusi = d.id 
          INNER JOIN karyawan k ON k.id = u.id_pengirim WHERE d.jam_datang > d.estimasi_jam_datang + INTERVAL 15 MINUTE AND k.id = k1.id AND (d.jam_berangkat BETWEEN ? AND ?)) tidak_tepat_waktu ,
          (SELECT COUNT(*) FROM gaji u LEFT JOIN distribusi d ON u.id_distribusi = d.id 
          INNER JOIN karyawan k ON k.id = u.id_pengirim WHERE d.jam_datang <= d.estimasi_jam_datang + INTERVAL 15 MINUTE AND k.id = k1.id AND (d.jam_berangkat BETWEEN ? AND ?)) tepat_waktu
          FROM gaji u
          LEFT JOIN distribusi d ON u.id_distribusi = d.id 
          INNER JOIN karyawan k1 ON k1.id = u.id_pengirim
          WHERE (d.jam_berangkat BETWEEN ? AND ?) AND d.jam_datang IS NOT NULL AND k1.id = IF(? = 'all', k1.id, ?) 
          GROUP BY k1.nama ORDER BY k1.nama ASC";
          $stmt = $db->prepare($selectSql);
          $stmt->bindParam(1, $tgl_rekap_awal);
          $stmt->bindParam(2, $tgl_rekap_akhir);
          $stmt->bindParam(3, $tgl_rekap_awal);
          $stmt->bindParam(4, $tgl_rekap_akhir);
          $stmt->bindParam(5, $tgl_rekap_awal);
          $stmt->bindParam(6, $tgl_rekap_akhir);
          $stmt->bindParam(7, $_SESSION['id_karyawan_prestasi']);
          $stmt->bindParam(8, $_SESSION['id_karyawan_prestasi']);
          $stmt->execute();

          $no = 1;
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= $row['nama'] ?></td>
              <td><?= $row['total_berangkat'] ?></td>
              <td><?= $row['tepat_waktu'] . "x (" . (round($row['tepat_waktu'] / $row['total_berangkat'], 2)) * 100 . "%)" ?></td>
              <td><?= $row['tidak_tepat_waktu'] . "x (" . (round($row['tidak_tepat_waktu'] / $row['total_berangkat'], 2)) * 100 . "%)" ?></td>
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
              <td>
                <a href="?page=prestasikaryawandetail&id=<?= $row['id_karyawan']; ?>" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i> Lihat</a>
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