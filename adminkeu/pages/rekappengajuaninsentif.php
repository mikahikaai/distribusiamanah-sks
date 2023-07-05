<?php include_once "../partials/cssdatatables.php" ?>

<?php
$database = new Database;
$db = $database->getConnection();

?>

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Rekap Pengajuan Insentif</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="?page=home">Home</a></li>
          <li class="breadcrumb-item active">Rekap Pengajuan Insentif</li>
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
      <h3 class="card-title font-weight-bold">Data Rekap Pengajuan Insentif<br>Periode : <?= tanggal_indo($_SESSION['tgl_rekap_awal_pengajuan_insentif']->format('Y-m-d')) . " sd " . tanggal_indo($_SESSION['tgl_rekap_akhir_pengajuan_insentif']->format('Y-m-d')) ?></h3>
      <a href="report/reportpengajuaninsentif.php" target="_blank" class="btn btn-warning btn-sm float-right">
        <i class="fa fa-file-pdf"></i> Export PDF
      </a>
    </div>
    <div class="card-body">
      <table id="mytable" class="table table-bordered table-hover" style="white-space: nowrap;">
        <thead>
          <tr>
            <th>No.</th>
            <th>Tanggal Pengajuan</th>
            <th>No. Pengajuan</th>
            <th>Nama Karyawan</th>
            <th>Tanggal Verifikasi</th>
            <th>Nama Verifikator</th>
            <th>Kode Verifikasi</th>
            <th>Status</th>
            <th>Total Bongkar</th>
            <th>Total Ontime</th>
            <th>Opsi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $tgl_awal = $_SESSION['tgl_rekap_awal_pengajuan_insentif']->format('Y-m-d H:i:s');
          $tgl_akhir = $_SESSION['tgl_rekap_akhir_pengajuan_insentif']->format('Y-m-d H:i:s');
          $selectSql = "SELECT p.*, i.*, d.*, k1.nama nama_pengirim, k2.nama nama_verifikator FROM pengajuan_insentif_borongan p
          RIGHT JOIN gaji i on p.id_insentif = i.id
          LEFT JOIN karyawan k1 on i.id_pengirim = k1.id
          LEFT JOIN karyawan k2 on p.id_verifikator = k2.id
          INNER JOIN distribusi d on i.id_distribusi = d.id
          WHERE (p.tgl_pengajuan BETWEEN ? AND ?) AND p.terbayar = IF (? = 'all', p.terbayar, ?) AND i.id_pengirim = IF (? = 'all', i.id_pengirim, ?)";
          $stmt = $db->prepare($selectSql);
          $stmt->bindParam(1, $tgl_awal);
          $stmt->bindParam(2, $tgl_akhir);
          $stmt->bindParam(3, $_SESSION['status_rekap_pengajuan_insentif']);
          $stmt->bindParam(4, $_SESSION['status_rekap_pengajuan_insentif']);
          $stmt->bindParam(5, $_SESSION['id_karyawan_rekap_pengajuan_insentif']);
          $stmt->bindParam(6, $_SESSION['id_karyawan_rekap_pengajuan_insentif']);
          $stmt->execute();
          if ($stmt->rowCount() > 0) {
            $selectSql = "SELECT p.*, i.*, d.*, k1.nama nama_pengirim, k2.nama nama_verifikator , SUM(i.bongkar) total_bongkar, SUM(i.ontime) total_ontime FROM pengajuan_insentif_borongan p
          RIGHT JOIN gaji i on p.id_insentif = i.id
          LEFT JOIN karyawan k1 on i.id_pengirim = k1.id
          LEFT JOIN karyawan k2 on p.id_verifikator = k2.id
          INNER JOIN distribusi d on i.id_distribusi = d.id
          WHERE (p.tgl_pengajuan BETWEEN ? AND ?) AND p.terbayar = IF (? = 'all', p.terbayar, ?) AND i.id_pengirim = IF (? = 'all', i.id_pengirim, ?)
          GROUP BY acc_code ORDER BY tgl_pengajuan DESC, no_pengajuan DESC, terbayar ASC";
            $stmt = $db->prepare($selectSql);
            $stmt->bindParam(1, $tgl_awal);
            $stmt->bindParam(2, $tgl_akhir);
            $stmt->bindParam(3, $_SESSION['status_rekap_pengajuan_insentif']);
            $stmt->bindParam(4, $_SESSION['status_rekap_pengajuan_insentif']);
            $stmt->bindParam(5, $_SESSION['id_karyawan_rekap_pengajuan_insentif']);
            $stmt->bindParam(6, $_SESSION['id_karyawan_rekap_pengajuan_insentif']);
            $stmt->execute();
          }
          $no = 1;
          $total_bongkar = 0;
          $total_ontime = 0;
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $total_bongkar += $row['total_bongkar'];
            $total_ontime += $row['total_ontime'];
          ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= tanggal_indo($row['tgl_pengajuan']) ?></td>
              <td><?= $row['no_pengajuan'] ?></td>
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
              <td style="text-align: right;"><?= 'Rp. ' . number_format($row['total_bongkar'], 0, ',', '.') ?></td>
              <td style="text-align: right;"><?= 'Rp. ' . number_format($row['total_ontime'], 0, ',', '.') ?></td>
              <td>
                <a href="?page=rekapdetailpengajuaninsentif&acc_code=<?= $row['acc_code']; ?>" class="btn btn-primary btn-sm mr-1"><i class="fa fa-eye"></i> Lihat</a>
                <?php if ($row['terbayar'] == '2') { ?>
                  <a href="report/reportpengajuaninsentifdetail.php?acc_code=<?= $row['acc_code']; ?>" target="_blank" class="btn btn-success btn-sm">
                    <i class="fa fa-download"></i> Unduh
                  </a>
                <?php } else { ?>
                  <button class="btn btn-sm btn-secondary" disabled><i class="fa fa-download"></i> Unduh</button>
                <?php } ?>
              </td>
            </tr>
          <?php } ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="8" style="text-align: center; font-weight: bold;">TOTAL</td>
            <td style="text-align: right; font-weight: bold;"><?= 'Rp. ' . number_format($total_bongkar, 0, ',', '.') ?></td>
            <td style="text-align: right; font-weight: bold;"><?= 'Rp. ' . number_format($total_ontime, 0, ',', '.') ?></td>
            <td></td>
          </tr>
          <tr>
            <td colspan="8" style="text-align: center; font-weight: bold;">GRAND TOTAL</td>
            <td colspan="2" style="text-align: center; font-weight: bold;"><?= 'Rp. ' . number_format($total_bongkar + $total_ontime, 0, ',', '.') ?></td>
            <td></td>
          </tr>
        </tfoot>
      </table>
      <!-- <form action="" method="post">
        <button type="submit" class="btn btn-md btn-success float-right mt-2" name="ajukan">Ajukan</button>
      </form> -->
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