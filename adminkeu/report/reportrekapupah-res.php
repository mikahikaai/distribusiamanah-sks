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

$tgl_rekap_awal = $_SESSION['tgl_rekap_awal_upah']->format('Y-m-d H:i:s');
$tgl_rekap_akhir = $_SESSION['tgl_rekap_akhir_upah']->format('Y-m-d H:i:s');

$selectSql = "SELECT u.*, d.*, p.*, k.*, k.id id_karyawan, SUM(upah) total_upah FROM gaji u
          INNER JOIN distribusi d on u.id_distribusi = d.id
          LEFT JOIN pengajuan_upah_borongan p on p.id_upah = u.id
          INNER JOIN karyawan k ON k.id = u.id_pengirim
          WHERE (d.jam_berangkat BETWEEN ? AND ?) AND p.terbayar='2' AND u.id_pengirim = IF (? = 'all', u.id_pengirim, ?)
          GROUP BY k.nama ORDER BY k.nama";
$stmt = $db->prepare($selectSql);
$stmt->bindParam(1, $tgl_rekap_awal);
$stmt->bindParam(2, $tgl_rekap_akhir);
$stmt->bindParam(3, $_SESSION['id_karyawan_rekap_upah']);
$stmt->bindParam(4, $_SESSION['id_karyawan_rekap_upah']);
$stmt->execute();

$stmt1 = $db->prepare($selectSql);
$stmt1->bindParam(1, $tgl_rekap_awal);
$stmt1->bindParam(2, $tgl_rekap_akhir);
$stmt1->bindParam(3, $_SESSION['id_karyawan_rekap_upah']);
$stmt1->bindParam(4, $_SESSION['id_karyawan_rekap_upah']);
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
    <td align="center" style="font-weight: bold; padding-bottom: 20px; font-size: x-large;"><u>DATA REKAP UPAH</u></td>
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
    <td>Periode Upah</td>
    <td align="right">:</td>
    <td align="left"><?= tanggal_indo($_SESSION['tgl_rekap_awal_upah']->format('Y-m-d')) . " sd " . tanggal_indo($_SESSION['tgl_rekap_akhir_upah']->format('Y-m-d')) ?></td>
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
      <th>Status</th>
      <th>Total Upah</th>
    </tr>
  </thead>
  <tbody>
    <?php

    $no = 1;
    $total_upah = 0;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $total_upah += $row['total_upah'];
    ?>
      <tr>
        <td><?= $no++ ?></td>
        <td><?= $row['nama'] ?></td>
        <td>Terverifikasi</td>
        <td style="text-align: right;"><?= 'Rp. ' . number_format($row['total_upah'], 0, ',', '.') ?></td>
      </tr>
    <?php } ?>
  </tbody>
  <tfoot>
    <tr style="background-color: blanchedalmond">
      <td colspan="3" style="text-align: center; font-weight: bold;">TOTAL</td>
      <td style="text-align: right; font-weight: bold;"><?= 'Rp. ' . number_format($total_upah, 0, ',', '.') ?></td>
    </tr>
  </tfoot>
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