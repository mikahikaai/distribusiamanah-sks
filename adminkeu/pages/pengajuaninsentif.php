<?php include_once "../partials/cssdatatables.php" ?>

<?php
$database = new Database;
$db = $database->getConnection();

$tgl_pengajuan_insentif_awal = $_SESSION['tgl_pengajuan_insentif_awal']->format('Y-m-d H:i:s');
$tgl_pengajuan_insentif_akhir = $_SESSION['tgl_pengajuan_insentif_akhir']->format('Y-m-d H:i:s');

if (isset($_SESSION['hasil_create'])) {
  if ($_SESSION['hasil_create']) {
?>
    <div id='hasil_create'></div>
<?php }
  unset($_SESSION['hasil_create']);
}
?>

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Pengajuan Insentif</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="?page=home">Home</a></li>
          <li class="breadcrumb-item active">Pengajuan Insentif</li>
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
      <h3 class="card-title font-weight-bold">Data Insentif Belum Pengajuan<br>Periode : <?= $_SESSION['tgl_pengajuan_insentif_awal']->format('d-M-Y') . " sd " . $_SESSION['tgl_pengajuan_insentif_akhir']->format('d-M-Y') ?></h3>
      <a href="report/reportinsentifbelumdiajukan.php" target="_blank" class="btn btn-warning btn-sm float-right">
        <i class="fa fa-file-pdf"></i> Export PDF
      </a>
    </div>
    <div class="card-body">
      <table id="mytable" class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>No.</th>
            <th>Nama</th>
            <th>Status</th>
            <th>Total Bongkar</th>
            <th>Total Ontime</th>
            <th>Opsi</th>
          </tr>
        </thead>
        <tbody>
          <?php

          $selectSql = "SELECT * FROM gaji i
          LEFT JOIN pengajuan_insentif_borongan p ON p.id_insentif = i.id
          WHERE p.terbayar is NULL";
          // var_dump($tgl_rekap_awal);
          // var_dump($tgl_rekap_akhir);
          // die();
          $stmt = $db->prepare($selectSql);
          $stmt->execute();
          if ($stmt->rowCount() > 0) {
            $selectSql = "SELECT p.*, i.*,k.*, k.id id_karyawan, d.*, SUM(i.ontime) total_ontime, sum(i.bongkar) total_bongkar FROM pengajuan_insentif_borongan p
            RIGHT JOIN gaji i on p.id_insentif = i.id
            INNER JOIN karyawan k on i.id_pengirim = k.id
            INNER JOIN distribusi d on i.id_distribusi = d.id
            WHERE p.terbayar IS NULL AND (d.jam_berangkat BETWEEN ? AND ?) AND d.jam_datang IS NOT NULL
            GROUP BY k.nama ORDER BY k.nama ASC";
            $stmt = $db->prepare($selectSql);
            $stmt->bindParam(1, $tgl_pengajuan_insentif_awal);
            $stmt->bindParam(2, $tgl_pengajuan_insentif_akhir);
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
              <td><?= $row['nama'] ?></td>
              <td>
                <?php
                if ($row['terbayar'] == NULL) {
                  echo 'Belum Mengajukan';
                } else if ($row['terbayar'] == '1') {
                  echo 'Mengajukan';
                } else if ($row['terbayar'] == '2') {
                  echo 'Terverifikasi';
                }
                ?>
              </td>
              <td style="text-align: right;"><?= 'Rp. ' . number_format($row['total_bongkar'], 0, ',', '.') ?></td>
              <td style="text-align: right;"><?= 'Rp. ' . number_format($row['total_ontime'], 0, ',', '.') ?></td>
              <td>
                <a href="?page=detailpengajuaninsentif&idk=<?= $row['id_karyawan']; ?>" class="btn btn-sm btn-primary">
                  <i class="fa fa-eye"></i> Lihat</a>
                <a href="report/reportinsentifbelumdiajukandetail.php?idk=<?= $row['id_karyawan']; ?>" target="_blank" class="btn btn-sm btn-success">
                  <i class="fa fa-download"></i> Unduh</a>
              </td>
            </tr>
          <?php } ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="3" style="text-align: center; font-weight: bold;">TOTAL</td>
            <td style="text-align: right; font-weight: bold;"><?= 'Rp. ' . number_format($total_bongkar, 0, ',', '.') ?></td>
            <td style="text-align: right; font-weight: bold;"><?= 'Rp. ' . number_format($total_ontime, 0, ',', '.') ?></td>
            <td></td>
          </tr>
          <tr>
            <td colspan="3" style="text-align: center; font-weight: bold;">GRAND TOTAL</td>
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

  if ($('div#hasil_create').length) {
    Swal.fire({
      title: 'Sukses!',
      text: 'Insentif berhasil diajukan',
      icon: 'success',
      confirmButtonText: 'OK'
    })
  }
</script>