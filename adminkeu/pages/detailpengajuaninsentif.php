<?php include_once "../partials/cssdatatables.php" ?>
<?php
$database = new Database;
$db = $database->getConnection();

if (isset($_GET['idk'])) {
  $selectSql = "SELECT d.*, i.*, p.*, k.*, i.id id_insentif FROM pengajuan_insentif_borongan p
  RIGHT JOIN gaji i ON p.id_insentif = i.id
  INNER JOIN distribusi d ON d.id = i.id_distribusi
  INNER JOIN karyawan k ON k.id = i.id_pengirim
  WHERE id_pengirim=? AND no_pengajuan IS NULL AND d.jam_datang IS NOT NULL";
  $stmt = $db->prepare($selectSql);
  $stmt->bindParam(1, $_GET['idk']);
  $stmt->execute();
}

$select_no_pengajuan_insentif = "SELECT no_pengajuan FROM pengajuan_insentif_borongan WHERE MONTH(tgl_pengajuan) = MONTH(NOW()) and YEAR(tgl_pengajuan) = YEAR(NOW()) ORDER BY no_pengajuan DESC LIMIT 1";
$stmt_no_pengajuan_insentif = $db->prepare($select_no_pengajuan_insentif);
$stmt_no_pengajuan_insentif->execute();
if ($stmt_no_pengajuan_insentif->rowCount() == 0) {
  $no_pengajuan_insentif = str_pad('1', 4, '0', STR_PAD_LEFT);
} else {
  $row_no_pengajuan_insentif = $stmt_no_pengajuan_insentif->fetch(PDO::FETCH_ASSOC);
  $no_pengajuan_insentif = $row_no_pengajuan_insentif['no_pengajuan'];

  $no_pengajuan_insentif = str_pad(number_format(substr($no_pengajuan_insentif, -4)) + 1, 4, '0', STR_PAD_LEFT);
}
$no_pengajuan_insentif_new = "PJI/" . date('Y/') . date('m/') . $no_pengajuan_insentif;


if (isset($_POST['ajukan'])) {
  if (!empty($_POST['cid'])) {
    $checkbox_id_pengajuan_insentif = $_POST['cid'];
    $acc_code = uniqid();
    for ($i = 0; $i < sizeof($checkbox_id_pengajuan_insentif); $i++) {
      $insertSql = "INSERT INTO pengajuan_insentif_borongan (tgl_pengajuan, no_pengajuan, id_insentif, acc_code, terbayar) VALUES (?,?,?,?,'1')";
      $tgl_pengajuan = date('Y-m-d');
      $stmt_insert = $db->prepare($insertSql);
      $stmt_insert->bindParam(1, $tgl_pengajuan);
      $stmt_insert->bindParam(2, $no_pengajuan_insentif_new);
      $stmt_insert->bindParam(3, $checkbox_id_pengajuan_insentif[$i]);
      $stmt_insert->bindParam(4, $acc_code);
      if ($stmt_insert->execute()) {
        $_SESSION['hasil_create'] = true;
      } else {
        $_SESSION['hasil_create'] = false;
      }
    }
    echo '<meta http-equiv="refresh" content="0;url=?page=pengajuaninsentif"/>';
    exit;
  }
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
          <li class="breadcrumb-item"><a href="?page=pengajuaninsentif">Pengajuan Insentif</a></li>
          <li class="breadcrumb-item active">Detail Pengajuan Insentif</li>
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
      <h3 class="card-title font-weight-bold">Data Detail Pengajuan Insentif<br>Periode : <?= tanggal_indo($_SESSION['tgl_pengajuan_insentif_awal']->format('Y-m-d')) . " sd " . tanggal_indo($_SESSION['tgl_pengajuan_insentif_akhir']->format('Y-m-d')) ?></h3>
      <a href="report/reportinsentifbelumdiajukandetail.php?idk=<?= $_GET['idk'] ?>" target="_blank" class="btn btn-warning btn-sm float-right">
        <i class="fa fa-plus-circle"></i> Export PDF
      </a>
    </div>
    <form action="" method="post">
      <div class="card-body">
        <table id="mytable" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th><input type="checkbox" name="selectAll" id="selectAll"></th>
              <th>No.</th>
              <th>Tanggal & Jam Berangkat</th>
              <th>No Perjalanan</th>
              <th>Nama</th>
              <th>Bongkar</th>
              <th>Ontime</th>
            </tr>
          </thead>
          <tbody>
            <?php

            $no = 1;
            $total_bongkar = 0;
            $total_ontime = 0;
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
              $total_bongkar += $row['bongkar'];
              $total_ontime += $row['ontime'];
            ?>
              <tr>
                <td><input type="checkbox" name="cid[]" value="<?= $row['id_insentif']; ?>"></td>
                <td><?= $no++ ?></td>
                <td><?= tanggal_indo($row['jam_berangkat']) ?></td>
                <td><a href="?page=detaildistribusi&id=<?= $row['id_distribusi'] ?>"><?= $row['no_perjalanan'] ?></a></td>
                <td><?= $row['nama'] ?></td>
                <td style="text-align: right;"><?= 'Rp. ' . number_format($row['bongkar'], 0, ',', '.') ?></td>
                <td style="text-align: right;"><?= 'Rp. ' . number_format($row['ontime'], 0, ',', '.') ?></td>
              </tr>
            <?php } ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="5" style="text-align: center; font-weight: bold;">TOTAL</td>
              <td style="text-align: right; font-weight: bold;"><?= 'Rp. ' . number_format($total_bongkar, 0, ',', '.') ?></td>
              <td style="text-align: right; font-weight: bold;"><?= 'Rp. ' . number_format($total_ontime, 0, ',', '.') ?></td>
            </tr>
            <tr>
              <td colspan="5" style="text-align: center; font-weight: bold;">GRAND TOTAL</td>
              <td colspan="2" style="text-align: center; font-weight: bold;"><?= 'Rp. ' . number_format($total_bongkar + $total_ontime, 0, ',', '.') ?></td>
            </tr>
          </tfoot>
        </table>
        <a href="?page=pengajuaninsentif" class="btn btn-sm mt-2 btn-danger float-right"><i class="fa fa-arrow-left"></i> Kembali</a>
        <button type="submit" name="ajukan" class="btn btn-sm float-right btn-success mt-2 mr-1"><i class="fas fa-paper-plane"></i> Ajukan</button>
    </form>
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
      }, ]
    })
  });
</script>