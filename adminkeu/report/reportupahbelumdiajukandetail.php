<?php

require_once '../../vendor/autoload.php';

ob_start();
include '../../pages/report/reportheader.php';
$header = ob_get_clean();
// ob_end_clean();

ob_start();
include 'reportupahbelumdiajukandetail-res.php';
$html = ob_get_clean();
// ob_end_clean();

ob_start();
include '../../pages/report/reportfooter.php';
$footer = ob_get_clean();
ob_end_clean();

$mpdf = new \Mpdf\Mpdf([
  'format' => 'A4-P',
  'margin_top' => '32',
  'margin_bottom' => '30'
]);

$mpdf->SetTitle('Amanah | Report Upah Belum Pengajuan Per Karyawan');
$mpdf->SetHTMLHeader($header);
$mpdf->SetHTMLFooter($footer);
$mpdf->WriteHTML($html);
$mpdf->Output('Report Upah Belum Pengajuan Per Karyawan.pdf', 'I');
