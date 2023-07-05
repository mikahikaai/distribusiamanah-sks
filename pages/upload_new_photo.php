<?php
session_start();

$folderPath = '../dist/img/';

if (isset($_POST['image'])) {
  $data = $_POST['image'];
  $image_array_1 = explode(";", $data);
  $image_array_2 = explode(",", $image_array_1[1]);
  $data = base64_decode($image_array_2[1]);
  $_SESSION['foto_upload'] = uniqid() . '.png';
  $image_name = $folderPath . $_SESSION['foto_upload'];
  file_put_contents($image_name, $data);
  echo $image_name;
}
