<?php
$database = new Database;
$db = $database->getConnection();

$validasi = "SELECT * FROM armada WHERE plat = ?";
$stmt = $db->prepare($validasi);
$stmt->bindParam(1, $_POST['plat']);
$stmt->execute();

if ($stmt->rowCount() > 0) {
?>
  <div class="alert alert-danger alert-dismissable">
    <button class="close" type="button" data-dismiss="alert" aria-hidden="true">X</button>
    <h5><i class="icon fas fa-times"></i>Gagal</h5>
    Data sudah ada di database
  </div>
<?php
} else {

  if (isset($_POST['button_create'])) {
    $insertsql = "insert into armada (plat, jenis_mobil, kateg_mobil, kecepatan_kosong, kecepatan_muatan) values (?,?,?,?,?)";
    $stmt = $db->prepare($insertsql);

    $plat = strtoupper($_POST['plat']);
    $jenis = strtoupper($_POST['jenis_mobil']);

    switch ($jenis) {
      case 'GRAN MAX':
      case 'L300':
        $kateg = 'S';
        break;
      case 'ENGKEL':
        $kateg = 'M';
        break;
      case 'PS':
        $kateg = 'L';
        break;
      case 'FUSO':
        $kateg = 'XL';
        break;
    }

    switch ($kateg) {
      case 'S':
      case 'M':
        $kecepatan_kosong = '55';
        $kecepatan_muatan = '40';
        break;
      case 'L':
        $kecepatan_kosong = '50';
        $kecepatan_muatan = '35';
        break;
      case 'XL':
        $kecepatan_kosong = '45';
        $kecepatan_muatan = '30';
        break;
    }

    $stmt->bindParam(1, $plat);
    $stmt->bindParam(2, $jenis);
    $stmt->bindParam(3, $kateg);
    $stmt->bindParam(4, $kecepatan_kosong);
    $stmt->bindParam(5, $kecepatan_muatan);

    if ($stmt->execute()) {
      $_SESSION['hasil_create'] = true;
      $_SESSION['pesan'] = "Berhasil Menyimpan Data";
    } else {
      $_SESSION['hasil_create'] = false;
      $_SESSION['pesan'] = "Gagal Menyimpan Data";
    }
    echo '<meta http-equiv="refresh" content="0;url=?page=armadaread"/>';
    exit;
  }
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
          <li class="breadcrumb-item active">Tambah Armada</li>
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
      <h3 class="card-title">Data Tambah Armada</h3>
    </div>
    <div class="card-body">
      <form action="" method="post">
        <div class="form-group">
          <label for="plat">Plat</label>
          <input type="text" name="plat" class="form-control" style="text-transform: uppercase;" required>
        </div>
        <div class="form-group">
          <label for="jenis_mobil">Jenis Mobil</label>
          <select name="jenis_mobil" class="form-control" required>
            <option value="">--Pilih Jenis Mobil--</option>
            <?php
            $options = array('GRAN MAX', 'L300', 'ENGKEL', 'PS', 'FUSO');
            foreach ($options as $option) {
              $selected = $_POST['jenis_mobil'] == $option ? 'selected' : '';
              echo "<option value=\"" . $option . "\"" . $selected . ">" . $option . "</option>";
            }
            ?>
          </select>
        </div>
        <a href="?page=armadaread" class="btn btn-danger btn-sm float-right">
          <i class="fa fa-arrow-left"></i> Kembali
        </a>
        <button type="submit" name="button_create" class="btn btn-success btn-sm float-right mr-1">
          <i class="fa fa-save"></i> Simpan
        </button>
      </form>
    </div>
  </div>
</div>
<!-- /.content -->

<?php
include_once "../partials/scriptdatatables.php";
?>