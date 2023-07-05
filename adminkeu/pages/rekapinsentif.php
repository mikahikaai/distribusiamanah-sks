<?php
include_once "../partials/cssdatatables.php";
$database = new Database;
$db = $database->getConnection();
?>

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Rekap Insentif</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="?page=home">Home</a></li>
          <li class="breadcrumb-item active">Rekap Insentif</li>
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
      <h3 class="card-title font-weight-bold">Data Rekap Insentif<br>Periode : <?= tanggal_indo($_SESSION['tgl_rekap_insentif_awal']->format('Y-m-d')) . " sd " . tanggal_indo($_SESSION['tgl_rekap_insentif_akhir']->format('Y-m-d')) ?></h3>
      <a href="report/reportrekapinsentif.php" target="_blank" class="btn btn-warning btn-sm float-right">
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
          $tgl_rekap_awal = $_SESSION['tgl_rekap_insentif_awal']->format('Y-m-d H:i:s');
          $tgl_rekap_akhir = $_SESSION['tgl_rekap_insentif_akhir']->format('Y-m-d H:i:s');

          $selectSql = "SELECT i.*, d.*, p.*, k.*, k.id id_karyawan FROM gaji i
          INNER JOIN distribusi d on i.id_distribusi = d.id
          LEFT JOIN pengajuan_insentif_borongan p on p.id_insentif = i.id
          INNER JOIN karyawan k ON k.id = i.id_pengirim
          WHERE (tanggal BETWEEN ? AND ?) AND terbayar='2' AND i.id_pengirim = IF (? = 'all', i.id_pengirim, ?)";
          $stmt = $db->prepare($selectSql);
          $stmt->bindParam(1, $tgl_rekap_awal);
          $stmt->bindParam(2, $tgl_rekap_akhir);
          $stmt->bindParam(3, $_SESSION['id_karyawan_rekap_insentif']);
          $stmt->bindParam(4, $_SESSION['id_karyawan_rekap_insentif']);
          $stmt->execute();
          if ($stmt->rowCount() > 0) {
            $selectSql = "SELECT i.*, d.*, p.*, k.*, k.id id_karyawan, SUM(i.bongkar) total_bongkar, SUM(i.ontime) total_ontime FROM gaji i
          INNER JOIN distribusi d on i.id_distribusi = d.id
          LEFT JOIN pengajuan_insentif_borongan p on p.id_insentif = i.id
          INNER JOIN karyawan k ON k.id = i.id_pengirim
          WHERE (tanggal BETWEEN ? AND ?) AND terbayar='2' AND i.id_pengirim = IF (? = 'all', i.id_pengirim, ?)
          GROUP BY k.nama ORDER BY k.nama ASC";
            $stmt = $db->prepare($selectSql);
            $stmt->bindParam(1, $tgl_rekap_awal);
            $stmt->bindParam(2, $tgl_rekap_akhir);
            $stmt->bindParam(3, $_SESSION['id_karyawan_rekap_insentif']);
            $stmt->bindParam(4, $_SESSION['id_karyawan_rekap_insentif']);
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
                if ($row['terbayar'] == '0') {
                  echo 'Belum';
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
                <a href="?page=rekapdetailinsentif&id=<?= $row['id_karyawan']; ?>" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i> Lihat</a>
                <a href="report/reportrekapinsentifdetail.php?id=<?= $row['id_karyawan']; ?>" target="_blank" class="btn btn-success btn-sm"><i class="fa fa-download"></i> Unduh</a>
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