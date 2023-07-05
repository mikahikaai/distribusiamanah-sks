<?php include_once "../partials/cssdatatables.php" ?>

<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Armada</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="./">Home</a></li>
          <li class="breadcrumb-item active">Armada</li>
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
      <h3 class="card-title">Data Armada</h3>
    </div>
    <div class="card-body">
      <table id="mytable" class="table table-bordered" style="white-space: nowrap; background-color: white; width: 100%">
        <thead>
          <tr>
            <th>No.</th>
            <th>Plat</th>
            <th>Jenis Mobil</th>
            <th>Kategori Ukuran</th>
            <th>Kecepatan Kosong</th>
            <th>Kecepatan Muatan</th>
            <th>Status Keaktifan</th>
            <th style="display: flex;">Opsi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $database = new Database;
          $db = $database->getConnection();

          $selectsql = 'SELECT * FROM armada';
          $stmt = $db->prepare($selectsql);
          $stmt->execute();

          $no = 1;
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= $row['plat'] ?></td>
              <td><?= $row['jenis_mobil'] ?></td>
              <td><?= $row['kateg_mobil'] ?></td>
              <td><?= $row['kecepatan_kosong'] ?></td>
              <td><?= $row['kecepatan_muatan'] ?></td>
              <td><?= $row['status_keaktifan'] ?></td>
              <td>
                <a href="?page=armadadetail&id=<?= $row['id']; ?>" class="btn btn-success btn-sm mr-1">
                  <i class="fa fa-eye"></i> Lihat
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
    $('#mytable').DataTable({
      pagingType: "full_numbers",
      scrollX: true,
    });
  });
</script>