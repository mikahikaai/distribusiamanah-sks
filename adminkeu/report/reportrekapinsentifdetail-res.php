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

$tgl_rekap_awal = $_SESSION['tgl_rekap_insentif_awal']->format('Y-m-d H:i:s');
$tgl_rekap_akhir = $_SESSION['tgl_rekap_insentif_akhir']->format('Y-m-d H:i:s');

if (isset($_GET['id'])) {
  $selectSql = "SELECT d.*, u.*, p.*, k1.nama nama_pengirim, k2.nama nama_verifikator, u.bongkar bongkar2 FROM pengajuan_insentif_borongan p
  RIGHT JOIN gaji u ON p.id_insentif = u.id
  INNER JOIN distribusi d ON d.id = u.id_distribusi
  INNER JOIN karyawan k1 ON k1.id = u.id_pengirim
  INNER JOIN karyawan k2 ON k2.id = p.id_verifikator
  WHERE k1.id=? AND (d.jam_datang BETWEEN ? AND ?) AND terbayar='2'";
  $stmt = $db->prepare($selectSql);
  $stmt->bindParam(1, $_GET['id']);
  $stmt->bindParam(2, $tgl_rekap_awal);
  $stmt->bindParam(3, $tgl_rekap_akhir);
  $stmt->execute();

  $stmt1 = $db->prepare($selectSql);
  $stmt1->bindParam(1, $_GET['id']);
  $stmt1->bindParam(2, $tgl_rekap_awal);
  $stmt1->bindParam(3, $tgl_rekap_akhir);
  $stmt1->execute();
  $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);
}
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
    <td align="center" style="font-weight: bold; padding-bottom: 20px; font-size: x-large;"><u>DATA REKAP INSENTIF PER KARYAWAN</u></td>
  </tr>
</table>

<!-- content dibawah header -->
<table id="content1">
  <tr>
    <td>Nama Karyawan</td>
    <td align="right">:</td>
    <td align="left"><?= $row1['nama_pengirim'] ?></td>
    <td align="right"></td>
  </tr>
  <tr>
    <td>Periode Insentif</td>
    <td align="right">:</td>
    <td align="left"><?= tanggal_indo($_SESSION['tgl_rekap_insentif_awal']->format('Y-m-d')) . " sd " . tanggal_indo($_SESSION['tgl_rekap_insentif_akhir']->format('Y-m-d')) ?></td>
    <td align="right"></td>
  </tr>
</table>
<!-- end content diatas header -->

<!-- content -->
<table id="content">
  <thead>
    <tr>
      <th>No.</th>
      <th>Tanggal & Jam Berangkat</th>
      <th>No Perjalanan</th>
      <th>Nama</th>
      <th>No Pengajuan</th>
      <th>Nama Verifikator</th>
      <th>Tanggal Verifikasi</th>
      <th>Bongkar</th>
      <th>Ontime</th>
    </tr>
  </thead>
  <tbody>
    <?php

    $no = 1;
    $total_bongkar = 0;
    $total_ontime = 0;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $total_bongkar += $row['bongkar2'];
      $total_ontime += $row['ontime'];
    ?>
      <tr>
        <td><?= $no++ ?></td>
        <td><?= tanggal_indo($row['jam_berangkat']) ?></td>
        <td><?= $row['no_perjalanan'] ?></td>
        <td><?= $row['nama_pengirim'] ?></td>
        <td><?= $row['no_pengajuan'] ?></td>
        <td><?= $row['nama_verifikator'] ?></td>
        <td><?= tanggal_indo($row['tgl_verifikasi']) ?></td>
        <td style="text-align: right;"><?= 'Rp. ' . number_format($row['bongkar2'], 0, ',', '.') ?></td>
        <td style="text-align: right;"><?= 'Rp. ' . number_format($row['ontime'], 0, ',', '.') ?></td>
      </tr>
    <?php } ?>
  </tbody>
  <tfoot>
    <tr style="background-color: blanchedalmond">
      <td colspan="7" style="text-align: center; font-weight: bold;">TOTAL</td>
      <td style="text-align: right; font-weight: bold;"><?= 'Rp. ' . number_format($total_bongkar, 0, ',', '.') ?></td>
      <td style="text-align: right; font-weight: bold;"><?= 'Rp. ' . number_format($total_ontime, 0, ',', '.') ?></td>
    </tr>
    <tr style="background-color: blanchedalmond">
      <td colspan="7" style="text-align: center; font-weight: bold;">GRAND TOTAL</td>
      <td colspan="2" style="text-align: center; font-weight: bold;"><?= 'Rp. ' . number_format($total_bongkar+$total_ontime, 0, ',', '.') ?></td>
    </tr>
  </tfoot>
</table>

<!-- end content -->

<!-- summary -->
<!-- <page_break type='clonebycss' /> -->
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