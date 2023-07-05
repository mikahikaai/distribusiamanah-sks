<?php
session_start();
$folderPath = '../dist/img/';

$image_parts = explode(";base64,", $_POST['image']);
$image_type_aux = explode("image/", $image_parts[0]);
$image_type = $image_type_aux[1];
$image_base64 = base64_decode($image_parts[1]);
$_SESSION['foto_upload'] = uniqid() . '.png';
$file = $folderPath . $_SESSION['foto_upload'];
file_put_contents($file, $image_base64);
echo json_encode(["foto berhasil diupload"]);
