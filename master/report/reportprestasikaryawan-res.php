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

$tgl_rekap_awal = $_SESSION['tgl_prestasi_awal']->format('Y-m-d H:i:s');
$tgl_rekap_akhir = $_SESSION['tgl_prestasi_akhir']->format('Y-m-d H:i:s');

$selectSql = "SELECT k.nama,
(SELECT count(*) FROM distribusi_anggota da WHERE (driver = k.id or helper_1 = k.id or helper_2 = k.id) AND jam_datang <= estimasi_jam_datang + INTERVAL 15 MINUTE AND (jam_berangkat BETWEEN :awal AND :akhir)) tepat_waktu,
(SELECT count(*) FROM distribusi_anggota da WHERE (driver = k.id or helper_1 = k.id or helper_2 = k.id) AND jam_datang > estimasi_jam_datang + INTERVAL 15 MINUTE AND (jam_berangkat BETWEEN :awal AND :akhir)) tidak_tepat_waktu,
(SELECT count(*) FROM distribusi_anggota da WHERE (driver = k.id or helper_1 = k.id or helper_2 = k.id) AND jam_datang is not null AND (jam_berangkat BETWEEN :awal and :akhir)) total_berangkat
FROM karyawan k
HAVING total_berangkat > 0 
";
$stmt = $db->prepare($selectSql);
$stmt->bindParam('awal', $tgl_rekap_awal);
$stmt->bindParam('akhir', $tgl_rekap_akhir);
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
    <td align="center" style="font-weight: bold; padding-bottom: 20px; font-size: x-large;"><u>DATA PRESTASI KEBERANGKATAN</u></td>
  </tr>
</table>

<!-- content dibawah header -->
<table id="content1">
  <!-- <tr>
    <td width="20%">Nama Karyawan</td>
    <td width="5%" align="right">:</td>
    <td width="50%" align="left"><?= $row1['nama'] ?></td>
    <td width="25%" align="right"></td>
  </tr> -->
  <tr>
    <td>Periode Keberangkatan</td>
    <td align="right">:</td>
    <td align="left"><?= tanggal_indo($_SESSION['tgl_prestasi_awal']->format('Y-m-d')) . " sd " . tanggal_indo($_SESSION['tgl_prestasi_akhir']->format('Y-m-d')) ?></td>
    <td align="right"></td>
  </tr>
</table>
<!-- end content diatas header -->

<!-- content -->
<table id="content">
  <thead>
    <tr>
      <th>No.</th>
      <th>Nama</th>
      <th>Total Berangkat</th>
      <th>Tepat Waktu</th>
      <th>Terlambat</th>
      <th>Keterangan</th>
    </tr>
  </thead>
  <tbody>
    <?php

    $no = 1;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    ?>
      <tr>
        <td><?= $no++ ?></td>
        <td><?= $row['nama'] ?></td>
        <td align="right"><?= $row['total_berangkat'] ?></td>
        <td><?= $row['tepat_waktu'] . "x (" . (round($row['tepat_waktu'] / $row['total_berangkat'], 2)) * 100 . "%)" ?></td>
        <td><?= $row['tidak_tepat_waktu'] . "x (" . (round($row['tidak_tepat_waktu'] / $row['total_berangkat'], 2)) * 100 . "%)" ?></td>
        <td>
          <?php
          if ($row['tepat_waktu'] / $row['total_berangkat'] >= 0.8) {
            echo "<div style='color: green;'>Sangat Baik</div>";
          } else if ($row['tepat_waktu'] / $row['total_berangkat'] >= 0.6 and $row['tepat_waktu'] / $row['total_berangkat'] < 0.8) {
            echo "<div style='color: blue;'>Baik</div>";
          } else if ($row['tepat_waktu'] / $row['total_berangkat'] >= 0.3 and $row['tepat_waktu'] / $row['total_berangkat'] < 0.6) {
            echo "<div style='color: orange;'>Buruk</div>";
          } else if ($row['tepat_waktu'] / $row['total_berangkat'] < 0.3) {
            echo "<div style='color: red;'>Sangat Buruk</div>";
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
function tanggal_indo($tanggal, $cetak_hari = false)
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
  $split     = explode('-', $tanggal);
  $tgl_indo = $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];

  if ($cetak_hari) {
    $num = date('N', strtotime($tanggal));
    return $hari[$num] . ', ' . $tgl_indo;
  }
  return $tgl_indo;
}
?>