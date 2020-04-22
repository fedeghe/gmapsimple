<?php
$title = 'Trial page';

$intro = '<h1>TRIAL</h1>';

$sample_code = '';
?>

<?php
include(realpath(dirname(__FILE__).'/../gmaps3simple.class.php'));
$gmaps = new gmaps3simple(array('id'=>'map_new','cache'=>false));

$gmaps->center_point('Padova, Italia');

$gmaps->add_direct_menu(
	'Select a location',
	array(
		array('label'=>'Cortina d`Ampezzo', 'group'=>'home', 'place'=>'cortina', 'baloon'=>'I really &hearts; Cortina', 'zoom'=>8),
		array('label'=>'Verona', 'place'=>'verona', 'baloon'=>'Cheers from Verona', 'zoom'=>3),
		array('label'=>'New York','group'=>'home', 'place'=>'new york', 'baloon'=>'I &hearts; NYC', 'zoom'=>13)
	),
	array('top'=>'10px', 'left'=>'10px', 'border'=>'1px solid black', 'backgroundColor'=>'white'),
	true
);
$gmaps->set_map_genre('ROADMAP');
$gmaps->set_size(500, 400);
$gmaps->set_zoom_level(7);
$script =  $gmaps->render();

include('tpl.php');
