<?
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment;filename=photo.zip');
header('Cache-Control: no-cache, must-revalidate');
header('Content-Transfer-Encoding: binary');
header('Content-Length: '.filesize($file));
readfile($file);
?>
