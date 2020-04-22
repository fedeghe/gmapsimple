<?php
$title = 'Places';
$intro ='<p>Automatically add markers on places.</p>';
$sample_code = '
$gmaps = new gmaps3simple(array(\'id\'=>\'map_new\',\'hash\'=>array(\'libraries\'=>array(\'places\'))));

$gmaps->center_point(\'Padova, Italia\');
$gmaps->edit_panel(
	array(
		\'zoomControl\'=>array(\'show\'=>true),
		\'mapTypeControl\'=>array(\'show\'=>true),
		\'navigationControl\'=>array(\'show\'=>true)
	)
);
$gmaps->view_places(array(\'store\',\'restaurant\'), \'Padova\', 100);

$gmaps->set_size(500, 400);

$gmaps->set_zoom_level(17);

echo $gmaps->render();	
';

include(realpath(dirname(__FILE__).'/../gmaps3simple.class.php'));
$gmaps = new gmaps3simple(array('id'=>'map_new','hash'=>array('libraries'=>array('places')),'cache'=>false));
include(realpath(dirname(__FILE__).'/apikey.php')); // runs $gmaps->set_api_key('A_VALID_API_KEY');
$gmaps->center_point('Padova, Italia');
$gmaps->edit_panel(
	array(
		'zoomControl'=>array('show'=>true),
		'mapTypeControl'=>array('show'=>true),
		'navigationControl'=>array('show'=>true)
	)
);
$gmaps->view_places(array('store','restaurant'), 'Padova', 100);

$gmaps->set_size(500, 400);
$gmaps->set_zoom_level(17);
$script = $gmaps->render();

include('tpl.php');
