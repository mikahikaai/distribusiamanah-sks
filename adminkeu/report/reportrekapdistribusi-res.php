<?php
session_start();

if (!isset($_SESSION['jabatan'])) {
  echo '<meta http-equiv="refresh" content="0;url=../../logout.php"/>';
  exit;
}
include "../../database/database.php";

date_default_timezone_set("Asia/Kuala_Lumpur");

$database = new Database;
$db = $database->getConnection();

$tgl_rekap_awal = $_SESSION['tgl_rekap_awal_distribusi']->format('Y-m-d H:i:s');
$tgl_rekap_akhir = $_SESSION['tgl_rekap_akhir_distribusi']->format('Y-m-d H:i:s');
// var_dump($_SESSION['status_kedatangan_distribusi']);
// die();

$selectsql = "SELECT a.*, d.*, k1.nama supir, k1.upah_borongan usupir1, k2.nama helper1, k2.upah_borongan uhelper2, k3.nama helper2, k3.upah_borongan uhelper2, v.nama validator, do1.nama distro1, do1.jarak jdistro1, do2.nama distro2, do2.jarak jdistro2, do3.nama distro3, do3.jarak jdistro3
      FROM distribusi d INNER JOIN armada a on d.id_plat = a.id
      LEFT JOIN karyawan k1 on d.driver = k1.id
      LEFT JOIN karyawan k2 on d.helper_1 = k2.id
      LEFT JOIN karyawan k3 on d.helper_2 = k3.id
      LEFT JOIN karyawan v on d.validasi_oleh = v.id
      LEFT JOIN distributor do1 on d.nama_pel_1 = do1.id
      LEFT JOIN distributor do2 on d.nama_pel_2 = do2.id
      LEFT JOIN distributor do3 on d.nama_pel_3 = do3.id
      WHERE (IF (? = 'all',d.jam_datang IS NULL OR d.jam_datang IS NOT NULL, IF(? = '1',d.jam_datang IS NOT NULL, d.jam_datang IS NULL))) AND (d.driver = IF (? = 'all', d.driver, ?) OR d.helper_1 = IF (? = 'all', d.helper_1, ?) OR d.helper_2 = IF (? = 'all', d.helper_2, ?)) AND (d.jam_berangkat BETWEEN ? AND ?)
      ORDER BY jam_berangkat ASC; ";
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
$stmt->execute();

$stmt1 = $db->prepare($selectsql);
$stmt1->bindParam(1, $_SESSION['status_kedatangan_distribusi']);
$stmt1->bindParam(2, $_SESSION['status_kedatangan_distribusi']);
$stmt1->bindParam(3, $_SESSION['id_karyawan_rekap_distribusi']);
$stmt1->bindParam(4, $_SESSION['id_karyawan_rekap_distribusi']);
$stmt1->bindParam(5, $_SESSION['id_karyawan_rekap_distribusi']);
$stmt1->bindParam(6, $_SESSION['id_karyawan_rekap_distribusi']);
$stmt1->bindParam(7, $_SESSION['id_karyawan_rekap_distribusi']);
$stmt1->bindParam(8, $_SESSION['id_karyawan_rekap_distribusi']);
$stmt1->bindParam(9, $tgl_rekap_awal);
$stmt1->bindParam(10, $tgl_rekap_akhir);
$stmt1->execute();
$row1 = $stmt1->fetch(PDO::FETCH_ASSOC);
?>
<style>
  table#content {
    font-family: Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    /* table-layout: fixed; */
    width: 100%;
    margin-bottom: 30px;
  }

  table#content th {
    border: 1px solid grey;
    padding: 8px;
    text-align: center;
    width: fit-content;
    background-color: #5a5e5a;
    color: white;

  }

  table#content td {
    border: 1px solid grey;
    padding: 8px;
  }

  table#content tbody tr:nth-child(even) {
    background-color: #e4ede4;
  }

  table#content1 {
    /* width: 100%; */
    border-collapse: collapse;
    margin-bottom: 10px;
  }

  table#content1 tr td:nth-child(n+2) {
    padding-left: 10px;
  }

  table#content1 td {
    /* border: 1px solid black; */
    padding-bottom: 10px;
  }

  table#summary {
    width: 100%;
    border-collapse: collapse;
  }
</style>

<!-- header -->

<table style="width: 100%; margin-bottom: 10px;">
  <tr>
    <td align="center" style="font-weight: bold; padding-bottom: 20px; font-size: x-large;"><u>DATA REKAP DISTRIBUSI</u></td>
  </tr>
</table>

