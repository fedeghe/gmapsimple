<?php
include('style_elements.php');
$title = 'Style Your map!!!';
$intro = '
		<p>You can personalize the map colors within the constructor, making any number of non repeated rules
			that specify a <i>featureType</i> , an <i>elementType</i> and <i>stylers</i> specification array.<br />
			Refer to <a href="#bottom">the bottom of that page</a> for a quick reference on <i>featureType</i> and <i>elementType</i> possible values.
		</p>	
';
$sample_code = '
$gmaps = new gmaps3simple(
	array(
		\'id\'=>\'map_new\',
		\'styles\'=>array(
			\'all\'=>array(
				\'style\'=>array(
					\'saturation\'=>100,
					\'hue\'=>"#000000",
					\'gamma\'=>1.0
				),
				\'element\'=>\'labels\'
			),
			\'road\'=>array(
				\'style\'=>array(
					\'saturation\'=>-100,
					\'lightness\'=>-100
				),
				\'element\'=>\'geometry\'
			),
			\'water\'=>array(
				\'style\'=>array(
					\'lightness\'=>0,
					\'saturation\'=>100,
					\'hue\'=>"#00ff00",
					\'gamma\'=>1.0
				),
				\'element\'=>\'geometry\'
			)
		),
		\'cache\'=>false
	)
);

$gmaps->edit_panel(
	array(
		\'panControl\'=>array(\'show\'=>true),
		\'zoomControl\'=>array(\'show\'=>true),
		\'mapTypeControl\'=>array(\'show\'=>true),
		\'scaleControl\'=>array(\'show\'=>true),
		\'streetViewControl\'=>array(\'show\'=>true),
		\'overviewMapControl\'=>array(\'show\'=>true),
		\'navigationControl\'=>array(\'show\'=>true)
	)
);

// note that styles are not applied in ROADMAP mode
$gmaps->set_map_genre(\'HYBRID\'); 

$gmaps->center_point(\'Padova, Italia\');

$gmaps->add_point(
	\'padova,italia\',
	\'padova\',
	array(
		\'baloon\'=>\'<div style="color:red">Here`s Padova !!! :D</div>\',
		\'zindex\'=>3,
		\'title\'=>\'just the title\',
		\'draggable\'=>\'function(lat,lon){document.getElementById(\\\'debug\\\').innerHTML ="DRAG END:<br />Lat: "+lat+"<br />Lon: "+lon;}\',
	)
);
$gmaps->set_size(500, 400);
$gmaps->set_zoom_level(7);
echo $gmaps->render();
';
include(realpath(dirname(__FILE__).'/../gmaps3simple.class.php'));
$gmaps = new gmaps3simple(
	array(
		'id'=>'map_new',
		'styles'=>array(

			'all'=>array(
				'style'=>array(
					'saturation'=>100,
					'hue'=>"#000000",
					'gamma'=>1.0
				),
				'element'=>'labels'
			),
			'road'=>array(
				'style'=>array(
					'saturation'=>-100,
					'lightness'=>-100
				),
				'element'=>'geometry'
			),
			'water'=>array(
				'style'=>array(
					'lightness'=>0,
					'saturation'=>100,
					'hue'=>"#00ff00",
					'gamma'=>1.0
				),
				'element'=>'geometry'
			)
		),
		'cache'=>true
	)
);
include(realpath(dirname(__FILE__).'/apikey.php'));

$gmaps->edit_panel(
	array(
		'panControl'=>array('show'=>true),
		'zoomControl'=>array('show'=>true),
		'mapTypeControl'=>array('show'=>true),
		'scaleControl'=>array('show'=>true),
		'streetViewControl'=>array('show'=>true),
		'overviewMapControl'=>array('show'=>true),
		'navigationControl'=>array('show'=>true)
	)
);

//if not set ROADMAP is used
$gmaps->set_map_genre('HYBRID'); 

$gmaps->center_point('Padova, Italia');

$gmaps->add_helper('reverseGC');

$gmaps->add_point(
	'padova,italia',
	'padova',
	array(
		'baloon'=>'<div style="color:red">Here`s Padova !!! :D</div>',
		'zindex'=>3,
		'title'=>'just the title',
		'icon'=>'img/beachflag.png',
		'icon_size'=>'20,32',
		'shadow'=>'img/beachflag_shadow.png',
		'shadow_size'=>'37,32',
		'draggable'=>'function(lat,lon){document.getElementById(\'debug\').innerHTML ="DRAG END:<br />Lat: "+lat+"<br />Lon: "+lon;}',
		//'onclick'=>'function(lat,lng){reverse(lat, lng, function(resp){console.debug(resp);})}'
	)
);
$gmaps->set_size(500, 400);
$gmaps->set_zoom_level(7);
$script = $gmaps->render();


$list1 ='';
foreach($featureType as $label => $ft) $list1.= '<li><label style="font-weight:bold">'.$label.'</label><br /><label>'.$ft.'</label></li>';
$list2 ='';
foreach($elementType as $label => $ft) $list2.= '<li><label style="font-weight:bold">'.$label.'</label><br /><label>'.$ft.'</label></li>';
$after = '<a name="bottom"></a>
		<br />
		<hr />
		<table align="center">
			<tr valign="top">
				<td>
					<p>
						<h3>featureType</h3>
						<ul class="ft_et">'.$list1.'</ul>
					</p>
				</td>
				<td>
					<p>
						<h3>elementType</h3>
						<ul class="ft_et">'.$list2.'</ul>
					</p>
				</td>
			</tr>
		</table>';
$before = '<div id="debug"></div>';
include('tpl.php');
