<?php
$title = 'More options on points';
$intro = '
<p>More options on points:</p>
<ul>
	<li>the first point on click will execute a callback (which receives coordinates as params) and will be dropped down as far as the map loads (maybe You`ll have to "chrome-it" to see it smooth).</li>
	<li>the second point will be draggable and a callback will be executed at the "dragend" event fired when it will be released; as for `click` event, coordinates of the end point will be passed to it</li>
	<li>the mapTypeId is changed to SATELLITE</li>
</ul>
';
$sample_code='
$gmaps = new gmaps3simple(
	array(
		\'id\'=>\'map_new\',
		//\'cache\'=>true
	)
);
//if not set ROADMAP is used
$gmaps->set_map_genre(\'SATELLITE\'); 

$gmaps->center_point(\'Padova, Italia\');
$gmaps->limit_baloons_to(2);
$gmaps->add_point(
	array(
		array(
			\'verona\',
			\'verona\',
			array(
				\'baloon\'=>\'<div>Latitude: %LAT% <br /> Longitude: %LON%</div>\',
				\'zindex\'=>3,
				\'title\'=>\'just the title\',
				\'onclick\'=>\'function(lat, lon){	document.getElementById("debug").innerHTML ="Lat: "+lat+"<br/>Lon: "+lon;}\',
				\'animation\'=>\'DROP\'
			)
		),
		array(
			\'riccione\',
			\'riccione\',
			array(
				\'baloon\'=>\'<div>Cheers from %LOCATION% !!!<br /><br />:D<br /><br />drag me around!!<br />lat: %LAT%<br /> lon: %LON%}<br />elevation: %ELEVATION%</div>\',
				\'zindex\'=>3,
				\'title\'=>\'drag me around\',
				\'icon\'=>\'img/beachflag.png\',
				\'icon_size\'=>\'20,32\',
				\'shadow\'=>\'img/beachflag_shadow.png\',
				\'shadow_size\'=>\'37,32\',
				\'draggable\'=>\'function(lat,lon){document.getElementById("debug").innerHTML ="DRAG END:<br />Lat: "+lat+"<br />Lon: "+lon;}\',
			)
		)
	)

);
$gmaps->set_size(500, 400);
$gmaps->set_zoom_level(7);
echo $gmaps->render();
';

include(realpath(dirname(__FILE__).'/../gmaps3simple.class.php'));
$gmaps = new gmaps3simple(
	array(
		'id'=>'map_new',
		'cache'=>false
	)
);
include(realpath(dirname(__FILE__).'/apikey.php')); // runs $gmaps->set_api_key('A_VALID_API_KEY');
//if not set ROADMAP is used
$gmaps->set_map_genre('SATELLITE'); 

$gmaps->center_point('Bologna, Italia');
$gmaps->limit_baloons_to(2);
$gmaps->add_point(
	array(
		array(
			'verona',
			'verona',
			array(
				'baloon'=>'<div>Latitude: %LAT% <br /> Longitude: %LON%</div>',
				'zindex'=>3,
				'title'=>'just the title',
				'onclick'=>'function(lat, lon){	document.getElementById("debug").innerHTML ="Lat: "+lat+"<br/>Lon: "+lon;}',
				'animation'=>'DROP'
			)
		),
		array(
			'riccione',
			'riccione',
			array(
				'baloon'=>'<div>Cheers from %LOCATION% !!!<br /><br />:D<br /><br />drag me around!!<br />lat: %LAT%<br /> lon: %LON%}<br />elevation: %ELEVATION%</div>',
				'zindex'=>3,
				'title'=>'drag me around',
				'icon'=>'img/beachflag.png',
				'icon_size'=>'20,32',
				'shadow'=>'img/beachflag_shadow.png',
				'shadow_size'=>'37,32',
				'draggable'=>'function(lat,lon){document.getElementById("debug").innerHTML ="DRAG END:<br />Lat: "+lat+"<br />Lon: "+lon;}',
			)
		)
	)

);
$gmaps->set_size(500, 400);
$gmaps->set_zoom_level(7);
$script = $gmaps->render();
$before ='<div id="debug"></div>';
include('tpl.php');
