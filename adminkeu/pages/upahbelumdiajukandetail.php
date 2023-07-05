<?php include_once "../partials/cssdatatables.php" ?>
<?php
$database = new Database;
$db = $database->getConnection();

if (isset($_GET['id'])) {
  $selectSql = "SELECT p.*, u.*,k.*, d.*, k.id id_karyawan FROM pengajuan_upah_borongan p
  RIGHT JOIN gaji u on p.id_upah = u.id
  INNER JOIN karyawan k on u.id_pengirim = k.id
  INNER JOIN distribusi d on u.id_distribusi = d.id
  WHERE p.terbayar IS NULL AND d.jam_datang IS NOT NULL AND u.id_pengirim = ?
  ORDER BY d.jam_berangkat ASC";
  $stmt = $db->prepare($selectSql);
  $stmt->bindParam(1, $_GET['id']);
  $stmt->execute();
}

?>

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Detail Upah Belum Pengajuan</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="?page=home">Home</a></li>
          <li class="breadcrumb-item"><a href="?page=upahbelumdiajukan">Upah Belum Pengajuan</a></li>
          <li class="breadcrumb-item active">Detail Upah Belum Pengajuan</li>
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
      <h3 class="card-title">Detail Data Upah Belum Pengajuan</h3>
      <a href="report/reportupahbelumdiajukandetail.php?id=<?= $_GET['id'] ?>" target="_blank" class="btn btn-warning btn-sm float-right">
        <i class="fa fa-file-pdf"></i> Export PDF
      </a>
    </div>
    <form action="" method="post">
      <div class="card-body">
        <table id="mytable" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>
              <th>Tanggal & Jam Berangkat</th>
              <th>No Perjalanan</th>
              <th>Nama</th>
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
                <td><?= $row['nama'] ?></td>
                <td style="text-align: right;"><?= 'Rp. ' . number_format($row['upah'], 0, ',', '.') ?></td>
              </tr>
            <?php } ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="4" style="text-align: center; font-weight: bold;">TOTAL</td>
              <td style="text-align: right; font-weight: bold;"><?= 'Rp. ' . number_format($total_upah, 0, ',', '.') ?></td>
            </tr>
          </tfoot>
        </table>
    </form>
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
    $('#selectAll').click(function(e) {
      $(this).closest('table').find('td input:checkbox').prop('checked', this.checked);
    });
    $('#mytable').DataTable({

      "columnDefs": [{
        "orderable": false,
        "targets": [0]
      }]
    });
  });
</script>