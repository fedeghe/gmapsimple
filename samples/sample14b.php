<?php
$title = 'Reverse geocoding';
$intro = '
<p>Reverse geocoding is a service that allows you to get information about a place starting from latitude and longituge information.<br />
	In this example we will use a custom function <code>open_baloon_on_dbclick()</code> which bind the double click on the map to the opening<br />
	of a baloon in that location. That accepts a parameter that will be the content of the opening baloon and in the content You can use three placeholders:<br/>
	<ul>
		<li>%LAT% : the latitude</li>
		<li>%LON% : the longitude</li>
		<li>%LOCATION% : a description of the place</li>
		<li>%ELEVATION% : the elevation in meters of the place</li>
	</ul>
</p>
';
$sample_code='
$gmaps = new gmaps3simple(array(\'id\'=>\'map_new\'));

$gmaps->center_point(\'Padova, Italia\');

$gmaps->set_map_genre(\'HYBRID\');

$gmaps->open_baloon_on_dbclick(\'Elevation: %ELEVATION% m<br />Location: %LOCATION%<br />Lat: %LAT%<br />Lon: %LON%\');

$gmaps->set_size(500, 400);

$gmaps->set_zoom_level(7);

$script = $gmaps->render();	
';
include(realpath(dirname(__FILE__).'/../gmaps3simple.class.php'));
$gmaps = new gmaps3simple(array('id'=>'map_new','cache'=>false));
include(realpath(dirname(__FILE__).'/apikey.php'));
$gmaps->center_point('Padova, Italia');

$gmaps->set_map_genre('HYBRID');

$gmaps->open_baloon_on_dbclick('Elevation: %ELEVATION% m<br />Location: %LOCATION%<br />Lat: %LAT%<br />Lon: %LON%');

$gmaps->set_size(500, 400);

$gmaps->set_zoom_level(7);

$script = $gmaps->render();

include('tpl.php');
