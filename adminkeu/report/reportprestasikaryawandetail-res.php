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

if (isset($_GET['id'])) {
  $selectSql = "SELECT u.*, d.*, p.*, k.*, d.id id_distribusi FROM gaji u
    INNER JOIN distribusi d on u.id_distribusi = d.id
    LEFT JOIN pengajuan_upah_borongan p on p.id_upah = u.id
    INNER JOIN karyawan k ON k.id = u.id_pengirim
    WHERE u.id_pengirim = ? AND (tanggal BETWEEN ? AND ?) AND jam_datang IS NOT NULL
    ORDER BY jam_berangkat DESC";
  // var_dump($tgl_rekap_awal);
  // var_dump($tgl_rekap_akhir);
  // die();
  $stmt = $db->prepare($selectSql);
  $stmt->bindParam(1, $_GET['id']);
  $stmt->bindParam(2, $tgl_rekap_awal);
  $stmt->bindParam(3, $tgl_rekap_akhir);
  $stmt->execute();
  $jumlah_data = $stmt->rowCount();

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
    <td align="center" style="font-weight: bold; padding-bottom: 20px; font-size: x-large;"><u>DATA PRESTASI KEBERANGKATAN PER KARYAWAN</u></td>
  </tr>
</table>

<!-- content dibawah header -->
<table id="content1">
  <tr>
    <td>Nama Karyawan</td>
    <td align="right">:</td>
    <td align="left"><?= $row1['nama'] ?></td>
    <td align="right"></td>
  </tr>
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
      <th>No Perjalanan</th>
      <th>Jam Berangkat</th>
      <th>Estimasi Jam Datang</th>
      <th>Aktual Jam Datang</th>
      <th>Keterangan</th>
    </tr>
  </thead>
  <tbody>
    <?php

    $no = 1;
    $jumlah_tepat_waktu = 0;
    $jumlah_terlambat = 0;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    ?>
      <tr>
        <td><?= $no++ ?></td>
        <td><?= $row['nama'] ?></td>
        <td><?= $row['no_perjalanan'] ?></td>
        <td><?= tanggal_indo($row['jam_berangkat']) ?></td>
        <td><?= tanggal_indo($row['estimasi_jam_datang']) ?></td>
        <td><?= tanggal_indo($row['jam_datang']) ?></td>
        <td>
          <?php
          if (strtotime($row['jam_datang']) <= strtotime($row['estimasi_jam_datang']) + 900) {
            echo "<div style='color: green;'>Tepat Waktu</div>";
            $jumlah_tepat_waktu += 1;
          } else {
            echo "<div style='color: red;'>Terlambat</div>";
            $jumlah_terlambat += 1;
          }
          ?>
        </td>
      </tr>
    <?php }
    $jumlah_berangkat = $jumlah_tepat_waktu + $jumlah_terlambat;
    ?>
  </tbody>
</table>
<table style="page-break-inside: avoid; padding-left: 40px;" autosize="1">
  <tr>
    <td align="right">Kesimpulan :</td>
    <td align="left">Jumlah Tepat Waktu = <?= $jumlah_tepat_waktu . "x (" . round($jumlah_tepat_waktu / $jumlah_data, 2) * 100 . " %)" ?></td>
  </tr>
  <tr>
    <td></td>
    <td align="left">Jumlah Terlambat = <?= $jumlah_terlambat . "x (" . round($jumlah_terlambat / $jumlah_data, 2) * 100 . " %)" ?></td>
  </tr>
  <tr>
    <td align="right">Nilai :</td>
    <td style="font-weight: bold;">
      <?php
      if ($jumlah_tepat_waktu / $jumlah_berangkat >= 0.8) {
        echo "<div style='color: green;'>Sangat Baik</div>";
      } else if ($jumlah_tepat_waktu / $jumlah_berangkat >= 0.6 and $jumlah_tepat_waktu / $jumlah_berangkat < 0.8) {
        echo "<div style='color: blue;'>Baik</div>";
      } else if ($jumlah_tepat_waktu / $jumlah_berangkat >= 0.3 and $jumlah_tepat_waktu / $jumlah_berangkat < 0.6) {
        echo "<div style='color: orange;'>Buruk</div>";
      } else if ($jumlah_tepat_waktu / $jumlah_berangkat < 0.3) {
        echo "<div style='color: red;'>Sangat Buruk</div>";
      }
      ?>
    </td>
  </tr>
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