<?php
$database = new Database;
$db = $database->getConnection();

if (isset($_GET['id'])) {
  $selectsql = "SELECT * FROM karyawan where id=?";
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
        <h1 class="m-0">Karyawan</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="?page=home">Home</a></li>
          <li class="breadcrumb-item"><a href="?page=karyawanread">Karyawan</a></li>
          <li class="breadcrumb-item active">Detail Karyawan</li>
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
      <h3 class="card-title">Detail Data Karyawan</h3>
    </div>
    <div class="card-body">
      <form action="" method="post" id='karyawancreate'>
        <div class="form-group">
          <label for="nama">Nama Lengkap</label>
          <input type="text" class="form-control" style="text-transform:uppercase" value="<?= $row['nama'] ?>" readonly>
        </div>
        <div class="form-group">
          <label for="nik">NIK</label>
          <input type="text" class="form-control" style="text-transform:uppercase" value="<?= $row['nik'] ?>" readonly>
        </div>
        <div class="form-group">
          <label for="username">Username</label>
          <div class="input-group">
            <input type="text" class="form-control" value="<?= $row['username'] ?>" readonly>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="jenis_kelamin">Jenis Kelamin</label>
              <input type="text" class="form-control" style="text-transform:uppercase" value="<?= $row['jenis_kelamin'] ?>" readonly>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="email">E-Mail</label>
              <input type="text" class="form-control" value="<?= $row['email'] ?>" readonly>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="tempat_lahir">Tempat Lahir</label>
              <input type="text" class="form-control" style="text-transform:uppercase" value="<?= $row['tempat_lahir'] ?>" readonly>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="tanggal_lahir">Tanggal Lahir</label>
              <input type="text" class="form-control" value="<?= $row['tanggal_lahir'] ?>" readonly>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label for="alamat">Alamat</label>
          <input type="text" class="form-control" style="text-transform:uppercase" value="<?= $row['alamat'] ?>" readonly>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="no_telepon">No. Telepon</label>
               <input type="text" class="form-control" style="text-transform:uppercase" value="<?= $row['no_telepon'] ?>" readonly>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="agama">Agama</label>
              <input type="text" class="form-control" style="text-transform:uppercase" value="<?= $row['agama'] ?>" readonly>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="status">Status Kawin</label>
              <input type="text" class="form-control" style="text-transform:uppercase" value="<?= $row['status'] ?>" readonly>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="gol_darah">Golongan Darah</label>
              <input type="text" class="form-control" style="text-transform:uppercase" value="<?= $row['gol_darah'] ?>" readonly>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="sim">SIM</label>
              <input type="text" class="form-control" style="text-transform:uppercase" value="<?= $row['sim'] ?>" readonly>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="jabatan">Jabatan</label>
              <input type="text" class="form-control" style="text-transform:uppercase" value="<?= $row['jabatan'] ?>" readonly>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="status_karyawan">Status Karyawan</label>
              <input type="text" class="form-control" style="text-transform:uppercase" value="<?= $row['status_karyawan'] ?>" readonly>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="status_keaktifan">Status Keaktifan</label>
              <input type="text" class="form-control" style="text-transform:uppercase" value="<?= $row['status_keaktifan'] ?>" readonly>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label for="upah">Gaji Per Hari</label>
          <input type="text" class="form-control" value="<?= $row['upah_borongan'] ?>" readonly>
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