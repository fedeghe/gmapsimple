<?php
include('../../gmaps3simple.class.php');

$gmaps = new gmaps3simple(
    array(
        'id'=>'map_new3',
        'cache'=>true //optional: that script will be produced one time and used as it will exists
    )
 );

$gmaps->set_map_genre('ROADMAP');   
$gmaps->center_point('Verona, Italia');
$gmaps->add_point(
    'padova', // just a label , not so important, but be careful : must be unique
    'padova', // used for localization, here you can pass even an array('lat'=>value, 'lon'=>value)
    array( // the whole array is optional
        'baloon'=>'<div>ON THE IFRAME I SAY<br />Latitude: %LAT% <br /> Longitude: %LON%</div>',
        'zindex'=>3,
        'title'=>'just the title',
    )
);

$gmaps->set_size(300, 200);
$gmaps->set_zoom_level(7);
                       
echo $gmaps->render();

?>
<div style="position:relative;">
    <div id="map_new3" style="width:300px; height:200px; border:3px solid black"></div>
</div>