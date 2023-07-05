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

$selectSql = "SELECT * FROM gaji u
LEFT JOIN pengajuan_upah_borongan p ON p.id_upah = u.id
INNER JOIN distribusi d on u.id_distribusi = d.id
WHERE p.terbayar IS NULL AND d.jam_datang IS NOT NULL";
$stmt = $db->prepare($selectSql);
$stmt->execute();
if ($stmt->rowCount() > 0) {
  $selectSql = "SELECT p.*, u.*,k.*, d.*, k.id id_karyawan, SUM(upah) total_upah FROM pengajuan_upah_borongan p
RIGHT JOIN gaji u on p.id_upah = u.id
INNER JOIN karyawan k on u.id_pengirim = k.id
INNER JOIN distribusi d on u.id_distribusi = d.id
WHERE p.terbayar IS NULL AND d.jam_datang IS NOT NULL
GROUP BY k.nama ORDER BY k.nama ASC";
  $stmt = $db->prepare($selectSql);
  $stmt->execute();
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
    <td align="center" style="font-weight: bold; padding-bottom: 20px; font-size: x-large;"><u>DATA UPAH BELUM PENGAJUAN</u></td>
  </tr>
</table>

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
      $total_upah += round($row['total_upah'], 0);
    ?>
      <tr>
        <td><?= $no++ ?></td>
        <td><?= $row['nama'] ?></td>
        <td>Belum Mengajukan</td>
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