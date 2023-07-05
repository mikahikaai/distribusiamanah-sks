<?php

if (isset($_GET['page'])) {
  $page = $_GET['page'];

  switch ($page) {
    case '':

    case 'home':
      file_exists('pages/home.php') ? include 'pages/home.php' : include '../pages/404.php';
      $title = 'Home';
      break;
    case 'dataorder':
      file_exists('pages/order/orderread.php') ? include 'pages/order/orderread.php' : include '../pages/404.php';
      $title = 'Data Order';
      break;
    case 'ordercreate':
      file_exists('pages/order/ordercreate.php') ? include 'pages/order/ordercreate.php' : include '../pages/404.php';
      $title = 'Data Order';
      break;
    case 'orderupdate':
      file_exists('pages/order/orderupdate.php') ? include 'pages/order/orderupdate.php' : include '../pages/404.php';
      $title = 'Data Order';
      break;
    case 'orderdelete':
      file_exists('pages/order/orderdelete.php') ? include 'pages/order/orderdelete.php' : include '../pages/404.php';
      $title = 'Data Order';
      break;
    case 'distributorread':
      file_exists('pages/distributor/distributorread.php') ? include 'pages/distributor/distributorread.php' : include '../pages/404.php';
      $title = 'Distributor';
      break;
    case 'distributorcreate':
      file_exists('pages/distributor/distributorcreate.php') ? include 'pages/distributor/distributorcreate.php' : include '../pages/404.php';
      $title = 'Distributor';
      break;
    case 'distributorupdate':
      file_exists('pages/distributor/distributorupdate.php') ? include 'pages/distributor/distributorupdate.php' : include '../pages/404.php';
      $title = 'Distributor';
      break;
    case 'distributordelete':
      file_exists('pages/distributor/distributordelete.php') ? include 'pages/distributor/distributordelete.php' : include '../pages/404.php';
      $title = 'Distributor';
      break;
    case 'distributordetail':
      file_exists('pages/distributor/distributordetail.php') ? include 'pages/distributor/distributordetail.php' : include '../pages/404.php';
      $title = 'Distributor';
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
    case 'rangepengajuaninsentif':
      file_exists('pages/rangepengajuaninsentif.php') ? include 'pages/rangepengajuaninsentif.php' : include '../pages/404.php';
      $title = 'Pengajuan Insentif';
      break;
    case 'pengajuaninsentif':
      file_exists('pages/pengajuaninsentif.php') ? include 'pages/pengajuaninsentif.php' : include '../pages/404.php';
      $title = 'Pengajuan Insentif';
      break;
    case 'detailpengajuaninsentif':
      file_exists('pages/detailpengajuaninsentif.php') ? include 'pages/detailpengajuaninsentif.php' : include '../pages/404.php';
      $title = 'Pengajuan Insentif';
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
    case 'rekapdetailpengajuanupah':
      file_exists('pages/rekapdetailpengajuanupah.php') ? include 'pages/rekapdetailpengajuanupah.php' : include '../pages/404.php';
      $title = 'Rekap Pengajuan Upah';
      break;
    case 'rangepengajuanupah':
      file_exists('pages/rangepengajuanupah.php') ? include 'pages/rangepengajuanupah.php' : include '../pages/404.php';
      $title = 'Pengajuan Upah';
      break;
    case 'upahbelumdiajukan':
      file_exists('pages/upahbelumdiajukan.php') ? include 'pages/upahbelumdiajukan.php' : include '../pages/404.php';
      $title = 'Upah Belum Diajukan';
      break;
    case 'upahbelumdiajukandetail':
      file_exists('pages/upahbelumdiajukandetail.php') ? include 'pages/upahbelumdiajukandetail.php' : include '../pages/404.php';
      $title = 'Upah Belum Diajukan';
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
    case 'rangerekapupah':
      file_exists('pages/rangerekapupah.php') ? include 'pages/rangerekapupah.php' : include '../pages/404.php';
      $title = 'Rekap Upah';
      break;
    case 'rekapupah':
      file_exists('pages/rekapupah.php') ? include 'pages/rekapupah.php' : include '../pages/404.php';
      $title = 'Rekap Upah';
      break;
    case 'rekapdetailupah':
      file_exists('pages/rekapdetailupah.php') ? include 'pages/rekapdetailupah.php' : include '../pages/404.php';
      $title = 'Rekap Upah';
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
    case 'rangerekapgaji':
      file_exists('pages/rangerekapgaji.php') ? include 'pages/rangerekapgaji.php' : include '../pages/404.php';
      $title = 'Rekap Gaji';
      break;
    case 'rekapgaji':
      file_exists('pages/rekapgaji.php') ? include 'pages/rekapgaji.php' : include '../pages/404.php';
      $title = 'Rekap Gaji';
      break;
    case 'rekapdetailgaji':
      file_exists('pages/rekapdetailgaji.php') ? include 'pages/rekapdetailgaji.php' : include '../pages/404.php';
      $title = 'Rekap Gaji';
      break;
    case 'rangerekapdistribusi':
      file_exists('pages/rangerekapdistribusi.php') ? include 'pages/rangerekapdistribusi.php' : include '../pages/404.php';
      $title = 'Rekap Distribusi';
      break;
    case 'rekapdistribusi':
      file_exists('pages/rekapdistribusi.php') ? include 'pages/rekapdistribusi.php' : include '../pages/404.php';
      $title = 'Rekap Distribusi';
      break;
    case 'detaildistribusi':
      file_exists('pages/detaildistribusi.php') ? include 'pages/detaildistribusi.php' : include '../pages/404.php';
      $title = 'Distribusi';
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
    case 'cetakupah':
      file_exists('pages/cetakupah.php') ? include 'pages/cetakupah.php' : include '../pages/404.php';
      $title = 'Cetak Upah';
      break;
    case 'cetakinsentif':
      file_exists('pages/cetakinsentif.php') ? include 'pages/cetakinsentif.php' : include '../pages/404.php';
      $title = 'Cetak Insentif';
      break;
    case 'ubahpassword':
      file_exists('../pages/ubahpassword.php') ? include '../pages/ubahpassword.php' : include '../pages/404.php';
      $title = 'Ubah Password';
      break;
    case 'gantifoto':
      file_exists('../pages/gantifoto.php') ? include '../pages/gantifoto.php' : include '../pages/404.php';
      $title = 'Ubah Foto';
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
