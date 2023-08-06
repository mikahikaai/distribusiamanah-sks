<?php include_once "../partials/cssdatatables.php" ?>

<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Distributor</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="?page=home">Home</a></li>
          <li class="breadcrumb-item active">Distributor</li>
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
      <h3 class="card-title">Data Distributor</h3>
      <!-- <a href="report/reportdistributor.php" target="_blank" class="btn btn-warning btn-sm float-right">
        <i class="fa fa-file-pdf"></i> Export PDF
      </a> -->
    </div>
    <div class="card-body">
      <table id="mytable" class="table table-bordered" style="white-space: nowrap;">
        <thead>
          <tr>
            <th>No.</th>
            <th>Nama Lengkap</th>
            <th>ID Distributor</th>
            <th>Paket</th>
            <th>Alamat Dropping</th>
            <th>No. Telepon</th>
            <th>Jarak</th>
            <th>Status Keaktifan</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $database = new Database;
          $db = $database->getConnection();

          $selectsql = 'SELECT * FROM distributor order by nama asc';
          $stmt = $db->prepare($selectsql);
          $stmt->execute();

          $no = 1;
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // $str = $row['alamat_dropping'];
            // if (strlen($str) > 80)
            //     $str = substr($str, 0, 77) . '...';
          ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= $row['nama'] ?></td>
              <td><?= $row['id_da'] ?></td>
              <td><?= $row['paket'] ?></td>
              <td><?= $row['alamat_dropping'] ?></td>
              <td><?= $row['no_telepon'] ?></td>
              <td><?= $row['jarak'] ?></td>
              <td><?= $row['status_keaktifan'] ?></td>
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
      stateSave: true,
      stateDuration: 60,
      scrollX: true,
      scrollCollapse: true,
      select: true,
      fixedColumns: {
        leftColumns: 2,
      },
    });
  });
</script>