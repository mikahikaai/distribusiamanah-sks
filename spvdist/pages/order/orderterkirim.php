<?php include_once "../partials/cssdatatables.php" ?>

<?php
$tgl_awal = $_SESSION['tgl_rekap_awal']->format('Y-m-d H:i:s');
$tgl_akhir = $_SESSION['tgl_rekap_akhir']->format('Y-m-d H:i:s');

if (isset($_SESSION['hasil'])) {
  if ($_SESSION['hasil']) {
?>
    <div class="alert alert-success alert-dismissable">
      <button class="close" type="button" data-dismiss="alert" aria-hidden="true">X</button>
      <h5><i class="icon fas fa-check"></i>Sukses</h5>
      <?= $_SESSION['pesan'] ?>
    </div>

  <?php
  } else {
  ?>
    <div class="alert alert-danger alert-dismissable">
      <button class="close" type="button" data-dismiss="alert" aria-hidden="true">X</button>
      <h5><i class="icon fas fa-times"></i>Terjadi Kesalahan</h5>
      <?= $_SESSION['pesan'] ?>
    </div>
  <?php }
  unset($_SESSION['hasil']);
  unset($_SESSION['pesan']);
} elseif (isset($_SESSION['hasil_delete'])) {
  if ($_SESSION['hasil_delete']) {
  ?>
    <div id='hasil_delete'></div>
  <?php }
  unset($_SESSION['hasil_delete']);
} elseif (isset($_SESSION['hasil_create'])) {
  if ($_SESSION['hasil_create']) {
  ?>
    <div id='hasil_create'></div>
  <?php }
  unset($_SESSION['hasil_create']);
} elseif (isset($_SESSION['hasil_update'])) {
  if ($_SESSION['hasil_update']) {
  ?>
    <div id='hasil_update'></div>
<?php }
  unset($_SESSION['hasil_update']);
} ?>


<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Order Terkirim</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="./">Home</a></li>
          <li class="breadcrumb-item active">Order Terkirim</li>
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
      <h3 class="card-title">Data Order Terkirim <br> Periode : <?= tanggal_indo($_SESSION['tgl_rekap_awal']->format('Y-m-d')) . " sd " . tanggal_indo($_SESSION['tgl_rekap_akhir']->format('Y-m-d')) ?></h3>
      <!-- <a href="report/reportretur.php" target="_blank" class="btn btn-warning btn-sm float-right">
        <i class="fa fa-file-pdf"></i> Export PDF
      </a> -->
    </div>
    <div class="card-body">
      <table id="mytable" class="table table-bordered table-hover" style="white-space: nowrap; background-color: white; width: 100%;">
        <thead>
          <tr>
            <th>No.</th>
            <th>No. Order</th>
            <th>Tgl. Order</th>
            <th>Nama Distributor</th>
            <th>Tgl. Terkirim</th>
            <th>Cup 240 ml</th>
            <th>Amigol 330 ml</th>
            <th>Amigol 500 ml</th>
            <th>Amigol 600 ml</th>
            <th>Refill Galon 19 ltr</th>
            <!-- <th>Status</th>
            <th style="display: flex;">Opsi</th> -->
          </tr>
        </thead>
        <tbody>
          <?php
          $database = new Database;
          $db = $database->getConnection();

          $selectsql = 'SELECT *,
          (p.cup - r.rcup) terkirim_cup,
          (p.a330 - r.ra330) terkirim_a330,
          (p.a500 - r.ra500) terkirim_a500,
          (p.a600 - r.ra600) terkirim_a600,
          (p.refill - r.rrefill) terkirim_refill
          FROM pemesanan p
          INNER JOIN distribusi_barang db ON p.id = db.id_order
          LEFT JOIN distribusi_anggota da ON da.id = db.id_distribusi_anggota
          INNER JOIN retur r ON r.id_distribusi_barang = db.id
          INNER JOIN distributor d ON d.id = p.id_distro
          WHERE (tanggal BETWEEN :awal AND :akhir)';
          $stmt = $db->prepare($selectsql);
          $stmt->bindParam('awal', $tgl_awal);
          $stmt->bindParam('akhir', $tgl_akhir);
          $stmt->execute();
          $no = 1;
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= $row['nomor_order'] ?></td>
              <td><?= tanggal_indo($row['tgl_order']) ?></td>
              <td><?= $row['nama'] ?></td>
              <td><?= tanggal_indo(date_format(date_create($row['tanggal']), 'Y-m-d')) ?></td>
              <td><?= $row['terkirim_cup'] ?></td>
              <td><?= $row['terkirim_a330'] ?></td>
              <td><?= $row['terkirim_a500'] ?></td>
              <td><?= $row['terkirim_a600'] ?></td>
              <td><?= $row['terkirim_refill'] ?></td>
            </tr>
          <?php } ?>
        </tbody>
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
    $('a#deleteorder').click(function(e) {
      e.preventDefault();
      var urlToRedirect = e.currentTarget.getAttribute('href');
      //use currentTarget because the click may be on the nested i tag and not a tag causing the href to be empty
      Swal.fire({
        title: 'Apakah anda yakin?',
        text: "Data yang dihapus tidak dapat kembali!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Batal',
        confirmButtonText: 'Hapus'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location = urlToRedirect;
        }
      })
    });

    if ($('div#hasil_delete').length) {
      Swal.fire({
        title: 'Deleted!',
        text: 'Data berhasil dihapus',
        icon: 'success',
        confirmButtonText: 'OK'
      })
    } else if ($('div#hasil_create').length) {
      Swal.fire({
        title: 'Created!',
        text: 'Data berhasil disimpan',
        icon: 'success',
        confirmButtonText: 'OK'
      })
    } else if ($('div#hasil_update').length) {
      Swal.fire({
        title: 'Updated!',
        text: 'Data berhasil diubah',
        icon: 'success',
        confirmButtonText: 'OK'
      })
    }
    $('#mytable').DataTable({
      pagingType: "full_numbers",
      scrollX: true,
    });
  });
</script>