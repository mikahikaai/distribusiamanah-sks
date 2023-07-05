<?php
include_once "../partials/cssdatatables.php";

$database = new Database;
$db = $database->getConnection();

if (isset($_POST['button_show'])) {
  $_SESSION['tgl_rekap_insentif_awal'] = DateTime::createFromFormat('d/m/Y', $_POST['tgl_rekap_insentif_awal'])->setTime(0, 0, 0);
  $_SESSION['tgl_rekap_insentif_akhir'] = DateTime::createFromFormat('d/m/Y', $_POST['tgl_rekap_insentif_akhir'])->setTime(0, 0, 0)->modify('+23 Hours')->modify('59 Minutes')->modify('59 Seconds');
  $_SESSION['id_karyawan_rekap_insentif'] = $_POST['id_karyawan_rekap_insentif'];
  // var_dump($_SESSION['tgl_rekap_awal']);
  // die();

  echo '<meta http-equiv="refresh" content="0;url=?page=rekapdetailinsentif"/>';
  exit;
}
?>

<div class="content-header">
  <div class="card col-md-6">
    <div class="card-header">
      <h3 class="card-title font-weight-bold">Pilih Periode Rekap Insentif</h3>
    </div>
    <div class="card-body">
      <form action="" method="POST">
        <div class="row mb-2 mt-2 align-items-center">
          <div class="col-md-2">
            <label for="id_karyawan_rekap_insentif">Nama Karyawan</label>
          </div>
          <div class="col-md-1 d-flex justify-content-end">
            <label for="id_karyawan_rekap_insentif">:</label>
          </div>
          <div class="col-md-4">
            <select name="id_karyawan_rekap_insentif" id="nama_karyawan" class="form-control">
              <option value=<?= $_SESSION['id'] ?> selected><?= $_SESSION['nama'] ?></option>
            </select>
          </div>
        </div>
        <div class="row align-items-center">
          <div class="col-md-2">
            <label for="tgl_rekap_insentif_awal">Tanggal Awal</label>
          </div>
          <div class="col-md-1 d-flex justify-content-end">
            <label for="tgl_rekap_insentif_awal">:</label>
          </div>
          <div class="col-md-4">
            <input id='datetimepicker2' type='text' class='form-control' data-td-target='#datetimepicker2' placeholder="dd/mm/yyyy" name="tgl_rekap_insentif_awal" required>
          </div>
        </div>
        <div class="row align-items-center mt-2">
          <div class="col-md-2">
            <label for="tgl_rekap_insentif_akhir">Tanggal Akhir</label>
          </div>
          <div class="col-md-1 d-flex justify-content-end">
            <label for="tgl_rekap_insentif_akhir">:</label>
          </div>
          <div class="col-md-4">
            <input id='datetimepicker3' type='text' class='form-control' data-td-target='#datetimepicker3' placeholder="dd/mm/yyyy" name="tgl_rekap_insentif_akhir" required>
          </div>
        </div>
        <button type="submit" name="button_show" class="btn btn-success btn-sm mt-3">
          <i class="fa fa-eye"></i> Tampilkan
        </button>
      </form>
    </div>
  </div>
</div>

<?php
include_once "../partials/scriptdatatables.php";
?>