<?php
/*
 __  __        _                             
|  \/  | __ _ (_) ___  _ __ __ _ _ __   __ _ 
| |\/| |/ _` || |/ _ \| '__/ _` | '_ \ / _` |
| |  | | (_| || | (_) | | | (_| | | | | (_| |
|_|  |_|\__,_|/ |\___/|_|  \__,_|_| |_|\__,_|
            |__/   
 * 
 * 
 * This is an adaptation of a class of Majorana framework (http://www.majoranaframework.org)
 * 
 * Author : Federico Ghedina
 * Version : 1.0  
 * Email: info@majoranaframework.org, info@freakstyle.it
 * 
 * THIS SOFTWARE IS PROVIDED "AS IS" AND ANY EXPRESSED OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO,
 * THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT
 * SHALL THE REGENTS OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF
 * USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF
 * THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

class gmaps3simple{
	
	/**
	 * The final script that will be rendered
	 * @var string 
	 */
	private $out_js;
	private $tmp_var;
	private $tmp_arr;
		
	private $center_point;
	private $points = array();
	private $helpers = array();
	
	private $polylines = array();
	private $routes = array();
	private $routes_panel = false;
	private $polygons = array();
	private $places = array();
	private $direct_menu = '';
	
	
	private $places_flag=false;
		
	private $circles=array();
	private $rectangles=array();
			
	private $more_scripts=array();
			
			//layers
	private $traffic_layer,$bike_layer,$panoramio_layer, $flickr_layer;
			
	private $zoom_level = 8;

	private $proto = 'http';
	private $script_hash = array();
	private $out_style='';
	private $tilt = false;
	private $map_id;
	private $map_options;
	private $map_styles;
	
	// dimensions
	private $width, $height;
	
	private $inner_divs = array();
	
	private $only_one_baloon = false;
	private $do_center_on_click = false;
	private $do_center_on_click_zoom = false;
		
	// used only for google earth
	private $api_key; 
	
	
	private $center_after_geo = false;
	
	
	//libraries
	private $load_places=false;
	
	
	// private $base_script = '://maps.googleapis.com/maps/api/js?key=%API_KEY%';
	private $base_script = '://maps.googleapis.com/maps/api/js?key=%API_KEY%';
	const gears_script = 'http://code.google.com/apis/gears/gears_init.js';
	// private $geo_code = 'http://maps.googleapis.com/maps/api/geocode/json?address=%address%&sensor=false';
	private $geo_code = 'https://maps.googleapis.com/maps/api/geocode/json?address=%address%&key=%API_KEY%';
	

	private $map_genres = array('ROADMAP','SATELLITE','HYBRID','TERRAIN');
	private $panel_elements = array(
		'panControl'=>array('show'=>false,'position'=>false),
		'zoomControl'=>array('show'=>false,'position'=>false,'style'=>false),
		'mapTypeControl'=>array('show'=>false,'position'=>false,'style'=>false),
		'scaleControl'=>array('show'=>false,'position'=>false),
		'streetViewControl'=>array('show'=>false,'position'=>false),
		'overviewMapControl'=>array('show'=>false, 'opened'=>false),
		'navigationControl'=>array('show'=>false, 'style'=>false),
		
	);
	private $panel_positions=array(
		'TOP_LEFT', 'TOP_CENTER', 'TOP_RIGHT',
		'LEFT_TOP', 'RIGHT_TOP',
		'LEFT_CENTER', 'RIGHT_CENTER',
		'LEFT_BOTTOM', 'RIGHT_BOTTOM',
		'BOTTOM_LEFT', 'BOTTOM_CENTER', 'BOTTOM_RIGHT'
	);
	private $map_genre = 'ROADMAP';
			
	/**
	 * //google get params
	 * sensor (for using device gps for locating)
	 * region
	 * 
	 * //used to add code
	 * https boolean
	 * load_gears
	 * mobile_mode
	 * async
	 * 
	 * @var array
	 */
	private $hash_defaults=array(
		'sensor'=>false,
		'region'=>false
	);
	
	private $MapTypeStyleFeatureType=array(
		'administrative'=>array( //	Apply the rule to administrative areas
			'country',		// Apply the rule to countries.
			'land_parcel',	// Apply the rule to land parcels.
			'locality',		// Apply the rule to localities.
			'neighborhood',	// Apply the rule to neighborhoods.
			'province'		// Apply the rule to provinces.
		),
		'all',			//Apply the rule to all selector types.
		'landscape'=>array(	//Apply the rule to landscapes.
			'man_made',		//Apply the rule to man made structures.
			'natural'		//Apply the rule to natural features.
		),
		'poi'=>array(	//Apply the rule to points of interest.
			'attraction',	//Apply the rule to attractions for tourists.
			'business',		//Apply the rule to businesses.
			'government',	//Apply the rule to government buildings.
			'medical',		//Apply the rule to emergency services (hospitals, pharmacies, police, doctors, etc).
			'park',			//Apply the rule to parks.
			'place_of_worship',//Apply the rule to places of worship, such as church, temple, or mosque.
			'school',	//Apply the rule to schools.
			'sports_complex'	//Apply the rule to sports complexes.
		),
		'road'=>array(	// 	Apply the rule to all roads.
			'arterial',	//	Apply the rule to arterial roads.
			'highway',	//	Apply the rule to highways.
			'local'	//	Apply the rule to local roads.
		),
		'transit'=>array(// 	Apply the rule to all transit stations and lines.
			'line',	// 	Apply the rule to transit lines.
			'station'=>array( //Apply the rule to all transit stations.
				'airport', //Apply the rule to airports.
				'bus', //	Apply the rule to bus stops.
				'rail'// 	Apply the rule to rail stations.
			)
		),
		'water'
	);
	private $MapTypeStyleElementType = array('all', 'geometry', 'labels');
	
	private $helper_functions = array(
		
		'place_marker'=>'function placeMarker(location) {
			var marker = new google.maps.Marker({
				position: location, 
				map: %map%
			});
			return marker;
		};',
		
		'center_and_zoom'=>'
			
		',
		'reverseGC'=>'
			var geocoder = new google.maps.Geocoder();
			function reverse(lat, lon, win){
				geocoder.geocode(
					{latLng: new google.maps.LatLng(lat, lon)},
					function(results, status){
						if (status == google.maps.GeocoderStatus.OK) {
							if (results[1]) {
								var html = win.getContent();
								html = html.replace("%LOCATION%", results[1].formatted_address);
								win.setContent(html);
								//win.open(%map%);
								
							}
						} else {
							var html = win.getContent();
							html = html.replace("%LOCATION%", "failed reverse geocoding");
							win.setContent(html);
							//win.open(%map%);
						}
					}
				);
			};',
		'elevation'=>'
			function elevation(lat, lon, win) {
				var elevator = elevator = new google.maps.ElevationService();
				var locations = [];
				
				var clickedLocation = new google.maps.LatLng(lat,lon);
				locations.push(clickedLocation);
				var positionalRequest = {
					locations: locations
				};
				
				elevator.getElevationForLocations(
					positionalRequest,
					function(results, status) {
						if (status == google.maps.ElevationStatus.OK) {
							if (results[0]) {
								var html = win.getContent();
								var el = results[0].elevation.toFixed(2);
								html = html.replace("%ELEVATION%", el);
								win.setContent(html);
								//win.open(%map%);

							} else {
								var html = win.getContent();
								html = html.replace("%ELEVATION%", "failed elevation request");
								win.setContent(html);
								//win.open(%map%);
							}
						} else {
							alert("Elevation service failed due to: " + status);
						}
					}
				);
			}
		'
		
	);

	
	//some lib flag
	private $loaded_libs=array();
	
	
	//check cache?
	private $cache=false;
	private $may_cache_file=false;
	private $cached_file_content=false;
	
	//set it manually
	private $use_jQuery = false;
	
	/**
	 * for using geometry functions, like distance, area, etc, add 'geometry' to the libraries hash parameter
	 * 
	 * 
	 * @param type $options 
	 * 
	 */
	private function check_cache(){
		$bt = debug_backtrace();
		if(defined(MAJORANA)){
			$this->may_cache_file = PATH_CACHE.'cache.'.__CLASS__.'.'.crc32(file_get_contents($bt[2]['file'])).'.html';
		}else{
			$this->may_cache_file = realpath(dirname(__FILE__)).'/cache.'.__CLASS__.'.'.crc32(file_get_contents($bt[1]['file'])).'.html';
		}
		if(file_exists($this->may_cache_file)){
			$this->cached_file_content = file_get_contents( $this->may_cache_file) ;
		}
	}
	public function __construct($options=false){
		
		if(array_key_exists('cache', $options) && $options['cache']){
			$this->cache = true;
			$this->check_cache();
			if($this->cached_file_content)return true;
		}
		
		
		$this->map_id = $options['id'];
		
		if(array_key_exists('jQuery', $options))$this->use_jQuery = is_bool($options['jQuery'])?$options['jQuery'] : false;
		
		if(array_key_exists('tilt', $options))$this->tilt = ($options['tilt']==45)?45:0;
		//if(array_key_exists('libraries', $options) && array_key_exists('places', $options['libraries']))$this->load_places = true;
		
		$this->out_style = html::esplicit_script('#'.$this->map_id.' { height: 100% }', 'css');
		
		//set map options
		$this->map_options = array_key_exists('options', $options)? $options['options'] : array();
		
		$this->map_styles = array_key_exists('styles', $options)? $options['styles'] : array();
		
		$this->script_hash = $this->hash_defaults;
		
		if(array_key_exists('hash', $options) && is_array($options['hash'])){
			foreach($options['hash'] as $k => $v) $this->script_hash[$k] = $v;
			foreach($this->hash_defaults as $k => $v)
				if(!array_key_exists($k, $this->script_hash))$this->script_hash[$k] = $v;
		}
		
		
		//check center user will
		if($this->script_hash['sensor'] =='center'){
			$this->center_after_geo = true;
		}
		//rewrite sensor to string
		$this->script_hash['sensor'] = $this->script_hash['sensor']?'true':'false';
		
		
		$this->loaded_libs = array_key_exists('libraries', $this->script_hash)? $this->script_hash['libraries'] : array();
		
		
		$this->tmp_arr = array();
		$this->tmp_var = NULL;
		
		return $this;
	}
	
	public function __destruct() {
		$this->out_js = NULL;
		$this->tmp_arr = array();
		$this->tmp_var = NULL;
    }
    
    public function set_api_key($ak) {
        $this->api_key = $ak;
        $this->base_script = str_replace('%API_KEY%', $this->api_key, $this->base_script);
        return $this;
    }
	
	public function set_size($w, $h){
		$this->width = $w;
		$this->height = $h;
		if($this->cached_file_content)return;
		if($this->use_jQuery){
			$this->add_user_script("jQuery('#".$this->map_id."').css({width:'".$w."px', height:'".$h."px'});");
		}else{
			$this->add_user_script("
				var el = document.getElementById('".$this->map_id."'); el.style.width='".$w."px'; el.style.height='".$h."px';
			");
		}
		return $this;
	}
	
	public function set_map_genre($genre){
		if($this->cached_file_content)return;
		if(in_array($genre, $this->map_genres))$this->map_genre = $genre;
		return $this;
	}
	
	//set protocol
	public function set_protocol($p){
		if($this->cached_file_content)return;
		if(in_array($p,array('http','https')))$this->proto = $p;
		return $this;
	}
	

	public function center_point($arr){
		if($this->cached_file_content)return;
		if(!is_array($arr))$arr = $this->address2coords ($arr);
		
		$this->center_point = 'new google.maps.LatLng('.$arr['lat'].','.$arr['lon'].')';//array('lat'=>$arr['lat'], 'lon'=>$arr['lon']);
		return $this;
	}
	
	public function get_center_point(){	return $this->center_point;	}
	
	public function set_zoom_level($i){
		if($this->cached_file_content)return;
		if(is_int($i))$this->zoom_level = intval($i);
		return $this;
	}
	
	public function edit_panel($p){
		if($this->cached_file_content)return;
		/*
		'panControl'=>array('show'=>false,'position'=>false),
		'zoomControl'=>array('show'=>false,'position'=>false,'style'=>false),
		'mapTypeControl'=>array('show'=>false,'position'=>false,'style'=>false),
		'scaleControl'=>array('show'=>false,'position'=>false),
		'streetViewControl'=>array('show'=>false,'position'=>false),
		'overviewMapControl'=>array('show'=>false,'position'=>false)
		*/
		foreach($p as $name => $opt){
			
			if(array_key_exists($name, $this->panel_elements)){
				
				$this->panel_elements[$name]['show'] = $opt['show'];
				
				if(array_key_exists('position', $opt) && $opt['position'] && in_array($opt['position'], $this->panel_positions)){
					$this->panel_elements[$name]['position'] = 'google.maps.ControlPosition.'.$opt['position'];
				}
				switch($name){
					case 'mapTypeControl':
						if(array_key_exists('style', $opt) && in_array($opt['style'], array('HORIZONTAL_BAR','DROPDOWN_MENU','DEFAULT')))
							$this->panel_elements[$name]['style'] = 'google.maps.MapTypeControlStyle.'.$opt['style'];
					break;
					case 'zoomControl':
						if(array_key_exists('style', $opt) && in_array($opt['style'], array('SMALL','LARGE','DEFAULT')))
							$this->panel_elements[$name]['style'] = 'google.maps.ZoomControlStyle.'.$opt['style'];
					break;
					case 'overviewMapControl':
						if(array_key_exists('opened', $opt) && $opt['opened'])
							$this->panel_elements[$name]['opened'] = $opt['opened']?'true':'false';
					break;
					case 'navigationControl':
						if(array_key_exists('style', $opt) && in_array($opt['style'], array('SMALL','DEFAULT')) )
							$this->panel_elements[$name]['style'] = 'google.maps.NavigationControlStyle.'.$opt['style'];
					break;
					default:break;
				}
				
			}
			
		}
		return $this;
	}

	///content will not be parsed
	public function add_inner_div($id, $style=false, $hidden=false, $content=false){
		if($style)$style['position']='absolute';
		$this->inner_divs[$id] = array(
			//'tag'=>html::tag('div',false,array('id'=>$id, 'style'=>arr::assoc2style($style) )),
			'id'=>$id,
			'style'=>$style,
			'hidden'=>$hidden,
			'content'=>$content
		);
		//var_dump($this->inner_divs);
		//	die();
		return $this;
	}
	private function write_inner_divs(){
		if($this->use_jQuery){
			$js='if(jQuery("#'.$this->map_id.'").parent().css(\'position\')==\'relative\'){';
			foreach($this->inner_divs as $id => $inner_div){
                $js.='jQuery("#'.$this->map_id.'").parent().append(\''.
                        html::tag('div',false,array('id'=>$inner_div['id'], 'style'=>arr::assoc2style($inner_div['style']) )).
                        '\');';

                // TODO check
                // if($inner_div['content']){
                //     $js.='jQuery(\'#'.$id.'\').html("'.$inner_div['content'].'");';
                // }
				if($inner_div['hidden']){
					$js.='jQuery(\'#'.$id.'\').hide();';
				}
			}
			$js.='}';
		}else{
			$js = 'if(document.getElementById("'.$this->map_id.'").parentNode.style.position=="relative"){';
			foreach($this->inner_divs as $id => $inner_div){
				$js.='var tag = document.createElement(\'div\'); tag.setAttribute(\'id\', \''.$inner_div['id'].'\');';
				//foreach($inner_div['style'] as $k =>$v)
				$js.='tag.setAttribute(\'style\', \''.arr::assoc2style($inner_div['style']).'\');';
                $js.='document.getElementById("'.$this->map_id.'").parentNode.appendChild(tag);';
                // TODO check
                // if($inner_div['content']){
				// 	$js.='document.getElementById("'.$id.'").innerHMTL = "'.$inner_div['content'].'";';
				// }
				if($inner_div['hidden']){
					$js.='document.getElementById("'.$id.'").style.display = "none";';
				}
			}
			$js.='}';
		}
		return $js;
	}
	
	
	public function open_baloon_on_dbclick($html){
		if($this->cached_file_content)return;
		
		$this->map_options['disableDoubleClickZoom'] = true;
		$id = uniqid();
		$js = '
		var info_event_Window_'.$id.' = new google.maps.InfoWindow();
			google.maps.event.addListener('.$this->map_id.', \'dblclick\', function(event) {
				var lat = event.latLng.lat();
				var lon = event.latLng.lng();
				var html = \''.$html.'\';
				
				'.$this->baloon_parse('html', 'lat', 'lon', 'info_event_Window_'.$id).'

				info_event_Window_'.$id.'.setContent(html+"");
				info_event_Window_'.$id.'.setPosition(event.latLng);

				info_event_Window_'.$id.'.open('.$this->map_id.');
				
				
				
			}
		);';
		$this->add_user_script($js);
	}
	
	/**
	 *array(
	 *	'label'=>'etichetta nel menu',
	 *  'place'=>'luogo',
	 *	*'zoom'=>false o intero in zoomrange,
	 *  *'baloon'=>'testo del baloon' 
	 * )
	 */
	
	public function add_direct_menu($no_selection, $elements, $style){
		$script = 'var simple_custom_menu_Windows = [];
			var simple_custom_menu_Points = [];
		';
		$script.='
			var select = document.createElement("select");
			select.setAttribute("name", "mySelect");
			select.setAttribute("id", "simple_custom_menu");
		';
		//overwrite if specified
		$style['position']='absolute';
		foreach($style as $k => $v){$script .='select.style.'.$k.'="'.$v.'";
		';}
		
		
		$script .= 'var option;';
		$script .='
			option = document.createElement("option");
			option.setAttribute("value", "");
			option.innerHTML = "'.$no_selection.'";
			select.appendChild(option);
		';
		//first check and create optgroups
		$optgroups = array();
		foreach($elements as $k => $element){
			if(array_key_exists('group', $element)){
				$script .='var optg_'.$k.' = document.createElement("optgroup"); optg_'.$k.'.label = "'.$element['group'].'";'; 
				if(!array_key_exists($element['group'], $optgroups))$optgroups[$element['group']] = 'optg_'.$k;
			}		
		}
		
		//for the moment save but not add to script
		$tmp_script = '';
		
		foreach($elements as $k => $element){
			$coords = $this->address2coords($element['place']);
			
			$script .= 'simple_custom_menu_Points['.$k.'] = new google.maps.LatLng('.$coords['lat'].','.$coords['lon'].');'
			;
			
			if(array_key_exists('group', $element)){
				$script .='
					option = document.createElement("option");
					option.setAttribute("value", "'.$k.'_'.(array_key_exists('zoom', $element)?intval($element['zoom']):'0').'");
					option.innerHTML = "'.$element['label'].'";';
				$script.= $optgroups[$element['group']].'.appendChild(option);';				
			}else{
				$tmp_script .='
					option = document.createElement("option");
					option.setAttribute("value", "'.$k.'_'.(array_key_exists('zoom', $element)?intval($element['zoom']):'0').'");
					option.innerHTML = "'.$element['label'].'";';
				$tmp_script .= 'select.appendChild(option);';
			}
			
			if($element['baloon']){
				$script.= '
				simple_custom_menu_Windows['.$k.'] = new google.maps.InfoWindow();
				simple_custom_menu_Windows['.$k.'].setContent("'.$element['baloon'].'");
				simple_custom_menu_Windows['.$k.'].setPosition(simple_custom_menu_Points['.$k.']);	
				';
			}
			
		}
		foreach($optgroups as $k => $optgroup){
			$script.='select.appendChild('.$optgroup.');';
		}
		
		//now add options that are not ina  optgroup
		$script.=$tmp_script;
		
		
		$script .= '
			var current_point = false;
			var current_marker = false;
			var current_window = false;
			
			select.onchange = function(){
				
				if(this.value.trim() == "")return;
				var valz= this.value.split("_");				
				
				if(valz[1] && parseInt(valz[1], 10)>0){'.$this->map_id.'.setZoom(parseInt(valz[1], 10));}
				
				current_point = simple_custom_menu_Points[valz[0]];
				
				// close current window
				if(current_window){	current_window.close();	}
				// close current point
				if(current_marker){	current_marker.setMap(null);}	
				
				current_point = simple_custom_menu_Points[valz[0]];
				current_window = simple_custom_menu_Windows[valz[0]];

				'.$this->map_id.'.setCenter(current_point);
				current_window.open('.$this->map_id.');
				
				current_marker =  new google.maps.Marker({
					position: current_point, 
					map: '.$this->map_id.',
					animation:google.maps.Animation.DROP,
					visible:true
				});
					
				google.maps.event.addListener(current_marker , \'click\', function() {
					
					current_window.open('.$this->map_id.');
				});
			};
		';
		
		$script .='
		var tag_inner_menu = document.createElement(\'div\'); tag_inner_menu.appendChild(select);
		';
		
		$script .= '
		if(document.getElementById("'.$this->map_id.'").parentNode.style.position=="relative"){
			document.getElementById("'.$this->map_id.'").parentNode.appendChild(tag_inner_menu);
		}
		';
		
		$this->direct_menu = $script;
	}
	
	public function add_points($points){
		if($this->cached_file_content)return;
		foreach($points as $point){
			$this->add_point($point[0], $point[1], $point[2]);
		}
		return $this;
	}
	
	
	/*****************************************************/
	//
	// POINTS
	// 
	// add a point
	public function add_point($name, $coords=false, $opts=array()){
		
		if($this->cached_file_content)return;
		if(is_array($name)){
			foreach($name as $point)
				count($point)>2 ? $this->add_point($point[0], $point[1], $point[2]) : $this->add_point($point[0], $point[1]);
			return;
		}
		
		if(!is_array($coords)){
			$res = $this->address2coords($coords);
			if($res['lat'] != 0) $coords = array('lat'=>$res['lat'], 'lon'=>$res['lon']);
		}
		
		$data = array('coords'=>$coords);
		
		foreach($opts as $k => $opt)
			if(in_array($k, array('openbaloon','baloon', 'title', 'drop','icon','icon_size','shadow','shadow_size','zindex','animation','draggable', 'onclick'))) 
				$data[$k] = is_array($opts[$k])? $opts[$k] : str_replace (array("\n","\r"), '', $opts[$k]);
		
		
		$this->points[$name] = $data;
		
		return $this;
	}
	
	public function get_point($name){
		return array_key_exists($name, $this->points) ? $this->points[$name] : false;
	}
	
	
	// removes a point
	public function remove_point($name){
		if($this->cached_file_content)return;
		if(array_key_exists($name, $this->points))
			unset($this->points[$name]);
		return $this;
	}
	
	

	
	public function limit_baloons_to($num = 1){
		if($this->cached_file_content)return;
		$this->only_one_baloon = $num;
		return $this;
	}
	private function write_one_baloon(){
		if($this->cached_file_content)return;
		if($this->only_one_baloon){
			$js='
				var opened_baloons = new Array();
				function swap_baloons(){
					for(var i =0, len=opened_baloons.length; i<len-'.intval($this->only_one_baloon).';i++){
						var shif = opened_baloons.shift();
						if(typeof shif !==\'undefined\'){shif.close();}
					}
					
					var max_zindex=0;
					var len = 0;
					for(var i =0, len=opened_baloons.length; i<len;i++){
						var zindex = opened_baloons[i].getZIndex();
						if(max_zindex < zindex){max_zindex = zindex;}
					}
					opened_baloons[len-1].setZIndex(max_zindex+1);
					
				}
				function add_baloon(b){
					var ob_new = [];
					for(var i =0, len=opened_baloons.length;i<len;i++){
						if(opened_baloons[i]!==b){
							ob_new.push(opened_baloons[i]);
						}
					}
					opened_baloons = ob_new;
					opened_baloons.push(b);
					swap_baloons();
					return true;
				}
			';
			return $js;//'var opened_baloon = false;';
		}
	}
	
	
	
	public function add_circle($circle){
		if($this->cached_file_content)return;
		$this->circles[] = $circle;
		return $this;
	}
	public function add_rectangle($rectangle){
		if($this->cached_file_content)return;
		$this->rectangles[] = $rectangle;
		return $this;
	}
	

	
	
	
	
	
	
	///////////////////////////////////////////////////////////////
	/**
	 * PRIVATE WRITERS
	 */
	
	
	private function baloon_parse($baloon_name, $lat_name, $lon_name, $window_name, $more = 'false' ){
		return '
			
			'.$baloon_name.' = '.$baloon_name.'.replace("%LAT%", '.$lat_name.')
				.replace("%LON%", '.$lon_name.');
			//rev_geo?
			if('.$more.'){
				var tmp = '.$more.';
				for(var h in tmp){
					'.$baloon_name.' = '.$baloon_name.'.replace("%"+h+"%", tmp[h]);
				}
			}
			if('.$baloon_name.'.match("%LOCATION%")){
				reverse('.$lat_name.', '.$lon_name.', '.$window_name.');
			}
			//else{'.$window_name.'.open('.$this->map_id.');}
			

			// elevation
			if('.$baloon_name.'.match("%ELEVATION%")){
				elevation('.$lat_name.', '.$lon_name.', '.$window_name.');
			}
			//else{'.$window_name.'.open('.$this->map_id.');}
		';
	}
	
	
	/**
	 * Writes the js necessary to show all the circles requested with add_circle();
	 *
	 * @return string 
	 */
	private function write_circles(){
		if($this->cached_file_content)return;
		$js='';
		
		//loop on booked elements
		foreach($this->circles as $k => $circle){
			
			//center is mandatory
			$center= '';
			$center = $this->param2coords($circle['center']);
			
			
			
			$radius = (array_key_exists('radius', $circle)?$circle['radius']:'100');
			$js.='var circle_'.$k.' = new google.maps.Circle({
				strokeColor: "'.(array_key_exists('strokeColor', $circle)?$circle['strokeColor']:'#FF0000').'",
				strokeOpacity: '.(array_key_exists('strokeOpacity', $circle)?$circle['strokeOpacity']:'0.8').',
				strokeWeight: '.(array_key_exists('strokeWeight', $circle)?$circle['strokeWeight']:'2').',
				fillColor: "'.(array_key_exists('fillColor', $circle)?$circle['fillColor']:'#FF0000').'",
				fillOpacity: '.(array_key_exists('fillOpacity', $circle)?$circle['fillOpacity']:'0.35').',
				map: '.$this->map_id.',
				center: '.$center.',
				radius: '.$radius.'
			});';
			
			
			$js.='
				google.maps.event.addListener(circle_'.$k.', \'click\',
					function (event) {
						'.( $this->write_center_on_click('event.latLng') ).'
					}
				);
			';
			
			
			//maybe inner baloon
			if(array_key_exists('in_baloon', $circle)){
				$in_baloon = $circle['in_baloon'];
				
				//$in_baloon = str_replace('%AREA%',   , $in_baloon);
				
				
				
				$js.='
				google.maps.event.addListener(circle_'.$k.', \'click\',
					function (event) {
						var lat = event.latLng.lat(),
							lon = event.latLng.lng();
						var area = '.(pi()*$radius*$radius/1E6).'.toFixed(2);
						var in_baloon = \''.$in_baloon.'\';
						
						'.$this->baloon_parse('in_baloon', 'lat', 'lon', 'circ_infowindow_'.$k, '{AREA : area}').'
						
						'.($this->only_one_baloon ? 'add_baloon(circ_infowindow_'.$k.');':'').'
							
						circ_infowindow_'.$k.'.open('.$this->map_id.');
						'.( $this->write_center_on_click('event.latLng') ).'
						circ_infowindow_'.$k.'.setPosition(event.latLng);
						circ_infowindow_'.$k.'.setContent(in_baloon);
							
						
						
						//////////
						
					}
				);
				var circ_infowindow_'.$k.' = new google.maps.InfoWindow();
				';
			}
		}
		return $js;
	}
	
	private function write_rectangles(){
		if($this->cached_file_content)return;
		$js='';
		foreach($this->rectangles as $k => $rect){
			
			//bounds are the only mandatory
			$bounds= array();
			if(is_array($rect['bound1'])){
				$bounds[0] = 'new google.maps.LatLng('.$rect['bound1'][0].', '.$rect['bound1'][1].')';
			}else{
				$lat_lng = $this->address2coords($rect['bound1']);
				$bounds[0] = 'new google.maps.LatLng('.$lat_lng['lat'].', '.$lat_lng['lon'].')';
			}
			
			if(is_array($rect['bound2'])){
				$bounds[1] = 'new google.maps.LatLng('.$rect['bound2'][0].', '.$rect['bound2'][1].')';
			}else{
				$lat_lng = $this->address2coords($rect['bound2']);
				$bounds[1] = 'new google.maps.LatLng('.$lat_lng['lat'].', '.$lat_lng['lon'].')';
			}
			
			$js.='var rectangle_'.$k.' = new google.maps.Rectangle({
				strokeColor: "'.(array_key_exists('strokeColor', $rect)?$rect['strokeColor']:'#FF0000').'",
				strokeOpacity: '.(array_key_exists('strokeOpacity', $rect)?$rect['strokeOpacity']:'0.8').',
				strokeWeight: '.(array_key_exists('strokeWeight', $rect)?$rect['strokeWeight']:'2').',
				fillColor: "'.(array_key_exists('fillColor', $rect)?$rect['fillColor']:'#FF0000').'",
				fillOpacity: '.(array_key_exists('fillOpacity', $rect)?$rect['fillOpacity']:'0.35').',
				map: '.$this->map_id.',
				bounds: new google.maps.LatLngBounds('.$bounds[0].', '.$bounds[1].' )
			});';
			
			
			
			//maybe inner baloon
			if(array_key_exists('in_baloon', $rect)){
				$in_baloon = $rect['in_baloon'];
				$js.='
				google.maps.event.addListener(rectangle_'.$k.', \'click\',
					function (event) {
						var lat = event.latLng.lat(),
							lon = event.latLng.lng()
						
						var in_baloon = \''.$in_baloon.'\', area;
						
						if(in_baloon.match("%AREA%")!==null){
						
							var tmp_bounds = rectangle_'.$k.'.getBounds();
							var bounds = [tmp_bounds.getNorthEast(), tmp_bounds.getSouthWest()];
							var realbounds = [
								bounds[0],
								new google.maps.LatLng(bounds[0].lat(), bounds[1].lng() ),
								bounds[1],
								new google.maps.LatLng(bounds[1].lat(), bounds[0].lng() )
							]
							area = google.maps.geometry.spherical.computeArea(realbounds)/1E6;
							//in_baloon = in_baloon.replace("%AREA%", area.toFixed(2));
						}
						'.$this->baloon_parse('in_baloon', 'lat', 'lon', 'rect_infowindow_'.$k, '{AREA : area.toFixed(2)}').'
						'.($this->only_one_baloon ? 'add_baloon(rect_infowindow_'.$k.');':'').
						( $this->write_center_on_click('event.latLng') ).'			
						
						rect_infowindow_'.$k.'.open('.$this->map_id.');
						
						rect_infowindow_'.$k.'.setContent(in_baloon);
						rect_infowindow_'.$k.'.setPosition(event.latLng);
						
						


					}
				);
				var rect_infowindow_'.$k.' = new google.maps.InfoWindow();
				';
			}			
		}
		return $js;

	}
	
	private function write_traffic_layer(){
		if($this->cached_file_content)return;
		return $this->traffic_layer;
	}
	
	private function write_bike_layer(){
		if($this->cached_file_content)return;
		return $this->bike_layer;
	}
	
	private function write_panoramio_layer(){
		if($this->cached_file_content)return;
		return $this->panoramio_layer;
	}
	
	private function write_user_script(){
		if($this->cached_file_content)return;
		return count($this->more_scripts)?implode("\n", $this->more_scripts) : '';
	}
	
	private function write_polylines(){
		if($this->cached_file_content)return;
		$js = '';
		foreach($this->polylines as $k => $path){
			
			$style = $path['style'];
			unset($path['style']);
			
			$scr =' var polyline_path_'.$k.' = [';
			$points = array();
			$p = '';
			
			foreach($path as $name => $point){
				$points[]='new google.maps.LatLng('.$point['coords']['lat'].','.$point['coords']['lon'].')';
				
				
				
				//markers
				$p.=' var polyline_point_'.$name.$k.'='.end($points).";\n";
				$p.=' var polyline_marker_'.$name.$k.'= new google.maps.Marker({
					  position: polyline_point_'.$name.$k.', 
					  map: '.$this->map_id.',
					  '.(array_key_exists('title', $point['opts'])?'title:"'.$point['opts']['title'].'"':'').'
				  });'."\n";

				
				
				//maybe a baloon is desired
				if(array_key_exists('baloon', $point['opts']) || array_key_exists('openbaloon', $point['opts'])){

					switch(true){
					
						case array_key_exists('baloon', $point['opts']):
							$baloon = str_replace(array('%LAT%','%LON%'),array($point['coords']['lat'],$point['coords']['lon']),$point['opts']['baloon'] );
							$p.='var in_baloon = \''.$baloon.'\',
								polyline_baloon_'.$name.$k.'= new google.maps.InfoWindow({ content: in_baloon }),
								lat = '.$point['coords']['lat'].',
								lon = '.$point['coords']['lon'].';'."\n";
							 $p.= $this->baloon_parse('in_baloon', 'lat', 'lon', 'polyline_baloon_'.$name.$k);
						
							$op = ($this->only_one_baloon ? 'add_baloon(polyline_baloon_'.$name.$k.');':'');
						
							$p .= 'google.maps.event.addListener(
								polyline_marker_'.$name.$k.',
								\'click\',
								function() {
									'.$op.'
									
									polyline_baloon_'.$name.$k.'.open('.$this->map_id.',polyline_marker_'.$name.$k.');
									
									'.$this->write_center_on_click('polyline_point_'.$name.$k).'	
								}
							);';
						
							
							
						break;
					
						case array_key_exists('openbaloon', $point['opts']['opts']):
							//placeholders ?
							$openbaloon = str_replace(
                                array('%LAT%', '%LON%'),
                                array($point['coords']['lat'], $point['coords']['lon']),
                                $point['opts']['openbaloon']
                            );
							
							$p.='var in_baloon = \''.$openbaloon.'\',
								polyline_baloon_'.$name.$k.'= new google.maps.InfoWindow({ content: in_baloon }),
								lat = '.$point['coords']['lat'].',
								lon = '.$point['coords']['lon'].';'."\n";
							$p.= $this->baloon_parse('in_baloon', 'lat', 'lon', 'polyline_baloon_'.$name.$k);


							$op =($this->only_one_baloon ? 'add_baloon(polyline_baloon_'.$name.$k.');':'');
							
							$p .= $op.'polyline_baloon_'.$name.$k.'.open('.$this->map_id.',polyline_marker_'.$name.$k.');'."\n";
							$p .= 'google.maps.event.addListener(
								polyline_marker_'.$name.$k.',
								\'click\',
								function() {
									'.($this->only_one_baloon ? 'add_baloon(polyline_baloon_'.$name.$k.');':'').
									'polyline_baloon_'.$name.$k.'.open('.$this->map_id.',polyline_marker_'.$name.$k.');
									'.$this->write_center_on_click('polyline_point_'.$name.$k).'	
								}
							);'."\n";
						break;
					}
				}
			}
			$scr.=implode(',', $points);
			$scr.='];';
			$scr.='var polyline_inpath_'.$k.' = new google.maps.Polyline({path: polyline_path_'.$k.',strokeColor: "'.$style['strokeColor'].'",strokeOpacity: '.$style['strokeOpacity'].',strokeWeight: '.$style['strokeWeight'].'});
				polyline_inpath_'.$k.'.setMap('.$this->map_id.');'."\n";
			
			$js.=$p;
			
			$js.=$scr;
			
			
		}
		return $js;
	}
	
	private function write_routes(){
		if($this->cached_file_content)return;
		$js = 'var directionsService = new google.maps.DirectionsService();'."\n";
		foreach($this->routes as $route){
			$js.=$route;
		}
		return $js;
	}
	
	private function write_polygons(){
		if($this->cached_file_content)return;
		// js output
		$js = '';
		
		//is geom lib available ?
		$can_use_library_geometry = in_array('geometry', $this->loaded_libs);
		
		//loop over booked ploygons
		foreach($this->polygons as $k => $path){
			
			$style = $path['style'];
			unset($path['style']);
			$in_baloon = $path['in_baloon'];
			unset($path['in_baloon']);
			
			
			
			
			$points = array();
			
			$baloon_and_markers = '';
			foreach($path as $name => $point){
				$js.=' var polygon_js_point_'.$name.$k.'= new google.maps.LatLng('.$point['coords']['lat'].','.$point['coords']['lon'].');'."\n";
				$points[]='polygon_js_point_'.$name.$k;
				
				
				
				//markers
				
				$baloon_and_markers.=' var polygon_marker_'.$name.'= new google.maps.Marker({
					  position: new google.maps.LatLng('.$point['coords']['lat'].','.$point['coords']['lon'].'), 
					  map: '.$this->map_id.',
					  '.(array_key_exists('title', $point['opts'])?'title:"'.$point['opts']['title'].'"':'').'
				  });
				'."\n";

				//maybe a baloon is desired
				if(array_key_exists('baloon', $point['opts']) || array_key_exists('openbaloon', $point['opts'])){
					
					
					
					//content for the baloon
					$content = '';
					switch(true){
						case array_key_exists('baloon', $point['opts']):
							$baloon_and_markers.= 'var in_baloon = \''.$point['opts']['baloon'].'\', lat = '.$point['coords']['lat'].', lon = '.$point['coords']['lon'].';';
							
							
							$point['opts']['baloon'] = str_replace(array('%LAT%', '%LON%'),array($point['coords']['lat'],$point['coords']['lon']), $point['opts']['baloon']);
							//$baloon_and_markers.=' var polygon_baloon_'.$name.$k.'= new google.maps.InfoWindow({ content: \''.$point['opts']['baloon'].'\' });';
							$baloon_and_markers.=' var polygon_baloon_'.$name.$k.'= new google.maps.InfoWindow({ content: in_baloon });';
							$baloon_and_markers.= $this->baloon_parse('in_baloon', 'lat', 'lon', 'polygon_baloon_'.$name.$k); 
							
							
							$op = ($this->only_one_baloon ? 'add_baloon(polygon_baloon_'.$name.$k.'); ':'');
							$baloon_and_markers .= 'google.maps.event.addListener(
								polygon_marker_'.$name.',
								\'click\',
								function() {
									'.$op.'
									polygon_baloon_'.$name.$k.'.open('.$this->map_id.',polygon_marker_'.$name.');
									'.$this->write_center_on_click('polygon_js_point_'.$name.$k).'
								}
							);'."\n";
						break;
						///maybe should be opened automatically
						case array_key_exists('openbaloon', $point['opts']['opts']):
							$point['opts']['openbaloon'] = str_replace(array('%LAT%', '%LON%'),array($point['coords']['lat'],$point['coords']['lon']), $point['opts']['openbaloon']);
							$baloon_and_markers .=' var polygon_baloon_'.$name.$k.'= new google.maps.InfoWindow({ content: \''.$point['opts']['openbaloon'].'\' });';
							$baloon_and_markers .= 'polygon_baloon_'.$name.$k.'.open('.$this->map_id.',polygon_marker_'.$name.');';
							$op = ($this->only_one_baloon ? 'add_baloon(polygon_baloon_'.$name.$k.'); ':'');
							$baloon_and_markers .= 'google.maps.event.addListener(
								polygon_marker_'.$name.',
								\'click\',
								function() {
									'.$op.'
									polygon_baloon_'.$name.$k.'.open('.$this->map_id.',polygon_marker_'.$name.');
									'.$this->write_center_on_click('polygon_js_point_'.$name.$k).'
								}
							);'."\n";
							
						break;
					}

				}
				
				
			}
			
			
			
			
			
			// write down polygon points array
			$script =' var polygon_poly_'.$k.' = ['.implode(',', $points).'];';
			
			// create the plygon object
			$script.='var polygon_inpoly_'.$k.' = new google.maps.Polygon({
					paths: polygon_poly_'.$k.',
					strokeColor: "'.$style['strokeColor'].'",
					strokeOpacity: '.$style['strokeOpacity'].',
					strokeWeight: '.$style['strokeWeight'].',
					fillColor: "'.$style['fillColor'].'",
					fillOpacity: '.$style['fillOpacity'].'
				});'."\n";
			// attach
			$script.='polygon_inpoly_'.$k.'.setMap('.$this->map_id.');'."\n";
			
			
			if($in_baloon){
				
				$found_area_ph = false;
				//if geom lib is loaded search for placeholder %AREA%
				if($can_use_library_geometry){
					$found_area_ph = true;
				}else{
					$in_baloon = str_replace('%AREA%', '', $in_baloon);
				}
				
				
				
				$script.='
				google.maps.event.addListener(polygon_inpoly_'.$k.', \'click\',
					function (event) {
						var lat = event.latLng.lat(),
							lon = event.latLng.lng();
						'.($found_area_ph ? 'var areaX = google.maps.geometry.spherical.computeArea(['.implode(',',$points).'])/1E6;':'').'
						var in_baloon_area_'.$k.' = \''.$in_baloon.'\'
							.replace(\'%LAT%\', lat)
							.replace(\'%LON%\', lon)'.($found_area_ph ? '.replace(\'%AREA%\',areaX.toFixed(2))':'').';
						
						
						polygon_infowindow_'.$k.'.setContent(in_baloon_area_'.$k.');
						'.$this->baloon_parse('in_baloon_area_'.$k, 'lat', 'lon', 'polygon_infowindow_'.$k).'
						polygon_infowindow_'.$k.'.setPosition(event.latLng);
						
						'.($this->only_one_baloon ? 'add_baloon(polygon_infowindow_'.$k.');':'').'

						//rev_geo?
						if(in_baloon_area_'.$k.'.match("%LOCATION%")){
							reverse(lat, lon, polygon_infowindow_'.$k.');
						}else{
							polygon_infowindow_'.$k.'.open('.$this->map_id.');
						}
						//elevation?
						if(in_baloon_area_'.$k.'.match("%ELEVATION%")){
							elevation(lat, lon, polygon_infowindow_'.$k.');
						}else{
							polygon_infowindow_'.$k.'.open('.$this->map_id.');
						}
						'.$this->write_center_on_click('event.latLng').'
						polygon_infowindow_'.$k.'.open('.$this->map_id.');

						
					}
				);
				var polygon_infowindow_'.$k.' = new google.maps.InfoWindow();
				';
			}
			
			// add baloons, markers, polygons and listeners to the response
			$js .= $baloon_and_markers . $script;
		}
		return $js;
	}
	
	private function write_places(){
		if($this->cached_file_content)return;
		$js='';
		if(count($this->places)>0)
			$js.=',types:[\''.implode("','", $this->places['types']).'\']';
		
		return $js;
	}
	
	private function write_panel(){
		if($this->cached_file_content)return;
		$tmp = array();
		
		
		
		foreach($this->panel_elements as $name => $opts){
			
			$elements =array();
			
			
			$elements[] = $name.':'.($opts['show']?'true':'false');
			
			if($opts['show']==true){
				
				
				$inner_els =array();
				
				$options = false;
				if(array_key_exists('position', $opts) && $opts['position']){
					$options=true;
					
					$inner_els[] = 'position:'.$opts['position'];
				}
				if(array_key_exists('style', $opts) && $opts['style']){
					$options=true;
					$inner_els[] = 'style:'.$opts['style'];
				}
				if(array_key_exists('opened', $opts) && $opts['opened']){
					$options=true;
					$inner_els[] = 'opened:'.$opts['opened'];
				}
				
				if($options){
					$elements[] = $name.'Options:{'.implode(',', $inner_els).'}';	
				}
				
				
			}
			$tmp[]= implode(',', $elements);
			
			
		}
		
		return implode(',', $tmp);
	}
	
	private function head(){
		if($this->cached_file_content)return;
		$head = 'var initialLocation; var browserSupportFlag = new Boolean(); '.$this->write_one_baloon();
		//is a string
		if($this->script_hash['sensor'] == 'true'){
			$head.='
			if(navigator.geolocation) {
				browserSupportFlag = true;
				navigator.geolocation.getCurrentPosition(
					function(position) {
						initialLocation = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
						'.($this->center_after_geo ? $this->map_id.'.setCenter(initialLocation);' : '').'
					},
					function() {
						handleNoGeolocation(browserSupportFlag);
					}
				);
			}
			var initialLocation=false;
			var siberia = new google.maps.LatLng(60, 105);
			var newyork = new google.maps.LatLng(40.69847032728747, -73.9514422416687);
			var get_initialLocation = function(){return initialLocation;};
			function handleNoGeolocation(errorFlag) {
				if (errorFlag == true) {
					alert("Geolocation service failed.");
					initialLocation = newyork;
				} else {
					alert("Your browser doesn\'t support geolocation. We\'ve placed you in Siberia.");
					initialLocation = new google.maps.LatLng(60, 105);;
				}
			}';
		}
		return $head;
	}
	
	private function address2coords($address){
		if($this->cached_file_content)return;
		$request = $this->geo_code;
		$req = str_replace(array('%address%', '%API_KEY%'), array(urlencode($address), $this->api_key), $request);
		
		$tmp = array();
		$ret = curl::get($req);
		$res = json_decode($ret, true);
		if($res['status']==='OK'){
			return array(
				'lat'=>$res['results'][0]['geometry']['location']['lat'],
				'lon'=>$res['results'][0]['geometry']['location']['lng']
			);
		}
		return array('lat'=>0, 'lon'=>0);
	}
	
	// write down collected points
	private function write_points(){
		if($this->cached_file_content)return;
		$p = '';
		$k=0;
		$js_points=array();
		foreach($this->points as $name => $point){
			$k++;
			
			//icon, shadow
			$icon = array_key_exists('icon', $point) ? $point['icon'] : false;
			$icon_size = array_key_exists('icon_size', $point) ? explode(',', $point['icon_size']) : false;
			$shadow = array_key_exists('shadow', $point) ? $point['shadow'] : false;
			$shadow_size = array_key_exists('shadow_size', $point)? explode(',', $point['shadow_size']) : false;			
			$zindex = array_key_exists('zindex', $point) ? $point['zindex'] : false;
			$animation = (array_key_exists('animation', $point)&& in_array($point['animation'], array('BOUNCE','DROP'))) ? $point['animation'] : false;
			$draggable = array_key_exists('draggable', $point) ? $point['draggable'] : false;
			$onclick = array_key_exists('onclick', $point) ? $point['onclick'] : false;
			
			if($icon && $icon_size){
				$p.='
				var img_point_'.$k.' = new google.maps.MarkerImage(\''.$icon.'\',
					new google.maps.Size('.$icon_size[0].', '.$icon_size[1].'),
					new google.maps.Point(0,0),
					new google.maps.Point(0, '.$icon_size[1].')
				);
				';				
			}
			if($shadow && $shadow_size){
				$p.='
				var shad_point_'.$k.' = new google.maps.MarkerImage(\''.$shadow.'\',
					new google.maps.Size('.$shadow_size[0].', '.$shadow_size[1].'),
					new google.maps.Point(0,0),
					new google.maps.Point(0, '.$shadow_size[1].')
				);
				';				
			}
			
			$p .= 'var js_point_'.$k.' = new google.maps.LatLng('.$point['coords']['lat'].','.$point['coords']['lon'].');';
			
			$p.=' var marker_point_'.$k.'= new google.maps.Marker({
					position: js_point_'.$k.', 
					map: '.$this->map_id.'
					'.(array_key_exists('title', $point)?',title:"'.$point['title'].'"':'').'
						
					'.(($icon && $icon_size)?',icon:img_point_'.$k:'').'
					'.(($shadow && $shadow_size)? ',shadow:shad_point_'.$k:'').'
					'.($zindex? ',zindex:'.$zindex : '').'
					'.($animation? ',animation:google.maps.Animation.'.$animation : '').'
					'.($draggable? ',draggable:true' : '').'
				  });
			';
			
			if($onclick){
				$p.='
					google.maps.event.addListener(
						marker_point_'.$k.',
						\'click\',
						function(event) {
							var lat = event.latLng.lat();
							var lng = event.latLng.lng();
							
							var cb = '.$onclick.';
							if(cb)cb(lat,lng);
						});
				';
			}
			
			
			$p .= 'google.maps.event.addListener(
						marker_point_'.$k.',
						\'click\',
						function() {
							'.( $this->write_center_on_click('js_point_'.$k) ).'
						});';
			
			//maybe a baloon is desired
			if(array_key_exists('baloon', $point) || array_key_exists('openbaloon', $point)){
				$baloon = false;
				$p.='var content_'.$k.' = "";';
				switch(true){
					case array_key_exists('baloon', $point):
						
						//must wait for tabs implementation !!!
						/*
						if(is_array($point['baloon'])){
							$p .='var tabs_'.$name.' = [];'; 
							foreach($point['baloon'] as $label => $content){
								$p.='tabs_'.$name.'.push(new GInfoWindowTab(\''.$label.'\', \''.$content.'\')); ';

							}
							$p.='marker_'.$name.'.openInfoWindowTabsHtml(tabs_'.$name.');';
						}else{
*/							$baloon = $point['baloon'];
							$p.='content_'.$k.' = "'.$baloon.'";';
							$p.=' var baloon_point_'.$k.'= new google.maps.InfoWindow({ content: content_'.$k.' });';
						
//						}
					break;
					case array_key_exists('openbaloon', $point):
						
						$baloon = $point['openbaloon'];
						$p.= ' content_'.$k.' = "'.$baloon.'";';
						$p.= ' var baloon_point_'.$k.'= new google.maps.InfoWindow({ content: content_'.$k.' });';
						$p.= $this->only_one_baloon ? 'add_baloon(baloon_point_'.$k.');':'';
						$p.= ' baloon_point_'.$k.'.setContent(content);';
						$p.= ' baloon_point_'.$k.'.open('.$this->map_id.',marker_point_'.$k.');';
					break;
				}
				$p .= 'google.maps.event.addListener(
						marker_point_'.$k.',
						\'click\',
						function() {
							'.$this->baloon_parse('content_'.$k.'', $point['coords']['lat'], $point['coords']['lon'], 'baloon_point_'.$k).'
							baloon_point_'.$k.'.setContent(content_'.$k.');
							baloon_point_'.$k.'.open('.$this->map_id.',marker_point_'.$k.');
							'.($this->only_one_baloon ? 'add_baloon(baloon_point_'.$k.');':'').'
							
						});';
				$p.=$draggable?'
					google.maps.event.addListener(
						marker_point_'.$k.',
						\'dragend\',
						function(event) {
							var lat = event.latLng.lat();
							var lng = event.latLng.lng();
							var content = \''.$baloon.'\';
							'.$this->baloon_parse('content', 'lat', 'lng', 'baloon_point_'.$k).'	
							baloon_point_'.$k.'.setContent(content);
							var cb = '.$draggable.';
							if(cb)cb(lat,lng);
						});
				':'';
					
			}
			
			
		}
		return $p;
	}
	

	
	
	private function write_optional_map_options(){
		if($this->cached_file_content)return;
		$ret = array();
		$ret[] = $this->write_panel();
		foreach($this->map_options as $opt =>$value){
			$ret[] = $opt .':'.(is_bool($value)?($value?'true':'false') : $value);
		}
		
		//remove empty ones
		foreach($ret as $k => $v)if($v==='')unset($ret[$k]);
		return ",\n".implode(",\n", $ret);
	}
	
	private function write_map_styles(){
		if($this->cached_file_content)return;
		//return '';
		$out = '';
		if(count($this->map_styles)){
			$out = 'var my_map_styles = [';
			$out_els = array();

			foreach($this->map_styles as $label => $elements){
				$tmp = '{featureType:"'.$label.'",stylers : [';
				$elz = array();
				foreach($elements['style'] as $element => $value){
					$elz[]='{'.$element.':'.(is_string($value)?'"'.$value.'"':$value).'}';
				}
				$tmp.=implode(',', $elz);
				$tmp.=']';

				if(array_key_exists('element', $elements)){
					$tmp.=',elementType:"'.$elements['element'].'"';
				}

				$tmp.='}';
				$out_els[] = $tmp;
			}
			$out.=implode(',', $out_els);
			$out.='];';
		}
		//die($out);
		return $out;
	}
	
	
	public function center_on_click($panbool_or_zoomint=false){
		if($this->cached_file_content)return;
		
		$function = (is_bool($panbool_or_zoomint) && $panbool_or_zoomint)?'panTo':'setCenter';
		$and_zoom = is_int($panbool_or_zoomint)?intval($panbool_or_zoomint):false;
		
		$this->do_center_on_click = $this->map_id.'.'.$function.'(%LOCATION%);';
		
		if($and_zoom){
			$this->do_center_on_click_zoom = $and_zoom;
			$this->do_center_on_click .= $this->map_id.'.setZoom('.$and_zoom.');';
		}
		return $this;
	}
	private function write_center_on_click($location){
		if($this->cached_file_content)return;
		return $this->do_center_on_click ? str_replace('%LOCATION%', $location, $this->do_center_on_click): '';
	}
	
	
	private function write_js(){
		
		if($this->cached_file_content)return;
		
		if($this->places_flag){
			$place = $this->address2coords($this->places['location']);
		}
		$this->add_helper('reverseGC');
		$this->add_helper('elevation');
		
		$this->out_js='
			'.$this->head().'
			var initialize = function() {
				'.str_replace('%map%' , $this->map_id, $this->write_helpers_functions()).'
				
				var myOptions = {
					zoom: '.$this->zoom_level.',
					center: '.$this->center_point.',
					mapTypeId: google.maps.MapTypeId.'.$this->map_genre.'
					'.$this->write_optional_map_options().'
					
				};
				
				'.$this->write_map_styles().'
				

				var '.$this->map_id.' = new google.maps.Map(document.getElementById("'.$this->map_id.'"), myOptions);
				//window.'.$this->map_id.' = '.$this->map_id.';
				'.((count($this->map_styles))?'var MyMapType = new google.maps.StyledMapType(my_map_styles);
				'.$this->map_id.'.mapTypes.set(\'pink_parks\', MyMapType);
				'.$this->map_id.'.setMapTypeId(\'pink_parks\');
				':'').'

				'.(( in_array($this->map_genre, array('SATELLITE','HYBRID'))  && $this->tilt)?$this->map_id.'.setTilt('.$this->tilt.');':'').'
				'.($this->places_flag?str_replace(array('%PLAT%','%PLON%'),array($place['lat'], $place['lon']),$this->places_flag):'').'
				
				
				
				// POINTS
				'.str_replace('%map%' , $this->map_id, $this->write_points()).'
				
				//LAYERS
				'.$this->write_traffic_layer().'			
				'.$this->write_bike_layer().'			
				'.$this->write_panoramio_layer().'
				'.$this->flickr_layer.'
				//polylines
				'.$this->write_polylines().'
				//ROUTES
				'.$this->write_routes().'					
				//POLYGONS
				'.$this->write_polygons().'
				//CIRCLES
				'.$this->write_circles().'
				//RECTANGLES
				'.$this->write_rectangles().'
					

				//ADD_INNER_DIVS
				'.$this->write_inner_divs().'

				//USER_SCRIPTS
				'.$this->write_user_script().'
					
				//ZOOM AGAIN
				'.$this->write_zoom().'
					
				//direct
				'.$this->direct_menu.'
					
				//MAY CENTER AFTER GEO
				'.($this->center_after_geo ? 'window.'.$this->map_id.'='.$this->map_id : '').'
			  }

		';
	}	
		
	private function write_zoom(){
		return $this->map_id.'.setZoom('.$this->zoom_level.');';
	}

	private function write_marker_creator(){
		if($this->cached_file_content)return;
		return '
			function createMarker(place) {
				var placeLoc = place.geometry.location;
				var marker = new google.maps.Marker({
					map: '.$this->map_id.',
					position: place.geometry.location
				});

				google.maps.event.addListener(marker, \'click\', function() {
					infowindow.setContent(place.name);
					infowindow.open('.$this->map_id.', this);
					'.($this->only_one_baloon ? 'add_baloon(infowindow);':'').'
				});
			}
		';
	}
	// write down collected helpers
	private function write_helpers_functions(){
		
		
		if($this->cached_file_content)return;
		$js='';
		if(count($this->helpers))
			foreach($this->helpers as $helper){
				$js.=$this->helper_functions[$helper];
			}
		return $js;
	}	
	
			

	private function param2coords($param){
		if($this->cached_file_content)return;
		if(is_array($param)){
			$js_point = 'new google.maps.LatLng('.$param[0].', '.$param[1].')';
		}else{
			$lat_lng = $this->address2coords($param);
			$js_point = 'new google.maps.LatLng('.$lat_lng['lat'].', '.$lat_lng['lon'].')';
		}
		return $js_point;
	}
	
	
	
	public function add_traffic_layer(){
		if($this->cached_file_content)return;
		$this->traffic_layer = 'var trafficLayer = new google.maps.TrafficLayer(); trafficLayer.setMap('.$this->map_id.');';
		return $this;
	}
	public function add_bicyle_layer(){
		if($this->cached_file_content)return;
		$this->bike_layer = 'var bikeLayer = new google.maps.BicyclingLayer();bikeLayer.setMap('.$this->map_id.');';
		return $this;
	}
	
	
	private function gcurl($url, $json = false){
		$ch = curl_init();
		curl_setopt_array($ch, array(CURLOPT_URL => $url,CURLOPT_RETURNTRANSFER=>true,CURLOPT_HEADER => false));
		return curl_exec($ch);
	}	
	public function add_flickr_layer($options){
		$this->add_helper('place_marker');
		$this->flickr_layer='';
		$apikey = $options['apikey'];
		$photoset = $options['photoset'];
		$limit = array_key_exists('limit', $options) ? $options['limit'] :false;
		$baloon = addslashes(array_key_exists('baloon', $options) ? $options['baloon'] : '%TITLE%<br />%IMG%<br />%TAGS%');
		
		//curl to get data, geolocation and tags
		
		$to_sort_photo_arr = array();		
		
		$data = json_decode(curl::get("http://api.flickr.com/services/rest/?&method=flickr.photosets.getPhotos&api_key=$apikey&photoset_id=$photoset&format=json&nojsoncallback=1"));
		if(!$data)return false;
		
		$innerjs = '';
		if($data->stat == 'ok'){
			
				
			for($i = 0,$len = count($data->photoset->photo); $i<$len; $i++ ){
				
				if($limit && ($len-$i)>$limit)continue;
				
				$ph = $data->photoset->photo[$i];
				$mphotoUrl = 'http://farm'. $ph->farm . '.static.flickr.com/' . $ph->server . '/' . $ph->id . '_' . $ph->secret . '_m.jpg';
				
				$photoUrl = 'http://farm' . $ph->farm . '.static.flickr.com/' . $ph->server . '/' . $ph->id . '_' . $ph->secret . '.jpg';
				$photoID = $ph->id;
				$photoTITLE = $ph->title;
				
		
				$locations = json_decode(curl::get("http://api.flickr.com/services/rest/?&method=flickr.photos.geo.getLocation&api_key=$apikey&photo_id=$photoID&format=json&nojsoncallback=1"));
				
				if($locations->stat != 'fail'){
					
					$tags = array();
					
					$arrtags = json_decode( curl::get("http://api.flickr.com/services/rest/?&method=flickr.photos.getInfo&api_key=$apikey&photo_id=$photoID&format=json&nojsoncallback=1") );
					
					if($arrtags->stat == 'ok'){
						$infos = array(
							'user'=>$arrtags->photo->owner->username,
							'title'=>$arrtags->photo->title->_content,
							'description'=>$arrtags->photo->description->_content,
							'taken'=>$arrtags->photo->dates->taken,
							'latitude'=>$arrtags->photo->location->latitude,
							'longitude'=>$arrtags->photo->location->longitude,
							'tags'=>'',
							'img'=>'<img src=\"'.$mphotoUrl.'\" />',
							'uimg'=>$photoUrl

						);


						


						if(count($arrtags->photo->tags->tag)){
							$tmptag = array();
							foreach($arrtags->photo->tags->tag as $t){
								$tmptag[] = '<a href=\"http://www.flickr.com/photos/tags/' . $t->_content . '\" target=\"_blank\">' . $t->raw .  '</a>';
							}
							$infos['tags'] = implode(', ', $tmptag);
						}
							
						
						$tmpbaloon = str_replace(array('%USER%','%TITLE%','%DESCRIPTION%','%TAKEN%','%LATITUDE%','%LONGITUDE%','%TAGS%','%IMG%','%UIMG%'), $infos, $baloon);

						$innerjs.='
							flickr_mrk['.$i.'] = placeMarker(new google.maps.LatLng('.$infos['latitude'].', '.$infos['longitude'].'));
							content = "'.$tmpbaloon.'" ;
							flickr_win['.$i.'] = new google.maps.InfoWindow({ content: \'\'+content });
							google.maps.event.addListener(
								flickr_mrk['.$i.'],
								\'click\',
								function(event) {
									flickr_win['.$i.'].open('.$this->map_id.',flickr_mrk['.$i.']);
									'.($this->only_one_baloon ? 'add_baloon(flickr_win['.$i.']);':'').'
									'.( $this->write_center_on_click('event.latLng') ).'
								}
							);
						';
						
					}
					
				}
				
			}

		}
		
		$js = '
			
		(function(){ 
			var my_photos = [],
				one_photo = function(){
					this.murl = "";
					this.url = "";
					this.lat = 0;
					this.lon = 0;
				},
				
				flickr_mrk = [],
				flickr_win = [],
				content;
				
			'.$innerjs.'

		})();';
		$this->flickr_layer = $js;
		
	}
	
	
	
	
	
	
	
	
	
	public function add_panoramio_layer($opt=false){
		if($this->cached_file_content)return;
		$this->panoramio_layer = 'var panoramioLayer = new google.maps.panoramio.PanoramioLayer();panoramioLayer.setMap('.$this->map_id.');';
		$this->script_hash['libraries'][] = 'panoramio';
		if(is_array($opt)){
			$this->panoramio_layer.=
				(array_key_exists('tag', $opt)?'panoramioLayer.setTag(\''.$opt['tag'].'\');':'').
				(array_key_exists('userid',$opt)?'panoramioLayer.setUserId(\''.$opt['userid'].'\');':'');
		}
		return $this;
		
	}
	
	
	
	
	

	
	
	public function add_user_script($raw_script){
		if($this->cached_file_content)return;
		$this->more_scripts[] = $raw_script;
		return $this;
	}
	

	
	
	public function get_distance($from, $to, $mode='bicycling'){
		if($this->cached_file_content)return;
		
		$m = in_array($mode,array('driving','walking','bicycling')) ? $mode : 'bicycling';
		
		$req='http://maps.googleapis.com/maps/api/distancematrix/json?origins='.$from.'&destinations='.$to.'&mode='.$mode.'&language=en-EN&sensor=false';
		$tmp = array();
		$ret = curl::get($req);
		$res = json_decode($ret, true);
		$return = array('distance'=>0, 'duration'=>0);
		if($res['status']==='OK'){
			$return['distance'] = $res['rows'][0]['elements'][0]['distance']['value'];
			$return['duration'] = $res['rows'][0]['elements'][0]['duration']['value'];
			return $return;
		}
		return false;
	}
	
	
	
	
	/**
	 *
	 * @param type $path_points array of point as in add_point array(name, coords, opts)
	 * @param type $style 
	 */
	
	public function add_polyline($polyline_points, $style){
		if($this->cached_file_content)return;
		$path = array(
			'style'=>$style
		);
		
		foreach($polyline_points as $point){
			$name = $point[0];
			$address_or_coords = $point[1];
			$opts = $point[2];
			
			if(!is_array($address_or_coords))$address_or_coords = $this->address2coords($address_or_coords);
			$path[] = array('name'=>$name, 'coords'=>$address_or_coords, 'opts'=>$opts);
		}
		$this->polylines[] = $path;
		return $this;
	}
	

	public function set_route_panel($id){
		$this->routes_panel = $id;
	}
	
	
	public function add_route($from, $to, $opts=array()){
		if($this->cached_file_content)return;
		
		$way_points = (array_key_exists('way_points', $opts) && is_array($opts['way_points'])) ? $opts['way_points']:false;
		
		$avoids = (array_key_exists('avoids', $opts) && is_array($opts['avoids'])) ? $opts['avoids']:false;
		
		$polyOpts = array_key_exists('stroke', $opts) ? ', polylineOptions: '.arr::assoc2json($opts['stroke']).' ': false;
		
		
		
		$provideRouteAlternatives = array_key_exists('provideRouteAlternatives', $opts)? $opts['provideRouteAlternatives']:false;
		
		if(is_array($way_points)){
			$wp = array();
			foreach($opts['way_points'] as $w){
				$wp[] = '{location: "'.$w.'", stopover:false}';
			}
			$way_points = '['.implode(',', $wp).']';
		}
		
		$mode = array_key_exists('mode', $opts) ? $opts['mode'] : 'DRIVING';
		
		$show_steps = array_key_exists('show_steps', $opts) ? $opts['show_steps'] : false;
		
		
		$drag_directions = array_key_exists('draggable_points', $opts) ? $opts['draggable_points'] : false;;
		
		
		
		
		
		if(!in_array($mode, array('DRIVING','WALKING','BICYCLING')))$mode = 'DRIVING';
		
		$scr = '';
		$next_index = count($this->routes);
		
		$scr.='
		

		var directionsDisplay_'.$next_index.';
		directionsDisplay_'.$next_index.'= new google.maps.DirectionsRenderer('.($drag_directions?'{draggable: true'.$polyOpts.'}':'').');
		directionsDisplay_'.$next_index.'.setMap('.$this->map_id.');
		

		'.($this->routes_panel ? 'directionsDisplay_'.$next_index.'.setPanel(document.getElementById("'.$this->routes_panel .'"));' : '').'
		
		
		'.($show_steps?'var stepDisplay = new google.maps.InfoWindow(); var markerArray = [];':'').'
		 

		var route_'.$next_index.' = {
			origin:"'.$from.'",
			destination:"'.$to.'",
			'.($way_points? 'waypoints:'.$way_points.',optimizeWaypoints: true,' : '').'
			'.(($avoids && in_array('tolls', $avoids))? 'avoidTolls:true,' : '').'
			'.(($avoids && in_array('highways', $avoids))? 'avoidHighways:true,' : '').'
			'.($way_points? 'waypoints:'.$way_points.',optimizeWaypoints: true,' : '').'
			'.($provideRouteAlternatives? 'provideRouteAlternatives:true,' : '').'
			travelMode: google.maps.TravelMode.'.$mode.'
			
		};
		directionsService.route(
			route_'.$next_index.',
			function(result, status) {
				if (status == google.maps.DirectionsStatus.OK) {
					directionsDisplay_'.$next_index.'.setDirections(result);
					'.($show_steps?'showSteps(result);':'').'
				}
			}
		);';
		if($show_steps){
			$scr.='	
			function showSteps(directionResult) {
			  var myRoute = directionResult.routes[0].legs[0];

			  for (var i = 0; i < myRoute.steps.length; i++) {
				  var marker = new google.maps.Marker({
					position: myRoute.steps[i].start_point,
					map: '.$this->map_id.'
				  });
				  attachInstructionText(marker, myRoute.steps[i].instructions);
				  markerArray[i] = marker;
			  }
			  
			  
			}

			function attachInstructionText(marker, text) {
			  google.maps.event.addListener(marker, \'click\', function() {
				stepDisplay.setContent(text);
				stepDisplay.open('.$this->map_id.', marker);
			  });
			}
			
			';
		}
		$this->routes[] = $scr;
		return $this;
	}

	
	
	public function add_polygon($path_points, $style, $in_baloon=false){
		if($this->cached_file_content)return;
		$path = array(
			'style'=>$style,
			'in_baloon'=>$in_baloon
		);
		foreach($path_points as $point){
			$name = $point[0];
			$address_or_coords = $point[1];
			$opts = $point[2];
			
			if(!is_array($address_or_coords))$address_or_coords = $this->address2coords($address_or_coords);
			$path[] = array('name'=>$name, 'coords'=>$address_or_coords, 'opts'=>$opts);
		}
		$this->polygons[] = $path;
		return $this;
	}
	

	
	
	//can be called only once
	public function view_places($places, $location, $radius, $baloon="%name%"){
		if($this->cached_file_content)return;
		if($this->places_flag)return;
		
		$this->places = array(
			'types'=>array(),
			'location'=>$location,
			'radius'=>$radius,
			'baloon'=>$baloon
		);
		foreach($places as $p)$this->places['types'][] = $p;
		$this->places_flag = '
					var service;
					service = new google.maps.places.PlacesService('.$this->map_id.');
					 
					function places_callback(results, status) {
						if (status == google.maps.places.PlacesServiceStatus.OK) {
							for (var i = 0; i < results.length; i++) {
								var place = results[i];
								
								
								var request = {	reference: results[i].reference };
								var infowindow = new google.maps.InfoWindow();


								 service.getDetails(request, function(details, status) {
								  if (status == google.maps.places.PlacesServiceStatus.OK) {
									var marker = new google.maps.Marker({
									  map: '.$this->map_id.',
									  position: details.geometry.location
									});
									
									

									var content = \''.$this->places['baloon'].'\'.replace("%name%",details.name)
									   .replace("%rating%",details.rating)
									   .replace("%url%",details.url)
									   .replace("%website%",details.website)
									   .replace("%html_attributions%",details.html_attributions)
									   .replace("%formatted_phone_number%",details.formatted_phone_number)
									   .replace("%formatted_address%",details.formatted_address)									   
									   .replace("%types%",details.types.join(", "));
									   
									google.maps.event.addListener(marker, \'click\', function() {
									  infowindow.setContent(content);
									  infowindow.open('.$this->map_id.', this);
									});
								  }
								});


								
								//alert(results[i].reference);
							}
						}
					}
					
					var request = {
						location: new google.maps.LatLng(%PLAT%, %PLON%),
						radius: \''.$this->places['radius'].'\'
						'.$this->write_places().'
					};
					
					
					
					infowindow = new google.maps.InfoWindow();

					service.search(request, places_callback);
					
	
				';
		
		return $this;
	}

	
	/*****************************************************/
	//
	// HELPERS
	// 
	// add a inner helper
	public function add_helper($helpers){
		if($this->cached_file_content)return;
		if(is_array($helpers)){
			foreach($helpers as $helper)$this->add_helper($helper);
			return;
		}
		if(array_key_exists($helpers, $this->helper_functions) && !in_array($helpers, $this->helpers) )$this->helpers[] = $helpers;
		return $this;
	}
	// remove a inner helper
	public function remove_helper($helper){
		if($this->cached_file_content)return;
		if(in_array($helper, $this->helpers))unset($this->helpers[$helper]);
		return $this;
	}


	
	
	
	
	/*****************************************************/
	/*****************************************************/
	/*****************************************************/

	/*****************************************************/
	/*****************************************************/
	/*****************************************************/
	
	
	
	
	
	

	
	public function render(){
		if($this->cached_file_content)return $this->cached_file_content;
		
		$this->write_js();
		
		$out = $this->out_style;
		
		//remove fase hashes
		foreach($this->script_hash as $k => $s)if($s==false)unset($this->script_hash[$k]);
		
		
		
		
		if(array_key_exists('libraries', $this->script_hash)){ 
            $this->script_hash['libraries']= implode(',', $this->script_hash['libraries']);
        }
        
		$out.= html::implicit_script($this->proto . $this->base_script . arr::arr2get($this->script_hash));
		
		
		
		$out.= html::esplicit_script($this->out_js, 'js');
		
		if($this->use_jQuery){
			$out.= html::esplicit_script('jQuery(function(){initialize();});', 'js');
		}else{
			
			$j='if (window.addEventListener) { 
				window.addEventListener(\'load\', initialize, false); 
			} 
			else if (window.attachEvent) { 
				window.attachEvent(\'onload\', initialize); 
			}   ';
			$out.= html::esplicit_script($j, 'js');
		}
		
		$this->out_js = NULL;
		if($this->cache)file_put_contents($this->may_cache_file, $out);
		
		return $out;
	}
	
	

		
}




/**
 *  Three minimal helper static classes
 * 
 */

class arr{
	/**
	 *
	 * 	Se viene passato $md a TRUE allora tutti i valori in get vengono md5izzati,
	 * 	questo per rendere molto difficile che un uente metta in blacklist un altro utente tramite url)
	 */
	public static function arr2get($par=null, $md = FALSE, $straight = TRUE) {

		if( !is_null($par) ){

		    $params = '';
		    $i = 0;

		    foreach ($par as $k => $p) {
				$val = ($md) ? md5($p) : $p;
				$params.= ( $straight ? '&' : (($i == 0) ? '?' : '&')) . "$k=$val";
				$i++;
		    }

		    return $params;

		} else {
		    return '';
		}
	}
	

	/**
	 *  trasform an associative array in a string key1="value1" key2="value2"
	 *
	 * @param array $opt
	 * @return string
	 */
	public static function assoc2attrib($opt){
		$out = ' ';
		if(is_array($opt) && count($opt) > 0){
			foreach($opt as $k => $val)
				$out .= $k.'="'.$val.'" ';
		}
		return ($out==' ')?'':$out;
	}
	
	
	
	
	/**
	 *  trasform an associative array in a string key1:value1;key2:value2;
	 *
	 * @param array $opt
	 * @return string
	 */
	public static function assoc2style($opt){
		$out = ' ';
		if(is_array($opt) && count($opt) > 0){
			foreach($opt as $k => $val)
				$out .= $k.':'.$val.';';
		}
		return ($out==' ')?'':$out;
	}
	
	
	/**
	 *  trasform an associative array in a string {"key1":"value1","key2":"value2"}
	 *
	 * @param array $opt
	 * @return string
	 */
	public static function assoc2json($opt){
		$out = '{%json%}';
		$arr = array();
		if(is_array($opt) && count($opt) > 0){
			foreach($opt as $k => $val)
				$arr[] = '"'.$k.'":"'.$val.'"';
		}
		return count($arr) ? str_replace('%json%', implode(',',$arr), $out) : '{}';
	}
	
	
	
			
			

}





class html{
	
	private static $instance;
	private static $out = FALSE;
	public static $tags = array(
		'a',
		'body',
		'div',
		'form',
		'head', 'hr', 'html',
		'img', 'input',
		'li', 'link','label',
		'meta',
		'option',
		'p',
		'script', 'select', 'span', 'style',
		'textarea','title',
		'ul',
		'h1','h2','h3','h4','h5','h6'
	);		
	private static $default_dtd;

	private function __construct(){}
	
	
		
	private static function get_instance() {
		
		if ( empty( self::$instance ) ) {
			self::$instance = new html();
		}
		return self::$instance;
	}
	
	public static function tag($name, $in=false, $args=false){

		$vals = explode('_',$name);
		$ret = '';
		//if($args)$in = $args[0];
		//echo $vals[0].'<br />';
		switch(count($vals)){
			case 0:return; break;
			case 1:
			//	echo $vals[0];
				if(in_array($vals[0], self::$tags)){
					$ag = arr::assoc2attrib($args);
					
					
					$ret.= $in?'<'.$vals[0].$ag.'>'.$in.'</'.$vals[0].'>' : '<'.$vals[0].$ag.'/>';
				}
			break;
			case 2:
				list($tag, $action) = $vals;
				//echo $tag.'<br /><br />';
				//print_r(self::$tags);
				if(in_array($tag, self::$tags)){
					switch($action){
						case 'start':
							$ret.= '<'.$tag.arr::assoc2attrib($args).'>';
						break;
						case 'end':
							$ret.= '</'.$tag.'>';
						break;
					}
				}
			break;
			default:return;break;
		}
		//echo $ret;
		return $ret;
		//echo $name;
	}
	
	public static function esplicit_script($content, $type, $out = false){
		self::get_instance($out);

		switch($type){
			case 'js':
				$ret = '<script type="text/javascript">'.$content.'</script>';
			break;
			case 'css':
				$ret = '<style type="text/css">'.$content.'</style>';
			break;
		}
		if($ret && self::$out) echo $ret;

		return $ret;
    }
    
    public function implicit_script($url, $out = false) {
        self::get_instance($out);
        $ret = '<script type="text/javascript" src="'.$url.'"></script>';
		if($ret && self::$out) echo $ret;
		return $ret;
    }
	
}



class curl {

	public static function get($uri, $options=array()){
		
		
		$ch = curl_init();
		$sname = session_name();
		$sid = session_id();

		$strCookie = $sname.'=' . (array_key_exists($sname, $_COOKIE)?$_COOKIE[$sname] : $sid) . '; path=/';

		session_write_close();

		$curl_opts = array(
			CURLOPT_URL => $uri,
			CURLOPT_HEADER=> false,
			CURLOPT_COOKIE=>$strCookie,
			CURLOPT_RETURNTRANSFER=>true,
			CURLOPT_ENCODING =>"gzip"
		);
		
		if(count($options)){
			foreach($options as $k =>$o)$curl_opts[$k]=$o;
			//utility::print_d($curl_opts);
			//die();
		}
		
		curl_setopt_array(
			$ch,
			$curl_opts
		);
		$ret = curl_exec($ch);
		curl_close($ch);
		return $ret;
	}



}


