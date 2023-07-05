<?php

$database = new Database;
$db = $database->getConnection();

$select_distro = "SELECT * FROM distributor WHERE status_keaktifan = 'AKTIF' ORDER BY nama ASC";
$stmt_distro = $db->prepare($select_distro);



if (isset($_POST['button_create'])) {

  //validasi 0 input
  $cup = !empty($_POST['cup']) ? $_POST['cup'] : 0;
  $a330 = !empty($_POST['a330']) ? $_POST['a330'] : 0;
  $a500 = !empty($_POST['a500']) ? $_POST['a500'] : 0;
  $a600 = !empty($_POST['a600']) ? $_POST['a600'] : 0;
  $refill = !empty($_POST['refill']) ? $_POST['refill'] : 0;


  $updatesql = "UPDATE pemesanan SET tgl_order=?, id_distro=?, cup=?, a330=?, a500=?, a600=?, refill=? WHERE id=?";
  $stmt = $db->prepare($updatesql);

  $tanggal = date_create_from_format('d/m/Y', $_POST['tanggal'])->format('Y-m-d');
  $stmt->bindParam(1, $tanggal);

  $stmt->bindParam(2, $_POST['distributor']);
  $stmt->bindParam(3, $cup);
  $stmt->bindParam(4, $a330);
  $stmt->bindParam(5, $a500);
  $stmt->bindParam(6, $a600);
  $stmt->bindParam(7, $refill);
  $stmt->bindParam(8, $_GET['id']);

  if ($stmt->execute()) {
    $_SESSION['hasil_update'] = true;
    $_SESSION['pesan'] = "Berhasil Mengubah Data";
  } else {
    $_SESSION['hasil_update'] = false;
    $_SESSION['pesan'] = "Gagal Mengubah Data";
  }
  echo '<meta http-equiv="refresh" content="0;url=?page=dataorder"/>';
  exit;
}

if (isset($_GET['id'])) {
  $selectsql = "SELECT * FROM pemesanan where id=?";
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
        <h1 class="m-0">Order</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="?page=home">Home</a></li>
          <li class="breadcrumb-item"><a href="?page=dataorder">Order</a></li>
          <li class="breadcrumb-item active">Ubah Order</li>
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
      <h3 class="card-title">Data Ubah Order</h3>
    </div>
    <div class="card-body">
      <form action="" method="post">
        <div class="form-group">
          <label for="no_order">Nomor Order</label>
          <input type="text" class="form-control" value="<?= $row['nomor_order']?>" readonly>
        </div>
        <div class="form-group">
          <label for="tanggal">Tanggal Order</label>
          <input type="text" name="tanggal" class="form-control" id="datetimepicker2" value="<?= $row['tgl_order']?>" required>
        </div>
        <div class="form-group">
          <label for="distributor">Distributor</label>
          <select name="distributor" class="form-control" required>
            <option value="">--Pilih Distributor--</option>
            <?php
            $stmt_distro->execute();
            while ($row_distro = $stmt_distro->fetch(PDO::FETCH_ASSOC)) {
              $selected = $row_distro['id'] == $row['id_distro'] ? 'selected' : '';
              echo "<option $selected value=\"" . $row_distro['id'] . "\">" . $row_distro['nama'], " - ", $row_distro['id_da'], " (", $row_distro['jarak'], " km)" . "</option>";
            }
            ?>
          </select>
        </div>
        <div class="row">
          <div class="col-md">
            <div class="form-group">
              <label for="cup">Cup</label>
              <input type="number" name="cup" class="form-control" value="<?= $row['cup'] ?>">
            </div>
          </div>
          <div class="col-md">
            <div class="form-group">
              <label for="a330">A330</label>
              <input type="number" name="a330" class="form-control" value="<?= $row['a330'] ?>">
            </div>
          </div>
          <div class="col-md">
            <div class="form-group">
              <label for="a500">A500</label>
              <input type="number" name="a500" class="form-control" value="<?= $row['a500'] ?>">
            </div>
          </div>
          <div class="col-md">
            <div class="form-group">
              <label for="a600">A600</label>
              <input type="number" name="a600" class="form-control" value="<?= $row['a600'] ?>">
            </div>
          </div>
          <div class="col-md">
            <div class="form-group">
              <label for="refill">Refill</label>
              <input type="number" name="refill" class="form-control" value="<?= $row['refill'] ?>">
            </div>
          </div>
        </div>
        <a href="?page=dataorder" class="btn btn-danger btn-sm float-right">
          <i class="fa fa-arrow-left"></i> Kembali
        </a>
        <button type="submit" name="button_create" class="btn btn-success btn-sm float-right mr-1">
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