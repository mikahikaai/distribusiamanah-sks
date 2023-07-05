<?php
$database = new Database;
$db = $database->getConnection();
    if(isset($_GET['id'])){
        $deletesql = "DELETE from karyawan where id=?"; 
        $stmt = $db->prepare($deletesql);
        $stmt->bindParam(1, $_GET['id']);
    }
    if ($stmt->execute()){
        $_SESSION['hasil_delete'] = true;
        $_SESSION['pesan'] = "Berhasil Menghapus Data";
    } else {
        $_SESSION['hasil_delete'] = false;
        $_SESSION['pesan'] = "Gagal Menghapus Data";
    }
    echo '<meta http-equiv="refresh" content="0;url=?page=karyawanread"/>';
    exit;
