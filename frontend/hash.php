<?php
// test_pdf.php
require_once '../libs/dompdf/autoload.inc.php';
use Dompdf\Dompdf;

$dompdf = new Dompdf();
$dompdf->loadHtml('<h1>Hello World</h1>');
$dompdf->render();
$dompdf->stream('hash.pdf');
?>