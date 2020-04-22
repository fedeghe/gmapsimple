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
$gmaps = new gmaps3simple(array('id'=>'map_new','cache'=>false));
include(realpath(dirname(__FILE__).'/apikey.php'));
$gmaps->center_point('Padova, Italia');

$gmaps->set_size(500, 400);

$gmaps->set_zoom_level(7);

$script = $gmaps->render();

include('tpl.php');
