<?php

include '../../../database/database.php';

$database = new Database;
$db = $database->getConnection();

$query = "SELECT * FROM pemesanan WHERE id=?";
$stmt = $db->prepare($query);
$stmt->bindParam(1, $_GET['id']);
$stmt->execute();

$result = $stmt->fetch(PDO::FETCH_ASSOC);

$data = [];
  $data[] = tanggal_indo($result['tgl_order']);
  $data[] = $result['cup'];
  $data[] = $result['a330'];
  $data[] = $result['a500'];
  $data[] = $result['a600'];
  $data[] = $result['refill'];

echo json_encode($data);

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
