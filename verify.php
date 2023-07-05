<?php
session_start();

include 'database/database.php';

$database = new Database;
$db = $database->getConnection();

if (isset($_GET['code'])) {
  $selectSql = "SELECT d.*, u.*, p.*, k1.*, k2.nama nama_verifikator, SUM(upah) total_upah FROM pengajuan_upah_borongan p
  INNER JOIN gaji u ON p.id_upah = u.id
  INNER JOIN distribusi d ON d.id = u.id_distribusi
  INNER JOIN karyawan k1 ON k1.id = u.id_pengirim
  INNER JOIN karyawan k2 ON k2.id = p.id_verifikator
  WHERE qrcode=?";
  $stmt = $db->prepare($selectSql);
  $stmt->bindParam(1, $_GET['code']);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  // var_dump($stmt->fetch(PDO::FETCH_ASSOC));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verifikasi Pengajuan</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

  <style>
    .row {
      margin-top: 20px;
    }

    .row>* {
      font-family: Arial, Helvetica, sans-serif;
      font-size: large;
    }
  </style>

</head>

<body>
  <div class="container my-2">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Data Verifikasi Pengajuan Upah</h3>
      </div>
      <div class="card-body">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Nomor Pengajuan Upah : <?= $row['no_pengajuan'] ?></h4>
          </div>
          <div class="card-body">
            <div class="form-group">
              <div class="row">
                <div class="col-md-3">
                  <label for="">Nama</label>
                </div>
                <div class="col-md-9">
                  <span><?= $row['nama'] ?></span>
                </div>
              </div>
              <div class="row">
                <div class="col-md-3">
                  <label for="">Jumlah Upah</label>
                </div>
                <div class="col-md-9">
                  <span><?= "Rp. " . number_format($row['total_upah'], 0, ',', '.') ?></span>
                </div>
              </div>
              <div class="row">
                <div class="col-md-3">
                  <label for="">Tanggal Pengajuan Upah</label>
                </div>
                <div class="col-md-9">
                  <span><?= tanggal_indo($row['tgl_pengajuan'], true); ?></span>
                </div>
              </div>
              <div class="row">
                <div class="col-md-3">
                  <label for="">Verifikator</label>
                </div>
                <div class="col-md-9">
                  <span><?= $row['nama_verifikator'] ?></span>
                </div>
              </div>
              <div class="row">
                <div class="col-md-3">
                  <label for="">Tanggal Verifikasi</label>
                </div>
                <div class="col-md-9">
                  <span><?= tanggal_indo($row['tgl_verifikasi'], true); ?></span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card mt-3">
          <div class="card-header">
            <h4 class="card-title">Rincian Perjalanan</h4>
          </div>
          <div class="card-body">
            <div class="form-group">
              <?php
              $detailsql = "SELECT d.*, u.*, p.*, k.*, r1.nama nama_pel_1, r1.alamat_dropping alamat_pel_1, r2.nama nama_pel_2, r2.alamat_dropping alamat_pel_2, r3.alamat_dropping alamat_pel_3, r3.nama nama_pel_3 FROM pengajuan_upah_borongan p
              RIGHT JOIN gaji u ON p.id_upah = u.id
              INNER JOIN distribusi d ON d.id = u.id_distribusi
              LEFT JOIN distributor r1 on d.nama_pel_1 = r1.id
              LEFT JOIN distributor r2 on d.nama_pel_2 = r2.id
              LEFT JOIN distributor r3 on d.nama_pel_3 = r3.id
              INNER JOIN karyawan k ON k.id = u.id_pengirim
              WHERE qrcode=?";
              $stmtdetail = $db->prepare($detailsql);
              $stmtdetail->bindParam(1, $_GET['code']);
              $stmtdetail->execute();
              while ($rowdetail = $stmtdetail->fetch(PDO::FETCH_ASSOC)) {
              ?>
                <div class="row">
                  <div class="col-md-3">
                    <label for="">No Perjalanan</label>
                  </div>
                  <div class="col-md-9">
                    <span><?= $rowdetail['no_perjalanan'] ?></span>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-3">
                    <label for="">Tanggal Perjalanan</label>
                  </div>
                  <div class="col-md-9">
                    <span><?= tanggal_indo($rowdetail['jam_berangkat']) ?></span>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-3">
                    <label for="">Tujuan</label>
                  </div>
                  <div class="col-md-9">
                    <?php
                    $array = array_filter(array($rowdetail['nama_pel_1'], $rowdetail['alamat_pel_1'], $rowdetail['nama_pel_2'], $rowdetail['alamat_pel_2'], $rowdetail['nama_pel_3'], $rowdetail['alamat_pel_3']))
                    ?>
                    <span><?= implode(' <br> ', $array) ?></span>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-3">
                    <label for="">Upah</label>
                  </div>
                  <div class="col-md-9">
                    <span><?= "Rp. " . number_format($rowdetail['upah'], 0, ',', '.') ?></span>
                  </div>
                </div>
                <hr>
              <?php } ?>
            </div>
          </div>
        </div>
        <?php
        if (isset($_SESSION['jabatan'])) {
          if ($_SESSION['jabatan'] == "ADMINKEU") { ?>
            <a href="./adminkeu/report/reportpengajuanupahdetail.php?acc_code=<?= $row['acc_code']; ?>" target="_blank" class="btn btn-md btn-warning float-end mt-3"><i class="fa fa-print"></i> Cetak</a>
        <?php
          }
        }
        ?>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>

</html>

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