<?php

function numberToRomanRepresentation($number)
{
  $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
  $returnValue = '';
  while ($number > 0) {
    foreach ($map as $roman => $int) {
      if ($number >= $int) {
        $number -= $int;
        $returnValue .= $roman;
        break;
      }
    }
  }
  return $returnValue;
}

$database = new Database;
$db = $database->getConnection();

$select_distro = "SELECT * FROM distributor WHERE status_keaktifan = 'AKTIF' ORDER BY nama ASC";
$stmt_distro = $db->prepare($select_distro);

if (isset($_POST['button_create'])) {

  $select_nomor_order = "SELECT nomor_order FROM pemesanan WHERE MONTH(tgl_order) = MONTH(NOW()) and YEAR(tgl_order) = YEAR(NOW()) ORDER BY nomor_order DESC LIMIT 1";
  $stmt_nomor_order = $db->prepare($select_nomor_order);
  $stmt_nomor_order->execute();
  if ($stmt_nomor_order->rowCount() == 0) {
    $nomor_order = str_pad('1', 4, '0', STR_PAD_LEFT);
  } else {
    $row_nomor_order = $stmt_nomor_order->fetch(PDO::FETCH_ASSOC);
    $nomor_order = $row_nomor_order['nomor_order'];

    $nomor_order = str_pad(strtok($nomor_order, "/") + 1, 4, '0', STR_PAD_LEFT);
    // var_dump($nomor_order);
    // die();
  }
  $nomor_order_new = "$nomor_order" . "/DO/PKS/" .  numberToRomanRepresentation(date('m')) . "/" . date('Y');

  //validasi 0 input
  $cup = !empty($_POST['cup']) ? $_POST['cup'] : 0;
  $a330 = !empty($_POST['a330']) ? $_POST['a330'] : 0;
  $a500 = !empty($_POST['a500']) ? $_POST['a500'] : 0;
  $a600 = !empty($_POST['a600']) ? $_POST['a600'] : 0;
  $refill = !empty($_POST['refill']) ? $_POST['refill'] : 0;


  $insertsql = "INSERT INTO pemesanan (nomor_order, tgl_order, id_distro, cup, a330, a500, a600, refill) VALUES (?,?,?,?,?,?,?,?)";
  $stmt = $db->prepare($insertsql);

  $stmt->bindParam(1, $nomor_order_new);
  $tanggal = date_create_from_format('d/m/Y', $_POST['tanggal'])->format('Y-m-d');
  $stmt->bindParam(2, $tanggal);
  $stmt->bindParam(3, $_POST['distributor']);
  $stmt->bindParam(4, $cup);
  $stmt->bindParam(5, $a330);
  $stmt->bindParam(6, $a500);
  $stmt->bindParam(7, $a600);
  $stmt->bindParam(8, $refill);

  if ($stmt->execute()) {
    $_SESSION['hasil_create'] = true;
    $_SESSION['pesan'] = "Berhasil Menyimpan Data";
  } else {
    $_SESSION['hasil_create'] = false;
    $_SESSION['pesan'] = "Gagal Menyimpan Data";
  }
  echo '<meta http-equiv="refresh" content="0;url=?page=dataorder"/>';
  exit;
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
          <li class="breadcrumb-item active">Tambah Order</li>
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
      <h3 class="card-title">Data Tambah Order</h3>
    </div>
    <div class="card-body">
      <form action="" method="post">
        <div class="form-group">
          <label for="tanggal">Tanggal Order</label>
          <input type="text" name="tanggal" class="form-control" id="datetimepicker2" required>
        </div>
        <div class="form-group">
          <label for="distributor">Distributor</label>
          <select name="distributor" class="form-control" required>
            <option value="">--Pilih Distributor--</option>
            <?php
            $stmt_distro->execute();
            while ($row_distro = $stmt_distro->fetch(PDO::FETCH_ASSOC)) {

              echo "<option value=\"" . $row_distro['id'] . "\">" . $row_distro['nama'], " - ", $row_distro['id_da'], " (", $row_distro['jarak'], " km)" . "</option>";
            }
            ?>
          </select>
        </div>
        <div class="row">
          <div class="col-md">
            <div class="form-group">
              <label for="cup">Cup</label>
              <input type="number" name="cup" class="form-control">
            </div>
          </div>
          <div class="col-md">
            <div class="form-group">
              <label for="a330">A330</label>
              <input type="number" name="a330" class="form-control">
            </div>
          </div>
          <div class="col-md">
            <div class="form-group">
              <label for="a500">A500</label>
              <input type="number" name="a500" class="form-control">
            </div>
          </div>
          <div class="col-md">
            <div class="form-group">
              <label for="a600">A600</label>
              <input type="number" name="a600" class="form-control">
            </div>
          </div>
          <div class="col-md">
            <div class="form-group">
              <label for="refill">Refill</label>
              <input type="number" name="refill" class="form-control">
            </div>
          </div>
        </div>
        <a href="?page=dataorder" class="btn btn-danger btn-sm float-right">
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