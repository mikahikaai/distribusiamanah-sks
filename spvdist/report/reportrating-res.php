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

$selectsql = "SELECT *, k1.nama supir, k2.nama helper1, k3.nama helper2, da.id id_distribusi_anggota, db.status status_terkirim
FROM distribusi_barang db
LEFT JOIN distribusi_anggota da on da.id = db.id_distribusi_anggota
INNER JOIN armada a ON a.id = da.id_plat
LEFT JOIN karyawan k1 ON k1.id = da.driver
LEFT JOIN karyawan k2 ON k2.id = da.helper_1
LEFT JOIN karyawan k3 ON k3.id = da.helper_2
LEFT JOIN pemesanan p ON p.id = db.id_order
LEFT JOIN distributor d ON d.id = p.id_distro
WHERE db.status='Sudah Dikirim'
ORDER BY db.id DESC";
$stmt = $db->prepare($selectsql);
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
    <td align="center" style="font-weight: bold; padding-bottom: 20px; font-size: x-large;"><u>DATA RATING</u></td>
  </tr>
</table>

<!-- content -->
<table id="content">
  <thead>
    <tr>
      <th>No.</th>
      <th>Rating</th>
      <th>No. Order</th>
      <th>No. Perjalanan</th>
      <th>Plat</th>
      <th>Nama Driver</th>
      <th>Nama Helper 1</th>
      <th>Nama Helper 2</th>
      <th>Tujuan</th>
    </tr>
  </thead>
  <tbody>
    <?php

    $no = 1;


    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $supir = $row['supir'] == NULL ? '-' : $row['supir'];
      $helper1 = $row['helper1'] == NULL ? '-' : $row['helper1'];
      $helper2 = $row['helper2'] == NULL ? '-' : $row['helper2'];
      $distro = $row['nama'] == NULL ? '-' : $row['nama'];
      $jam_datang = $row['jam_datang'] == NULL ? '-' : tanggal_indo($row['jam_datang']);
      $estimasi_lama_perjalanan = date_diff(date_create($row['jam_berangkat']), date_create($row['estimasi_jam_datang']))->format('%d Hari %h Jam %i Menit %s Detik');
    ?>
      <tr>
        <td><?= $no++ ?></td>
        <td><?= $row['rating'] ?></td>
        <td><?= $row['nomor_order'] ?></td>
        <td><?= $row['no_perjalanan'] ?></td>
        <td><?= $row['plat'], ' - ', $row['jenis_mobil']; ?></td>
        <td><?= $supir ?></td>
        <td><?= $helper1 ?></td>
        <td><?= $helper2 ?></td>
        <td><?= $distro ?></td>
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