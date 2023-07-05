<?php
$database = new Database;
$db = $database->getConnection();

if (isset($_GET['id'])) {
  $selectsql = "SELECT * FROM armada where id=?";
  $stmt = $db->prepare($selectsql);
  $stmt->bindParam(1, $_GET['id']);
  $stmt->execute();

  $row = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Armada</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="?page=home">Home</a></li>
          <li class="breadcrumb-item"><a href="?page=armadaread">Armada</a></li>
          <li class="breadcrumb-item active">Detail Armada</li>
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
      <h3 class="card-title">Detail Data Armada</h3>
    </div>
    <div class="card-body">
      <form action="" method="post"" id=" updateForm">
        <div class="form-group">
          <label for="plat">Plat</label>
          <input type="text" class="form-control" value="<?= $row['plat'] ?>" readonly>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="jenis_mobil">Jenis Mobil</label>
              <input type="text" class="form-control" value="<?= $row['jenis_mobil'] ?>" readonly>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="ukuran_mobil">Kategori Ukuran Mobil</label>
              <input type="text" class="form-control" value="<?= $row['kateg_mobil'] ?>" readonly>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="muatan">Kecepatan Muatan</label>
              <input type="text" class="form-control" value="<?= $row['kecepatan_muatan'] ?>" readonly>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="kosong">Kecepatan Kosong</label>
              <input type="text" class="form-control" value="<?= $row['kecepatan_kosong'] ?>" readonly>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label for="status_keaktifan">Status Keaktifan</label>
          <input type="text" class="form-control" value="<?= $row['status_keaktifan'] ?>" readonly>
        </div>
        <button type="button" class="btn btn-danger btn-sm float-right mr-1" onclick="history.back()">
          <i class="fa fa-arrow-left"></i> Kembali
        </button>
      </form>
    </div>
  </div>
</div>
<!-- /.content -->

<?php
include_once "../partials/scriptdatatables.php";
?>