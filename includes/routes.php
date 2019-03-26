<?php

//define Routes
$route['/'] = array('controller' => 'IndexController', 'uniqueName' => 'index');
$route['/index'] = array('controller' => 'IndexController', 'uniqueName' => 'index');
$route['/index.php'] = array('controller' => 'IndexController', 'uniqueName' => 'index');
$route['/index.html'] = array('controller' => 'IndexController', 'uniqueName' => 'index');


$route['/dashboard'] = array('controller' => 'DashboardController', 'uniqueName' => 'dashboard');
$route['/dashboard.php'] = array('controller' => 'DashboardController', 'uniqueName' => 'dashboard');
$route['/dashboard.html'] = array('controller' => 'DashboardController', 'uniqueName' => 'dashboard');

