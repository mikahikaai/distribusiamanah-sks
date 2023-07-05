<?php include_once "../partials/cssdatatables.php" ?>
<!-- Content Header (Page header) -->
<?php
if (isset($_SESSION['hasil'])) {
  if ($_SESSION['hasil']) {
?>
    <div class="alert alert-success alert-dismissable">
      <button class="close" type="button" data-dismiss="alert" aria-hidden="true">X</button>
      <h5><i class="icon fas fa-check"></i>Sukses</h5>
      <?= $_SESSION['pesan'] ?>
    </div>

  <?php
  } else {
  ?>
    <div class="alert alert-danger alert-dismissable">
      <button class="close" type="button" data-dismiss="alert" aria-hidden="true">X</button>
      <h5><i class="icon fas fa-times"></i>Terjadi Kesalahan</h5>
      <?= $_SESSION['pesan'] ?>
    </div>
  <?php }
  unset($_SESSION['hasil']);
  unset($_SESSION['pesan']);
} elseif (isset($_SESSION['hasil_delete'])) {
  if ($_SESSION['hasil_delete']) {
  ?>
    <div id='hasil_delete'></div>
  <?php }
  unset($_SESSION['hasil_delete']);
} elseif (isset($_SESSION['hasil_create'])) {
  if ($_SESSION['hasil_create']) {
  ?>
    <div id='hasil_create'></div>
  <?php }
  unset($_SESSION['hasil_create']);
} elseif (isset($_SESSION['hasil_update'])) {
  if ($_SESSION['hasil_update']) {
  ?>
    <div id='hasil_update'></div>
<?php }
  unset($_SESSION['hasil_update']);
} ?>

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Distribusi</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="?page=home">Home</a></li>
          <li class="breadcrumb-item active">Distribusi</li>
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
      <h3 class="card-title">Data Distribusi</h3>
      <a href="?page=distribusicreate" class="btn btn-success btn-sm float-right">
        <i class="fa fa-plus-circle"></i> Tambah Data
      </a>
    </div>
    <div class="card-body">
      <table id="mytable" class="table table-bordered table-hover" style="white-space: nowrap; background-color: white; table-layout: fixed;">
        <thead>
          <tr>
            <th>No.</th>
            <th>No. Perjalanan</th>
            <th>Tanggal Input</th>
            <th>Plat</th>
            <th>Nama Driver</th>
            <th>Nama Helper 1</th>
            <th>Nama Helper 2</th>
            <th>Tujuan 1</th>
            <th>Tujuan 2</th>
            <th>Tujuan 3</th>
            <th>Bongkar</th>
            <th>Total Cup</th>
            <th>Total A330</th>
            <th>Total A500</th>
            <th>Total A600</th>
            <th>Total Refill</th>
            <th>Jam Berangkat</th>
            <th>Estimasi Jam Datang</th>
            <th>Estimasi Lama Perjalanan</th>
            <th>Jam Datang</th>
            <th>Keterangan</th>
            <th>Tanggal Validasi</th>
            <th>Validator</th>
            <th>Status</th>
            <th class="d-block">Opsi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $database = new Database;
          $db = $database->getConnection();

          $selectsql = "SELECT a.*, d.*, k1.nama supir, k1.upah_borongan usupir1, k2.nama helper1, k2.upah_borongan uhelper2, k3.nama helper2, k3.upah_borongan uhelper2, v.nama validator, do1.nama distro1, do1.jarak jdistro1, do2.nama distro2, do2.jarak jdistro2, do3.nama distro3, do3.jarak jdistro3
                    FROM distribusi d INNER JOIN armada a on d.id_plat = a.id
                    LEFT JOIN karyawan k1 on d.driver = k1.id
                    LEFT JOIN karyawan k2 on d.helper_1 = k2.id
                    LEFT JOIN karyawan k3 on d.helper_2 = k3.id
                    LEFT JOIN karyawan v on d.validasi_oleh = v.id
                    LEFT JOIN distributor do1 on d.nama_pel_1 = do1.id
                    LEFT JOIN distributor do2 on d.nama_pel_2 = do2.id
                    LEFT JOIN distributor do3 on d.nama_pel_3 = do3.id
                    ORDER BY tanggal DESC; ";
          $stmt = $db->prepare($selectsql);
          $stmt->execute();

          $no = 1;
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $supir = $row['supir'] == NULL ? '-' : $row['supir'];
            $helper1 = $row['helper1'] == NULL ? '-' : $row['helper1'];
            $helper2 = $row['helper2'] == NULL ? '-' : $row['helper2'];
            $distro1 = $row['distro1'] == NULL ? '-' : $row['distro1'];
            $distro2 = $row['distro2'] == NULL ? '-' : $row['distro2'];
            $distro3 = $row['distro3'] == NULL ? '-' : $row['distro3'];
            $bongkar = $row['bongkar'] == 0 ? 'TIDAK' : 'YA';
            $keterangan = $row['keterangan'] == NULL ? '-' : $row['keterangan'];
            $jam_datang = $row['jam_datang'] == NULL ? '-' : tanggal_indo($row['jam_datang']);
            $tgl_validasi = $row['tgl_validasi'] == NULL ? '-' : tanggal_indo($row['tgl_validasi']);
            $validasi_oleh = $row['validator'] == NULL ? '-' : $row['validator'];
            $estimasi_lama_perjalanan = date_diff(date_create($row['jam_berangkat']), date_create($row['estimasi_jam_datang']))->format('%d Hari %h Jam %i Menit %s Detik');
            switch ($row['status']) {
              case '0':
                $status = 'Belum Divalidasi';
                break;
              case '1':
                $status = 'Divalidasi';
                break;
              case '2':
                $status = 'Perlu ACC Uang makan';
                break;
              case '3':
                $status = 'Tidak ACC';
                break;
            }
          ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= $row['no_perjalanan'] ?></td>
              <td><?= tanggal_indo($row['tanggal']) ?></td>
              <td><?= $row['plat'], ' - ', $row['jenis_mobil']; ?></td>
              <td><?= $supir ?></td>
              <td><?= $helper1 ?></td>
              <td><?= $helper2 ?></td>
              <td><?= $distro1 ?></td>
              <td><?= $distro2 ?></td>
              <td><?= $distro3 ?></td>
              <td><?= $bongkar ?></td>
              <td><?= $row['cup1'] + $row['cup2'] + $row['cup3'] ?></td>
              <td><?= $row['a3301'] + $row['a3302'] + $row['a3303'] ?></td>
              <td><?= $row['a5001'] + $row['a5002'] + $row['a5003'] ?></td>
              <td><?= $row['a6001'] + $row['a6002'] + $row['a6003'] ?></td>
              <td><?= $row['refill1'] + $row['refill2'] + $row['refill3'] ?></td>
              <td><?= tanggal_indo($row['jam_berangkat']) ?></td>
              <td><?= tanggal_indo($row['estimasi_jam_datang']) ?></td>
              <td><?= $estimasi_lama_perjalanan ?></td>
              <td><?= $jam_datang ?></td>
              <td><?= $keterangan ?></td>
              <td><?= $tgl_validasi ?></td>
              <td><?= $validasi_oleh ?></td>
              <td>
                <?php
                if ($row['status'] == '1') { ?>
                  <span class="text-success"><i class="fa fa-check"></i> Tervalidasi</span>
                <?php } ?>
              </td>
              <td>
                <a href="?page=detaildistribusi&id=<?= $row['id']; ?>" class="btn btn-success btn-sm mr-1">
                  <i class="fa fa-eye"></i> Lihat
                </a>
                <?php if ($row['status'] == '0') { ?>
                  <a href="?page=distribusiupdate&id=<?= $row['id']; ?>" class="btn btn-primary btn-sm mr-1">
                    <i class="fa fa-edit"></i> Ubah
                  </a>
                  <a href="?page=distribusidelete&id=<?= $row['id']; ?>" class="btn btn-danger btn-sm mr-1" id="deletedistribusi">
                    <i class="fa fa-trash"></i> Hapus
                  </a>
                <?php } else if ($row['status'] == '1') { ?>
                  <a href="#" class="btn btn-secondary btn-sm mr-1 disabled" role="button" aria-disabled="true" id="distribusidisable">
                    <i class="fa fa-edit"></i> Ubah
                  </a>
                  <a href="#" class="btn btn-secondary btn-sm mr-1 disabled" role="button" aria-disabled="true" id="distribusidisable">
                    <i class="fa fa-trash"></i> Hapus
                  </a>

                <?php }; ?>

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