<?php
$database = new Database;
$db = $database->getConnection();

$validasi = "SELECT * FROM karyawan WHERE username = ? or no_telepon = ? or nik=?";
$stmt = $db->prepare($validasi);
$stmt->bindParam(1, $_POST['username']);
$stmt->bindParam(2, $_POST['no_telepon']);
$stmt->bindParam(3, $_POST['nik']);
$stmt->execute();

$selectsql2 = "SELECT email FROM karyawan WHERE email =? AND id != ?";
$stmt2 = $db->prepare($selectsql2);
$stmt2->bindParam(1, $_POST['email']);
$stmt2->bindParam(2, $_SESSION['id']);
$stmt2->execute();

if ($stmt->rowCount() > 0) {
?>
  <div class="alert alert-danger alert-dismissable">
    <button class="close" type="button" data-dismiss="alert" aria-hidden="true">X</button>
    <h5><i class="icon fas fa-times"></i>Gagal</h5>
    NIK, No. Telepon atau Username sudah terdaftar
  </div>
  <?php
} elseif (isset($_POST['button_create'])) {
  if ($_POST['password'] != $_POST['password2']) {
  ?>
    <div class="alert alert-danger alert-dismissable">
      <button class="close" type="button" data-dismiss="alert" aria-hidden="true">X</button>
      <h5><i class="icon fas fa-times"></i>Gagal</h5>
      Password tidak sama
    </div>
  <?php } elseif ($_POST['password'] == '' and $_POST['password2'] = '') {
  ?>
    <div class="alert alert-danger alert-dismissable">
      <button class="close" type="button" data-dismiss="alert" aria-hidden="true">X</button>
      <h5><i class="icon fas fa-times"></i>Gagal</h5>
      Password belum diisi
    </div>
  <?php
  } elseif ($stmt2->rowCount() > 0) { ?>
    <div class="alert alert-danger alert-dismissable">
      <button class="close" type="button" data-dismiss="alert" aria-hidden="true">X</button>
      <h5><i class="icon fas fa-times"></i>Gagal Menambahkan Email</h5>
      Email sudah ada
    </div>
<?php
  } else {

    $tanggal_lahir_format = date_create_from_format('d/m/Y', $_POST['tanggal_lahir']);
    $tanggal_lahir = $tanggal_lahir_format->format('Y-m-d');
    $alamat = strtoupper($_POST['alamat']);
    $tempat_lahir = strtoupper($_POST['tempat_lahir']);

    $insertsql = "insert into karyawan (nama, username, password, nik, tempat_lahir, tanggal_lahir, jenis_kelamin,
        alamat, agama, status, jabatan, no_telepon, gol_darah, sim, email, status_karyawan, upah_borongan, foto) values
        (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $stmt = $db->prepare($insertsql);
    $nama_upper = strtoupper($_POST['nama']);
    $username = strtolower($_POST['username']);
    $stmt->bindParam(1, $nama_upper);
    $stmt->bindParam(2, $username);
    $md5 = md5($_POST['password']);
    $stmt->bindParam(3, $md5);
    $stmt->bindParam(4, $_POST['nik']);
    $stmt->bindParam(5, $tempat_lahir);
    $stmt->bindParam(6, $tanggal_lahir);
    $stmt->bindParam(7, $_POST['jenis_kelamin']);
    $stmt->bindParam(8, $alamat);
    $stmt->bindParam(9, $_POST['agama']);
    $stmt->bindParam(10, $_POST['status']);
    $stmt->bindParam(11, $_POST['jabatan']);
    $stmt->bindParam(12, $_POST['no_telepon']);
    $stmt->bindParam(13, $_POST['gol_darah']);
    $stmt->bindParam(14, $_POST['sim']);
    $stmt->bindParam(15, $_POST['email']);
    $stmt->bindParam(16, $_POST['status_karyawan']);
    $stmt->bindParam(17, $_POST['upah_borongan']);
    $stmt->bindParam(18, $_SESSION['foto_upload']);

    if ($stmt->execute()) {
      $_SESSION['hasil_create'] = true;
      $_SESSION['pesan'] = "Berhasil Menyimpan Data";
    } else {
      $_SESSION['hasil_create'] = false;
      $_SESSION['pesan'] = "Gagal Menyimpan Data";
    }
    echo '<meta http-equiv="refresh" content="0;url=?page=karyawanread"/>';
    exit;
  }
}
?>
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Karyawan</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="?page=home">Home</a></li>
          <li class="breadcrumb-item"><a href="?page=karyawanread">Karyawan</a></li>
          <li class="breadcrumb-item active">Tambah Karyawan</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Data Tambah Karyawan</h3>
    </div>
    <div class="card-body">
      <form action="" method="post" id='karyawancreate'>
        <div class="form-group">
          <label for="nama">Nama Lengkap</label>
          <input type="text" name="nama" class="form-control" id='nama' onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || event.charCode == 8 || event.charCode == 13 || event.charCode == 32 || event.keyCode == 44 || event.keyCode == 46" style="text-transform:uppercase" value="<?= isset($_POST['button_create']) ? $_POST['nama'] : '' ?>" required>
        </div>
        <div class="form-group">
          <label for="nik">NIK</label>
          <input type="text" name="nik" class="form-control" id="nik" onkeypress="return (event.charCode > 47 && event.charCode <58) || event.charCode == 46" min="0" maxlength="16" value="<?= isset($_POST['button_create']) ? $_POST['nik'] : '' ?>" required>
        </div>
        <div class="form-group">
          <label for="username">Username</label>
          <div class="input-group">
            <input type="text" name="username" class="form-control" id="username" style="text-transform:lowercase" value="<?= isset($_POST['button_create']) ? $_POST['username'] : '' ?>" required readonly>
            <div class="input-group-append">
              <a href="javascript:void(0);" class="btn btn-success btn-active" id='generate' style="display: none;">Generate</a>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" name="password" class="form-control" required>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="password2">Ulangi Password</label>
              <input type="password" name="password2" class="form-control" required>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="jenis_kelamin">Jenis Kelamin</label>
              <select name="jenis_kelamin" class="form-control" required>
                <option value="">--Pilih Jenis Kelamin--</option>
                <?php
                $options = array('LAKI-LAKI', 'PEREMPUAN');
                foreach ($options as $option) {
                  $selected = $_POST['jenis_kelamin'] == $option ? 'selected' : '';
                  echo "<option value=\"" . $option . "\"" . $selected . ">" . $option . "</option>";
                }
                ?>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="email">E-Mail</label>
              <input type="email" name="email" class="form-control" required>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="tempat_lahir">Tempat Lahir</label>
              <input type="text" name="tempat_lahir" class="form-control" onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || event.charCode == 8 || event.charCode == 13 || event.charCode == 32" style="text-transform: uppercase;" value="<?= isset($_POST['button_create']) ? $_POST['tempat_lahir'] : '' ?>" required>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="tanggal_lahir">Tanggal Lahir</label>
              <input type="text" id="datetimepicker2" data-td-target="#datetimepicker2" name="tanggal_lahir" class="form-control" value="<?= isset($_POST['button_create']) ? $_POST['tanggal_lahir'] : '' ?>" required>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label for="alamat">Alamat</label>
          <input type="text" name="alamat" class="form-control" value="<?= isset($_POST['button_create']) ? $_POST['alamat'] : '' ?>" style="text-transform: uppercase;" required>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="no_telepon">No. Telepon</label>
              <input type="text" name="no_telepon" class="form-control" onkeypress="return (event.charCode > 47 && event.charCode <58) || event.charCode == 46" min="0" maxlength="14" value="<?= isset($_POST['button_create']) ? $_POST['no_telepon'] : '' ?>" required>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="agama">Agama</label>
              <select name="agama" class="form-control" required>
                <option value="">--Pilih Agama--</option>
                <?php
                $options = array('ISLAM', 'KRISTEN PROTESTAN', 'KRISTEN KATOLIK', 'HINDU', 'BUDHA', 'KONGHUCU');
                foreach ($options as $option) {
                  $selected = $_POST['agama'] == $option ? 'selected' : '';
                  echo "<option value=\"" . $option . "\"" . $selected . ">" . $option . "</option>";
                }
                ?>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="status">Status Kawin</label>
              <select name="status" class="form-control" required>
                <option value="">--Pilih Status Kawin--</option>
                <?php
                $options = array('KAWIN', 'BELUM KAWIN');
                foreach ($options as $option) {
                  $selected = $_POST['status'] == $option ? 'selected' : '';
                  echo "<option value=\"" . $option . "\"" . $selected . ">" . $option . "</option>";
                }
                ?>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="gol_darah">Golongan Darah</label>
              <select name="gol_darah" class="form-control" required>
                <option value="">--Pilih Golongan Darah--</option>
                <?php
                $options = array('-', 'A', 'B', 'AB', 'O');
                foreach ($options as $option) {
                  $selected = $_POST['gol_darah'] == $option ? 'selected' : '';
                  echo "<option value=\"" . $option . "\"" . $selected . ">" . $option . "</option>";
                }
                ?>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="sim">SIM</label>
              <select name="sim" class="form-control" required>
                <option value="">--Pilih SIM--</option>
                <?php
                $options = array('-', 'A', 'A UMUM', 'B1', 'B1 UMUM', 'B2', 'B2 UMUM', 'C', 'D');
                foreach ($options as $option) {
                  $selected = $_POST['sim'] == $option ? 'selected' : '';
                  echo "<option value=\"" . $option . "\"" . $selected . ">" . $option . "</option>";
                }
                ?>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="jabatan">Jabatan</label>
              <select name="jabatan" class="form-control" required>
                <option value="">--Pilih Jabatan--</option>
                <?php
                $options = array('DRIVER', 'HELPER', 'ADMINKEU', 'SPVDISTRIBUSI', 'MGRDISTRIBUSI');
                foreach ($options as $option) {
                  $selected = $_POST['jabatan'] == $option ? 'selected' : '';
                  echo "<option value=\"" . $option . "\"" . $selected . ">" . $option . "</option>";
                }
                ?>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="status_karyawan">Status Karyawan</label>
              <select name="status_karyawan" class="form-control" required>
                <option value="">--Pilih Status Karyawan--</option>
                <?php
                $options = array('BORONGAN', 'BULANAN');
                foreach ($options as $option) {
                  $selected = $_POST['status_karyawan'] == $option ? 'selected' : '';
                  echo "<option value=\"" . $option . "\"" . $selected . ">" . $option . "</option>";
                }
                ?>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="upah">Gaji Per Hari</label>
              <input type="text" name="upah_borongan" class="form-control" value="<?= isset($_POST['button_create']) ? $_POST['upah_borongan'] : '' ?>" required>
            </div>
          </div>
        </div>
        <div class="form-group">
          <h5>Upload Foto</h5>
          <input type="file" name="image" class="image">
          <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="modalLabel">Crop image</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                  </button>
                </div>
                <div class="modal-body">
                  <div class="img-container">
                    <div class="row">
                      <div class="col-lg-7 col-7">
                        <!--  default image where we will set the src via jquery-->
                        <img id="image">
                      </div>
                      <div class="col-lg-5 col-5">
                        <div class="preview"></div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                  <button type="button" class="btn btn-primary" id="crop">Crop</button>
                </div>
              </div>
            </div>
          </div>
        </div>
        </button>
        <a href="?page=karyawanread" class="btn btn-danger btn-sm float-right">
          <i class="fa fa-arrow-left"></i> Kembali
        </a>
        <button type="submit" name="button_create" class="btn btn-success btn-sm float-right mr-1">
          <i class="fa fa-save"></i> Simpan
      </form>
    </div>
  </div>
