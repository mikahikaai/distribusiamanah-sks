<?php
include_once "../partials/cssdatatables.php";
?>

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Detail Rekap Prestasi</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="?page=home">Home</a></li>
          <li class="breadcrumb-item active">Detail Rekap Prestasi</li>
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
      <h3 class="card-title font-weight-bold">Data Detail Rekap Prestasi<br>
        Periode : <?= tanggal_indo($_SESSION['tgl_prestasi_awal']->format('Y-m-d')) . " sd " .  tanggal_indo($_SESSION['tgl_prestasi_akhir']->format('Y-m-d')); ?></h3>
    </div>
    <div class="card-body">
      <table id="mytable" class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>No.</th>
            <th>Tanggal & Jam Berangkat</th>
            <th>No Perjalanan</th>
            <th>Nama</th>
            <th>Jam Berangkat</th>
            <th>Estimasi Jam Datang</th>
            <th>Aktual Jam Datang</th>
            <th>Keterangan</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $tgl_rekap_awal = $_SESSION['tgl_prestasi_awal']->format('Y-m-d H:i:s');
          $tgl_rekap_akhir = $_SESSION['tgl_prestasi_akhir']->format('Y-m-d H:i:s');
          $database = new Database;
          $db = $database->getConnection();

          $selectSql = "SELECT u.*, d.*, p.*, k.*, d.id id_distribusi FROM gaji u
          INNER JOIN distribusi d on u.id_distribusi = d.id
          LEFT JOIN pengajuan_upah_borongan p on p.id_upah = u.id
          INNER JOIN karyawan k ON k.id = u.id_pengirim
          WHERE u.id_pengirim = ? AND (tanggal BETWEEN ? AND ?) AND jam_datang IS NOT NULL
          ORDER BY jam_berangkat DESC";
          // var_dump($tgl_rekap_awal);
          // var_dump($tgl_rekap_akhir);
          // die();
          $stmt = $db->prepare($selectSql);
          $stmt->bindParam(1, $_SESSION['id_karyawan_prestasi']);
          $stmt->bindParam(2, $tgl_rekap_awal);
          $stmt->bindParam(3, $tgl_rekap_akhir);
          $stmt->execute();
          $jumlah_data = $stmt->rowCount();

          $no = 1;
          $jumlah_tepat_waktu = 0;
          $jumlah_terlambat = 0;
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= tanggal_indo($row['jam_berangkat']) ?></td>
              <td><a href="?page=detaildistribusi&id=<?= $row['id_distribusi'] ?>"><?= $row['no_perjalanan'] ?></a></td>
              <td><?= $row['nama'] ?></td>
              <td><?= tanggal_indo($row['jam_berangkat']) ?></td>
              <td><?= tanggal_indo($row['estimasi_jam_datang']) ?></td>
              <td><?= tanggal_indo($row['jam_datang']) ?></td>
              <td>
                <?php
                if (strtotime($row['jam_datang']) <= strtotime($row['estimasi_jam_datang']) + 900) {
                  echo "<div style='color: green;'>Tepat Waktu</div>";
                  $jumlah_tepat_waktu += 1;
                } else {
                  echo "<div style='color: red;'>Terlambat</div>";
                  $jumlah_terlambat += 1;
                }
                ?>
              </td>
            </tr>
          <?php } ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="7" class="font-weight-bold text-center align-middle">Kesimpulan</td>
            <td>Tepat Waktu : <?= $jumlah_tepat_waktu . "x (" . round($jumlah_tepat_waktu / $jumlah_data, 2) * 100 . " %)" ?> <br> Terlambat : <?= $jumlah_terlambat . "x (" . round($jumlah_terlambat / $jumlah_data, 2) * 100 . " %)" ?></td>
          </tr>
        </tfoot>
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