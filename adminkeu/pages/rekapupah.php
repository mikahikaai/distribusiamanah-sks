<?php
include_once "../partials/cssdatatables.php";
$database = new Database;
$db = $database->getConnection();
?>

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Rekap Upah</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="?page=home">Home</a></li>
          <li class="breadcrumb-item active">Rekap Upah</li>
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
      <h3 class="card-title font-weight-bold">Data Rekap Upah<br>Periode : <?= tanggal_indo($_SESSION['tgl_rekap_awal_upah']->format('Y-m-d')) . " sd " . tanggal_indo($_SESSION['tgl_rekap_akhir_upah']->format('Y-m-d')) ?></h3>
      <a href="report/reportrekapupah.php" target="_blank" class="btn btn-warning btn-sm float-right">
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
            <th>Opsi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $tgl_rekap_awal = $_SESSION['tgl_rekap_awal_upah']->format('Y-m-d H:i:s');
          $tgl_rekap_akhir = $_SESSION['tgl_rekap_akhir_upah']->format('Y-m-d H:i:s');

          $selectSql = "SELECT u.*, d.*, p.*, k.*, k.id id_karyawan FROM gaji u
          INNER JOIN distribusi d on u.id_distribusi = d.id
          LEFT JOIN pengajuan_upah_borongan p on p.id_upah = u.id
          INNER JOIN karyawan k ON k.id = u.id_pengirim
          WHERE (d.jam_berangkat BETWEEN ? AND ?) AND terbayar='2' AND u.id_pengirim = IF (? = 'all', u.id_pengirim, ?)";
          $stmt = $db->prepare($selectSql);
          $stmt->bindParam(1, $tgl_rekap_awal);
          $stmt->bindParam(2, $tgl_rekap_akhir);
          $stmt->bindParam(3, $_SESSION['id_karyawan_rekap_upah']);
          $stmt->bindParam(4, $_SESSION['id_karyawan_rekap_upah']);
          $stmt->execute();
          if ($stmt->rowCount() > 0) {
            $selectSql = "SELECT u.*, d.*, p.*, k.*, k.id id_karyawan, SUM(upah) total_upah FROM gaji u
          INNER JOIN distribusi d on u.id_distribusi = d.id
          LEFT JOIN pengajuan_upah_borongan p on p.id_upah = u.id
          INNER JOIN karyawan k ON k.id = u.id_pengirim
          WHERE (d.jam_berangkat BETWEEN ? AND ?) AND p.terbayar='2' AND u.id_pengirim = IF (? = 'all', u.id_pengirim, ?)
          GROUP BY k.nama ORDER BY k.nama";
            $stmt = $db->prepare($selectSql);
            $stmt->bindParam(1, $tgl_rekap_awal);
            $stmt->bindParam(2, $tgl_rekap_akhir);
            $stmt->bindParam(3, $_SESSION['id_karyawan_rekap_upah']);
            $stmt->bindParam(4, $_SESSION['id_karyawan_rekap_upah']);
            $stmt->execute();
          }
          $no = 1;
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
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
              <td style="text-align: right;"><?= 'Rp. ' . number_format($row['total_upah'], 0, ',', '.') ?></td>
              <td>
                <a href="?page=rekapdetailupah&id=<?= $row['id_karyawan']; ?>" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i> Lihat</a>
                <a href="report/reportrekapupahdetail.php?id=<?= $row['id_karyawan']; ?>" target="_blank" class="btn btn-success btn-sm"><i class="fa fa-download"></i> Unduh</a>
              </td>
            </tr>
          <?php } ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="3" style="text-align: center; font-weight: bold;">TOTAL</td>
            <td style="text-align: right; font-weight: bold;"></td>
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
    $('#mytable').DataTable({
      footerCallback: function(row, data, start, end, display) {
        var api = this.api();

        // Remove the formatting to get integer data for summation
        var intVal = function(i) {
          return typeof i === 'string' ? i.replace(/[^0-9]+/g, "") * 1 : typeof i === 'number' ? i : 0;
        };

        // Total over all pages
        nb_cols = api.columns().nodes().length;
        var j = 3;
        while (j < nb_cols && j < 4) {
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