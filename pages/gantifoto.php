<?php

$database = new Database;
$db = $database->getConnection();

if (!isset($_SESSION['foto_upload'])) {
  $_SESSION['foto_upload'] = $_SESSION['foto'];
}

if (isset($_POST['upload'])) {
  $sqlupdate = "UPDATE karyawan SET foto=? WHERE id=?";
  $stmt = $db->prepare($sqlupdate);
  $stmt->bindParam(1, $_SESSION['foto_upload']);
  $stmt->bindParam(2, $_SESSION['id']);
  $stmt->execute();

  $_SESSION['foto'] = $_SESSION['foto_upload'];

  echo '<meta http-equiv="refresh" content="0;url=?page=gantifoto"/>';
}

?>
<style>
  .image_area {
    position: relative;
  }

  img {
    display: block;
    width: 30vh;
    height: auto;
    max-width: 100%;
  }

  .preview {
    overflow: hidden;
    width: 160px;
    height: 160px;
    margin: 10px;
    border: 1px solid red;
  }

  .modal-lg {
    max-width: 1000px !important;
  }

  .overlay {
    position: absolute;
    bottom: 10px;
    left: 0;
    right: 0;
    background-color: rgba(244, 246, 249, 0.5);

    overflow: hidden;
    height: 0;
    transition: .5s ease;
    width: 100%;
  }

  .image_area:hover .overlay {
    height: 50%;
    cursor: pointer;
  }

  .text {
    color: #333;
    font-size: 20px;
    position: absolute;
    top: 50%;
    left: 50%;
    -webkit-transform: translate(-50%, -50%);
    -ms-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);
    text-align: center;
  }
</style>

<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Foto Profil</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="?page=home">Home</a></li>
          <li class="breadcrumb-item active">Ubah Foto</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<div class="container" align="center">
  <br />
  <h3 align="center">Silahkan Pilih Foto Baru yang Ingin Anda Ubah</h3>
  <h4 align="center">Kemudian CROP Pada Bagian yang Anda Suka</h4>
  <br />
  <form action="" method="post">
    <div class="row">
      <div class="col-md-4">&nbsp;</div>
      <div class="col-md-4">
        <div class="image_area">
          <label for="upload_image">
            <img src="../dist/img/<?= file_exists("../dist/img/" . ($_SESSION['foto'] == NULL ? 'null' : $_SESSION['foto'])) ? $_SESSION['foto'] : 'avatarm.png'; ?>" id="uploaded_image" class="img-responsive img-circle" alt="User Image">
            <div class="overlay">
              <div class="text">Klik untuk Mengubah Foto Profil Anda</div>
            </div>
            <input type="file" name="image" class="image" id="upload_image" style="display:none" />
          </label>
        </div>
        <button type="submit" name="upload" class="btn btn-md btn-success" style="display: none;" id="simpan"><i class="fa fa-save"></i> Simpan</button>
      </div>
  </form>
  <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">Crop Image Before Upload</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="img-container">
            <div class="row">
              <div class="col-lg-7 col-7">
                <img src="" id="image" />
              </div>
              <div class="col-lg-5 col-5">
                <div class="preview"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="button" id="crop" class="btn btn-primary">Crop</button>
        </div>
      </div>
    </div>
  </div>
</div>
</div>

<?php
include_once "../partials/scriptdatatables.php";
?>

<script>
  $(document).ready(function() {
    $("#crop").click(function() {
      $("#simpan").show();
    });

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
            url: '../pages/upload_new_photo.php',
            method: 'POST',
            data: {
              image: base64data
            },
            success: function(data) {
              bs_modal.modal('hide');
              $('#uploaded_image').attr('src', data);
            }
          });
        };
      });
    });

  });
</script>