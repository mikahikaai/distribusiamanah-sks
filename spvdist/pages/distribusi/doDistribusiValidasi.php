<?php
$database = new Database;
$db = $database->getConnection();

// var_dump($_POST['upload_surat_perjalanan']);
// die();

$gambar_produk = $_FILES['upload_surat']['name'];

// var_dump($gambar_produk);
// die();

$ekstensi_diperbolehkan = array('png', 'jpg'); //ekstensi file gambar yang bisa diupload 
$x = explode('.', $gambar_produk); //memisahkan nama file dengan ekstensi yang diupload
$ekstensi = strtolower(end($x));
// var_dump(explode('.', $gambar_produk));
// die();
$file_tmp = $_FILES['upload_surat']['tmp_name'];
$angka_acak = uniqid();
$nama_gambar_baru = $angka_acak . "." . $ekstensi; //menggabungkan angka acak dengan nama file sebenarnya
// var_dump($nama_gambar_baru);
// die();
if (in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
  move_uploaded_file($file_tmp, '../images/' . $nama_gambar_baru); //memindah file gambar ke folder gambar
  // jalankan query INSERT untuk menambah data ke database pastikan sesuai urutan (id tidak perlu karena dibikin otomatis)
}

$jam_datang_format = date_create_from_format('d/m/Y, H.i.s', $_POST['jam_datang']);
$jam_datang = $jam_datang_format->format('Y-m-d H:i:s');

$updatesql = "UPDATE distribusi_anggota SET jam_datang=?, bukti_kedatangan=? WHERE id = ?";
$stmt_update = $db->prepare($updatesql);
$stmt_update->bindParam(1, $jam_datang);
$stmt_update->bindParam(2, $nama_gambar_baru);
$stmt_update->bindParam(3, $_GET['id']);
$stmt_update->execute();

$jumlah_data = count($_POST['pesanan']);
// var_dump(count($_POST['pesanan']));
// die();

for ($i = 0; $i < $jumlah_data; $i++) {
  $updatestatuspesanan = "UPDATE distribusi_barang SET status='Sudah Dikirim', rating=? WHERE id_order = ?";
  $stmt_update_status_pesanan = $db->prepare($updatestatuspesanan);
  $stmt_update_status_pesanan->bindParam(1, $_POST['rating'][$i]);
  $stmt_update_status_pesanan->bindParam(2, $_POST['pesanan'][$i]);
  $stmt_update_status_pesanan->execute();

  $updateretur = "UPDATE retur SET rcup=?, ra330=?, ra500=?, ra600=?, rrefill=? WHERE id_distribusi_barang = ?";
  $stmt_update_status_pesanan = $db->prepare($updateretur);
  $stmt_update_status_pesanan->bindParam(1, $_POST['rcup1'][$i]);
  $stmt_update_status_pesanan->bindParam(2, $_POST['ra3301'][$i]);
  $stmt_update_status_pesanan->bindParam(3, $_POST['ra5001'][$i]);
  $stmt_update_status_pesanan->bindParam(4, $_POST['ra6001'][$i]);
  $stmt_update_status_pesanan->bindParam(5, $_POST['rrefill1'][$i]);
  $stmt_update_status_pesanan->bindParam(6, $_POST['id_db'][$i]);
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
