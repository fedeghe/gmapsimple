<?php
$title = 'Simple Map 17';
$intro = '<p>This is my intro</p>';

include(realpath(dirname(__FILE__).'/../gmaps3simple.class.php'));
$gmaps = new gmaps3simple(array('id'=>'map_new'));
include(realpath(dirname(__FILE__).'/apikey.php'));
$gmaps->center_point('Padova, Italia');
$gmaps->set_size(500, 400);
$gmaps->set_zoom_level(7);
$script =  $gmaps->render();
$sample_code = '
$gmaps = new gmaps3simple(array(\'id\'=>\'map_new\'));

$gmaps->center_point(\'Padova, Italia\');

$gmaps->set_size(500, 400);

$gmaps->set_zoom_level(7);   dsdsdfsdf                      

echo $gmaps->render();	
';
include('tpl.php');
