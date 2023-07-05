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
    </tr>
  </thead>
  <tbody>
    <?php

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
        <td><?= $row['refill'] ?></td>
        <td><?= tanggal_indo($row['jam_berangkat']) ?></td>
        <td><?= tanggal_indo($row['estimasi_jam_datang']) ?></td>
        <td><?= $estimasi_lama_perjalanan ?></td>
        <td><?= $jam_datang ?></td>
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