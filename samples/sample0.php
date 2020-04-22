<?php
$title = 'Simple Map';

$intro = '<p>This is the most simple and unuseful usage: just load a map</p>';

$sample_code = '
';

include(realpath(dirname(__FILE__).'/../gmaps3simple.class.php'));
$gmaps = new gmaps3simple(array('id'=>'map_new','cache'=>false));
include(realpath(dirname(__FILE__).'/apikey.php')); // runs $gmaps->set_api_key('A_VALID_API_KEY');
$gmaps->center_point('Padova, Italia');
$gmaps->set_size(500, 400);
$gmaps->set_zoom_level(2);

$gmaps->add_point('Padova',/*array('lat'=>10.222222, 'lon'=>200.33)*/ 'padova', array('title'=>'prova', 'baloon'=>'Elevation : %ELEVATION%<br />Lat : %LAT%<br />Lon : %LON%') );

$script =  $gmaps->render();

include('tpl.php');
