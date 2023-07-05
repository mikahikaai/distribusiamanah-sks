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

$selectSql = "SELECT * FROM pemesanan p INNER JOIN distributor d ON p.id_distro = d.id";
$stmt = $db->prepare($selectSql);
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
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 10px;
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
    <td align="center" style="font-weight: bold; padding-bottom: 20px; font-size: x-large;"><u>DATA ORDER</u></td>
  </tr>
</table>

<!-- content -->
<table id="content">
  <thead>
    <tr>
      <th>No.</th>
      <th>No. Order</th>
      <th>Tgl Order</th>
      <th>Nama Distro</th>
      <th>Cup 240</th>
      <th>Amigol 330</th>
      <th>Amigol 500</th>
      <th>Amigol 500</th>
      <th>Refill</th>
    </tr>
  </thead>
  <tbody>
    <?php

    $no = 1;


    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $cup = !empty($row['cup']) ? number_format($row['cup']) : '-';
      $a330 = !empty($row['a330']) ? number_format($row['a330']) : '-';
      $a500 = !empty($row['a500']) ? number_format($row['a500']) : '-';
      $a600 = !empty($row['a600']) ? number_format($row['cup']) : '-';
      $refill = !empty($row['refill']) ? number_format($row['refill']) : '-';
    ?>
      <tr>
        <td><?= $no++ ?></td>
        <td><?= $row['nomor_order'] ?></td>
        <td><?= $row['tgl_order'] ?></td>
        <td><?= $row['nama'] ?></td>
        <td align="right"><?= $cup ?></td>
        <td align="right"><?= $a330 ?></td>
        <td align="right"><?= $a500 ?></td>
        <td align="right"><?= $a600 ?></td>
        <td align="right"><?= $refill ?></td>
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