<?php include_once "../partials/cssdatatables.php" ?>

<?php
$database = new Database;
$db = $database->getConnection();

?>

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Upah Belum Pengajuan</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="?page=home">Home</a></li>
          <li class="breadcrumb-item active">Upah Belum Pengajuan</li>
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
      <h3 class="card-title">Data Upah Belum Pengajuan</h3>
      <a href="report/reportupahbelumdiajukan.php" target="_blank" class="btn btn-warning btn-sm float-right">
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
            <th>Total Upah</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $selectSql = "SELECT * FROM gaji u
          LEFT JOIN pengajuan_upah_borongan p ON p.id_upah = u.id
          INNER JOIN distribusi d on u.id_distribusi = d.id
          WHERE p.terbayar IS NULL AND d.jam_datang IS NOT NULL";
          $stmt = $db->prepare($selectSql);
          $stmt->execute();
          if ($stmt->rowCount() > 0) {
            $selectSql = "SELECT p.*, u.*,k.*, d.*, k.id id_karyawan, SUM(upah) total_upah FROM pengajuan_upah_borongan p
          RIGHT JOIN gaji u on p.id_upah = u.id
          INNER JOIN karyawan k on u.id_pengirim = k.id
          INNER JOIN distribusi d on u.id_distribusi = d.id
          WHERE p.terbayar IS NULL AND d.jam_datang IS NOT NULL
          GROUP BY k.nama ORDER BY k.nama ASC";
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
              <td><?= $row['nama'] ?></td>
              <td>Belum Mengajukan</td>
              <td style="text-align: right;"><?= 'Rp. ' . number_format($row['total_upah'], 0, ',', '.') ?></td>
              <td>
                <a href="?page=upahbelumdiajukandetail&id=<?= $row['id_karyawan']; ?>" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i> Lihat</a>
                <a href="report/reportupahbelumdiajukandetail.php?id=<?= $row['id_karyawan']; ?>" target="_blank" class="btn btn-sm btn-success"><i class="fa fa-download"></i> Unduh</a>
              </td>
            </tr>
          <?php } ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="3" style="text-align: center; font-weight: bold;">TOTAL</td>
            <td style="text-align: right; font-weight: bold;"><?= 'Rp. ' . number_format($total_upah, 0, ',', '.') ?></td>
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