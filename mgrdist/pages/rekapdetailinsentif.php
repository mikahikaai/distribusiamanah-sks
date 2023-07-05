<?php include_once "../partials/cssdatatables.php" ?>
<?php
$database = new Database;
$db = $database->getConnection();

$tgl_rekap_awal = $_SESSION['tgl_rekap_insentif_awal']->format('Y-m-d H:i:s');
$tgl_rekap_akhir = $_SESSION['tgl_rekap_insentif_akhir']->format('Y-m-d H:i:s');

if (isset($_GET['id'])) {
  $selectSql = "SELECT d.*, i.*, p.*, k.*, i.bongkar bongkar2 FROM pengajuan_insentif_borongan p
  RIGHT JOIN gaji i ON p.id_insentif = i.id
  INNER JOIN distribusi d ON d.id = i.id_distribusi
  INNER JOIN karyawan k ON k.id = i.id_pengirim
  WHERE k.id=? AND (d.jam_datang BETWEEN ? AND ?) AND terbayar='2'";
  $stmt = $db->prepare($selectSql);
  $stmt->bindParam(1, $_GET['id']);
  $stmt->bindParam(2, $tgl_rekap_awal);
  $stmt->bindParam(3, $tgl_rekap_akhir);
  $stmt->execute();
}
?>

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Detail Rekap Insentif</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="?page=home">Home</a></li>
          <li class="breadcrumb-item"><a href="?page=rekappengajuanupah">Rekap Insentif</a></li>
          <li class="breadcrumb-item active">Detail Rekap Insentif</li>
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
      <h3 class="card-title font-weight-bold">Data Detail Rekap Insentif<br>Periode : <?= tanggal_indo($_SESSION['tgl_rekap_insentif_awal']->format('Y-m-d')) . " sd " . tanggal_indo($_SESSION['tgl_rekap_insentif_akhir']->format('Y-m-d')) ?></h3>
    </div>
    <div class="card-body">
      <table id="mytable" class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>No.</th>
            <th>Tanggal & Jam Berangkat</th>
            <th>No Perjalanan</th>
            <th>Nama</th>
            <th>Ontime</th>
            <th>Bongkar</th>
          </tr>
        </thead>
        <tbody>
          <?php

          $no = 1;
          $total_bongkar = 0;
          $total_ontime = 0;
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $total_bongkar += $row['bongkar2'];
            $total_ontime += $row['ontime'];
          ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= tanggal_indo($row['jam_berangkat']) ?></td>
              <td><a href="?page=detaildistribusi&id=<?= $row['id_distribusi'] ?>"><?= $row['no_perjalanan'] ?></a></td>
              <td><?= $row['nama'] ?></td>
              <td style="text-align: right;"><?= 'Rp. ' . number_format($row['bongkar2'], 0, ',', '.') ?></td>
              <td style="text-align: right;"><?= 'Rp. ' . number_format($row['ontime'], 0, ',', '.') ?></td>
            </tr>
          <?php } ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="4" style="text-align: center; font-weight: bold;">TOTAL</td>
            <td style="text-align: right; font-weight: bold;"><?= 'Rp. ' . number_format($total_bongkar, 0, ',', '.') ?></td>
            <td style="text-align: right; font-weight: bold;"><?= 'Rp. ' . number_format($total_ontime, 0, ',', '.') ?></td>
          </tr>
          <tr>
            <td colspan="4" style="text-align: center; font-weight: bold;">GRAND TOTAL</td>
            <td colspan="2" style="text-align: center; font-weight: bold;"><?= 'Rp. ' . number_format($total_bongkar + $total_ontime, 0, ',', '.') ?></td>
          </tr>
        </tfoot>
      </table>
      <button type="button" class="btn btn-sm mt-2 btn-danger float-right mr-1" onclick="history.back();"><i class="fa fa-arrow-left"></i> Kembali</button>
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