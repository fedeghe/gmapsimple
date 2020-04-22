<?php
$title = 'Trace Paths';
$intro = '
	<p>Trace a path between two points, is possible to specify waypoints and some options likes avoids and draggable path;<br />
		at this time I found "some" problems to make the steps change when the path is modified by dragging,<br />
		but I`m sure to find a way to get it working soon!!!
		<span style="color:red">I`m working on that</span>
	</p>
';
$sample_code = '
$gmaps = new gmaps3simple(array(\'id\'=>\'map_new\'));

$gmaps->center_point(\'Padova, Italia\');

$gmaps->set_size(500, 400);
$gmaps->limit_baloons_to();
$gmaps->set_route_panel(\'directions-panel\');   /// NEW, optional used to show driving directions
$gmaps->add_route(
	\'padova\',
	\'bologna\',
	array(
		\'show_steps\' => true,
		\'way_points\' => array(\'ferrara\', \'modena\'),
		\'draggable_points\' => true,
		\'avoids\'=>array(\'tolls\', \'highways\'),
		\'stroke\'=>array(
			\'strokeColor\'=>\'#f00\',
			\'strokeOpacity\'=>\'0.7\',
			\'strokeWeight\'=>\'20\'
		)
		
	)
);

$gmaps->add_route(
	\'genova\',
	\'roma\',
	array(
		\'show_steps\' => true,
		\'way_points\' => array(\'firenze\')
	)
);
$gmaps->set_zoom_level(7);

$script = $gmaps->render();	
';
include(realpath(dirname(__FILE__).'/../gmaps3simple.class.php'));
$gmaps = new gmaps3simple(array('id'=>'map_new','cache'=>false));
include(realpath(dirname(__FILE__).'/apikey.php')); // runs $gmaps->set_api_key('A_VALID_API_KEY');
$gmaps->center_point('Padova, Italia');

$gmaps->set_size(500, 400);
$gmaps->limit_baloons_to();
$gmaps->set_route_panel('directions-panel');
$gmaps->add_route(
	'padova',
	'bologna',
	array(
		'show_steps' => true,
		'way_points' => array('ferrara', 'modena'),
		'draggable_points' => true,
		'avoids'=>array('tolls', 'highways'),
		'stroke'=>array(
			'strokeColor'=>'#f00',
			'strokeOpacity'=>'0.7',
			'strokeWeight'=>'20'
		)
			
	)
);


$gmaps->add_route(
	'genova',
	'roma',
	array(
		'show_steps' => true,
		'way_points' => array('firenze')
	)
);
$gmaps->set_zoom_level(7);

$script = $gmaps->render();
	

include('tpl.php');
