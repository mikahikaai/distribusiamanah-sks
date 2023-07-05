<?php include_once "../partials/cssdatatables.php" ?>
<?php
$database = new Database;
$db = $database->getConnection();

$tgl_rekap_awal_upah = $_SESSION['tgl_rekap_awal_upah']->format('Y-m-d H:i:s');
$tgl_rekap_akhir_upah = $_SESSION['tgl_rekap_akhir_upah']->format('Y-m-d H:i:s');

if (isset($_SESSION['id_karyawan_rekap_upah'])) {
  $selectSql = "SELECT d.*, u.*, p.*, k1.nama nama_pengirim, k2.nama nama_verifikator FROM pengajuan_upah_borongan p
  RIGHT JOIN gaji u ON p.id_upah = u.id
  INNER JOIN distribusi d ON d.id = u.id_distribusi
  INNER JOIN karyawan k1 ON k1.id = u.id_pengirim
  INNER JOIN karyawan k2 ON k2.id = p.id_verifikator
  WHERE k1.id=? AND (d.jam_datang BETWEEN ? AND ?) AND terbayar='2'";
  $stmt = $db->prepare($selectSql);
  $stmt->bindParam(1, $_SESSION['id_karyawan_rekap_upah']);
  $stmt->bindParam(2, $tgl_rekap_awal_upah);
  $stmt->bindParam(3, $tgl_rekap_akhir_upah);
  $stmt->execute();
}
?>

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Detail Rekap Upah</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="?page=home">Home</a></li>
          <li class="breadcrumb-item"><a href="?page=rekappengajuanupah">Rekap Upah</a></li>
          <li class="breadcrumb-item active">Detail Rekap Upah</li>
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
      <h3 class="card-title font-weight-bold">Data Detail Rekap Upah<br>Periode : <?= tanggal_indo($_SESSION['tgl_rekap_awal_upah']->format('Y-m-d')) . " sd " . tanggal_indo($_SESSION['tgl_rekap_akhir_upah']->format('Y-m-d')) ?></h3>
      <!-- <a href="export/penggajianrekap-pdf.php" class="btn btn-success btn-sm float-right">
        <i class="fa fa-plus-circle"></i> Export PDF
      </a> -->
    </div>
    <div class="card-body">
      <table id="mytable" class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>No.</th>
            <th>Tanggal & Jam Berangkat</th>
            <th>No Perjalanan</th>
            <th>Nama</th>
            <th>No Pengajuan</th>
            <th>Nama Verifikator</th>
            <th>Tanggal Verifikasi</th>
            <th>Upah</th>
          </tr>
        </thead>
        <tbody>
          <?php

          $no = 1;
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= tanggal_indo($row['jam_berangkat']) ?></td>
              <td><a href="?page=detaildistribusi&id=<?= $row['id_distribusi'] ?>"><?= $row['no_perjalanan'] ?></a></td>
              <td><?= $row['nama_pengirim'] ?></td>
              <td><?= $row['no_pengajuan'] ?></td>
              <td><?= $row['nama_verifikator'] ?></td>
              <td><?= tanggal_indo($row['tgl_verifikasi']) ?></td>
              <td style="text-align: right;"><?= 'Rp. ' . number_format($row['upah'], 0, ',', '.') ?></td>
            </tr>
          <?php } ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="7" style="text-align: center; font-weight: bold;">TOTAL</td>
            <td style="text-align: right; font-weight: bold;"></td>
          </tr>
        </tfoot>
      </table>
      <a href="?page=rangerekapupah" class="btn btn-sm mt-2 btn-danger float-right mr-1"><i class="fa fa-arrow-left"></i> Kembali</a>
    </div>
  </div>
</div>
<!-- /.content -->
<?php
include_once "../partials/scriptdatatables.php";
?>
<script>
  $(function() {
    $('#mytable').DataTable({
      footerCallback: function(row, data, start, end, display) {
        var api = this.api();

        // Remove the formatting to get integer data for summation
        var intVal = function(i) {
          return typeof i === 'string' ? i.replace(/[^0-9]+/g, "") * 1 : typeof i === 'number' ? i : 0;
        };

        // Total over all pages
        nb_cols = api.columns().nodes().length;
        var j = 7;
        while (j < nb_cols) {
          total = api
            .column(j)
            .data()
            .reduce(function(a, b) {
              return intVal(a) + intVal(b);
            }, 0);
          $(api.column(j).footer()).html('Rp. ' + total.toLocaleString('id-ID'));
          j++
        }
        // Total over this page
        // pageTotal = api
        //   .column(4, {
        //     page: 'current'
        //   })
        //   .data()
        //   .reduce(function(a, b) {
        //     return intVal(a) + intVal(b);
        //   }, 0);

        // Update footer
        // $(api.column(j).footer()).html('Rp. ' + total.toLocaleString('id-ID'));
      }
    });
  });
</script>