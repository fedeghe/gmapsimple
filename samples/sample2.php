<?php
$title = 'Add a simple point';

$intro = '<p>How to add a simple point. As You can see in the baloon content You can use %LAT% %LON% placeholders.<br />
			Moreover we try to ask the user to allow us to get his position (only for browser that follow the <a href="http://dev.w3.org/geo/api/spec-source.html">W3C geolocation standard</a>)<br />
			As far as the user allow for location will be available a javascript function to get that info:<br />
<code>var i = get_initialLocation();</code><br />
<code>var lat = i.lat();</code><br />
<code>var lon = i.lng();</code></p>';

$sample_code = '
$gmaps = new gmaps3simple(
	array(
		\'id\'=>\'map_new\',
		\'hash\'=>array( // optional, sensor will be false by default
			\'sensor\'=>\'center\' //suggested by Charleson Reyes, ty Charleson
			/*
			>>> use \'true\'
			the position will be available in a few seconds after the confirmation
			and will be accessible with javascript through the function get_initialLocation();
			that will return an google.maps.LatLng object, try this on the console:
			#> var i = get_initialLocation();
			#> console.debug(i.lat(), i.lng());
			
			>>> use \'center\'
			the map will be automatically centered in the point (standing the user agreement)
			*/
		),
		\'cache\'=>true //optional: that script will be produced one time and used as it will exists
	)
 );

$gmaps->set_map_genre(\'ROADMAP\');   
$gmaps->center_point(\'Verona, Italia\');
$gmaps->add_point(
	\'padova\', // just a label , not so important, but be careful : must be unique
	\'padova\', // used for localization, here you can pass even an array(\'lat\'=>value, \'lon\'=>value)
	array( // the whole array is optional
		\'baloon\'=>\'<div>Latitude: %LAT% <br /> Longitude: %LON% <br /> Elevation: %ELEVATION%</div>\',
		\'zindex\'=>3,
		\'title\'=>\'just the title\',
	)
);

$gmaps->set_size(500, 400);
$gmaps->set_zoom_level(7);
                       
echo $gmaps->render();
';

include(realpath(dirname(__FILE__).'/../gmaps3simple.class.php'));
$gmaps = new gmaps3simple(
	array(
		'id' => 'map_new',
        'sensor' => 'true', // or 'center'
		'cache' => false
	)
);
include(realpath(dirname(__FILE__).'/apikey.php')); // runs $gmaps->set_api_key('A_VALID_API_KEY');
$gmaps->center_point('verona, Italia');
$gmaps->add_point(
	'padova',
	'padova',
	array(
		'baloon'=>'<div>Latitude: %LAT% <br /> Longitude: %LON%<br />Elevation: %ELEVATION%</div> ',
		'zindex'=>3,
		'title'=>'just the title',
	)
);
$gmaps->set_size(500, 400);
$gmaps->set_zoom_level(7);
$script = $gmaps->render();

include('tpl.php');