</div>

<?php
include_once "../partials/scriptdatatables.php";
?>

<script>
  var bs_modal = $('#modal');
  var image = document.getElementById('image');
  var cropper, reader, file;


  $("body").on("change", ".image", function(e) {
    var files = e.target.files;
    var done = function(url) {
      image.src = url;
      bs_modal.modal('show');
    };


    if (files && files.length > 0) {
      file = files[0];

      if (URL) {
        done(URL.createObjectURL(file));
      } else if (FileReader) {
        reader = new FileReader();
        reader.onload = function(e) {
          done(reader.result);
        };
        reader.readAsDataURL(file);
      }
    }
  });

  bs_modal.on('shown.bs.modal', function() {
    cropper = new Cropper(image, {
      aspectRatio: 1,
      viewMode: 3,
      preview: '.preview'
    });
  }).on('hidden.bs.modal', function() {
    cropper.destroy();
    cropper = null;
  });

  $("#crop").click(function() {
    canvas = cropper.getCroppedCanvas({
      width: 160,
      height: 160,
    });

    canvas.toBlob(function(blob) {
      url = URL.createObjectURL(blob);
      var reader = new FileReader();
      reader.readAsDataURL(blob);
      reader.onloadend = function() {
        var base64data = reader.result;

        $.ajax({
          type: "POST",
          dataType: "json",
          url: "../pages/upload.php",
          data: {
            image: base64data
          },
          success: function(data) {
            bs_modal.modal('hide');
            // alert("success upload image");
          }
        });
      };
    });
  });

  $('#generate').click(function() {
    nama1 = $('#nama').val().split(" ")[1] === undefined ? '' : $('#nama').val().charAt(0);
    nama2 = $('#nama').val().split(" ")[1] === undefined ? $('#nama').val().split(" ")[0] : $('#nama').val().split(" ")[1];
    nik4 = $('#nik').val().substr(-4);
    $('#username').val(nama1 + nama2 + nik4);
  });

  $('#karyawancreate').submit(function() {
    $('#generate').trigger('click');
  });

  $('#nama').on({
    change: function() {
      $('#generate').trigger('click');
    },
    keypress: function(e) {
      if (e.which == 13) {
        $('#generate').trigger('click');
      }
    }
  });


  $('#nik').on({
    change: function() {
      $('#generate').trigger('click');
    },
    keypress: function(e) {
      if (e.which == 13) {
        $('#generate').trigger('click');
      }
    }
  });
</script>
<!-- /.content -->