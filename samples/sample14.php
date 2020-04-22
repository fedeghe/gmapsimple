<?php
$title = 'Custom inner fixed div';
$intro = '
<p>Suppose us to need to :
	<ul>
		<li>have a inner position-fixed div where to write some info.</li>
		<li>have another one of thoose divs that is visible only when a marker is clicked</li>
	</ul>
	Get a look at that sample to find out how easy can be to do that using the <code>add_inner_div()</code> function.
</p>
';
$sample_code='
$gmaps = new gmaps3simple(array(\'id\'=>\'map_new\'));

$gmaps->center_point(\'Padova, Italia\');
$gmaps->add_inner_div(
	\'info_div\',
	array(\'top\'=>\'10px\', \'left\'=>\'10px\', \'width\'=>\'150px\', \'height\'=>\'28px\',
		\'border\'=>\'1px solid black\', \'background-color\'=>\'#ffffaa\',
		\'padding\'=>\'5px\', \'font-size\'=>\'10px\', \'font-family\'=>\'verdana,sans\'
	),
	true //this infodiv will not be shown until the marker is clicked
);	
$gmaps->add_inner_div(
	\'info_div2\',
	array(\'top\'=>\'10px\', \'right\'=>\'10px\', \'width\'=>\'150px\', \'height\'=>\'28px\',
		\'border\'=>\'2px dotted red\', \'background-color\'=>\'transparent\',
		\'padding\'=>\'5px\', \'font-size\'=>\'10px\', \'font-family\'=>\'verdana,sans\'
	)
);	

$gmaps->add_point(
	\'padova\',
	\'padova\',
	array(
		\'baloon\'=>\'<div>Latitude: %LAT% <br /> Longitude: %LON%</div>\',
		\'zindex\'=>3,
		\'title\'=>\'just the title\',
		// when is clicked, our callback executes receiving coordinates as parameters
		\'onclick\'=>\'function(la,lo){
			var div1 = document.getElementById("info_div");
			var div2 = document.getElementById("info_div2");
			div1.style.display = "block";
			div1.innerHTML = "lat: "+la+"<br />lon: "+lo;
			div2.style.backgroundColor = "white";
			div2.style.border = "2px solid red";
		}\',
		\'draggable\'=>\'function(lat,lon){
			document.getElementById("info_div2").innerHTML =
				"Dragged to<br>Lat:"+lat+"<br />Lon:"+lon;
		}\'
	)
);
		
$gmaps->set_size(500, 400);

$gmaps->set_zoom_level(7);                        

echo $gmaps->render();	
';
include(realpath(dirname(__FILE__).'/../gmaps3simple.class.php'));
$gmaps = new gmaps3simple(array('id'=>'map_new','cache'=>false));
include(realpath(dirname(__FILE__).'/apikey.php')); // runs $gmaps->set_api_key('A_VALID_API_KEY');
$gmaps->center_point('Padova, Italia');
$gmaps->add_inner_div(
	'info_div',
	array('top'=>'10px', 'left'=>'10px', 'width'=>'150px', 	'height'=>'28px',
		'border'=>'1px solid black', 'background-color'=>'#ffffaa',
		'padding'=>'5px', 'font-size'=>'10px', 'font-family'=>'verdana,sans'
	),
	true
);	
$gmaps->add_inner_div(
	'info_div2',
	array('top'=>'10px', 'right'=>'10px', 'width'=>'150px', 	'height'=>'45px',
		'border'=>'2px dotted red', 'background-color'=>'transparent',
		'padding'=>'5px', 'font-size'=>'10px', 'font-family'=>'verdana,sans'
	)
);	

$gmaps->add_point(
	'padova',
	'padova',
	array(
		'baloon'=>'<div>Latitude: %LAT% <br /> Longitude: %LON%</div>',
		'zindex'=>3,
		'title'=>'just the title',
		'onclick'=>'function(la,lo){
			var div1 = document.getElementById("info_div");
			var div2 = document.getElementById("info_div2");
			div1.style.display = "block";
			div1.innerHTML = "lat: "+la+"<br />lon: "+lo;
			div2.innerHTML="NOW.. drag the marker somewhere!";
			div2.style.backgroundColor = "white";
			div2.style.border = "2px solid red";
		}',
		'draggable'=>'function(lat,lon){document.getElementById("info_div2").innerHTML="Dragged to<br>Lat:"+lat+"<br />Lon:"+lon;}'
	)
);

$gmaps->set_size(500, 400);

$gmaps->set_zoom_level(7);

$script = $gmaps->render();

include('tpl.php');
