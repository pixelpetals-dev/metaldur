<?php
session_start();
session_destroy();
header('Location: /admin/super-admin/login.php');
exit;
