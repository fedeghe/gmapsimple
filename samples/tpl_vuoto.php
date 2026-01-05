<?php
include(realpath(dirname(__FILE__).'/tpl_head_redirect.php'));
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
		<?php include('paypal.php'); ?>
	</body>
</html>
