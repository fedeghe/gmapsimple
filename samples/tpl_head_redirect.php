<?php
// echo '<pre>' . print_r($_SERVER, true) . '</pre>';
$onJmvc = false;
switch($_SERVER['HTTP_HOST']) {
    case 'www.jmvc.org';
        define('BASE_URL', 'https://www.jmvc.org/gmaps3simple/samples/');
        $onJmvc = true;
    break;
    default: 
        define('BASE_URL', '/samples/');
    break;
}

if(isSet($_GET['id']) && intval($_GET['id'])>0  && file_exists('sample'.intval($_GET['id']).'.php') ){ 
	header('Location: '.BASE_URL.'sample'.intval($_GET['id']).'.php');
}
