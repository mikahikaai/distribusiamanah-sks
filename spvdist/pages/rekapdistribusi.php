<?php
include_once "../partials/cssdatatables.php";

$database = new Database;
$db = $database->getConnection();

$tgl_rekap_awal = $_SESSION['tgl_rekap_awal_distribusi']->format('Y-m-d H:i:s');
$tgl_rekap_akhir = $_SESSION['tgl_rekap_akhir_distribusi']->format('Y-m-d H:i:s');
?>
<!-- Content Header (Page header) -->

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Rekap Distribusi</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="?page=home">Home</a></li>
          <li class="breadcrumb-item active"> Rekap Distribusi</li>
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
      <h3 class="card-title font-weight-bold">Data Rekap Distribusi<br>Periode : <?= tanggal_indo($_SESSION['tgl_rekap_awal_distribusi']->format('Y-m-d')) . " sd " . tanggal_indo($_SESSION['tgl_rekap_akhir_distribusi']->format('Y-m-d')) ?></h3>
      <a href="report/reportrekapdistribusi.php" target="_blank" class="btn btn-warning btn-sm float-right">
        <i class="fa fa-file-pdf"></i> Export PDF
      </a>
    </div>
    <div class="card-body">
      <table id="mytable" class="table table-bordered table-hover" style="white-space: nowrap; background-color: white; table-layout: fixed;">
        <thead>
          <tr>
            <th>No.</th>
            <th>Tanggal Input</th>
            <th>No. Perjalanan</th>
            <th>Plat</th>
            <th>Nama Driver</th>
            <th>Nama Helper 1</th>
            <th>Nama Helper 2</th>
            <th>Tujuan</th>
            <th>Cup</th>
            <th>A330</th>
            <th>A500</th>
            <th>A600</th>
            <th>Refill</th>
            <th>Jam Berangkat</th>
            <th>Estimasi Jam Datang</th>
            <th>Estimasi Lama Perjalanan</th>
            <th>Jam Datang</th>
            <th class="d-block">Opsi</th>
          </tr>
        </thead>
        <tbody>
          <?php

          // var_dump($_SESSION['status_kedatangan_distribusi']);
          // die();

          $selectsql = "SELECT *, k1.nama supir, k2.nama helper1, k3.nama helper2, da.id id_distribusi_anggota, d.nama distro
          FROM distribusi_anggota da LEFT JOIN distribusi_barang db on da.id = db.id_distribusi_anggota
          INNER JOIN armada a ON a.id = da.id_plat
          LEFT JOIN karyawan k1 ON k1.id = da.driver
          LEFT JOIN karyawan k2 ON k2.id = da.helper_1
          LEFT JOIN karyawan k3 ON k3.id = da.helper_2
          INNER JOIN pemesanan p ON p.id = db.id_order
          INNER JOIN distributor d ON d.id = p.id_distro
              WHERE (IF (? = 'all',da.jam_datang IS NULL OR da.jam_datang IS NOT NULL, IF(? = '1',da.jam_datang IS NOT NULL, da.jam_datang IS NULL))) AND (da.driver = IF (? = 'all', da.driver, ?) OR da.helper_1 = IF (? = 'all', da.helper_1, ?) OR da.helper_2 = IF (? = 'all', da.helper_2, ?)) AND (da.jam_berangkat BETWEEN ? AND ?)
              ORDER BY tanggal DESC; ";
          $stmt = $db->prepare($selectsql);
          $stmt->bindParam(1, $_SESSION['status_kedatangan_distribusi']);
          $stmt->bindParam(2, $_SESSION['status_kedatangan_distribusi']);
          $stmt->bindParam(3, $_SESSION['id_karyawan_rekap_distribusi']);
          $stmt->bindParam(4, $_SESSION['id_karyawan_rekap_distribusi']);
          $stmt->bindParam(5, $_SESSION['id_karyawan_rekap_distribusi']);
          $stmt->bindParam(6, $_SESSION['id_karyawan_rekap_distribusi']);
          $stmt->bindParam(7, $_SESSION['id_karyawan_rekap_distribusi']);
          $stmt->bindParam(8, $_SESSION['id_karyawan_rekap_distribusi']);
          $stmt->bindParam(9, $tgl_rekap_awal);
          $stmt->bindParam(10, $tgl_rekap_akhir);
          // $stmt->debugDumpParams();
          // die();
          $stmt->execute();

          $no = 1;
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $supir = $row['supir'] == NULL ? '-' : $row['supir'];
            $helper1 = $row['helper1'] == NULL ? '-' : $row['helper1'];
            $helper2 = $row['helper2'] == NULL ? '-' : $row['helper2'];
            $distro1 = $row['distro'] == NULL ? '-' : $row['distro'];
            $jam_datang = $row['jam_datang'] == NULL ? 'BELUM DATANG' : tanggal_indo($row['jam_datang']);
            $estimasi_lama_perjalanan = date_diff(date_create($row['jam_berangkat']), date_create($row['estimasi_jam_datang']))->format('%d Hari %h Jam %i Menit %s Detik');
          ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= tanggal_indo($row['tanggal']) ?></td>
              <td><?= $row['no_perjalanan'] ?></td>
              <td><?= $row['plat'], ' - ', $row['jenis_mobil']; ?></td>
              <td><?= $supir ?></td>
              <td><?= $helper1 ?></td>
              <td><?= $helper2 ?></td>
              <td><?= $distro1 ?></td>
              <td><?= $row['cup'] ?></td>
              <td><?= $row['a330'] ?></td>
              <td><?= $row['a500'] ?></td>
              <td><?= $row['a600'] ?></td>
              <td><?= $row['refill']?></td>
              <td><?= tanggal_indo($row['jam_berangkat']) ?></td>
              <td><?= tanggal_indo($row['estimasi_jam_datang']) ?></td>
              <td><?= $estimasi_lama_perjalanan ?></td>
              <td><?= $jam_datang ?></td>
              <td>
                <a href="?page=detaildistribusi&id=<?= $row['id']; ?>" class="btn btn-success btn-sm mr-1">
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
    $('a#deletedistribusi').click(function(e) {
      e.preventDefault();
      var urlToRedirect = e.currentTarget.getAttribute('href');
      //use currentTarget because the click may be on the nested i tag and not a tag causing the href to be empty
      Swal.fire({
        title: 'Apakah anda yakin?',
        text: "Data yang dihapus tidak dapat kembali!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Batal',
        confirmButtonText: 'Hapus'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location = urlToRedirect;
        }
      })
    });
    // $('a#distribusidisable').click(function() {
    //     Swal.fire({
    //         icon: 'error',
    //         title: 'Ups',
    //         text: 'Data yang sudah divalidasi tidak bisa diubah!',
    //     })
    // });
    if ($('div#hasil_delete').length) {
      Swal.fire({
        title: 'Deleted!',
        text: 'Data berhasil dihapus',
        icon: 'success',
        confirmButtonText: 'OK'
      })
    } else if ($('div#hasil_create').length) {
      Swal.fire({
        title: 'Created!',
        text: 'Data berhasil disimpan',
        icon: 'success',
        confirmButtonText: 'OK'
      })
    } else if ($('div#hasil_update').length) {
      Swal.fire({
        title: 'Updated!',
        text: 'Data berhasil diubah',
        icon: 'success',
        confirmButtonText: 'OK'
      })
    }

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