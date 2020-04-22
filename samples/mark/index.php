<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>hi mark</title>   
		<link rel="stylesheet" type="text/css" href="http://www.freakstyle.it/classes/gmapsv3/css/style.css"/>
		<script type="text/javascript" src="http://www.freakstyle.it/classes/gmapsv3/samples/jquery-1.4.2.min.js"></script>
		<script type="text/javascript">
			jQuery(
				function(){
					
					//popup
					jQuery('#pop').bind('click',function(){
						window.open('http://www.freakstyle.it/classes/gmapsv3/samples/mark/pop.php','a title here','width=520,height=340');
					});
					
					
					//iframe
					jQuery('#ifr').bind('click',function(){
						jQuery('#target_iframe')
							.css({'width':'350px','height':'250px'})
							.attr('src', "http://www.freakstyle.it/classes/gmapsv3/samples/mark/ifr.php");
					});
					
					
					//div
					jQuery('#div').bind('click',function(){
						jQuery('#target_div').show(initialize);
					});
					
					
				});
		</script>
	</head>
	<body>
		<input type="button" value="open popup win" id="pop" />
		<input type="button" value="use iframe" id="ifr" />
		<input type="button" value="fill div content" id="div" />
		<hr />
		
		<div style="float:left; display:none" id="target_div"><?php include('div.php'); ?></div>
		<iframe src="" width="0" height="0" frameborder="0" id="target_iframe">
		  <p>Your browser does not support iframes.</p>
		</iframe> 
		
		<div class="clearer">&nbsp;</div>	
	</body>
</html>
