<?php
//echo '<pre>'.print_r($_SERVER, true).'</pre>';
$labels = array(
	'Basic map'=>'sample1.php',
	'Add a simple point'=>'sample2.php',
	'More points, some options'=>'sample3.php',
	'More options on points'=>'sample4.php',
	'Style your map'=>'sample5.php',
	'Panel setting'=>'sample6.php',
	'More UI settings'=>'sample6b.php',
	'Trace paths'=>'sample7.php',
	'Add polylines & polygons'=>'sample9.php',
	'Add circles and rectangles'=>'sample10.php',
	// 'Add Panoramio Layer'=>'sample11.php',
	// 'Add Flickr Layer'=>'sample11b.php',
	'More layers: traffic, bycicle'=>'sample12.php',
	'Get places'=>'sample13.php',
	'Something more on places'=>'sample13b.php',
	'Add a custom fixed info window'=>'sample14.php',
	'Inner jump menu'=>'sample14a.php',
	'Reverse geocoding'=>'sample14b.php',
	'Something about cache'=>'sample15.php',
	'About'=>'whoami.php'
);
$pages = array();
$i=1;
foreach($labels as $label => $url){
	$pages[($i++).'> '.$label] = BASE_URL.$url;
}

$select = '<select id="sample_select" style="font-size: 15px" onchange="if(this.value!==\'\')document.location.href=this.value;">';

$select .= '<option value=""> &lArr; pick a sample &rArr; </option>';
$sel =false;
$i = 0;
$prev = false;
$next = false;

foreach($pages as $label => $url){
	$s = ((!$sel && $url==$_SERVER['SCRIPT_NAME']) && $sel=true)? ' selected="selected"':false;
	$select.='<option value="'.$url.'"'.$s.'>'.$label.'</option>';
	if($s){
		$urls = array_values($labels);
		$prev = array_key_exists($i-1, $urls) ? $urls[$i-1] : false;
		$next = array_key_exists($i+1, $urls) ? $urls[$i+1] : false;
	}
	$i++;
}
$select .= '<option value="">more to come!!! :D </option>';
$select .='</select>';

// echo '<pre>' . print_r($_SERVER, true) . '</pre>';

?>
<span style="font-size: 15px">List of samples:</span><?php echo $select; ?>
<?php
if($prev || $next)echo '<br />';
if($prev)echo '<a href="'.$prev.'">&lArr; previous sample</a>';
if($prev && $next)echo ' | ';
if($next)echo '<a href="'.$next.'">next sample &rArr;</a>';
?>
<div style="position:absolute;right:0px;top:0px">
    <a href="https://github.com/fedeghe/gmapsimple">
        <img width="149" height="149" src="https://github.blog/wp-content/uploads/2008/12/forkme_right_green_007200.png?resize=149%2C149" class="attachment-full size-full" alt="Fork me on GitHub" data-recalc-dims="1">
    </a>
</div>