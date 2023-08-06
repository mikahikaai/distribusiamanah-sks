<?php
$jabatan = '';
if ($_SESSION['jabatan'] == 'ADMINKEU') {
  $jabatan = 'ADMIN KEUANGAN';
} else if ($_SESSION['jabatan'] == 'SPVDISTRIBUSI') {
  $jabatan = 'SUPERVISOR DISTRIBUSI';
} else if ($_SESSION['jabatan'] == 'DRIVER' or $_SESSION['jabatan'] == 'HELPER') {
  $jabatan = 'KARYAWAN';
} else if ($_SESSION['jabatan'] == 'MGRDISTRIBUSI') {
  $jabatan = 'MANAGER DISTRIBUSI';
} else if ($_SESSION['jabatan'] == 'MASTER') {
  $jabatan = 'MASTER';
}
?>
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
  </ul>
  <table width="100%">
    <tr>
      <td align="center" style="font-weight: bold; font-size: 20px;"> >>> Selamat Datang Di Aplikasi Distribusi Produk <span style="color : green;">PT PANCURAN KAAPIT SENDANG</span> - Saat Ini Anda Login Sebagai <span style="color: red;"><?= $jabatan ?></span>
        <<< </td>
    </tr>
  </table>
  <ul class="navbar-nav ml-auto">
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="fas fa-gear"></i>
        <!-- <p>Pengaturan</p> -->
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-item">Pengaturan Pengguna</span>
        <a href="?page=ubahpassword" class="dropdown-item">
          <i class="fas fa-key mr-2"></i> Ubah Password
        </a>
        <a href="/logout.php" class="dropdown-item" id="logout">
          <i class="fas fa-sign-out-alt mr-2"></i> Logout
        </a>
      </div>
    </li>
  </ul>
</nav>
<script>
  $('a#logout').click(function(e) {
    e.preventDefault();
    var urlToRedirect = e.currentTarget.getAttribute('href');
    //use currentTarget because the click may be on the nested i tag and not a tag causing the href to be empty
    Swal.fire({
      title: 'Yakin anda ingin logout?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      cancelButtonText: 'Batal',
      confirmButtonText: 'Ya'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location = urlToRedirect;
      }
    })
  });
</script>