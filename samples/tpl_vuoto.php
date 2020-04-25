<?php
define('BASE_URL', 'https://www.jmvc.org/gmaps3simple/samples/');
if(isSet($_GET['id']) && intval($_GET['id'])>0  && file_exists('sample'.intval($_GET['id']).'.php') ){ 
	header('Location: '.BASE_URL.'sample'.intval($_GET['id']).'.php');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title><?php  echo $title;  ?></title>   
		<link rel="stylesheet" type="text/css" href="css/style.css"/>
	</head>
	<body>
		
		<?php include('sample_list.php'); ?>
		<hr />
		<?php  echo $body;  ?> 
		<hr />
		<?php include('pp.php'); ?>
	</body>
</html>
