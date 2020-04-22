<?php
$title = 'Circles and Rectangles';
$intro = '
<p>We want to:
	<ul>
		<li>draw a rectangle</li>
		<li>draw two circles</li>
		<li>never display more than two baloons</li>
		<li>in one circle baloon, print out the distance between Padova and Verona by car</li>
	</ul>
	Moreover in all areas are available four placeholders:
	<ul>
		<li>%LAT% : the latitude of the point we clicked</li>
		<li>%LON% : the longitude of the point we clicked</li>
		<li>%AREA% : the area in km<sup>2</sup></li>
		<li>%LOCATION% : more info about the place we clicked</li>
		<li>%ELEVATION% : the elevation of the point we clicked on</li>
	</ul>
	NOTE: Areas computation requires <i>geometry</i> library, so we load it from the constructor.
</p>	
';
$sample_code = '
$gmaps = new gmaps3simple(
	array(\'id\'=>\'map_new\',
		\'hash\'=>array(
			\'libraries\'=>array(\'geometry\')
		)
	)
);

$gmaps->center_point(\'Padova, Italia\');
$gmaps->set_size(500, 400);
$gmaps->limit_baloons_to(2);
$gmaps->set_zoom_level(7);		

$gmaps->add_rectangle(array(
	\'bound1\'=>\'modena\',
	\'bound2\'=>\'padova\',
	\'in_baloon\'=>
		\'%LOCATION%<br /><b>Area:</b> %AREA% km<sup>2</sup><br />
		<b>Elevation</b>: %ELEVATION% m<br />
		<b>Lat:</b> %LAT%<br />
		<b>Lon:</b> %LON%\',
	\'strokeColor\'=>\'#00ff00\',
	\'strokeWeight\'=>7,
	\'strokeOpacity\'=>0.8,
	\'fillColor\'=>\'#00aa00\',
	\'fillOpacity\'=>0.3
));
// in meters
$dist = $gmaps->get_distance(\'padova\',\'verona\',\'driving\');
$gmaps->add_circle(array(
	\'center\'=>\'ponte san nicolo,padova\',
	\'in_baloon\'=>
		\'%LOCATION%<br /><b>Area</b>:%AREA% Km<sup>2</sup><br />
		<b>Elevation</b>: %ELEVATION% m<br />
		<b>Lat</b>: %LAT%<br />
		<b>Lon</b>: %LON%<br />
		Distance: \'.$dist[\'distance\'].\' m.\' ,
	\'radius\'=>19000,
	\'strokeColor\'=>\'#0000ff\',
	\'strokeWeight\'=>3,
	\'strokeOpacity\'=>0.3,
	\'fillColor\'=>\'#0000aa\',
	\'fillOpacity\'=>0.5
));		
$gmaps->add_circle(array(
	\'center\'=>\'verona\',
	\'in_baloon\'=>
		\'<b>Area</b>:%AREA% Km<sup>2</sup><br />
		<b>Elevation</b>: %ELEVATION% m<br />
		<b>Lat</b>: %LAT%<br />
		<b>Lon</b>: %LON%<br />\' ,
	\'radius\'=>28000,
	\'strokeColor\'=>\'#ff0000\',
	\'strokeWeight\'=>5,
	\'strokeOpacity\'=>0.9,
	\'fillColor\'=>\'#aa0000\',
	\'fillOpacity\'=>0.7
));		

echo $gmaps->render();	
';
include(realpath(dirname(__FILE__).'/../gmaps3simple.class.php'));
$gmaps = new gmaps3simple(
	array(
		'id'=>'map_new',
		'hash'=>array(
			'libraries'=>array('geometry')
		),'cache'=>false
	)
);
include(realpath(dirname(__FILE__).'/apikey.php'));
$gmaps->center_point('Padova, Italia');
$gmaps->set_size(500, 400);
$gmaps->limit_baloons_to(2);
$gmaps->add_rectangle(array(
	'bound1'=>'modena',
	'bound2'=>'padova',
	'in_baloon'=>'%LOCATION%<br /><b>Area:</b> %AREA% km<sup>2</sup><br /><b>Elevation</b>: %ELEVATION% m<br /><b>Lat:</b> %LAT%<br /><b>Lon:</b> %LON%',
	'strokeColor'=>'#00ff00',
	'strokeWeight'=>7,
	'strokeOpacity'=>0.8,
	'fillColor'=>'#00aa00',
	'fillOpacity'=>0.3
));
// in meters
$dist = $gmaps->get_distance('padova','verona','driving');
$gmaps->add_circle(array(
	'center'=>'ponte san nicolo,padova',
	'in_baloon'=>'%LOCATION%<br /><b>Area</b>:%AREA% Km<sup>2</sup><br /><b>Elevation</b>: %ELEVATION% m<br /><b>Lat</b>: %LAT%<br /><b>Lon</b>: %LON%<br />Distance (PD<-->VR): '.$dist['distance'].' m.' ,
	'radius'=>19000,
	'strokeColor'=>'#0000ff',
	'strokeWeight'=>3,
	'strokeOpacity'=>0.3,
	'fillColor'=>'#0000aa',
	'fillOpacity'=>0.5
));		
$gmaps->add_circle(array(
	'center'=>'verona',
	'in_baloon'=>'<b>Area</b>:%AREA% Km<sup>2</sup><br /><b>Elevation</b>: %ELEVATION% m<br /><b>Lat</b>: %LAT%<br /><b>Lon</b>: %LON%<br />' ,
	'radius'=>28000,
	'strokeColor'=>'#ff0000',
	'strokeWeight'=>5,
	'strokeOpacity'=>'0.9',
	'fillColor'=>'#aa0000',
	'fillOpacity'=>0.7
));		
		
		
$gmaps->set_zoom_level(7);

$script = $gmaps->render();

include('tpl.php');
