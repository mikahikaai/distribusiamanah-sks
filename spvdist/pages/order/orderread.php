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
            <!-- <th style="display: flex;">Opsi</th> -->
          </tr>
        </thead>
        <tbody>
          <?php
          $database = new Database;
          $db = $database->getConnection();

          $selectsql = 'SELECT * FROM pemesanan p
          LEFT JOIN distribusi_barang db on db.id_order = p.id
          LEFT JOIN retur r on r.id_distribusi_barang = db.id
          LEFT JOIN distributor d on d.id = p.id_distro
          HAVING (rcup+ra330+ra500+ra600+rrefill)>0 OR no_resi is NULL';
          $stmt = $db->prepare($selectsql);
          $stmt->execute();

          $no = 1;
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= $row['nomor_order'] ?></td>
              <td><?= tanggal_indo($row['tgl_order']) ?></td>
              <td><?= !empty($row['no_resi']) ? $row['nama'] . " (RETUR)" : $row['nama'] ?></td>
              <td><?= !empty($row['no_resi']) ? $row['rcup'] : $row['cup'] ?></td>
              <td><?= !empty($row['no_resi']) ? $row['ra330'] : $row['a330'] ?></td>
              <td><?= !empty($row['no_resi']) ? $row['ra500'] : $row['a500'] ?></td>
              <td><?= !empty($row['no_resi']) ? $row['ra600'] : $row['a600'] ?></td>
              <td><?= !empty($row['no_resi']) ? $row['rrefill'] : $row['refill'] ?></td>
              <?php if (DateTime::createFromFormat('Y-m-d', $row['tgl_order'])->setTime(0, 0, 0) >= new \DateTime('today')) { ?>
                <td>Order Baru</td>
                
              <?php } else { ?>
                <td>Pelu Kirim</td>
               
              <?php } ?>
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