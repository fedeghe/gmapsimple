<?php
$title = 'Insert a direct jump menu';

$intro = '<p>
	The function <code>add_direct_menu()</code> allows You to add a jump menu to a number of places.<br />You can even add a `group` keyed value and it will be used to label a optgroup.
</p>';

$sample_code = '
$gmaps = new gmaps3simple(array(\'id\'=>\'map_new\'));

$gmaps->center_point(\'Padova, Italia\');

$gmaps->add_direct_menu(
	\'Select a location\',
	array(
		array(
			\'label\'=>\'Cortina d`ampezzo\',
			\'place\'=>\'cortina\',
			\'baloon\'=>\'I really &hearts; Cortina\',
			\'zoom\'=>8, 
			\'group\'=>\'Italy\'
		),
		array(
			\'label\'=>\'Verona\',
			\'place\'=>\'verona\',
			\'baloon\'=>\'Cheers from Verona\',
			\'zoom\'=>3, 
			\'group\'=>\'Italy\'
		),
		array(
			\'label\'=>\'New York\',
			\'place\'=>\'new york\',
			\'baloon\'=>\'I &hearts; NYC\',
			\'zoom\'=>13
		)
	),
	array(\'top\'=>\'10px\', \'left\'=>\'10px\', \'border\'=>\'1px solid black\', \'backgroundColor\'=>\'white\')
);
$gmaps->set_map_genre(\'ROADMAP\');
$gmaps->set_size(500, 400);
$gmaps->set_zoom_level(7);
$script =  $gmaps->render();
';
?>

<?php
include(realpath(dirname(__FILE__).'/../gmaps3simple.class.php'));
$gmaps = new gmaps3simple(array('id'=>'map_new','cache'=>false));
include(realpath(dirname(__FILE__).'/apikey.php'));
$gmaps->center_point('Padova, Italia');

$gmaps->add_direct_menu(
	'Select a location',
	array(
		array('label'=>'Lugano', 'place'=>'lugano, via faggi 18', 'baloon'=>'I &hearts; in Lugano', 'zoom'=>15),
		array('label'=>'Cortina d`Ampezzo', 'place'=>'cortina', 'baloon'=>'%LAT%<img src=\"https://encrypted-tbn3.google.com/images?q=tbn:ANd9GcSxYyh_m6kNO1VValK_flHjK4nPtbfM-_o1QGoE_0KQ9Ywf8Gza\" /><br />I really &hearts; Cortina', 'zoom'=>8, 'group'=>'Italy'),
		array('label'=>'Verona', 'place'=>'verona', 'baloon'=>'Cheers from Verona', 'zoom'=>3, 'group'=>'Italy'),
		array('label'=>'New York', 'place'=>'new york', 'baloon'=>'I &hearts; NYC', 'zoom'=>13)
	),
	array('top'=>'10px', 'left'=>'10px', 'border'=>'1px solid black', 'backgroundColor'=>'white')
);
$gmaps->set_map_genre('ROADMAP');
$gmaps->set_size(500, 400);
$gmaps->set_zoom_level(7);
$script =  $gmaps->render();

include('tpl.php');
