<?php

if (isset($_GET['page'])) {
  $page = $_GET['page'];

  switch ($page) {
    case '':

    case 'home':
      file_exists('pages/home.php') ? include 'pages/home.php' : include '../pages/404.php';
      $title = 'Home';
      break;
    case 'armadaread':
      file_exists('pages/armada/armadaread.php') ? include 'pages/armada/armadaread.php' : include '../pages/404.php';
      $title = 'Armada';
      break;
    case 'armadacreate':
      file_exists('pages/armada/armadacreate.php') ? include 'pages/armada/armadacreate.php' : include '../pages/404.php';
      $title = 'Armada';
      break;
    case 'armadaupdate':
      file_exists('pages/armada/armadaupdate.php') ? include 'pages/armada/armadaupdate.php' : include '../pages/404.php';
      $title = 'Armada';
      break;
    case 'armadadelete':
      file_exists('pages/armada/armadadelete.php') ? include 'pages/armada/armadadelete.php' : include '../pages/404.php';
      $title = 'Armada';
      break;
    case 'armadadetail':
      file_exists('pages/armada/armadadetail.php') ? include 'pages/armada/armadadetail.php' : include '../pages/404.php';
      $title = 'Armada';
      break;
    case 'dataorder':
      file_exists('pages/order/orderread.php') ? include 'pages/order/orderread.php' : include '../pages/404.php';
      $title = 'Order';
      break;
    case 'dataretur':
      file_exists('pages/retur/returread.php') ? include 'pages/retur/returread.php' : include '../pages/404.php';
      $title = 'Retur';
      break;
    case 'rangeretur':
      file_exists('pages/retur/rangereturread.php') ? include 'pages/retur/rangereturread.php' : include '../pages/404.php';
      $title = 'Retur';
      break;
    case 'ratingread':
      file_exists('pages/rating/ratingread.php') ? include 'pages/rating/ratingread.php' : include '../pages/404.php';
      $title = 'Rating';
      break;
    case 'distribusiread':
      file_exists('pages/distribusi/distribusiread.php') ? include 'pages/distribusi/distribusiread.php' : include '../pages/404.php';
      $title = 'Distribusi';
      break;
    case 'distribusicreate':
      file_exists('pages/distribusi/distribusicreate.php') ? include 'pages/distribusi/distribusicreate.php' : include '../pages/404.php';
      $title = 'Distribusi';
      break;
    case 'distribusiupdate':
      file_exists('pages/distribusi/distribusiupdate.php') ? include 'pages/distribusi/distribusiupdate.php' : include '../pages/404.php';
      $title = 'Distribusi';
      break;
    case 'distribusidelete':
      file_exists('pages/distribusi/distribusidelete.php') ? include 'pages/distribusi/distribusidelete.php' : include '../pages/404.php';
      $title = 'Distribusi';
      break;
    case 'distribusidetail':
      file_exists('pages/distribusi/distribusidetail.php') ? include 'pages/distribusi/distribusidetail.php' : include '../pages/404.php';
      $title = 'Distribusi';
      break;
    case 'doDistribusiCreate':
      file_exists('pages/distribusi/doDistribusiCreate.php') ? include 'pages/distribusi/doDistribusiCreate.php' : include '../pages/404.php';
      $title = 'Distribusi';
      break;
    case 'doDistribusiUpdate':
      file_exists('pages/distribusi/doDistribusiUpdate.php') ? include 'pages/distribusi/doDistribusiUpdate.php' : include '../pages/404.php';
      $title = 'Distribusi';
      break;
    case 'distribusivalidasi':
      file_exists('pages/distribusi/distribusivalidasi.php') ? include 'pages/distribusi/distribusivalidasi.php' : include '../pages/404.php';
      $title = 'Distribusi';
      break;
    case 'doDistribusiValidasi':
      file_exists('pages/distribusi/doDistribusiValidasi.php') ? include 'pages/distribusi/doDistribusiValidasi.php' : include '../pages/404.php';
      $title = 'Distribusi';
      break;
    case 'distribusibatalvalidasi':
      file_exists('pages/distribusi/distribusibatalvalidasi.php') ? include 'pages/distribusi/distribusibatalvalidasi.php' : include '../pages/404.php';
      $title = 'Distribusi';
      break;
    case 'distributorread':
      file_exists('pages/distributor/distributorread.php') ? include 'pages/distributor/distributorread.php' : include '../pages/404.php';
      $title = 'Distributor';
      break;
    case 'distributordetail':
      file_exists('pages/distributor/distributordetail.php') ? include 'pages/distributor/distributordetail.php' : include '../pages/404.php';
      $title = 'Distributor';
      break;
    case 'karyawanread':
      file_exists('pages/karyawan/karyawanread.php') ? include 'pages/karyawan/karyawanread.php' : include '../pages/404.php';
      $title = 'Karyawan';
      break;
    case 'karyawancreate':
      file_exists('pages/karyawan/karyawancreate.php') ? include 'pages/karyawan/karyawancreate.php' : include '../pages/404.php';
      $title = 'Karyawan';
      break;
    case 'karyawanupdate':
      file_exists('pages/karyawan/karyawanupdate.php') ? include 'pages/karyawan/karyawanupdate.php' : include '../pages/404.php';
      $title = 'Karyawan';
      break;
    case 'karyawandelete':
      file_exists('pages/karyawan/karyawandelete.php') ? include 'pages/karyawan/karyawandelete.php' : include '../pages/404.php';
      $title = 'Karyawan';
      break;
    case 'karyawandetail':
      file_exists('pages/karyawan/karyawandetail.php') ? include 'pages/karyawan/karyawandetail.php' : include '../pages/404.php';
      $title = 'Karyawan';
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
    case 'prestasikaryawandetail':
      file_exists('pages/prestasi/prestasikaryawandetail.php') ? include 'pages/prestasi/prestasikaryawandetail.php' : include '../pages/404.php';
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
