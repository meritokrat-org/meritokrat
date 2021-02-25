<?php
phpinfo();
system('locale -a');
$time=getdate(strtotime("18-05-2011"));
print_r($time);