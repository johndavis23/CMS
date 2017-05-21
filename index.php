<?php

include_once 'vendor/autoload.php';
include 'Bootstrap/Bootstrap.php';

use App\Classes\SiteController;
use App\Classes\Request;


$request = new Request();

$app = new SiteController();
$app->route($request);
exit;
	
	?>




