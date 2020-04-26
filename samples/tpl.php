<?php
include(realpath(dirname(__FILE__).'/tpl_head_redirect.php'));
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php  echo $title;  ?></title>   
		<link rel="stylesheet" type="text/css" href="css/style.css"/>
		<?php if(isSet($jquery) && $jquery){?>
			<script type="text/javascript" src="jquery-1.6.4.min.js"></script>
				
		<?php
		}
		?>
	</head>
	<body>
		<?php include('sample_list.php'); ?>
		<hr />
		<h1><?php  echo $title;  ?></h1>   
		<?php echo $intro; ?>
		<br />
		<?php echo $script; ?>     
		<?php echo isSet($before)?$before:''; ?> 
		<div>
		   	<div id="map_new" style="width:500px; height:400px; border:3px solid black"></div>
		</div>  
		<div id="code_sample"><?php highlight_string('  
<?php
include(\'gmaps3simple.class.php\');
'.$sample_code.'
?>
<div style="position:relative;">
	<div id="map_new" style="width:500px; height:400px; border:3px solid black"></div>
</div>');
		?>
		</div>
		
		
		
		<div class="clearer">&nbsp;</div>
		
		<?php echo isSet($after) ? $after : ''; ?> 
		<div id="directions-panel"></div>
		<hr />
		
		<?php include('paypal.php'); ?>
		
		
		<?php include('analytics.php'); ?>
		
	</body>
</html>
