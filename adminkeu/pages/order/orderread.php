<?php include_once "../partials/cssdatatables.php" ?>

<?php
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
        <h1 class="m-0">Order</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="./">Home</a></li>
          <li class="breadcrumb-item active">Order</li>
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
      <h3 class="card-title">Data Order</h3>
      <a href="report/reportorder.php" target="_blank" class="btn btn-warning btn-sm float-right">
        <i class="fa fa-file-pdf"></i> Export PDF
      </a>
      <a href="?page=ordercreate" class="btn btn-success btn-sm mr-2 float-right">
        <i class="fa fa-plus-circle"></i> Tambah Data
      </a>
    </div>
    <div class="card-body">
      <table id="mytable" class="table table-bordered table-hover" style="white-space: nowrap; background-color: white; width: 100%;">
        <thead>
          <tr>
            <th>No.</th>
            <th>No. Order</th>
            <th>Tgl. Order</th>
            <th>Nama Distributor</th>
            <th>Cup 240 ml</th>
            <th>Amigol 330 ml</th>
            <th>Amigol 500 ml</th>
            <th>Amigol 600 ml</th>
            <th>Refill Galon 19 ltr</th>
            <th>Status</th>
            <th style="display: flex;">Opsi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $database = new Database;
          $db = $database->getConnection();

          $selectsql = 'SELECT *, p.id id_order FROM pemesanan p INNER JOIN distributor d ON p.id_distro = d.id LEFT JOIN distribusi_barang db ON db.id_order = p.id';
          $stmt = $db->prepare($selectsql);
          $stmt->execute();

          $no = 1;
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= $row['nomor_order'] ?></td>
              <td><?= tanggal_indo($row['tgl_order']) ?></td>
              <td><?= $row['nama'] ?></td>
              <td><?= $row['cup'] ?></td>
              <td><?= $row['a330'] ?></td>
              <td><?= $row['a500'] ?></td>
              <td><?= $row['a600'] ?></td>
              <td><?= $row['refill'] ?></td>
              <?php if ($row['status'] == NULL) { ?>
                <td>Sedang Diproses</td>
              <?php } else { ?>
                <td><?= $row['status'] ?></td>
              <?php } ?>
              <td>
                <a href="?page=orderupdate&id=<?= $row['id_order']; ?>" class="btn btn-primary btn-sm mr-1">
                  <i class="fa fa-edit"></i> Ubah
                </a>
                <a href="?page=orderdelete&id=<?= $row['id_order']; ?>" class="btn btn-danger btn-sm mr-1" id='deleteorder'>
                  <i class="fa fa-trash"></i> Hapus
                </a>
              </td>
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