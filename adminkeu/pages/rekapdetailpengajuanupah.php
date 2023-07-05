<?php include_once "../partials/cssdatatables.php" ?>
<?php
$database = new Database;
$db = $database->getConnection();

if (isset($_GET['acc_code'])) {
  $selectSql = "SELECT d.*, u.*, p.*, k1.nama nama_pengirim, k2.nama nama_verifikator FROM pengajuan_upah_borongan p
  RIGHT JOIN gaji u ON p.id_upah = u.id
  INNER JOIN distribusi d ON d.id = u.id_distribusi
  LEFT JOIN karyawan k1 ON k1.id = u.id_pengirim
  LEFT JOIN karyawan k2 ON k2.id = p.id_verifikator
  WHERE acc_code=?";
  $stmt = $db->prepare($selectSql);
  $stmt->bindParam(1, $_GET['acc_code']);
  $stmt->execute();

  $stmt1 = $db->prepare($selectSql);
  $stmt1->bindParam(1, $_GET['acc_code']);
  $stmt1->execute();
  $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);
  // $stmt1->debugDumpParams();
  // die();
}
?>

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Detail Rekap Pengajuan Upah</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="?page=home">Home</a></li>
          <li class="breadcrumb-item"><a href="?page=rekappengajuanupah">Rekap Pengajuan Upah</a></li>
          <li class="breadcrumb-item active">Detail Rekap Pengajuan Upah</li>
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
      <h3 class="card-title font-weight-bold">Data Detail Rekap Pengajuan Upah<br>Periode : <?= tanggal_indo($_SESSION['tgl_rekap_awal_pengajuan_upah']->format('Y-m-d')) . " sd " . tanggal_indo($_SESSION['tgl_rekap_akhir_pengajuan_upah']->format('Y-m-d')) ?></h3>
      <?php
      if ($row1['terbayar'] != '1') { ?>
        <a href="report/reportpengajuanupahdetail.php?acc_code=<?= $_GET['acc_code']; ?>" target="_blank" class="btn btn-warning btn-sm float-right">
          <i class="fa fa-file-pdf"></i> Export PDF
        </a>
      <?php } ?>
    </div>
    <div class="card-body">
      <table id="mytable" class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>No.</th>
            <th>Tanggal & Jam Berangkat</th>
            <th>No Perjalanan</th>
            <th>Nama</th>
            <th>Tanggal Verifikasi</th>
            <th>Nama Verifikator</th>
            <th>Kode Verifikasi</th>
            <th>Status</th>
            <th>Upah</th>
          </tr>
        </thead>
        <tbody>
          <?php

          $no = 1;
          $total_upah = 0;
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $total_upah += $row['upah'];
          ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= tanggal_indo($row['jam_berangkat']) ?></td>
              <td><a href="?page=detaildistribusi&id=<?= $row['id_distribusi'] ?>"><?= $row['no_perjalanan'] ?></a></td>
              <td><?= $row['nama_pengirim'] ?></td>
              <td>
                <?php
                if (empty($row['tgl_verifikasi'])) {
                  echo "<div style='color: red;'>BELUM DIVERIFIKASI</div>";
                } else {
                  echo tanggal_indo($row['tgl_verifikasi']);
                }
                ?>
              </td>
              <td>
                <?php
                if (empty($row['nama_verifikator'])) {
                  echo "<div style='color: red;'>BELUM DIVERIFIKASI</div>";
                } else {
                  echo $row['nama_verifikator'];
                }
                ?>
              </td>
              <td>
                <?php
                if (empty($row['qrcode'])) {
                  echo "<div style='color: red;'>BELUM DIVERIFIKASI</div>";
                } else {
                  echo $row['acc_code'];
                }
                ?>
              </td>
              <td>
                <?php
                if ($row['terbayar'] == '0') {
                  echo 'Belum';
                } else if ($row['terbayar'] == '1') {
                  echo 'Mengajukan';
                } else {
                  echo 'Terverifikasi';
                }
                ?>
              </td>
              <td style="text-align: right;"><?= 'Rp. ' . number_format($row['upah'], 1, ',', '.') ?></td>
            </tr>
          <?php } ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="8" style="text-align: center; font-weight: bold;">TOTAL</td>
            <td style="text-align: right; font-weight: bold;"><?= 'Rp. ' . number_format($total_upah, 0, ',', '.') ?></td>
          </tr>
        </tfoot>
      </table>
      <button type="button" class="btn btn-sm mt-2 btn-danger float-right mr-1" onclick="history.back();"><i class="fa fa-arrow-left"></i> Kembali</a>
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