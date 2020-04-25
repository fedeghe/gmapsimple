<?php
$title = 'More points, some options';
$intro = '
<p>How to add a more than one point, and some options on points:
	<ul>
		<li>the first point remains basic</li>
		<li>the second one on click will execute a callback (which receives coordinates as params)</li>
		<li>the third one will present the baloon just opened and a particular marker</li>
	</ul>
	Note that the v3 of Google maps API allows to have more than one baloon opened; sometimes could be a good idea to limit<br />
	the number of opened baloons at the same time. To do that we use the <code>limit_baloons_to(int)</code> function.<br/>
	Finally we change the MapTypeId to HYBRID passing that string to <code>set_map_genre()</code> function.
</p>
<div id="debug"></div>	
';
$sample_code = '
$gmaps = new gmaps3simple(
	array(
		\'id\'=>\'map_new\',
		\'cache\'=>true, // this is the default, You can skip that
	)
);
//if not set ROADMAP is used
$gmaps->set_map_genre(\'HYBRID\'); 

$gmaps->center_point(\'Padova, Italia\');
$gmaps->limit_baloons_to(2);
$gmaps->add_point(
	array(
		array(
			\'padova\',
			\'padova\',
			array(
				\'baloon\'=>\'<div>Latitude: %LAT% <br /> Longitude: %LON%</div>\',
				\'zindex\'=>3,
				\'title\'=>\'just the title\',
			)
		),
		array(
			\'verona\',
			\'verona\',
			array(
				\'baloon\'=>\'<div>Latitude: %LAT% <br /> Longitude: %LON%</div>\',
				\'zindex\'=>3,
				\'title\'=>\'just the title\',
				\'onclick\'=>\'function(lat, lon){
					document.getElementById("debug").innerHTML("Lat: "+lat+"\nLon: "+lon);
				}\'	
			)
		),
		array(
			\'cesena\',
			\'cesena\',
			array(
				\'openbaloon\'=>\'<div style=\"color:#f00\">Cheers from Cesena !!! :D</div>\',
				\'zindex\'=>3,
				\'title\'=>\'just the title\',

				\'icon\'=>\'img/beachflag.png\',
				\'icon_size\'=>\'20,32\',
				\'shadow\'=>\'img/beachflag_shadow.png\',
				\'shadow_size\'=>\'37,32\',
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
		'id' => 'map_new',
		'cache' => true // this is the default, You can skip that
	)
);
include(realpath(dirname(__FILE__).'/apikey.php')); // runs $gmaps->set_api_key('A_VALID_API_KEY');
//if not set ROADMAP is used
$gmaps->set_map_genre('HYBRID'); 

$gmaps->center_point('Padova, Italia');
$gmaps->limit_baloons_to(2);
$gmaps->add_point(
	array(
		array(
			'padova',
			'padova',
			array(
				'baloon'=>'<div>Latitude: %LAT% <br /> Longitude: %LON%</div>',
				'zindex'=>3,
				'title'=>'just the title',
			)
		),
		array(
			'verona',
			'verona',
			array(
				'baloon'=>'<div>Latitude: %LAT% <br /> Longitude: %LON%</div>',
				'zindex'=>3,
				'title'=>'just the title',
				'onclick'=>'function(lat, lon){document.getElementById(\'debug\').innerHTML ="Lat: "+lat+"\nLon: "+lon;}'
			)
		),
		array(
			'cesena',
			'cesena',
			array(
				'openbaloon'=>'<div style=\"color:#f00\">Cheers from Cesena !!! :D</div>',
				'zindex'=>3,
				'title'=>'just the title',
				'icon'=>'img/beachflag.png',
				'icon_size'=>'20,32',
				'shadow'=>'beachflag_shadow.png',
				'shadow_size'=>'37,32',
			)
		)
	)

);
$gmaps->set_size(500, 400);
$gmaps->set_zoom_level(7);
$script = $gmaps->render();

include('tpl.php');
