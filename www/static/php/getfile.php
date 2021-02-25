<?php
error_reporting(0);
$img = end(explode('/',$_GET['f']));
header("Content-Disposition: attachment; filename=$img");
header("Content-Type: application/x-force-download; name=\"$img\"");
echo readfile($_SERVER['DOCUMENT_ROOT'].$_GET['f']);
?>
