<?php include_once "../partials/cssdatatables.php" ?>

<?php
$database = new Database;
$db = $database->getConnection();

if (isset($_SESSION['hasil_verifikasi_upah'])) {
  if ($_SESSION['hasil_verifikasi_upah']) {
?>
    <div id='hasil_verifikasi_upah'></div>
<?php }
  unset($_SESSION['hasil_verifikasi_upah']);
}
?>

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Verifikasi Pengajuan Upah</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="?page=home">Home</a></li>
          <li class="breadcrumb-item active">Verifikasi Pengajuan Upah</li>
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
      <h3 class="card-title">Data Pengajuan Upah Belum Terverifikasi</h3>
      <!-- <a href="export/penggajianrekap-pdf.php" class="btn btn-success btn-sm float-right">
        <i class="fa fa-plus-circle"></i> Export PDF
      </a> -->
    </div>
    <div class="card-body">
      <table id="mytable" class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>No.</th>
            <th>Tanggal Pengajuan</th>
            <th>No. Pengajuan</th>
            <th>Nama</th>
            <th>Status</th>
            <th>Total Upah</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $selectSql = "SELECT * FROM gaji u
          INNER JOIN pengajuan_upah_borongan p ON p.id_upah = u.id
          WHERE p.terbayar='1'";
          // var_dump($tgl_rekap_awal);
          // var_dump($tgl_rekap_akhir);
          // die();
          $stmt = $db->prepare($selectSql);
          $stmt->execute();
          if ($stmt->rowCount() > 0) {
            $selectSql = "SELECT p.*, u.*,k.*, d.*, SUM(upah) total_upah FROM pengajuan_upah_borongan p
          INNER JOIN gaji u on p.id_upah = u.id
          INNER JOIN karyawan k on u.id_pengirim = k.id
          INNER JOIN distribusi d on u.id_distribusi = d.id
          WHERE p.terbayar='1' GROUP BY no_pengajuan";
            $stmt = $db->prepare($selectSql);
            $stmt->execute();
          }

          $no = 1;
          $total_upah = 0;
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $total_upah += $row['total_upah'];
          ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= tanggal_indo($row['tgl_pengajuan']) ?></td>
              <td><?= $row['no_pengajuan'] ?></td>
              <td><?= $row['nama'] ?></td>
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
              <td style="text-align: right;"><?= 'Rp. ' . number_format($row['total_upah'], 0, ',', '.') ?></td>
              <td>
                <a href="?page=detailpengajuan&acc_code=<?= $row['acc_code']; ?>" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i> Lihat</a>
              </td>
            </tr>
          <?php } ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="5" style="text-align: center; font-weight: bold;">TOTAL</td>
            <td style="text-align: right; font-weight: bold;"><?= 'Rp. ' . number_format($total_upah, 0, ',', '.') ?></td>
            <td></td>
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

  if ($('div#hasil_verifikasi_upah').length) {
    Swal.fire({
      title: 'Sukses!',
      text: 'Upah berhasil diverifikasi',
      icon: 'success',
      confirmButtonText: 'OK'
    })
  }
</script>