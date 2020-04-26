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
include(realpath(dirname(__FILE__).'/cacheFlag.php'));
$gmaps = new gmaps3simple(array('id'=>'map_new','cache'=>true));
include(realpath(dirname(__FILE__).'/apikey.php')); // runs $gmaps->set_api_key('A_VALID_API_KEY');
$gmaps->center_point('Padova, Italia');

$gmaps->edit_panel(
	array(
    'zoomControl' => array('show' => true, 'position' => 'TOP_CENTER', 'style' => 'SMALL'),
    'mapTypeControl' => array(
        'show' => true,
        'position' => 'BOTTOM_CENTER',
        'style' => 'HORIZONTAL_BAR',
        // 'mapTypeIds' => array('roadmap', 'satellite', 'hybrid', 'terrain')
    ),
    
    'scaleControl' => array('show' => true),
    
	'fullscreenControl' => array('show' => true, 'position' => 'BOTTOM_LEFT'),
	'streetViewControl' => array('show' => true),
	'rotateControl' => array('show' => false)
    )
);

$gmaps->set_size(500, 400);

$gmaps->set_zoom_level(7);

$script = $gmaps->render();
	
include('tpl.php');
