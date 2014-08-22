<?php

	$uid = $_POST['uid'];
	$url = $_POST['url'];
	$lat = $_POST['lat'];
	$lon = $_POST['lon'];

	$connecting_string = sprintf('mongodb://%s:%d/%s', 'ds047197.mongolab.com', 47197, 'city_explorer');
	$connection=  new Mongo($connecting_string,array('username'=>'roe2pj','password'=>'me'));
	$dbname = $connection->selectDB('city_explorer');

	$itineraries = $dbname->selectCollection('itineraries');
	
	$itinerary = array(
		'uid' => $uid,
		'img' => array('url' => $url, 'lat' => $lat, 'lon' => $lon),
		);
	$itineraries->save($itinerary);
	
?>
