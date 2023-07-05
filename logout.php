<?php 

session_start();
session_unset();
session_destroy();

setcookie('id', '', time()-3600);
setcookie('keylog', '', time()-3600);
 
echo '<meta http-equiv="refresh" content="0;url=./"/>';
exit;
