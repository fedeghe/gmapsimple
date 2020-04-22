<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/*
Chiave:
150b6d717e986dfcb3ee162b77a6c808

Segreto:
d026ec51852efe1f 
 * 
 */


/*
 $.getJSON(
	'http://api.flickr.com/services/rest/?&;method=flickr.photos.geo.getLocation&api_key=' + apiKey + '&photo_id=' + photoID + '&format=json&jsoncallback=?',
	function(data){
		//if the image has a location, build an html snippet containing the data
		if(data.stat != 'fail') {
			pLocation = '<a href="http://www.flickr.com/map?fLat=%27%20+%20data.photo.location.latitude%20+%20%27&fLon=%27%20+%20data.photo.location.longitude%20+%20%27&zl=1" target="_blank">' + data.photo.location.locality._content + ', ' + data.photo.location.region._content + ' (Click for Map)</a>';
		}
	}
);
majorana.io.json({
	url:'http://api.flickr.com/services/rest/?&method=flickr.photosets.getPhotos&api_key=150b6d717e986dfcb3ee162b77a6c808&photoset_id=72157627613506365&format=json&nojsoncallback=1',
	func : function(data){
			console.debug(data);
		}
	}
);
 * http://www.flickr.com/photos/52849657@N05/sets/72157627613506365/
*/
?>
<script type="text/javascript">
	
var my_photos = [];
var one_photo = function(){
	this.murl = '';
	this.url = '';
	this.lat = 0;
	this.lon = 0;
};

var apikey = '150b6d717e986dfcb3ee162b77a6c808';
var photoset = '72157627613506365';

new majorana.io.init().json({
	url:'http://api.flickr.com/services/rest/?&method=flickr.photosets.getPhotos&api_key='+apikey+'&photoset_id='+photoset+'&format=json&nojsoncallback=1',
	async : false,
	func : function(data){
		if(data.stat == 'ok'){
			for(var i = 0, len = data.photoset.photo.length; i<len; i++){
				var ph = data.photoset.photo[i];
				var mphotoUrl = 'http://farm' + ph.farm + '.static.flickr.com/' + ph.server + '/' + ph.id + '_' + ph.secret + '_m.jpg';
				var photoUrl = 'http://farm' + ph.farm + '.static.flickr.com/' + ph.server + '/' + ph.id + '_' + ph.secret + '.jpg';
				var photoID = ph.id;
				
				
				//use another ajax request to get the geo location data for the image
				new majorana.io.init().json({
					url : 'http://api.flickr.com/services/rest/?&method=flickr.photos.geo.getLocation&api_key='+apikey+'&photo_id=' + photoID + '&format=json&nojsoncallback=1',
					async : false,
					func : function(data){


							//if the image has a location, build an html snippet containing the data
							if(data.stat != 'fail') {
								//console.debug(data);
								var p = new one_photo();
								p.murl = mphotoUrl;
								p.url = photoUrl;
								p.lat = data.photo.location.latitude;
								p.lon = data.photo.location.longitude;
								my_photos.push(p);
								//var pLocation = '<a target="_blank" href="http://www.flickr.com/map?fLat=' + data.photo.location.latitude + '&fLon=' + data.photo.location.longitude + '&zl=1">' + data.photo.location.locality._content + ', ' + data.photo.location.region._content + ' (Click for Map)</a>';
								//console.debug(pLocation);
							}
						}
						
						
						
						
				});
				
				
				//console.debug(photoUrl);
			}
			console.debug(my_photos);
		}
	}
});
	
	
	
$(
	function(){
		jQuery('#a-link').remove();

		jQuery('<img />').attr('id', 'loader').attr('src', 'ajax-loader.gif').appendTo('#image-container');



		//assign your api key equal to a variable
		var apiKey = '4ef2fe2affcdd6e13218f5ddd0e2500d';

		//the initial json request to flickr
		//to get your latest public photos, use this request: http://api.flickr.com/services/rest/?&method=flickr.people.getPublicPhotos&api_key=' + apiKey + '&user_id=29096781@N02&per_page=15&page=2&format=json&jsoncallback=?
		$.getJSON('http://api.flickr.com/services/rest/?&method=flickr.photosets.getPhotos&api_key=' + apiKey + '&photoset_id=72157619415192530&format=json&jsoncallback=?',

			function(data){



				//loop through the results with the following function
				$.each(data.photoset.photo, function(i,item){

					//build the url of the photo in order to link to it
					var photoURL = 'http://farm' + item.farm + '.static.flickr.com/' + item.server + '/' + item.id + '_' + item.secret + '_m.jpg';

					//turn the photo id into a variable
					var photoID = item.id;

					//use another ajax request to get the geo location data for the image
					$.getJSON('http://api.flickr.com/services/rest/?&method=flickr.photos.geo.getLocation&api_key=' + apiKey + '&photo_id=' + photoID + '&format=json&jsoncallback=?',

						function(data){


							//if the image has a location, build an html snippet containing the data
							if(data.stat != 'fail') {
								pLocation = '<a target="_blank" href="http://www.flickr.com/map?fLat=' + data.photo.location.latitude + '&fLon=' + data.photo.location.longitude + '&zl=1">' + data.photo.location.locality._content + ', ' + data.photo.location.region._content + ' (Click for Map)</a>';
							}
						}
					);

					//use another ajax request to get the tags of the image
					$.getJSON('http://api.flickr.com/services/rest/?&method=flickr.photos.getInfo&api_key=' + apiKey + '&photo_id=' + photoID + '&format=json&jsoncallback=?',

						function(data){
							//if the image has tags
							if(data.photo.tags.tag != '') {
								//create an empty array to contain all the tags
								var tagsArr = new Array();

								//for each tag, run this function
								$.each(data.photo.tags.tag,
									function(j, item){

										//push each tag into the empty 'tagsArr' created above
										tagsArr.push('<a href="http://www.flickr.com/photos/tags/' + item._content + '">' + item.raw + '</a>');

									}
								);

								//turn the tags array into a string variable
								var tags = tagsArr.join(', ');
							}
						}
					);
					
					
				}
			);
		}
	);

//assign hover actions to each image
$('.image-container').live('mouseover', function(){
$(this).children('div').attr('class', 'image-info-active');
});
$('.image-container').live('mouseout', function(){
$(this).children('div').attr('class', 'image-info');
});

jQuery('#loader').remove();

});

</script>