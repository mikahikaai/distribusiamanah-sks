<?php include_once "../partials/cssdatatables.php" ?>

<?php
$database = new Database;
$db = $database->getConnection();

?>

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Rekap Pengajuan Upah</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="?page=home">Home</a></li>
          <li class="breadcrumb-item active">Rekap Pengajuan Upah</li>
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
      <h3 class="card-title font-weight-bold">Data Rekap Pengajuan Upah<br>Periode : <?= tanggal_indo($_SESSION['tgl_rekap_awal_pengajuan_upah']->format('Y-m-d')) . " sd " . tanggal_indo($_SESSION['tgl_rekap_akhir_pengajuan_upah']->format('Y-m-d')) ?></h3>
    </div>
    <div class="card-body">
      <table id="mytable" class="table table-bordered table-hover">
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
            <th>Total Upah</th>
            <th>Opsi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $tgl_awal = $_SESSION['tgl_rekap_awal_pengajuan_upah']->format('Y-m-d H:i:s');
          $tgl_akhir = $_SESSION['tgl_rekap_akhir_pengajuan_upah']->format('Y-m-d H:i:s');
          $selectSql = "SELECT p.*, u.*, d.*, k1.nama nama_pengirim, k2.nama nama_verifikator FROM pengajuan_upah_borongan p
          RIGHT JOIN gaji u on p.id_upah = u.id
          LEFT JOIN karyawan k1 on u.id_pengirim = k1.id
          LEFT JOIN karyawan k2 on p.id_verifikator = k2.id
          INNER JOIN distribusi d on u.id_distribusi = d.id
          WHERE u.id_pengirim = IF (? = 'all', u.id_pengirim, ?) AND (p.tgl_pengajuan BETWEEN ? AND ?) AND p.terbayar = IF (? = 'all', p.terbayar, ?)";
          $stmt = $db->prepare($selectSql);
          $stmt->bindParam(1, $_SESSION['id_karyawan_rekap_pengajuan_upah']);
          $stmt->bindParam(2, $_SESSION['id_karyawan_rekap_pengajuan_upah']);
          $stmt->bindParam(3, $tgl_awal);
          $stmt->bindParam(4, $tgl_akhir);
          $stmt->bindParam(5, $_SESSION['status_rekap_pengajuan_upah']);
          $stmt->bindParam(6, $_SESSION['status_rekap_pengajuan_upah']);
          $stmt->execute();
          // $stmt->debugDumpParams();
          // die();
          if ($stmt->rowCount() > 0) {
            $selectSql = "SELECT p.*, u.*, d.*, k1.nama nama_pengirim, k2.nama nama_verifikator , SUM(upah) total_upah FROM pengajuan_upah_borongan p
          RIGHT JOIN gaji u on p.id_upah = u.id
          LEFT JOIN karyawan k1 on u.id_pengirim = k1.id
          LEFT JOIN karyawan k2 on p.id_verifikator = k2.id
          INNER JOIN distribusi d on u.id_distribusi = d.id
          WHERE u.id_pengirim = IF (? = 'all', u.id_pengirim, ?) AND (p.tgl_pengajuan BETWEEN ? AND ?) AND p.terbayar = IF (? = 'all', p.terbayar, ?)
          GROUP BY acc_code ORDER BY tgl_pengajuan DESC, no_pengajuan DESC, terbayar ASC";
            $stmt = $db->prepare($selectSql);
            $stmt->bindParam(1, $_SESSION['id_karyawan_rekap_pengajuan_upah']);
            $stmt->bindParam(2, $_SESSION['id_karyawan_rekap_pengajuan_upah']);
            $stmt->bindParam(3, $tgl_awal);
            $stmt->bindParam(4, $tgl_akhir);
            $stmt->bindParam(5, $_SESSION['status_rekap_pengajuan_upah']);
            $stmt->bindParam(6, $_SESSION['status_rekap_pengajuan_upah']);
            $stmt->execute();
            // $stmt->debugDumpParams();
            // die();
          }
          $no = 1;
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
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
              <td style="text-align: right;"><?= 'Rp. ' . number_format($row['total_upah'], 0, ',', '.') ?></td>
              <td>
                <a href="?page=rekapdetailpengajuanupah&acc_code=<?= $row['acc_code']; ?>" class="btn btn-sm btn-primary mr-1"><i class="fa fa-eye"></i> Lihat</a>
              </td>
            </tr>
          <?php } ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="8" style="text-align: center; font-weight: bold;">TOTAL</td>
            <td style="text-align: right; font-weight: bold;"></td>
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
    $('#mytable').DataTable({
      footerCallback: function(row, data, start, end, display) {
        var api = this.api();

        // Remove the formatting to get integer data for summation
        var intVal = function(i) {
          return typeof i === 'string' ? i.replace(/[^0-9]+/g, "") * 1 : typeof i === 'number' ? i : 0;
        };

        // Total over all pages
        nb_cols = api.columns().nodes().length;
        var j = 8;
        while (j < nb_cols && j < 9) {
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