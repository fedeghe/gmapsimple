<?php
$title = 'More UI setting';
$intro = '
<p>We feel so cruel that we want to disable some UI interactions enabled by default, in particular we want to:
	<ul>
		<li>disable zoom-in on double click</li>
		<li>disable drag events for moving around (if we even decide to not show the panControl, the map will not move!)</li>
		<li>disable keyboards shortcuts</li>
		<li>limit the zoom range to a subset, we say [3, 6]</li>
		<li>disable scrollwheel to zoom-in and zoom-out</li>
	</ul>
	The cruel solution is really handy! :D
</p>	
';
$sample_code = '
$gmaps = new gmaps3simple(
	array(
		\'id\'=>\'map_new\',
		\'options\'=>array(
			\'disableDoubleClickZoom\'=>true,
			\'draggable\'=>false,
			\'keyboardShortcuts\'=>false,
			\'minZoom\'=>3,
			\'maxZoom\'=>6,
			\'scrollwheel\'=>false
		),
		\'cache\'=>true
	)
);

$gmaps->center_point(\'Padova, Italia\');
$gmaps->edit_panel(
	array(
		\'panControl\'=>array(\'show\'=>true,\'position\'=>\'TOP_CENTER\'),
		\'zoomControl\'=>array(\'show\'=>true), //style:{\'SMALL\',\'LARGE\',\'DEFAULT\'}
	)
);
$gmaps->set_size(500, 400);

$gmaps->set_zoom_level(6);                        

echo $gmaps->render();	
';
include(realpath(dirname(__FILE__).'/../gmaps3simple.class.php'));
include(realpath(dirname(__FILE__).'/cacheFlag.php'));
$gmaps = new gmaps3simple(
	array(
		'id'=>'map_new',
		'options'=>array(
			'disableDoubleClickZoom'=>true,
			'draggable'=>false,
			'keyboardShortcuts'=>false,
			'minZoom'=>3,
			'maxZoom'=>6,
			'scrollwheel'=>false
		),
		'cache'=>$cache
	)
);
include(realpath(dirname(__FILE__).'/apikey.php')); // runs $gmaps->set_api_key('A_VALID_API_KEY');
$gmaps->center_point('Padova, Italia');

$gmaps->edit_panel(
	array(
		'panControl'=>array('show'=>true,'position'=>'TOP_CENTER'),
		'zoomControl'=>array('show'=>true)
	)
);

$gmaps->set_size(500, 400);

$gmaps->set_zoom_level(6);

$script = $gmaps->render();
		
include('tpl.php');
