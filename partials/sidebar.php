<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4 position-fixed">
  <!-- Brand Logo -->
  <?php
  if (isset($_SESSION['jabatan'])) {
    if ($_SESSION['jabatan'] == "ADMINKEU") {
      $indexurl = "adminkeu" . "/";
    } else if ($_SESSION['jabatan'] == "SPVDISTRIBUSI") {
      $indexurl = "spvdist" . "/";
    } else if ($_SESSION['jabatan'] == "DRIVER" || $_SESSION['jabatan'] == "HELPER") {
      $indexurl = "karyawan" . "/";
    } else if ($_SESSION['jabatan'] == "MGRDISTRIBUSI") {
      $indexurl = "mgrdist" . "/";
    }
  }
  ?>
  <a href="/<?= $indexurl; ?>" class="brand-link">
    <img src="../images/logooo cropped resized compressed.png" alt="AMDK Amanah" class="brand-image">
    <span class="brand-text font-weight-light">AMDK Amanah</span>
  </a>
  <a href="#" class="brand-link">
    <img src="../dist/img/<?= file_exists("../dist/img/" . ($_SESSION['foto'] == NULL ? 'null' : $_SESSION['foto'])) ? $_SESSION['foto'] : 'avatarm.png'; ?>" class="brand-image img-circle elevation-3" alt="User Image">
    <span class="brand-text align-text-top" style="font-size: 16px;"><?= $_SESSION['nama']; ?></span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->

    <!-- SidebarSearch Form -->
    <div class="form-inline mt-3">
      <div class="input-group" data-widget="sidebar-search">
        <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-sidebar">
            <i class="fas fa-search fa-fw"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-colum" data-widget="treeview" role="menu" data-accordion="true">
        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
        <li class="nav-item">
          <a href="?page=home" class='nav-link' id='home'>
            <i class="nav-icon fas fa-home"></i>
            <p>
              Home
            </p>
          </a>
        </li>
        <?php
        if ($_SESSION['jabatan'] == 'DRIVER' || $_SESSION['jabatan'] == 'HELPER') {
        ?>
          <li class="nav-item" id="penggajian">
            <a href="#" class="nav-link" id="link_penggajian">
              <i class="nav-icon fas fa-money-bill"></i>
              <p>
                Penggajian
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="?page=pengajuanupah" class="nav-link" id="pengajuan">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Pengajuan Upah</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item" id="rekapitulasi">
            <a href="#" class="nav-link" id="link_rekapitulasi">
              <i class="nav-icon fas fa-paperclip"></i>
              <p>
                Rekapitulasi
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="?page=rangerekappengajuanupah" class="nav-link" id="rekappengajuanupah">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Rekap Pengajuan Upah</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="?page=rangerekappengajuaninsentif" class="nav-link" id="rekappengajuaninsentif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Rekap Pengajuan Insentif</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="?page=rangerekapupah" class="nav-link" id="rekapupah">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Rekap Upah</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="?page=rangerekapinsentif" class="nav-link" id="rekapinsentif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Rekap Insentif</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="?page=rangerekapgaji" class="nav-link" id="rekapgaji">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Rekap Gaji</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="?page=rangerekapdistribusi" class="nav-link" id="rekapdistribusi">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Rekap Distribusi</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item" id='master_distribusi'>
            <a href="#" class="nav-link" id='link_master_distribusi'>
              <i class="fas fa-truck nav-icon"></i>
              <p>
                Master Distribusi
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="?page=distribusiread" class="nav-link" id="distribusi"><i class="far fa-circle nav-icon"></i>
                  <p>Distribusi</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="?page=rangeprestasikaryawan" class="nav-link" id="prestasikaryawan"><i class="far fa-circle nav-icon"></i>
                  <p>Prestasi Keberangkatan</p>
                </a>
              </li>
            </ul>
          </li>
        <?php } else if ($_SESSION['jabatan'] == 'MASTER') {
        ?>
          <li class="nav-item" id='order'>
            <a href="#" class="nav-link" id='link_order'>
              <i class="nav-icon fas fa-cart-shopping"></i>
              <p>
                Order
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="?page=dataorder" class="nav-link" id="inputdataorder">
                  <i class="nav-icon far fa-circle nav-icon"></i>
                  <p>
                    Data Order
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="?page=datasisaorder" class="nav-link" id="sisaorder">
                  <i class="nav-icon far fa-circle nav-icon"></i>
                  <p>
                    Data Sisa Order
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="?page=dataretur" class="nav-link" id="dataretur">
                  <i class="nav-icon far fa-circle nav-icon"></i>
                  <p>
                    Data Retur
                  </p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item" id="rekapitulasi">
            <a href="#" class="nav-link" id="link_rekapitulasi">
              <i class="nav-icon fas fa-paperclip"></i>
              <p>
                Rekapitulasi
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="?page=rangerekapdistribusi" class="nav-link" id="rekapdistribusi">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Rekap Distribusi</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item" id='master_data'>
            <a href="#" class="nav-link" id='link_master_data'>
              <i class="fas fa-th nav-icon"></i>
              <p>
                Master Data
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="?page=armadaread" class="nav-link" id='armada'><i class="far fa-circle nav-icon"></i>
                  <p>Armada</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="?page=karyawanread" class="nav-link" id='karyawan'><i class="far fa-circle nav-icon"></i>
                  <p>Karyawan</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="?page=distributorread" class="nav-link" id="distributor"><i class="far fa-circle nav-icon"></i>
                  <p>Distributor</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item" id='master_distribusi'>
            <a href="#" class="nav-link" id='link_master_distribusi'>
              <i class="fas fa-truck nav-icon"></i>
              <p>
                Master Distribusi
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="?page=distribusiread" class="nav-link" id="distribusi"><i class="far fa-circle nav-icon"></i>
                  <p>Distribusi</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="?page=ratingread" class="nav-link" id="rating"><i class="far fa-circle nav-icon"></i>
                  <p>Kepuasan Pelanggan</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="?page=rangeprestasikaryawan" class="nav-link" id="prestasikaryawan"><i class="far fa-circle nav-icon"></i>
                  <p>Prestasi Keberangkatan</p>
                </a>
              </li>
            </ul>
          </li>
        <?php } else if ($_SESSION['jabatan'] == "ADMINKEU") {
        ?>
          <li class="nav-item">
            <a href="?page=dataorder" class="nav-link" id="inputdataorder">
              <i class="nav-icon fas fa-money-bill"></i>
              <p>
                Data Order
              </p>
            </a>
          </li>
          <!-- <li class="nav-item" id="rekapitulasi">
            <a href="#" class="nav-link" id="link_rekapitulasi">
              <i class="nav-icon fas fa-paperclip"></i>
              <p>
                Rekapitulasi
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="?page=rangerekappengajuanupah" class="nav-link" id="rekappengajuanupah">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Rekap Pengajuan Upah</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="?page=rangerekappengajuaninsentif" class="nav-link" id="rekappengajuaninsentif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Rekap Pengajuan Insentif</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="?page=rangerekapupah" class="nav-link" id="rekapupah">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Rekap Upah</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="?page=rangerekapinsentif" class="nav-link" id="rekapinsentif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Rekap Insentif</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="?page=rangerekapgaji" class="nav-link" id="rekapgaji">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Rekap Gaji</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="?page=rangerekapdistribusi" class="nav-link" id="rekapdistribusi">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Rekap Distribusi</p>
                </a>
              </li>
            </ul>
          </li> -->
          <li class="nav-item" id='master_data'>
            <a href="#" class="nav-link" id='link_master_data'>
              <i class="fas fa-th nav-icon"></i>
              <p>
                Master Data
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <!-- <li class="nav-item">
                <a href="?page=armadaread" class="nav-link" id='armada'><i class="far fa-circle nav-icon"></i>
                  <p>Armada</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="?page=karyawanread" class="nav-link" id='karyawan'><i class="far fa-circle nav-icon"></i>
                  <p>Karyawan</p>
                </a>
              </li> -->
              <li class="nav-item">
                <a href="?page=distributorread" class="nav-link" id="distributor"><i class="far fa-circle nav-icon"></i>
                  <p>Distributor</p>
                </a>
              </li>
            </ul>
          </li>
          <!-- <li class="nav-item" id='master_distribusi'>
            <a href="#" class="nav-link" id='link_master_distribusi'>
              <i class="fas fa-truck nav-icon"></i>
              <p>
                Master Distribusi
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="?page=distribusiread" class="nav-link" id="distribusi"><i class="far fa-circle nav-icon"></i>
                  <p>Distribusi</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="?page=rangeprestasikaryawan" class="nav-link" id="prestasikaryawan"><i class="far fa-circle nav-icon"></i>
                  <p>Prestasi Keberangkatan</p>
                </a>
              </li>
            </ul>
          </li> -->
        <?php } else if ($_SESSION['jabatan'] == "SPVDISTRIBUSI") {
        ?>
          <li class="nav-item" id='order'>
            <a href="#" class="nav-link" id='link_order'>
              <i class="nav-icon fas fa-cart-shopping"></i>
              <p>
                Order
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="?page=dataorder" class="nav-link" id="inputdataorder">
                  <i class="nav-icon far fa-circle nav-icon"></i>
                  <p>
                    Data Sisa Order
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="?page=rangeorderterkirim" class="nav-link" id="dataterkirim">
                  <i class="nav-icon far fa-circle nav-icon"></i>
                  <p>
                    Data Barang Diterima
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="?page=rangeretur" class="nav-link" id="dataretur">
                  <i class="nav-icon far fa-circle nav-icon"></i>
                  <p>
                    Data Retur
                  </p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item" id="rekapitulasi">
            <a href="#" class="nav-link" id="link_rekapitulasi">
              <i class="nav-icon fas fa-paperclip"></i>
              <p>
                Rekapitulasi
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="?page=rangerekapdistribusi" class="nav-link" id="rekapdistribusi">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Rekap Distribusi</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item" id='master_data'>
            <a href="#" class="nav-link" id='link_master_data'>
              <i class="fas fa-th nav-icon"></i>
              <p>
                Master Data
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="?page=armadaread" class="nav-link" id='armada'><i class="far fa-circle nav-icon"></i>
                  <p>Armada</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="?page=karyawanread" class="nav-link" id='karyawan'><i class="far fa-circle nav-icon"></i>
                  <p>Karyawan</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="?page=distributorread" class="nav-link" id="distributor"><i class="far fa-circle nav-icon"></i>
                  <p>Distributor</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item" id='master_distribusi'>
            <a href="#" class="nav-link" id='link_master_distribusi'>
              <i class="fas fa-truck nav-icon"></i>
              <p>
                Master Distribusi
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="?page=distribusiread" class="nav-link" id="distribusi"><i class="far fa-circle nav-icon"></i>
                  <p>Distribusi</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="?page=ratingread" class="nav-link" id="rating"><i class="far fa-circle nav-icon"></i>
                  <p>Kepuasan Pelanggan</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="?page=rangeprestasikaryawan" class="nav-link" id="prestasikaryawan"><i class="far fa-circle nav-icon"></i>
                  <p>Prestasi Keberangkatan</p>
                </a>
              </li>
            </ul>
          </li>
        <?php }; ?>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>