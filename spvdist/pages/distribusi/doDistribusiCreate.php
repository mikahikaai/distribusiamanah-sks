<?php
$database = new Database;
$db = $database->getConnection();

// <hitung jumlah tim pengirim yang berangkat>
$array_tim_pengirim = array($_POST['driver'], !empty($_POST['helper_1']) ? $_POST['helper_1'] : NULL, !empty($_POST['helper_2']) ? $_POST['helper_2'] : NULL);
$jumlah_tim_pengirim = count(array_filter($array_tim_pengirim)) ?? 0;
// <akhir hitung jumlah tim pengirim yang berangkat>

// <hitung jumlah distributor yang diantar>
$array_distributor = $_POST['nama_pel_1'];
$jumlah_distributor = count(array_filter($array_distributor)) ?? 0;
// <akhir hitung jumlah distributor yang diantar>

// <hitung jumlah id_order>
$array_order = $_POST['pesanan'];
$jumlah_order = count(array_filter($array_order)) ?? 0;
// <akhir hitung jumlah id_order>

// <hitung lama keberangkatan>
$array_jarak = [];
for ($i = 0; $i < sizeof($_POST['nama_pel_1']); $i++) {
  $jarak_distro = "SELECT * FROM distributor WHERE id=?";
  $stmt_jarak = $db->prepare($jarak_distro);
  $stmt_jarak->bindParam(1, $_POST['nama_pel_1'][$i]);
  $stmt_jarak->execute();
  $row_jarak = $stmt_jarak->fetch(PDO::FETCH_ASSOC);
  array_push($array_jarak, (float) $row_jarak['jarak']);
}

$kecepatan_q = "SELECT * FROM armada WHERE id=?";
$stmt_kecepatan = $db->prepare($kecepatan_q);
$stmt_kecepatan->bindParam(1, $_POST['id_plat']);
$stmt_kecepatan->execute();
$row_kecepatan = $stmt_kecepatan->fetch(PDO::FETCH_ASSOC);
$kecepatan_muatan = $row_kecepatan['kecepatan_muatan'];
$kecepatan_kosong = $row_kecepatan['kecepatan_kosong'];

$jarak_max = max($array_jarak);
// var_dump($jarak_max);
// die();

// var_dump($array_urutan_input_distro);
// die();

$lama_keberangkatan = ($jarak_max / $kecepatan_muatan) * 3600;
// <akhir hitung lama keberangkatan>

// <hitung lama bongkar>
$satuan_waktu_bongkar_cup = 30;
$satuan_waktu_bongkar_330 = 30;
$satuan_waktu_bongkar_500 = 35;
$satuan_waktu_bongkar_600 = 40;
$satuan_waktu_bongkar_refill = 45;

$array_cup1 = array_sum($_POST['cup1']);
$array_a3301 = array_sum($_POST['a3301']);
$array_a5001 = array_sum($_POST['a5001']);
$array_a6001 = array_sum($_POST['a6001']);
$array_refill1 = array_sum($_POST['refill1']);

$array_group = [[]];
for ($i = 0; $i < $jumlah_distributor; $i++) {
  $array_group[$i][] = $_POST['nama_pel_1'][$i];
  $array_group[$i][] = $array_jarak[$i];
  $array_group[$i][] = $array_order[$i];
}

// var_dump($array_urutan_input_distro);
// die();

usort($array_group, function ($a, $b) {
  return $a[1] <=> $b[1];
});

$lama_bongkar = (($array_cup1 * $satuan_waktu_bongkar_cup) + ($array_a3301 * $satuan_waktu_bongkar_330) + ($array_a5001 * $satuan_waktu_bongkar_500) + ($array_a6001 * $satuan_waktu_bongkar_600) + ($array_refill1 * $satuan_waktu_bongkar_refill)) / $jumlah_tim_pengirim;
// <akhir hitung lama bongkar>

// <hitung lama muat>
$satuan_waktu_muat_galkos = 20;
$lama_muat = $array_refill1 * $satuan_waktu_muat_galkos;
// <akhir hitung lama bongkar>

// <hitung lama istirahat>
$satuan_waktu_istirahat = 1800;
$lama_istirahat = $jumlah_distributor * $satuan_waktu_istirahat;
// <akhir hitung lama istirahat>

// <hitung lama kepulangan>
$lama_kepulangan = ($jarak_max / $kecepatan_kosong) * 3600;
// <akhir hitung lama kepulangan>

// <hitung lama perjalanan>
$lama_perjalanan = ceil($lama_keberangkatan + $lama_bongkar + $lama_muat + $lama_istirahat + $lama_kepulangan);

$jam_berangkat_format = date_create_from_format('d/m/Y, H.i.s', $_POST['jam_berangkat']);
$jam_berangkat = $jam_berangkat_format->format('Y-m-d H:i:s');
$format_date_interval = "PT" . $lama_perjalanan . "S";
$estimasi_datang = $jam_berangkat_format->add(new DateInterval($format_date_interval))->format('Y-m-d H:i:s');
// <akhir hitung lama perjalanan>