<!-- content dibawah header -->
<table id="content1">
  <tr>
    <td>Status Kedatangan</td>
    <td align="right">:</td>
    <td align="left">
      <?php if ($_SESSION['status_kedatangan_distribusi'] == 'all') {
        echo 'Semua';
      } else if ($_SESSION['status_kedatangan_distribusi'] == '1') {
        echo 'Sudah Datang';
      } else {
        echo 'Belum Datang';
      } ?>
    </td>
  <tr>
    <td>Periode</td>
    <td align="right">:</td>
    <td align="left"><?= tanggal_indo($_SESSION['tgl_rekap_awal_distribusi']->format('Y-m-d')) . " sd " . tanggal_indo($_SESSION['tgl_rekap_akhir_distribusi']->format('Y-m-d')) ?></td>
  </tr>
  </tr>
  <!-- <tr>
    <td width="20%"></td>
    <td width="5%" align="right"></td>
    <td width="50%" align="left"></td>
    <td width="25%" align="right"></td>
  </tr> -->
</table>
<!-- end content diatas header -->

<!-- content -->
<table id="content">
  <thead>
    <tr>
      <th>No.</th>
      <th>Jam Berangkat</th>
      <th>No Perjalanan</th>
      <th>Plat | Armada</th>
      <th>Driver</th>
      <th>Helper 1</th>
      <th>Helper 2</th>
      <th>Tujuan 1</th>
      <th>Tujuan 2</th>
      <th>Tujuan 3</th>
      <th>Muatan Cup</th>
      <th>Muatan A330</th>
      <th>Muatan A500</th>
      <th>Muatan A600</th>
      <th>Muatan Galon</th>
      <th>Status Kedatangan</th>
    </tr>
  </thead>
  <tbody>
    <?php

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
      $jam_datang = $row['jam_datang'] == NULL ? '-' : date('d-m-Y H:i:s', strtotime($row['jam_datang']));
      $tgl_validasi = $row['tgl_validasi'] == NULL ? '-' : date('d-m-Y H:i:s', strtotime($row['tgl_validasi']));
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
        <td><?= tanggal_indo($row['jam_berangkat']) ?></td>
        <td><?= $row['no_perjalanan'] ?></td>
        <td><?= $row['plat'], ' - ', $row['jenis_mobil']; ?></td>
        <td><?= $supir ?></td>
        <td><?= $helper1 ?></td>
        <td><?= $helper2 ?></td>
        <td><?= $distro1 ?></td>
        <td><?= $distro2 ?></td>
        <td><?= $distro3 ?></td>
        <td><?= $row['cup1'] + $row['cup2'] + $row['cup3'] ?></td>
        <td><?= $row['a3301'] + $row['a3302'] + $row['a3303'] ?></td>
        <td><?= $row['a5001'] + $row['a5002'] + $row['a5003'] ?></td>
        <td><?= $row['a6001'] + $row['a6002'] + $row['a6003'] ?></td>
        <td><?= $row['refill1'] + $row['refill2'] + $row['refill3'] ?></td>
        <td>
          <?php
          if (empty($row['jam_datang'])){
            echo "Belum";
          } else {
            echo "Sudah";
          }
          ?>
        </td>
      </tr>
    <?php } ?>
  </tbody>
</table>
<!-- end content -->

<!-- summary -->

<table id="summary" style="page-break-inside: avoid;" autosize="1">
  <tr>
    <td width="70%"></td>
    <td align="center">Banjarbaru, <?= tanggal_indo(date('Y-m-d')) ?></td>
  </tr>
  <tr>
    <td width=" 70%"></td>
    <td><br><br><br><br><br><br><br></td>
  </tr>
  <tr>
    <td width="70%"></td>
    <td align="center"><u><b><?= $_SESSION['nama'] ?></b></u></td>
  </tr>
</table>

<!-- end summary -->

<!-- footer -->
<!-- end footer -->

<?php
function tanggal_indo($date, $cetak_hari = false)
{
  $hari = array(
    1 =>    'Senin',
    'Selasa',
    'Rabu',
    'Kamis',
    'Jumat',
    'Sabtu',
    'Minggu'
  );

  $bulan = array(
    1 =>   'Januari',
    'Februari',
    'Maret',
    'April',
    'Mei',
    'Juni',
    'Juli',
    'Agustus',
    'September',
    'Oktober',
    'November',
    'Desember'
  );
  $split = explode(' ', $date);
  $split_tanggal = explode('-', $split[0]);
  if (count($split) == 1) {
    $tgl_indo = $split_tanggal[2] . ' ' . $bulan[(int)$split_tanggal[1]] . ' ' . $split_tanggal[0];
  } else {
    $split_waktu = explode(':', $split[1]);
    $tgl_indo = $split_tanggal[2] . ' ' . $bulan[(int)$split_tanggal[1]] . ' ' . $split_tanggal[0] . ' ' . $split_waktu[0] . ':' . $split_waktu[1] . ':' . $split_waktu[2];
  }

  if ($cetak_hari) {
    $num = date('N', strtotime($date));
    return $hari[$num] . ', ' . $tgl_indo;
  }
  return $tgl_indo;
}
?>