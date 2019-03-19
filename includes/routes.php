<?php

//define Routes
$route['/'] = array('controller' => 'IndexController', 'uniqueName' => 'index');
$route['/index'] = array('controller' => 'IndexController', 'uniqueName' => 'index');
$route['/index.php'] = array('controller' => 'IndexController', 'uniqueName' => 'index');
$route['/index.html'] = array('controller' => 'IndexController', 'uniqueName' => 'index');


$route['/login'] = array('controller' => 'DashboardController', 'uniqueName' => 'login');
$route['/login.php'] = array('controller' => 'DashboardController', 'uniqueName' => 'login');
$route['/login.html'] = array('controller' => 'DashboardController', 'uniqueName' => 'login');

$route['/dashboard'] = array('controller' => 'LogoutController', 'uniqueName' => 'logout');
$route['/dashboard.php'] = array('controller' => 'LogoutController', 'uniqueName' => 'logout');
$route['/dashboard.html'] = array('controller' => 'LogoutController', 'uniqueName' => 'logout');