// generate nomor perjalanan
$select_no_perjalanan = "SELECT no_perjalanan FROM distribusi_anggota WHERE MONTH(tanggal) = MONTH(NOW()) and YEAR(tanggal) = YEAR(NOW()) ORDER BY no_perjalanan DESC LIMIT 1";
$stmt_no_perjalanan = $db->prepare($select_no_perjalanan);
$stmt_no_perjalanan->execute();
if ($stmt_no_perjalanan->rowCount() == 0) {
  $no_perjalanan = str_pad('1', 4, '0', STR_PAD_LEFT);
} else {
  $row_no_perjalanan = $stmt_no_perjalanan->fetch(PDO::FETCH_ASSOC);
  $no_perjalanan = $row_no_perjalanan['no_perjalanan'];

  $no_perjalanan = str_pad(number_format(substr($no_perjalanan, -4)) + 1, 4, '0', STR_PAD_LEFT);
}
$no_perjalanan_new = "NJ/" . date('Y/') . date('m/') . $no_perjalanan;
// akhir generate nomor perjalanan

// generate nomor resi
// akhir generate nomor resi

// insert data distribusi ke db
$insertsql = "INSERT INTO distribusi_anggota (no_perjalanan, id_plat, driver, helper_1, helper_2, jam_berangkat, estimasi_jam_datang) VALUES (?,?,?,?,?,?,?)";
$helper_1 = !empty($_POST['helper_1']) ? $_POST['helper_1'] : null;
$helper_2 = !empty($_POST['helper_2']) ? $_POST['helper_2'] : null;
$stmt_insert = $db->prepare($insertsql);
$stmt_insert->bindParam(1, $no_perjalanan_new);
$stmt_insert->bindParam(2, $_POST['id_plat']);
$stmt_insert->bindParam(3, $_POST['driver']);
$stmt_insert->bindParam(4, $helper_1);
$stmt_insert->bindParam(5, $helper_2);
$stmt_insert->bindParam(6, $jam_berangkat);
$stmt_insert->bindParam(7, $estimasi_datang);
$stmt_insert->execute();
$sukses = true;

//notifikasi

$host = $_SERVER['SERVER_NAME'];

$last_id = $db->lastInsertId();
for ($i = 0; $i < $jumlah_distributor; $i++) {
  $no_resi = "AMNH" . str_pad(hexdec(uniqid()), 17, '0', STR_PAD_LEFT);
  $insert_distribusi_barang = "INSERT INTO distribusi_barang (id_distribusi_anggota, no_resi, id_order) VALUES (?,?,?)";
  $stmt_insert_distribusi_barang = $db->prepare($insert_distribusi_barang);
  $stmt_insert_distribusi_barang->bindParam(1, $last_id);
  $stmt_insert_distribusi_barang->bindParam(2, $no_resi);
  $stmt_insert_distribusi_barang->bindParam(3, $array_group[$i][2]);
  $stmt_insert_distribusi_barang->execute();

  $last_id_distribusi_barang= $db->lastInsertId();
  $insert_retur = "INSERT INTO retur (id_distribusi_barang) VALUES (?)";
  $stmt_insert_retur = $db->prepare($insert_retur);
  $stmt_insert_retur->bindParam(1, $last_id_distribusi_barang);
  $stmt_insert_retur->execute();

  $select_nope = 'SELECT * FROM distribusi_barang db INNER JOIN pemesanan p ON db.id_order = p.id INNER JOIN distributor d ON p.id_distro = d.id WHERE id_order = ?';
  $stmt_nope = $db->prepare($select_nope);
  $stmt_nope->bindParam(1, $array_group[$i][2]);
  $stmt_nope->execute();
  $row_nope = $stmt_nope->fetch(PDO::FETCH_ASSOC);

  $params = array(
    'token' => '4k335ti7s9wvizpa',
    'to' => $row_nope['no_telepon'],
    'body' =>
      'Hai ' . $row_nope['nama'] . ', ' . '\n' .
      'Kami telah mengatur pengiriman barang anda ke alamat tujuan dengan,' . '\n' .
      'Nomor Order : ' . $row_nope['nomor_order'] . '\n' .
      'Nomor Resi : ' . $row_nope['no_resi'] . '\n' .
      'Berikut rincian pengiriman barangnya :' . '\n' .
      'Cup 240ml : ' . $row_nope['cup'] . '\n' .
      'Amigol 330ml : ' . $row_nope['a330'] . '\n' .
      'Amigol 500ml : ' . $row_nope['a500'] . '\n' .
      'Amigol 600ml : ' . $row_nope['a600'] . '\n' .
      'Refill 19lt : ' . $row_nope['refill'] . '\n' .
      'Silahkan melakukan pengecekan secara berkala menggunakan nomor resi di atas melalui halaman web http://' . $host . '/lacakbarang.php?resi=' . $row_nope['no_resi'] . '\n' .
      'Terimakasih 🙏',
    'link' => 'http://' . $host . '/lacakbarang.php?resi=' . $row_nope['no_resi']
  );
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.ultramsg.com/instance32799/messages/chat", "https://api.ultramsg.com/instance32799/messages/link",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_SSL_VERIFYHOST => 0,
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => http_build_query($params),
    CURLOPT_HTTPHEADER => array(
      "content-type: application/x-www-form-urlencoded"
    ),
  ));

  $response = curl_exec($curl);
  $err = curl_error($curl);

  curl_close($curl);

  if ($err) {
    echo "cURL Error #:" . $err;
  }
}



if ($sukses) {
  $_SESSION['hasil_create'] = true;
  $_SESSION['pesan'] = "Berhasil Menambah Data";
} else {
  $_SESSION['hasil_create'] = false;
  $_SESSION['pesan'] = "Gagal Menambah Data";
}

echo '<meta http-equiv="refresh" content="0;url=?page=distribusiread"/>';
exit;
