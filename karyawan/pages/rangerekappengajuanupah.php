<?php include_once "../partials/cssdatatables.php" ?>

<?php
$database = new Database;
$db = $database->getConnection();

if (isset($_POST['button_show'])) {
  $_SESSION['tgl_rekap_awal_pengajuan_upah'] = DateTime::createFromFormat('d/m/Y', $_POST['tgl_rekap_awal'])->setTime(0, 0, 0);
  $_SESSION['tgl_rekap_akhir_pengajuan_upah'] = DateTime::createFromFormat('d/m/Y', $_POST['tgl_rekap_akhir'])->setTime(0, 0, 0)->modify('+23 Hours')->modify('59 Minutes')->modify('59 Seconds');
  $_SESSION['id_karyawan_rekap_pengajuan_upah'] = $_POST['id_karyawan_rekap_pengajuan_upah'];
  $_SESSION['status_rekap_pengajuan_upah'] = $_POST['status_rekap_pengajuan_upah'];

  echo '<meta http-equiv="refresh" content="0;url=?page=rekappengajuanupah"/>';
  exit;
}
?>

<div class="content-header">
  <div class="card col-md-6">
    <div class="card-header">
      <h3 class="card-title font-weight-bold">Pilih Periode Rekap Pengajuan Upah</h3>
    </div>
    <div class="card-body">
      <form action="" method="POST">
        <div class="row mb-2 mt-2 align-items-center">
          <div class="col-md-2">
            <label for="nama">Nama Karyawan</label>
          </div>
          <div class="col-md-1 d-flex justify-content-end">
            <label for="nama">:</label>
          </div>
          <div class="col-md-4">
            <select name="id_karyawan_rekap_pengajuan_upah" id="nama_karyawan" class="form-control">
              <option value=<?= $_SESSION['id'] ?> selected><?= $_SESSION['nama'] ?></option>
            </select>
          </div>
        </div>
        <div class="row align-items-center">
          <div class="col-md-2">
            <label for="tgl_rekap_awal">Tanggal Awal</label>
          </div>
          <div class="col-md-1 d-flex justify-content-end">
            <label for="tgl_rekap_awal">:</label>
          </div>
          <div class="col-md-4">
            <input id='datetimepicker2' type='text' class='form-control' data-td-target='#datetimepicker2' placeholder="dd/mm/yyyy" name="tgl_rekap_awal" required>
          </div>
        </div>
        <div class="row align-items-center mt-2">
          <div class="col-md-2">
            <label for="tgl_rekap_akhir">Tanggal Akhir</label>
          </div>
          <div class="col-md-1 d-flex justify-content-end">
            <label for="tgl_rekap_akhir">:</label>
          </div>
          <div class="col-md-4">
            <input id='datetimepicker3' type='text' class='form-control' data-td-target='#datetimepicker3' placeholder="dd/mm/yyyy" name="tgl_rekap_akhir" required>
          </div>
        </div>
        <div class="row mb-2 mt-2 align-items-center">
          <div class="col-md-2">
            <label for="status_rekap_pengajuan_upah">Status Pengajuan</label>
          </div>
          <div class="col-md-1 d-flex justify-content-end">
            <label for="status_rekap_pengajuan_upah">:</label>
          </div>
          <div class="col-md-4">
            <select name="status_rekap_pengajuan_upah" id="status_rekap_pengajuan_upah" class="form-control">
              <option value='all' selected>-- Semua Status --</option>
              <option value='1'>Mengajukan</option>
              <option value='2'>Diverifikasi</option>
            </select>
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