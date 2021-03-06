<?php
$title = 'Simple Map';
$intro ='<p>This is the most simple usage: just load a map</p>';
$sample_code = '
$gmaps = new gmaps3simple(array(\'id\'=>\'map_new\'));

$gmaps->center_point(\'Padova, Italia\');

$gmaps->set_size(500, 400);

$gmaps->set_zoom_level(7);                        

echo $gmaps->render();
';

include(realpath(dirname(__FILE__).'/../gmaps3simple.class.php'));
include(realpath(dirname(__FILE__).'/cacheFlag.php'));
$gmaps = new gmaps3simple(array('id'=>'map_new','cache'=>$cache));
include(realpath(dirname(__FILE__).'/apikey.php')); // runs $gmaps->set_api_key('A_VALID_API_KEY');
$gmaps->center_point('Padova, Italia');

$gmaps->set_size(500, 400);

$gmaps->set_zoom_level(7);

$script = $gmaps->render();

include('tpl.php');
