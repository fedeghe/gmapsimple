<?php
$title = 'Add polylines & polygons';
$intro = '
<p>
	In this example we want to
	<ul>
		<li>trace a soft red polyline knotting on three cities.</li> 
		<li>trace a polygon similar to Sicily silhouette get area info</li>
		<li>every time a marker is clicked, the map canters o that point (<code>center_on_click()</code>)</li>
	</ul>
	The <code>center_on_click()</code> function accepts an optional parameter:
	<ul>
		<li>false: this is the defaut, the setCenter() function will be used, the movement will be quick</li>
		<li>true: the setPan() function will be used, the movement will a smooth animation</li>
		<li>an integer: the setcenter() function will be used, and will be used the setZoom() function too, using the passed value as new zoom evel; not smooth</li>
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
	array(
		\'id\'=>\'map_new\',
		\'hash\'=>array(
			\'libraries\'=>array(
				\'geometry\'
			)
		),
		\'cache\'=>true
	)
);

$gmaps->center_point(\'Roma, Italia\');

$gmaps->set_size(500, 400);

//this MUST be called before trace any point
$gmaps->center_on_click(true);

/**
MUST BE CALLER BEFORE
Note: if you limit the number of visible baloons, the last
clicked baloon zindex will be automatically maximized
*/
$gmaps->limit_baloons_to(2);
// a polyline
$gmaps->add_polyline(array(
		array(\'label1\',\'albignasego\', array(\'title\'=>\'casa\', \'baloon\'=>\'&hearts;  home  %LOCATION%<\')),
		array(\'label2\',\'verona\', array(\'title\'=>\'zeppelin\', \'baloon\'=>\'<strong>%LAT% %LON%</strong>\')),
		array(\'label3\',\'roma\', array(\'title\'=>\'aquarius\', \'baloon\'=>\'hei U!  %ELEVATION%< \')),
	),
	array(
		\'strokeColor\'=> "#00ff00",
		\'strokeOpacity\'=> 1.0,
		\'strokeWeight\'=> 6
	)
);
//now add a polygon
$gmaps->add_polygon(
	array(
		array(\'p1\',\'messina,sicilia\', array(\'title\'=>\'just a title\', \'baloon\'=>\'Messina : %LAT% %LON%\')),
		array(\'p2\',\'palermo, sicilia\', array(\'title\'=>\'just a title\', \'baloon\'=>\'Palermo\')),
		array(\'p3\',\'trapani,sicilia\', array(\'title\'=>\'just a title\', \'baloon\'=>\'Trapani\')),
		array(\'p4\',\'marsala,sicilia\', array(\'title\'=>\'just a title\', \'baloon\'=>\'Marsala\')),
		array(\'p5\',\'pachino,sicilia\', array(\'title\'=>\'just a title\', \'baloon\'=>\'Pachino\')),
		array(\'p6\',\'siracusa,sicilia\', array(\'title\'=>\'just a title\', \'baloon\'=>\'Siracusa\')),
		array(\'p7\',\'catania,sicilia\', array(\'title\'=>\'just a title\', \'baloon\'=>\'Catania\')),
	),
	array(
		\'strokeColor\'=> \'#00ff00\',
		\'strokeOpacity\'=> 0.7,
		\'strokeWeight\'=> 5,
		\'fillColor\'=> \'#00ff00\',
		\'fillOpacity\'=> 0.2
	),
	\'Location: %LOCATION%<br />Sicily Area : %AREA%<br />Elevation: %ELEVATION% m<br />pos:(%LAT% ; %LON%)\'
);

//zoom out a bit 
$gmaps->set_zoom_level(5);

echo $gmaps->render();
';
include(realpath(dirname(__FILE__).'/../gmaps3simple.class.php'));
$gmaps = new gmaps3simple(
	array('id'=>'map_new',
		'hash'=>array(
			'libraries'=>array('geometry')
		),
		'cache'=>false
	)
);
include(realpath(dirname(__FILE__).'/apikey.php')); // runs $gmaps->set_api_key('A_VALID_API_KEY');
$gmaps->center_point('Roma, Italia');
$gmaps->set_size(500, 400);
$gmaps->limit_baloons_to(2);
$gmaps->center_on_click(true);
$gmaps->add_polyline(array(
		array('label1','albignasego', array('title'=>'casa', 'baloon'=>'&hearts; home %LOCATION%')),
		array('label2','verona', array('title'=>'just a title', 'baloon'=>'<strong>%LAT% %LON%</strong>')),
		array('label3','roma', array('title'=>'just a title', 'baloon'=>'hei U! %ELEVATION%')),
	),
	array(
		'strokeColor'=> "#ff0000",
		'strokeOpacity'=> 0.3,
		'strokeWeight'=> 6
	)
);

$gmaps->add_polygon(
	array(
		array('p1','messina,Italia', array('title'=>'just a title', 'baloon'=>'Messina : %LAT% %LON%')),
		array('p2','palermo,Italia', array('title'=>'just a title', 'baloon'=>'Palermo %LOCATION%')),
		array('p3','trapani,Italia', array('title'=>'just a title', 'baloon'=>'Trapani %ELEVATION%')),
		array('p4','marsala,Italia', array('title'=>'just a title', 'baloon'=>'Marsala %LOCATION%')),
		array('p5','pachino,Italia', array('title'=>'just a title', 'baloon'=>'Pachino %ELEVATION%')),
		array('p6','siracusa,Italia', array('title'=>'just a title', 'baloon'=>'Siracusa %LOCATION%'))
	),
	array(
		'strokeColor'=> "#00ff00",
		'strokeOpacity'=> 0.7,
		'strokeWeight'=> 5,
		'fillColor'=> '#00ff00',
		'fillOpacity'=> 0.2
	),
	'Location: %LOCATION%<br />Sicily Area : %AREA% Km<sup>2</sup><br />Elevation: %ELEVATION% m<br />pos:(%LAT% ; %LON%)'
);

$gmaps->set_zoom_level(5);

$script = $gmaps->render();

include('tpl.php');
