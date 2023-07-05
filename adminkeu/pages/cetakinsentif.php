<?php
include_once "../partials/cssdatatables.php";
?>

<div class="content-header">
  <div class="card col-md-6">
    <div class="card-header">
      <h3 class="card-title font-weight-bold">Tulis Kode Verifikasi</h3>
    </div>
    <form action="report/reportpengajuaninsentifdetail.php" method="get" target="_blank">
      <div class="card-body">
        <div class="form-group row">
          <label for="kode" class="col-sm-2 col-form-label">Kode Verifikasi</label>
          <div class="col-sm-4">
            <input type="text" class="form-control" name="acc_code" placeholder="Kode Verifikasi">
          </div>
          <button type="submit" class="btn btn-warning btn-sm col-sm-2">
            <i class="fa fa-print"></i> Cetak
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

<?php
include_once "../partials/scriptdatatables.php";
?>