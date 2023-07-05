<?php include_once "../partials/cssdatatables.php" ?>

<?php
$database = new Database;
$db = $database->getConnection();

if (isset($_POST['ajukan'])) {

  //buat no_pengajuan
  $select_no_pengajuan_upah = "SELECT no_pengajuan FROM pengajuan_upah_borongan WHERE MONTH(tgl_pengajuan) = MONTH(NOW()) and YEAR(tgl_pengajuan) = YEAR(NOW()) ORDER BY no_pengajuan DESC LIMIT 1";
  $stmt_no_pengajuan_upah = $db->prepare($select_no_pengajuan_upah);
  $stmt_no_pengajuan_upah->execute();
  if ($stmt_no_pengajuan_upah->rowCount() == 0) {
    $no_pengajuan_upah = str_pad('1', 4, '0', STR_PAD_LEFT);
  } else {
    $row_no_pengajuan_upah = $stmt_no_pengajuan_upah->fetch(PDO::FETCH_ASSOC);
    $no_pengajuan_upah = $row_no_pengajuan_upah['no_pengajuan'];

    $no_pengajuan_upah = str_pad(number_format(substr($no_pengajuan_upah, -4)) + 1, 4, '0', STR_PAD_LEFT);
  }
  $no_pengajuan_upah_new = "PJU/" . date('Y/') . date('m/') . $no_pengajuan_upah;

  if (!empty($_POST['cid'])) {
    $checkbox_id_upah = $_POST['cid'];
    $acc_code = uniqid();
    for ($i = 0; $i < sizeof($checkbox_id_upah); $i++) {
      $insert_ajukan = "INSERT INTO pengajuan_upah_borongan (tgl_pengajuan, no_pengajuan, id_upah, acc_code, terbayar) VALUES (?,?,?,?,'1') ";
      $tgl_pengajuan = date("Y-m-d");
      $stmt_insert = $db->prepare($insert_ajukan);
      $stmt_insert->bindParam(1, $tgl_pengajuan);
      $stmt_insert->bindParam(2, $no_pengajuan_upah_new);
      $stmt_insert->bindParam(3, $checkbox_id_upah[$i]);
      $stmt_insert->bindParam(4, $acc_code);
      $stmt_insert->execute();
    }
    $sukses = true;
    if ($sukses) {
      $_SESSION['hasil_pengajuan_upah'] = true;
    } else {
      $_SESSION['hasil_pengajuan_upah'] = false;
    }
  }
}

if (isset($_SESSION['hasil_pengajuan_upah'])) {
  if ($_SESSION['hasil_pengajuan_upah']) {
?>
    <div id='hasil_pengajuan_upah'></div>
<?php }
  unset($_SESSION['hasil_pengajuan_upah']);
}
?>

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Pengajuan Upah</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="?page=home">Home</a></li>
          <li class="breadcrumb-item active">Pengajuan Upah</li>
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
      <!-- <a href="export/penggajianrekap-pdf.php" class="btn btn-success btn-sm float-right">
        <i class="fa fa-plus-circle"></i> Export PDF
      </a> -->
    </div>
    <form action="" method="post">
      <div class="card-body">
        <table id="mytable" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th><input type="checkbox" name="selectall" id="selectAll"></th>
              <th>No.</th>
              <th>Tanggal & Jam Berangkat</th>
              <th>No Perjalanan</th>
              <th>Nama</th>
              <th>Upah</th>
              <th>Terbayar</th>
            </tr>
          </thead>
          <tbody>

            <?php
            $selectSql = "SELECT *, u.id id_upah FROM gaji u
            LEFT JOIN pengajuan_upah_borongan p ON u.id = p.id_upah
            INNER JOIN distribusi d ON u.id_distribusi = d.id
            WHERE u.id_pengirim = ? AND no_pengajuan IS NULL AND d.jam_datang IS NOT NULL";
            // var_dump($tgl_rekap_awal);
            // var_dump($tgl_rekap_akhir);
            // die();
            $stmt = $db->prepare($selectSql);
            $stmt->bindParam(1, $_SESSION['id']);
            $stmt->execute();

            $no = 1;
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            ?>
              <tr>
                <td><input type="checkbox" name="cid[]" value="<?= $row['id_upah'] ?>"></td>
                <td><?= $no++ ?></td>
                <td><?= tanggal_indo($row['jam_berangkat']) ?></td>
                <td><a href="?page=detaildistribusi&id=<?= $row['id_distribusi'] ?>"><?= $row['no_perjalanan'] ?></a></td>
                <td><?= $_SESSION['nama'] ?></td>
                <td style="text-align: right;"><?= 'Rp. ' . number_format($row['upah'], 0, ',', '.') ?></td>
                <td>
                  <?php
                  if ($row['terbayar'] == NULL) {
                    echo 'Belum';
                  } else {
                    echo 'Sudah';
                  }

                  ?>
                </td>
              </tr>
            <?php } ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="5" style="text-align: center; font-weight: bold;">TOTAL</td>
              <td style="text-align: right; font-weight: bold;"></td>
              <td style="text-align: right; font-weight: bold;"></td>
            </tr>
          </tfoot>
        </table>
        <?php
        if ($stmt->rowCount() > 0) {
        ?>
          <button type="submit" class="btn btn-sm btn-success float-right mt-2" name="ajukan"><i class="fa fa-paper-plane"></i> Ajukan</button>
        <?php } ?>
    </form>
  </div>
</div>
</div>
<!-- /.content -->
<?php
include_once "../partials/scriptdatatables.php";
?>
<script>
  $('#selectAll').click(function(e) {
    $(this).closest('table').find('td input:checkbox').prop('checked', this.checked);
  });

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
        var j = 5;
        while (j < nb_cols && j != 6) {
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
      },
      "columnDefs": [{
        "orderable": false,
        "targets": [0]
      }]
    });
  });

  if ($('div#hasil_pengajuan_upah').length) {
    Swal.fire({
      title: 'Sukses!',
      text: 'Upah berhasil diajukan',
      icon: 'success',
      confirmButtonText: 'OK'
    })
  }
</script>