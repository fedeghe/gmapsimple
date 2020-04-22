<?php

$jquery = true;

$title = 'Simple Flickr layer';
$intro = '<p>This is an easy way to add a <a href="http://www.flickr.com/" target="_blank">flickr</a> layer.</p>';
$sample_code='
$gmaps = new gmaps3simple(array(\'id\'=>\'map_new\',\'cache\'=>false));

$gmaps->center_point(\'Cadoneghe, Italia\');

$gmaps->limit_baloons_to(2);

$gmaps->set_map_genre(\'SATELLITE\'); 

$gmaps->add_flickr_layer(
	 array(
		\'apikey\'=>\'150b6d717e986dfcb3ee162b77a6c808\',
		\'photoset\'=>\'72157627613506365\'
		\'baloon\'=>\'
			Title: <strong>%TITLE%</strong><br />
			%IMG%<br />
			Tags: %TAGS%<br />
			Original image: <a href="%UIMG%" target="_blank" />img</a><br />
			Taken: %TAKEN%
		\',
		\'limit\'=>3 //the last 3 uploads
	)
);


$gmaps->set_size(500, 400);

$gmaps->set_zoom_level(14);

$script = $gmaps->render();

';
include(realpath(dirname(__FILE__).'/../gmaps3simple.class.php'));
$gmaps = new gmaps3simple(array('id'=>'map_new','cache'=>false));
include(realpath(dirname(__FILE__).'/apikey.php')); // runs $gmaps->set_api_key('A_VALID_API_KEY');
$gmaps->center_point('Cadoneghe, Italia');

$gmaps->limit_baloons_to(2);

$gmaps->set_map_genre('SATELLITE'); 

//$gmaps->center_on_click(true);

$gmaps->add_flickr_layer(
	 array(
		 'apikey'=>'150b6d717e986dfcb3ee162b77a6c808',
		 'photoset'=>'72157627613506365',
		 'baloon'=>'Title:<b>%TITLE%</b><br />%IMG%<br />Tags: %TAGS%<br />Original image: <a href="%UIMG%" target="_blank" />img</a><br />Taken: %TAKEN%',
		 'limit'=>3
	)
);

$gmaps->set_size(500, 400);

$gmaps->set_zoom_level(14);

$script = $gmaps->render();

include('tpl.php');
