<?php
$title = 'Places placeholders';
$intro ='<p>Moreover now you can specify the content of the baloon using all the following placeholders:</p>
<ul>
  <li>"%name%"</li>
 <li>"%rating%"</li>
 <li>"%url%"</li>
 <li>"%website%"</li>
 <li>"%html_attributions%"</li>
 <li>"%formatted_phone_number%"</li>
 <li>"%formatted_address%"</li>
 <li>"%types%"</li>
 </ul>
';
$sample_code = '
$gmaps = new gmaps3simple(array(
    \'id\' => \'map_new\',
    \'hash\' => array(
        \'libraries\' => array(\'places\')
    )
));

$gmaps->edit_panel(
	array(
		\'zoomControl\' => array(\'show\' => true),
		\'mapTypeControl\' => array(\'show\' => true),
		\'navigationControl\' => array(\'show\' => true)
	)
);

// this is the radius of research in meters -------------.	
//                                                       v
$gmaps->view_places(array(\'store\',\'massages\'), \'Miami\', 100, "name: %name%<br>rating: %rating%<br>url: %url%<br>website: %website%<br>html_attributions: %html_attributions%<br>formatted_phone_number: %formatted_phone_number%<br>formatted_address: %formatted_address%<br>types: %types%");

$gmaps->center_point(\'Miami, Florida, United States\');

$gmaps->set_size(800, 400);

$gmaps->set_zoom_level(17);

echo $gmaps->render();	
';

include(realpath(dirname(__FILE__).'/../gmaps3simple.class.php'));

$gmaps = new gmaps3simple(array(
    'id' => 'map_new',
    'hash' => array(
        'libraries' => array(
            'places'
        )
    ),
    'cache' => false
));
include(realpath(dirname(__FILE__).'/apikey.php')); // runs $gmaps->set_api_key('A_VALID_API_KEY');
$gmaps->edit_panel(
	array(
		'zoomControl' => array('show' => true),
		'mapTypeControl' => array('show' => true),
		'navigationControl' => array('show' => true)
	)
);
$gmaps->view_places(array('store','massages'), 'Miami', 100, "name: %name%<br>rating: %rating%<br>url: %url%<br>website: %website%<br>html_attributions: %html_attributions%<br>formatted_phone_number: %formatted_phone_number%<br>formatted_address: %formatted_address%<br>types: %types%");
$gmaps->center_point('Miami,Florida, United States');
$gmaps->set_size(800, 400);
$gmaps->set_zoom_level(17);
$script = $gmaps->render();  
include('tpl.php');
