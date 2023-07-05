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

if (isset($_POST['button_edit'])) {
  $selectsql2 = "SELECT email FROM karyawan WHERE email =? AND id != ?";
  $stmt2 = $db->prepare($selectsql2);
  $stmt2->bindParam(1, $_POST['email']);
  $stmt2->bindParam(2, $_SESSION['id']);
  $stmt2->execute();
  if ($_POST['password'] != $_POST['password2']) {
?>
    <div class="alert alert-danger alert-dismissable">
      <button class="close" type="button" data-dismiss="alert" aria-hidden="true">X</button>
      <h5><i class="icon fas fa-times"></i>Gagal Ubah Password</h5>
      Password tidak sama
    </div>
  <?php } elseif ($stmt2->rowCount() > 1) { ?>
    <div class="alert alert-danger alert-dismissable">
      <button class="close" type="button" data-dismiss="alert" aria-hidden="true">X</button>
      <h5><i class="icon fas fa-times"></i>Gagal Ubah Email</h5>
      Email sudah ada
    </div>
<?php
  } else {
    $tanggal_lahir_format = date_create_from_format('d/m/Y', $_POST['tanggal_lahir']);
    $tanggal_lahir = $tanggal_lahir_format->format('Y-m-d');
    $password = $_POST['password'] == '' && $_POST['password2'] == '' ? $row['password'] : md5($_POST['password']);
    $updatesql = "UPDATE karyawan SET password=?, nama=?, nik=?, tempat_lahir=?, tanggal_lahir=?, jenis_kelamin=?,
        alamat=?, agama=?, status=?, gol_darah=?, jabatan=?, no_telepon=?, sim=?, status_karyawan=?,
        status_keaktifan=?, upah_borongan=?, email=?  where id=?";
    $nama = strtoupper($_POST['nama']);
    $alamat = strtoupper($_POST['alamat']);
    $tempat_lahir = strtoupper($_POST['tempat_lahir']);
    $stmt = $db->prepare($updatesql);
    $stmt->bindParam(1, $password);
    $stmt->bindParam(2, $nama);
    $stmt->bindParam(3, $_POST['nik']);
    $stmt->bindParam(4, $tempat_lahir);
    $stmt->bindParam(5, $tanggal_lahir);
    $stmt->bindParam(6, $_POST['jenis_kelamin']);
    $stmt->bindParam(7, $alamat);
    $stmt->bindParam(8, $_POST['agama']);
    $stmt->bindParam(9, $_POST['status']);
    $stmt->bindParam(10, $_POST['gol_darah']);
    $stmt->bindParam(11, $_POST['jabatan']);
    $stmt->bindParam(12, $_POST['no_telepon']);
    $stmt->bindParam(13, $_POST['sim']);
    $stmt->bindParam(14, $_POST['status_karyawan']);
    $stmt->bindParam(15, $_POST['status_keaktifan']);
    $stmt->bindParam(16, $_POST['upah_borongan']);
    $stmt->bindParam(17, $_POST['email']);
    $stmt->bindParam(18, $_GET['id']);
    if ($stmt->execute()) {
      $_SESSION['hasil_update'] = true;
      $_SESSION['pesan'] = "Berhasil Mengubah Data";
    } else {
      $_SESSION['hasil_update'] = false;
      $_SESSION['pesan'] = "Gagal Mengubah Data";
    }
    echo '<meta http-equiv="refresh" content="0;url=?page=karyawanread"/>';
    exit;
  }
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
          <li class="breadcrumb-item active">Ubah Karyawan</li>
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
      <h3 class="card-title">Ubah Data Karyawan</h3>
      <a href="?page=karyawanread" class="btn btn-danger btn-sm float-right">
        <i class="fa fa-arrow-left"></i> Kembali
      </a>
    </div>
    <div class="card-body">
      <form action="" method="post" id='karyawancreate'>
        <div class="form-group">
          <label for="nama">Nama Lengkap</label>
          <input type="text" name="nama" class="form-control" id='nama' onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || event.charCode == 8 || event.charCode == 13 || event.charCode == 32 || event.keyCode == 44 || event.keyCode == 46" style="text-transform:uppercase" value="<?= $row['nama'] ?>" required>
        </div>
        <div class="form-group">
          <label for="nik">NIK</label>
          <input type="text" name="nik" class="form-control" id="nik" onkeypress="return (event.charCode > 47 && event.charCode <58) || event.charCode == 46" min="0" maxlength="16" value="<?= $row['nik'] ?>" required>
        </div>
        <div class="form-group">
          <label for="username" style="display: none;">Username</label>
          <div class="input-group">
            <input type="text" name="username" class="form-control" id="username" style="text-transform:lowercase; display: none;" value="<?= $row['username'] ?>" required readonly>
            <div class="input-group-append">
              <a href="javascript:void(0);" class="btn btn-success btn-active" id='generate' style="display: none;">Generate</a>
            </div>
          </div>

        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" name="password" class="form-control" placeholder="Isi jika ingin merubah">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="password2">Ulangi Password</label>
              <input type="password" name="password2" class="form-control" placeholder="Isi jika ingin merubah">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="jenis_kelamin">Jenis Kelamin</label>
              <select name="jenis_kelamin" class="form-control" required>
                <option value="">--Pilih Jenis Kelamin--</option>
                <?php
                $options = array('LAKI-LAKI', 'PEREMPUAN');
                foreach ($options as $option) {
                  $selected = $row['jenis_kelamin'] == $option ? 'selected' : '';
                  echo "<option value=\"" . $option . "\"" . $selected . ">" . $option . "</option>";
                }
                ?>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="email">E-Mail</label>
              <input type="email" name="email" class="form-control" value="<?= $row['email'] ?>" required>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="tempat_lahir">Tempat Lahir</label>
              <input type="text" name="tempat_lahir" class="form-control" onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || event.charCode == 8 || event.charCode == 13 || event.charCode == 32" style="text-transform: uppercase;" value="<?= $row['tempat_lahir'] ?>" required>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="tanggal_lahir">Tanggal Lahir</label>
              <input type="text" id="datetimepicker2" data-td-target="#datetimepicker2" name="tanggal_lahir" class="form-control" value="<?= $row['tanggal_lahir'] ?>" required>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label for="alamat">Alamat</label>
          <input type="textarea" name="alamat" class="form-control" value="<?= strtoupper($row['alamat']) ?>" maxlength="0" required>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="no_telepon">No. Telepon</label>
              <input type="text" name="no_telepon" class="form-control" onkeypress="return (event.charCode > 47 && event.charCode <58) || event.charCode == 46" min="0" maxlength="14" value="<?= $row['no_telepon'] ?>" required>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="agama">Agama</label>
              <select name="agama" class="form-control" required>
                <option value="">--Pilih Agama--</option>
                <?php
                $options = array('ISLAM', 'KRISTEN PROTESTAN', 'KRISTEN KATOLIK', 'HINDU', 'BUDHA', 'KONGHOCU');
                foreach ($options as $option) {
                  $selected = $row['agama'] == $option ? 'selected' : '';
                  echo "<option value=\"" . $option . "\"" . $selected . ">" . $option . "</option>";
                }
                ?>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="status">Status Kawin</label>
              <select name="status" class="form-control" required>
                <option value="">--Pilih Status Kawin--</option>
                <?php
                $options = array('KAWIN', 'BELUM KAWIN');
                foreach ($options as $option) {
                  $selected = $row['status'] == $option ? 'selected' : '';
                  echo "<option value=\"" . $option . "\"" . $selected . ">" . $option . "</option>";
                }
                ?>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="gol_darah">Golongan Darah</label>
              <select name="gol_darah" class="form-control" required>
                <option value="">--Pilih Golongan Darah--</option>
                <?php
                $options = array('-', 'A', 'B', 'AB', 'O');
                foreach ($options as $option) {
                  $selected = $row['gol_darah'] == $option ? 'selected' : '';
                  echo "<option value=\"" . $option . "\"" . $selected . ">" . $option . "</option>";
                }
                ?>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="sim">SIM</label>
              <select name="sim" class="form-control" required>
                <option value="">--Pilih SIM--</option>
                <?php
                $options = array('-', 'A', 'A UMUM', 'B1', 'B1 UMUM', 'B2', 'B2 UMUM', 'C', 'D');
                foreach ($options as $option) {
                  $selected = $row['sim'] == $option ? 'selected' : '';
                  echo "<option value=\"" . $option . "\"" . $selected . ">" . $option . "</option>";
                }
                ?>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="jabatan">Jabatan</label>
              <select name="jabatan" class="form-control" required>
                <option value="">--Pilih Jabatan--</option>
                <?php
                $options = array('DRIVER', 'HELPER', 'ADMINKEU', 'SPVDISTRIBUSI', 'MGRDISTRIBUSI');
                foreach ($options as $option) {
                  $selected = $row['jabatan'] == $option ? 'selected' : '';
                  echo "<option value=\"" . $option . "\"" . $selected . ">" . $option . "</option>";
                }
                ?>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="status_karyawan">Status Karyawan</label>
              <select name="status_karyawan" class="form-control" required>
                <option value="">--Pilih Status Karyawan--</option>
                <?php
                $options = array('BORONGAN', 'BULANAN');
                foreach ($options as $option) {
                  $selected = $row['status_karyawan'] == $option ? 'selected' : '';
                  echo "<option value=\"" . $option . "\"" . $selected . ">" . $option . "</option>";
                }
                ?>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="status_keaktifan">Status Keaktifan</label>
              <select name="status_keaktifan" class="form-control" required>
                <option value="">--Pilih Status Keaktifan--</option>
                <?php
                $options = array('AKTIF', 'NON AKTIF');
                foreach ($options as $option) {
                  $selected = $row['status_keaktifan'] == $option ? 'selected' : '';
                  echo "<option value=\"" . $option . "\"" . $selected . ">" . $option . "</option>";
                }
                ?>
              </select>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label for="upah">Gaji Per Hari</label>
          <input type="text" name="upah_borongan" class="form-control" value="<?= $row['upah_borongan'] ?>" required>
        </div>
        <a href="?page=karyawanread" class="btn btn-danger btn-sm float-right">
          <i class="fa fa-times"></i> Batal
        </a>
        <button type="submit" name="button_edit" class="btn btn-primary btn-sm float-right mr-1">
          <i class="fa fa-save"></i> Ubah
        </button>
      </form>
    </div>
  </div>
</div>
<!-- /.content -->

<?php
include_once "../partials/scriptdatatables.php";
?>