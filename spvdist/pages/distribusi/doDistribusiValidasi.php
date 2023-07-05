<?php
$database = new Database;
$db = $database->getConnection();

$jam_datang_format = date_create_from_format('d/m/Y, H.i.s', $_POST['jam_datang']);
$jam_datang = $jam_datang_format->format('Y-m-d H:i:s');

$updatesql = "UPDATE distribusi_anggota SET jam_datang=? WHERE id = ?";
$stmt_update = $db->prepare($updatesql);
$stmt_update->bindParam(1, $jam_datang);
$stmt_update->bindParam(2, $_GET['id']);
$stmt_update->execute();

$jumlah_data = count($_POST['pesanan']);
// var_dump(count($_POST['pesanan']));
// die();

for ($i = 0; $i < $jumlah_data; $i++) {
  $updatestatuspesanan = "UPDATE distribusi_barang SET status='Sudah Dikirim' WHERE id_order = ?";
  $stmt_update_status_pesanan = $db->prepare($updatestatuspesanan);
  $stmt_update_status_pesanan->bindParam(1, $_POST['pesanan'][$i]);
  $stmt_update_status_pesanan->execute();
}

$sukses = true;

if ($sukses) {
  $_SESSION['hasil_validasi'] = true;
  $_SESSION['pesan'] = "Berhasil Memvalidasi Data";
} else {
  $_SESSION['hasil_validasi'] = false;
  $_SESSION['pesan'] = "Gagal Memvalidasi Data";
}

echo '<meta http-equiv="refresh" content="0;url=?page=distribusiread"/>';
exit;
