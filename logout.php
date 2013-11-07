<?php
require_once dirname(__FILE__) . '/./AppDotNetPHP/EZAppDotNet.php';

$app = new EZAppDotNet();

// log out user
$app->deleteSession();

// redirect user after logging out
header('Location: .');

?>