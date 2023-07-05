<?php
include_once "../partials/cssdatatables.php";
$database = new Database;
$db = $database->getConnection();
?>

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Rekap Gaji</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="?page=home">Home</a></li>
          <li class="breadcrumb-item active">Rekap Gaji</li>
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
      <h3 class="card-title font-weight-bold">Data Rekap Gaji<br>Periode : <?= tanggal_indo($_SESSION['tgl_rekap_awal_gaji']->format('Y-m-d')) . " sd " . tanggal_indo($_SESSION['tgl_rekap_akhir_gaji']->format('Y-m-d')) ?></h3>
      <a href="report/reportrekapgaji.php" target="_blank" class="btn btn-warning btn-sm float-right">
        <i class="fa fa-file-pdf"></i> Export PDF
      </a>
    </div>
    <div class="card-body">
      <table id="mytable" class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>No.</th>
            <th>Nama</th>
            <th>Total Upah</th>
            <th>Total Insentif</th>
            <th>Opsi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $tgl_rekap_awal = $_SESSION['tgl_rekap_awal_gaji']->format('Y-m-d H:i:s');
          $tgl_rekap_akhir = $_SESSION['tgl_rekap_akhir_gaji']->format('Y-m-d H:i:s');

          $selectSql = "SELECT g.*, d.*, u.*, i.*, k.*, k.id id_karyawan FROM gaji g
          INNER JOIN distribusi d on g.id_distribusi = d.id
          LEFT JOIN pengajuan_upah_borongan u on u.id_upah = g.id
          LEFT JOIN pengajuan_insentif_borongan i on i.id_insentif = g.id
          INNER JOIN karyawan k ON k.id = g.id_pengirim
          WHERE (d.jam_berangkat BETWEEN ? AND ?) AND g.id_pengirim = IF (? = 'all', g.id_pengirim, ?)";
          $stmt = $db->prepare($selectSql);
          $stmt->bindParam(1, $tgl_rekap_awal);
          $stmt->bindParam(2, $tgl_rekap_akhir);
          $stmt->bindParam(3, $_SESSION['id_karyawan_rekap_gaji']);
          $stmt->bindParam(4, $_SESSION['id_karyawan_rekap_gaji']);
          $stmt->execute();
          if ($stmt->rowCount() > 0) {
            $selectSql = "SELECT g.*, d.*, u.*, i.*, k.*, k.id id_karyawan, SUM(upah) total_upah, SUM(g.bongkar+g.ontime) total_insentif FROM gaji g
          INNER JOIN distribusi d on g.id_distribusi = d.id
          LEFT JOIN pengajuan_upah_borongan u on u.id_upah = g.id
          LEFT JOIN pengajuan_insentif_borongan i on i.id_insentif = g.id
          INNER JOIN karyawan k ON k.id = g.id_pengirim
          WHERE (d.jam_berangkat BETWEEN ? AND ?) AND g.id_pengirim = IF (? = 'all', g.id_pengirim, ?)
          GROUP BY k.nama ORDER BY k.nama";
            $stmt = $db->prepare($selectSql);
            $stmt->bindParam(1, $tgl_rekap_awal);
            $stmt->bindParam(2, $tgl_rekap_akhir);
            $stmt->bindParam(3, $_SESSION['id_karyawan_rekap_gaji']);
            $stmt->bindParam(4, $_SESSION['id_karyawan_rekap_gaji']);
            $stmt->execute();
          }
          $no = 1;
          $total_upah = 0;
          $total_insentif = 0;
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $total_upah += $row['total_upah'];
            $total_insentif += $row['total_insentif'];
          ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= $row['nama'] ?></td>
              <td style="text-align: right;"><?= 'Rp. ' . number_format($row['total_upah'], 0, ',', '.') ?></td>
              <td style="text-align: right;"><?= 'Rp. ' . number_format($row['total_insentif'], 0, ',', '.') ?></td>
              <td>
                <a href="?page=rekapdetailgaji&id=<?= $row['id_karyawan']; ?>" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i> Lihat</a>
                <a href="report/reportrekapgajidetail.php?id=<?= $row['id_karyawan']; ?>" target="_blank" class="btn btn-success btn-sm"><i class="fa fa-download"></i> Unduh</a>
              </td>
            </tr>
          <?php } ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="2" style="text-align: center; font-weight: bold;">TOTAL</td>
            <td style="text-align: right; font-weight: bold;"><?= 'Rp. ' . number_format($total_upah, 0, ',', '.') ?></td>
            <td style="text-align: right; font-weight: bold;"><?= 'Rp. ' . number_format($total_insentif, 0, ',', '.') ?></td>
            <td></td>
          </tr>
          <tr>
            <td colspan="2" style="text-align: center; font-weight: bold;">GRAND TOTAL</td>
            <td colspan="2" style="text-align: center; font-weight: bold;"><?= 'Rp. ' . number_format($total_upah + $total_insentif, 0, ',', '.') ?></td>
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
    $('#mytable').DataTable({});
  });
</script>