<?php
ob_start();
phpinfo();
$pinfo=ob_get_contents();
$context['phpinfo']=preg_replace( '%^.*<body>(.*)</body>.*$%ms','$1',$pinfo);
ob_end_clean();