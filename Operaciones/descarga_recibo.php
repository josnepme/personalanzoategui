<?php
$fileName = '../TMP/recibo.pdf';
$downloadFileName = 'Recibo.pdf';

if (file_exists($fileName)) {
header('Expires: 0');
header('Content-Description: File Transfer');
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename='.$downloadFileName);
ob_clean();
flush();
readfile($fileName);
exit;
}
?>