<?php

if (isset($_GET['page'])) {
  $page = $_GET['page'];

  switch ($page) {
    case '':

    case 'home':
      file_exists('pages/home.php') ? include 'pages/home.php' : include '../pages/404.php';
      $title = 'Home';
      break;
    case 'pengajuanupah':
      file_exists('pages/pengajuanupah.php') ? include 'pages/pengajuanupah.php' : include '../pages/404.php';
      $title = 'Pengajuan Upah';
      break;
    case 'rangerekappengajuanupah':
      file_exists('pages/rangerekappengajuanupah.php') ? include 'pages/rangerekappengajuanupah.php' : include '../pages/404.php';
      $title = 'Rekap Pengajuan Upah';
      break;
    case 'rekappengajuanupah':
      file_exists('pages/rekappengajuanupah.php') ? include 'pages/rekappengajuanupah.php' : include '../pages/404.php';
      $title = 'Rekap Pengajuan Upah';
      break;
    case 'rekapdetailpengajuanupah':
      file_exists('pages/rekapdetailpengajuanupah.php') ? include 'pages/rekapdetailpengajuanupah.php' : include '../pages/404.php';
      $title = 'Rekap Pengajuan Upah';
      break;
    case 'rangerekappengajuaninsentif':
      file_exists('pages/rangerekappengajuaninsentif.php') ? include 'pages/rangerekappengajuaninsentif.php' : include '../pages/404.php';
      $title = 'Rekap Pengajuan Insentif';
      break;
    case 'rekappengajuaninsentif':
      file_exists('pages/rekappengajuaninsentif.php') ? include 'pages/rekappengajuaninsentif.php' : include '../pages/404.php';
      $title = 'Rekap Pengajuan Insentif';
      break;
    case 'rekapdetailpengajuaninsentif':
      file_exists('pages/rekapdetailpengajuaninsentif.php') ? include 'pages/rekapdetailpengajuaninsentif.php' : include '../pages/404.php';
      $title = 'Rekap Pengajuan Insentif';
      break;
    case 'rangerekapupah':
      file_exists('pages/rangerekapupah.php') ? include 'pages/rangerekapupah.php' : include '../pages/404.php';
      $title = 'Rekap Upah';
      break;
    case 'rekapupah':
      file_exists('pages/rekapupah.php') ? include 'pages/rekapupah.php' : include '../pages/404.php';
      $title = 'Rekap Upah';
      break;
    case 'rangerekapgaji':
      file_exists('pages/rangerekapgaji.php') ? include 'pages/rangerekapgaji.php' : include '../pages/404.php';
      $title = 'Rekap Gaji';
      break;
    case 'rekapgaji':
      file_exists('pages/rekapdetailgaji.php') ? include 'pages/rekapdetailgaji.php' : include '../pages/404.php';
      $title = 'Rekap Gaji';
      break;
    case 'rekapdetailupah':
      file_exists('pages/rekapdetailupah.php') ? include 'pages/rekapdetailupah.php' : include '../pages/404.php';
      $title = 'Rekap Upah';
      break;
    case 'detaildistribusi':
      file_exists('pages/detaildistribusi.php') ? include 'pages/detaildistribusi.php' : include '../pages/404.php';
      $title = 'Distribusi';
      break;
    case 'rangerekapdistribusi':
      file_exists('pages/rangerekapdistribusi.php') ? include 'pages/rangerekapdistribusi.php' : include '../pages/404.php';
      $title = 'Rekap Distribusi';
      break;
    case 'rekapdistribusi':
      file_exists('pages/rekapdistribusi.php') ? include 'pages/rekapdistribusi.php' : include '../pages/404.php';
      $title = 'Rekap Distribusi';
      break;
    case 'rangerekapinsentif':
      file_exists('pages/rangerekapinsentif.php') ? include 'pages/rangerekapinsentif.php' : include '../pages/404.php';
      $title = 'Rekap Insentif';
      break;
    case 'rekapinsentif':
      file_exists('pages/rekapinsentif.php') ? include 'pages/rekapinsentif.php' : include '../pages/404.php';
      $title = 'Rekap Insentif';
      break;
    case 'rekapdetailinsentif':
      file_exists('pages/rekapdetailinsentif.php') ? include 'pages/rekapdetailinsentif.php' : include '../pages/404.php';
      $title = 'Rekap Insentif';
      break;
    case 'distribusiread':
      file_exists('pages/distribusiread.php') ? include 'pages/distribusiread.php' : include '../pages/404.php';
      $title = 'Distribusi';
      break;
    case 'ubahpassword':
      file_exists('../pages/ubahpassword.php') ? include '../pages/ubahpassword.php' : include '../pages/404.php';
      $title = 'Ubah Password';
      break;
    case 'gantifoto':
      file_exists('../pages/gantifoto.php') ? include '../pages/gantifoto.php' : include '../pages/404.php';
      $title = 'Ubah Foto';
      break;
    case 'rangeprestasikaryawan':
      file_exists('pages/prestasi/rangeprestasikaryawan.php') ? include 'pages/prestasi/rangeprestasikaryawan.php' : include '../pages/404.php';
      $title = 'Prestasi';
      break;
    case 'prestasikaryawan':
      file_exists('pages/prestasi/prestasikaryawan.php') ? include 'pages/prestasi/prestasikaryawan.php' : include '../pages/404.php';
      $title = 'Prestasi';
      break;
    case 'gantiprofil':
      file_exists('../pages/gantiprofil.php') ? include '../pages/gantiprofil.php' : include '../pages/404.php';
      $title = 'Ubah profil';
      break;
    default:
      include '../pages/404.php';
      $title = '404 Halaman Tidak Ditemukan';
  }
} else {
  include 'pages/home.php';
  $title = 'Home';
}
