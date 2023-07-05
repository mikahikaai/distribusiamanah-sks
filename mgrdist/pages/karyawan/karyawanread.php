<?php include_once "../partials/cssdatatables.php" ?>

<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Karyawan</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="./">Home</a></li>
          <li class="breadcrumb-item active">Karyawan</li>
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
      <h3 class="card-title">Data Karyawan</h3>
    </div>
    <div class="card-body">
      <table id="mytable" class="table table-bordered table-hover" style="white-space: nowrap; background-color: white; table-layout: fixed;">
        <thead>
          <tr>
            <th>No.</th>
            <th>Nama Lengkap</th>
            <th>Username</th>
            <th>NIK</th>
            <th>Tempat Lahir</th>
            <th>Tanggal Lahir</th>
            <th>Jenis Kelamin</th>
            <th>Alamat</th>
            <th>Agama</th>
            <th>Status Kawin</th>
            <th>Jabatan</th>
            <th>No. Telpon</th>
            <th>Golongan Darah</th>
            <th>SIM</th>
            <th>Email</th>
            <th>Status Karyawan</th>
            <th>Tanggal Registrasi</th>
            <th>Status Keaktifan</th>
            <th>Upah</th>
            <th style="display: flex;">Opsi</th>

          </tr>
        </thead>
        <tbody>
          <?php
          $database = new Database;
          $db = $database->getConnection();

          $selectsql = 'SELECT * FROM karyawan';
          $stmt = $db->prepare($selectsql);
          $stmt->execute();

          $no = 1;
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= $row['nama'] ?></td>
              <td><?= $row['username'] ?></td>
              <td><?= $row['nik'] ?></td>
              <td><?= $row['tempat_lahir'] ?></td>
              <td><?= date('d-m-Y', strtotime($row['tanggal_lahir'])) ?></td>
              <td><?= $row['jenis_kelamin'] ?></td>
              <td><?= strtoupper($row['alamat']) ?></td>
              <td><?= $row['agama'] ?></td>
              <td><?= $row['status'] ?></td>
              <td><?= $row['jabatan'] ?></td>
              <td><?= $row['no_telepon'] ?></td>
              <td><?= $row['gol_darah'] ?></td>
              <td><?= $row['sim'] ?></td>
              <td><?= $row['email'] ?></td>
              <td><?= $row['status_karyawan'] ?></td>
              <td><?= date('d-m-Y', strtotime($row['tanggal_registrasi'])) ?></td>
              <td><?= $row['status_keaktifan'] ?></td>
              <td><?= number_format($row['upah_borongan'], 0, ",", ".") ?></td>
              <td>
                <a href="?page=karyawandetail&id=<?= $row['id']; ?>" class="btn btn-success btn-sm mr-1">
                  <i class="fa fa-eye"></i> Lihat
                </a>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<!-- /.content -->
<?php
include_once "../partials/scriptdatatables.php";
?>
<script>
  $(function() {
    $('#mytable').DataTable({
      pagingType: "full_numbers",
      stateSave: true,
      stateDuration: 60,
      scrollX: true,
      scrollCollapse: true,
      fixedColumns: {
        leftColumns: 2,
        rightColumns: 1
      },
    });
  });
</script>