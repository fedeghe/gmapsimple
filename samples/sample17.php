<?php
$title = 'Simple Map 17';
$intro = 'This is my intro';

include(realpath(dirname(__FILE__).'/../gmaps3simple.class.php'));
$gmaps = new gmaps3simple(array('id'=>'map_new','cache'=>false));
include(realpath(dirname(__FILE__).'/apikey.php')); // runs $gmaps->set_api_key('A_VALID_API_KEY');
$gmaps->center_point('Padova, Italia');
$gmaps->set_size(500, 400);
$gmaps->set_zoom_level(7);
$script =  $gmaps->render();
$sample_code = '
$gmaps = new gmaps3simple(array(\'id\'=>\'map_new\'));

$gmaps->center_point(\'Padova, Italia\');

$gmaps->set_size(500, 400);

$gmaps->set_zoom_level(7);                        

echo $gmaps->render();	
';
include('tpl.php');
