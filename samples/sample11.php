<?php
$title = 'Simple Panoramio layer';
$intro = '<p>This is an easy way to add a <a href="http://www.panoramio.com/" target="_blank">panoramio</a> layer filtered on userid or tags.</p>';
$sample_code='
$gmaps = new gmaps3simple(array(\'id\'=>\'map_new\'));

$gmaps->center_point(\'Padova, Italia\');

$gmaps->add_panoramio_layer(
	 array( //the tag wins on userid
		 //\'userid\'=>\'6276787\',
		 \'tag\'=>\'architettura\'
	)
);

$gmaps->set_size(500, 400);

$gmaps->set_zoom_level(8);                        

echo $gmaps->render();
';
include(realpath(dirname(__FILE__).'/../gmaps3simple.class.php'));
$gmaps = new gmaps3simple(array('id'=>'map_new','cache'=>false));
include(realpath(dirname(__FILE__).'/apikey.php'));
$gmaps->center_point('Padova, Italia');

$gmaps->add_panoramio_layer(
	 array( //the tag wins on userid
		 //'userid'=>'6276787',
		 'tag'=>'architettura'
	)
);

$gmaps->set_size(500, 400);

$gmaps->set_zoom_level(8);

$script = $gmaps->render();

include('tpl.php');
