<?php


	function get_location($id) {
	    # From http://www.flickr.com/services/api/response.php.html
		$flickr_api_key = 'YOUR_UNIQUE_FLICKR_API_KEY_GOES_HERE';

	    $params = array(
	        'api_key'       => $flickr_api_key,
	        'method'        => 'flickr.photos.geo.getLocation',
	        'photo_id'	=> $id,
	        'format'        => 'json',
	        'nojsoncallback'=> '1'
	    );
	    $encoded_params = array();

	    foreach ($params as $k => $v){

	        $encoded_params[] = urlencode($k).'='.urlencode($v);
	    }


	    #
	    # call the API and decode the response
	    #

	    $url = "https://api.flickr.com/services/rest/?".implode('&', $encoded_params);

	    $flickr = file_get_contents($url);
	    # flickr is dumb and doesn't return "proper" JSON so we have to replace \' with '
	    $flickrProper = str_replace("\\'", "'", $flickr);
	    $picture = json_decode($flickrProper, true);

	    $retval = array();
	    
	    $retval['lat'] = $picture['photo']['location']['latitude'];
	    $retval['lon'] = $picture['photo']['location']['longitude'];

	    return $retval;    
	}

	function get_photos($lat, $lon) {

		$uid = uniqid("", true);
		$flickr_api_key = 'YOUR_UNIQUE_FLICKR_API_KEY_GOES_HERE';

	    $params = array(
	        	'api_key'       => $flickr_api_key,
			'method'		=> 'flickr.photos.search',
			'lat'			=> $lat,
			'lon'			=> $lon,
			'format'		=> 'json',
			'sort'			=> 'interestingness-desc',
			'per_page'		=> '50',
			'radius'		=> '20',			
			'nojsoncallback'=> '1'
			);

		$encoded_params = array();

		foreach ($params as $k => $v) {
			$encoded_params[] = urlencode($k).'='.urlencode($v);
		}

		$url = "https://api.flickr.com/services/rest/?".implode('&', $encoded_params);

		
		$flickr = file_get_contents($url);
		$flickrProper = str_replace("\\'", "'", $flickr);

		$pictures = json_decode($flickrProper, true);

		$picture_list = $pictures['photos']['photo'];

		foreach ($picture_list as $key => $value) {
			$id = $value['id'];
			$value['location'] = get_location($id);
			$lat = $value['location']['lat'];
			$lon = $value['location']['lon'];
			$url = "http://farm".$value['farm'].".staticflickr.com/".$value['server']."/".$id."_".$value['secret'].".jpg";
			echo "<input style=\"padding:2em;\" type=\"image\" alt=\"Add to your itinerary\" src=\"".$url."\" onClick=\"UpdateMe('".$uid."', '".$url."', ".$lat.", ".$lon.")\"></a>";
			//Things to store in database: picture URL, and coords, but only when added
		}

	}

	function get_GPS($city) {

		$google_api_key = 'YOUR_UNIQUE_GOOGLE_API_KEY_GOES_HERE';

		$url = 'https://maps.googleapis.com/maps/api/geocode/json?sensor=false&key='.$google_api_key.'&oe=utf-8';
		$url = $url.'&address='.$city;
		$new_url = str_replace(' ', '%20', $url);
		$data = file_get_contents( $new_url);
		$arr = json_decode($data, true);

		$location = $arr['results'][0]['geometry']['location'];

		return array($location['lat'], $location['lng']);
	}

	function get_city($lat, $lon) {
		//I'll write this eventually
		return "hello";
	}

	
	$lat = 0.0;
	$lon = 0.0;

	$location_type = $_GET['type'];
	if ($location_type == 'GPS') {
		$lat = $_GET['lat'];
		$lon = $_GET['lon'];
		
	} else {
	
		$city = $_GET['city'];
		
		list($lat, $lon) = get_GPS($city);
		$static_city = $city;

	}

	get_photos($lat, $lon);
?>	
