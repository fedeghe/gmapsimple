<?php
$title = 'More layers';
$intro = '<p>Rotterdam is a wonderful city, even more if You have a bycicle</p>';
$sample_code = '
$gmaps = new gmaps3simple(array(\'id\'=>\'map_new\'));

$gmaps->center_point(\'rotterdam, holland\');
		
$gmaps->add_bicyle_layer();

// add a traffic layer
// $gmaps->add_traffic_layer();

$gmaps->set_size(500, 400);

$gmaps->set_zoom_level(7);                        

echo $gmaps->render();	
';
include(realpath(dirname(__FILE__).'/../gmaps3simple.class.php'));
include(realpath(dirname(__FILE__).'/cacheFlag.php'));
$gmaps = new gmaps3simple(array('id'=>'map_new','cache'=>$cache) );
include(realpath(dirname(__FILE__).'/apikey.php')); // runs $gmaps->set_api_key('A_VALID_API_KEY');
$gmaps->center_point('rotterdam, holland');

$gmaps->add_bicyle_layer();
//$gmaps->add_traffic_layer();
$gmaps->set_size(500, 400);

$gmaps->set_zoom_level(12);

$script = $gmaps->render();

include('tpl.php');
