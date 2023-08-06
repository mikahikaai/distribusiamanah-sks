<?php
session_start();

date_default_timezone_set("Asia/Kuala_Lumpur");

if (!isset($_SESSION['jabatan'])) {
  echo '<meta http-equiv="refresh" content="0;url=/login.php">';
  die();
} else {
  if ($_SESSION['jabatan'] != "MASTER") {
    echo '<h2>ANDA TIDAK MEMILIKI AKSES KE HALAMAN INI !</h2>';
    echo '<meta http-equiv="refresh" content="2;url=/login.php"/>';
    die();
  }
}

$host = $_SERVER['REQUEST_URI'];
// var_dump($host);
// die();
?>
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en" style="scroll-behavior: smooth;">

<?php
include "../database/database.php";
$title = '';
include "../partials/head.php";
include_once "../partials/scripts.php";
?>
<style>
  .preloader {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 9999;
    background-color: #343A40;
  }

  .preloader .loading {
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  #preloader {
    font-weight: 800;
    font-size: larger;
    color: lightblue;
    display: flex;
    justify-content: center;
  }

  input[name="bongkar"] {
    transform: scale(1.5);
  }

  img {
    display: block;
    max-width: 100%;
  }

  .preview {
    overflow: hidden;
    width: 160px;
    height: 160px;
    margin: 10px;
    border: 1px solid red;
  }
</style>

<body class="hold-transition sidebar-mini">
  <?php

  if ($host == "/" . "spvdist" . "/") {
  ?>
    <div class="preloader">
      <div class="loading">
        <img src="../images/hampirsampaicompressed.gif"><br>
        <p id="preloader">. . .Hampir sampai. . .</p>
      </div>
    </div>
  <?php } ?>

  <div class="wrapper">
    <?php include "../partials/nav.php"; ?>
    <?php include "../partials/sidebar.php"; ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <?php include "routes.php"; ?>
    </div>
    <!-- /.content-wrapper -->

    <?php include "../partials/control.php"; ?>
    <?php include "../partials/footer.php"; ?>
  </div>
  <!-- ./wrapper -->

  <!-- REQUIRED SCRIPTS -->
  <button class="btn btn-success btn-lg rounded-circle" id="tothetop" onclick="topFunction();" style="position : fixed; bottom: 20px; right: 20px; display: none;"><i class="fas fa-angle-double-up"></i></button>
  <?php
  echo '<meta http-equiv="refresh" content="900;url=../logout.php"/>';
  ?>
</body>

<script src="../plugins/tempusdominus-bootstrap-4/js/jQuery-provider.min.js"></script>

<script>
  $("title").html("Amanah | <?= $title ?>");
  $(document).ready(function() {
    $('input').attr('autocomplete', 'off')
    $('#datetimepicker1').tempusDominus({
      localization: id = {
        today: 'Hari Ini',
        clear: 'Hapus',
        close: 'Tutup',
        selectMonth: 'Pilih Bulan',
        previousMonth: 'Bulan Sebelumnya',
        nextMonth: 'Bulan Selanjutnya',
        selectYear: 'Pilih Tahun',
        previousYear: 'Tahun Sebelumnya',
        nextYear: 'Tahun Selanjutnya',
        selectDecade: 'Pilih Dekade',
        previousDecade: 'Dekade Sebelumnya',
        nextDecade: 'Dekade Selanjutnya',
        previousCentury: 'Abad Sebelumnya',
        nextCentury: 'Abad Selanjutnya',
        pickHour: 'Pilih Jam',
        incrementHour: 'Tambahkan Jam',
        decrementHour: 'Kurangkan Jam',
        pickMinute: 'Pilih Menit',
        incrementMinute: 'Tambahkan Menit',
        decrementMinute: 'Kurangkan Menit',
        pickSecond: 'Pilih Detik',
        incrementSecond: 'Tambahkan Detik',
        decrementSecond: 'Kurangkan Detik',
        toggleMeridiem: 'Matikan AM/PM',
        selectTime: 'Pilih Waktu',
        selectDate: 'Pilih Tanggal',
        dayViewHeaderFormat: {
          month: 'long',
          year: 'numeric'
        },
        locale: 'id',
        startOfTheWeek: 1
      },
      display: {
        components: {
          calendar: true,
          date: true,
          seconds: true,
          useTwentyfourHour: true
        },
        buttons: {
          today: true,
          close: true,
          clear: true
        }
      },
    });
    $('#datetimepicker2').tempusDominus({
      localization: {
        locale: 'id',
        dayViewHeaderFormat: {
          month: 'long',
          year: 'numeric'
        }
      },
      display: {
        components: {
          calendar: true,
          date: true,
          clock: false,
        }
      }
    });
    $('#datetimepicker3').tempusDominus({
      localization: {
        locale: 'id',
        dayViewHeaderFormat: {
          month: 'long',
          year: 'numeric'
        }
      },
      display: {
        components: {
          calendar: true,
          date: true,
          clock: false,
        }
      }
    });
    $(".preloader").delay(5000).fadeOut();
    var title = '<?= $title; ?>';
    if (title == "Home") {
      $("a#home").addClass("active");
    } else if (title == "Armada") {
      $("a#armada").addClass("active");
      $("li#master_data").addClass("menu-open");
      $("a#link_master_data").addClass("active");
    } else if (title == "Karyawan") {
      $("a#karyawan").addClass("active");
      $("li#master_data").addClass("menu-open");
      $("a#link_master_data").addClass("active");
    } else if (title == "Distributor") {
      $("a#distributor").addClass("active");
      $("li#master_data").addClass("menu-open");
      $("a#link_master_data").addClass("active");
    } else if (title == "Order") {
      $("a#inputdataorder").addClass("active");
      $("li#order").addClass("menu-open");
      $("a#link_order").addClass("active");
    } else if (title == "Sisa Order") {
      $("a#sisaorder").addClass("active");
      $("li#order").addClass("menu-open");
      $("a#link_order").addClass("active");
    } else if (title == "Retur") {
      $("a#dataretur").addClass("active");
      $("li#order").addClass("menu-open");
      $("a#link_order").addClass("active");
    } else if (title == "Distribusi") {
      $("a#distribusi").addClass("active");
      $("li#master_distribusi").addClass("menu-open");
      $("a#link_master_distribusi").addClass("active");
    } else if (title == "Rating") {
      $("a#rating").addClass("active");
      $("li#master_distribusi").addClass("menu-open");
      $("a#link_master_distribusi").addClass("active");
    } else if (title == "Retur") {
      $("a#retur").addClass("active");
      $("li#master_distribusi").addClass("menu-open");
      $("a#link_master_distribusi").addClass("active");
    }else if (title == "Prestasi") {
      $("a#prestasikaryawan").addClass("active");
      $("li#master_distribusi").addClass("menu-open");
      $("a#link_master_distribusi").addClass("active");
    } else if (title == "Rekap Distribusi") {
      $("a#rekapdistribusi").addClass("active");
      $("li#rekapitulasi").addClass("menu-open");
      $("a#link_rekapitulasi").addClass("active");
    } 
  });

  //Get the button
  var mybutton = document.getElementById("tothetop");

  // When the user scrolls down 20px from the top of the document, show the button
  window.onscroll = function() {
    scrollFunction()
  };

  function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
      mybutton.style.display = "block";
    } else {
      mybutton.style.display = "none";
    }
  }

  function topFunction() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
  }
</script>

<?php
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
?>

</html>