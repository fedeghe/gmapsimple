<?php
$title = 'Panel setting';
$intro = '
<p>The <code>edit_panel()</code> function lets You change some aspects of the panel</p>	
';
$sample_code = '
$gmaps = new gmaps3simple(array(\'id\'=>\'map_new\'));

$gmaps->center_point(\'Padova, Italia\');
$gmaps->edit_panel(
	array(
		\'panControl\'=>array(\'show\'=>true,\'position\'=>\'TOP_CENTER\'),
		\'zoomControl\'=>array(\'show\'=>true), //style:{\'SMALL\',\'LARGE\',\'DEFAULT\'}
		\'mapTypeControl\'=>array(\'show\'=>true), //style:{\'HORIZONTAL_BAR\',\'DROPDOWN_MENU\',\'DEFAULT\'}
		\'scaleControl\'=>array(\'show\'=>true),
		\'streetViewControl\'=>array(\'show\'=>true),
		\'overviewMapControl\'=>array(\'show\'=>true,\'opened\'=>true), // default is not opened
		\'navigationControl\'=>array(\'show\'=>true,\'style\'=>\'SMALL\') // style:{\'SMALL\',\'DEFAULT\'}
		/*
		moreover all elements but navigationControl can be positioned with the `position` option in
		{
			\'TOP_LEFT\', \'TOP_CENTER\', \'TOP_RIGHT\',
			\'LEFT_TOP\', \'RIGHT_TOP\',
			\'LEFT_CENTER\', \'RIGHT_CENTER\',
			\'LEFT_BOTTOM\', \'RIGHT_BOTTOM\',
			\'BOTTOM_LEFT\', \'BOTTOM_CENTER\', \'BOTTOM_RIGHT\'
		}
		*/
	)
);
$gmaps->set_size(500, 400);

$gmaps->set_zoom_level(7);                        

echo $gmaps->render();
';

include(realpath(dirname(__FILE__).'/../gmaps3simple.class.php'));
$gmaps = new gmaps3simple(array('id'=>'map_new','cache'=>false));
include(realpath(dirname(__FILE__).'/apikey.php'));
$gmaps->center_point('Padova, Italia');

$gmaps->edit_panel(
	array(
	'panControl'=>array('show'=>true,'position'=>'TOP_CENTER'),
	'zoomControl'=>array('show'=>true),
	'mapTypeControl'=>array('show'=>true),
	'scaleControl'=>array('show'=>true),
	'streetViewControl'=>array('show'=>true),
	'overviewMapControl'=>array('show'=>true,'opened'=>true),
	'navigationControl'=>array('show'=>true,'style'=>'SMALL')
	)
);

$gmaps->set_size(500, 400);

$gmaps->set_zoom_level(7);

$script = $gmaps->render();
	
include('tpl.php');
