<?php
$database = new Database;
$db = $database->getConnection();

$id = $_SESSION['id'];
$selectpw = "SELECT * FROM karyawan where id = ?";
$stmt = $db->prepare($selectpw);
$stmt->bindParam(1, $id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (isset($_POST['button_edit'])) {
  if ($_POST['password'] != $_POST['kf_password']) {
?>
    <div class="alert alert-danger alert-dismissable">
      <button class="close" type="button" data-dismiss="alert" aria-hidden="true">X</button>
      <h5><i class="icon fas fa-times"></i>Gagal</h5>
      Password baru dan konfirmasi password tidak sama
    </div>
  <?php
  } else if (md5($_POST['old_password']) != $row['password']) {
  ?>
    <div class="alert alert-danger alert-dismissable">
      <button class="close" type="button" data-dismiss="alert" aria-hidden="true">X</button>
      <h5><i class="icon fas fa-times"></i>Gagal</h5>
      Password lama tidak sama
    </div>
<?php
  } else {
    $updatepw = "UPDATE karyawan SET password = ? where id = ?";
    $stmt = $db->prepare($updatepw);
    $md5 = md5($_POST['password']);
    $stmt->bindParam(1, $md5);
    $stmt->bindParam(2, $id);
    if ($stmt->execute()) {
      $_SESSION['hasil_update_pw'] = true;
      $_SESSION['pesan'] = "Berhasil Mengubah Data";
    } else {
      $_SESSION['hasil_update_pw'] = false;
      $_SESSION['pesan'] = "Gagal Mengubah Data";
    }
    echo '<meta http-equiv="refresh" content="0;url=?page=home"/>';
  }
}
?>
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Password</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="?page=home">Home</a></li>
          <li class="breadcrumb-item active">Ubah Password</li>
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
      <h3 class="card-title">Ubah Password</h3>
    </div>
    <div class="card-body">
      <form action="" method="post" id='karyawancreate'>
        <div class="form-group">
          <label for="password">Password Baru</label>
          <input type="password" name="password" class="form-control col-6" required>
        </div>
        <div class="form-group">
          <label for="kf_password">Ulangi Password Baru</label>
          <input type="password" name="kf_password" class="form-control col-6" required>
        </div>
        <div class="form-group">
          <label for="old_password">Password Lama</label>
          <input type="password" name="old_password" class="form-control col-6" required>
        </div>

        <button type="submit" name="button_edit" class="btn btn-success btn-sm">
          <i class="fa fa-save"></i> Simpan
        </button>
        <a href="?page=home" class="btn btn-danger btn-sm mr-1">
          <i class="fa fa-times"></i> Batal
        </a>
      </form>
    </div>
  </div>
</div>
<!-- /.content -->

<?php
include_once "../partials/scriptdatatables.php";
?>